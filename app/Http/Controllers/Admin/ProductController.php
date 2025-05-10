<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use App\Models\ProductColor;
use App\Models\ProductSize;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'productImages']);
        
        // Tìm kiếm theo tên hoặc SKU
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo danh mục
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp
        $query->orderBy('created_at', 'desc');
        
        $products = $query->paginate(15)->withQueryString();
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        
        // Lấy danh sách màu sắc và kích thước từ các bảng riêng
        $allColors = ProductColor::orderBy('name')->get();
        $allSizes = ProductSize::orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'allColors', 'allSizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products',
            'status' => 'required|in:active,inactive,out_of_stock',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'initial_stock' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'stock_location' => 'nullable|string|max:255',
            'color_ids' => 'nullable|array',
            'color_ids.*' => 'exists:product_colors,id',
            'size_ids' => 'nullable|array',
            'size_ids.*' => 'exists:product_sizes,id',
        ]);
        
        // Tạo slug từ tên sản phẩm
        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        $productData = $request->except(['color_ids', 'size_ids', 'images', 'primary_image', 'initial_stock', 'low_stock_threshold', 'stock_location']);
        $productData['slug'] = $slug;
        $productData['featured'] = $request->has('featured');
        $productData['track_inventory'] = $request->has('track_inventory');
        
        $product = Product::create($productData);
        
        // Lưu liên kết với màu sắc
        $colorNames = [];
        $colorCodes = [];
        if ($request->has('color_ids')) {
            foreach ($request->color_ids as $colorId) {
                $color = ProductColor::find($colorId);
                if ($color) {
                    $product->colors()->attach($colorId, [
                        'color_code' => $color->code
                    ]);
                    // Lưu tên và mã màu để cập nhật vào bảng products
                    $colorNames[] = $color->name;
                    $colorCodes[] = $color->code;
                }
            }
            // Cập nhật cột colors và color_codes trong bảng products
            $product->update([
                'colors' => $colorNames,
                'color_codes' => $colorCodes
            ]);
        }
        
        // Lưu liên kết với kích thước
        $sizeNames = [];
        if ($request->has('size_ids')) {
            $product->sizes()->attach($request->size_ids);
            
            // Lấy tên kích thước và cập nhật vào bảng products
            $sizes = ProductSize::whereIn('id', $request->size_ids)->get();
            foreach ($sizes as $size) {
                $sizeNames[] = $size->name;
            }
            $product->update([
                'sizes' => $sizeNames
            ]);
        }
        
        // Xử lý upload ảnh
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $primaryImageIndex = $request->input('primary_image');
            
            // Upload tất cả các ảnh
            $uploadedImageIds = $this->handleProductImages($product, $images);
            
            // Nếu đã chọn ảnh làm chính (và không phải ảnh đầu tiên)
            if ($primaryImageIndex !== null && $primaryImageIndex != 0 && isset($uploadedImageIds[$primaryImageIndex])) {
                // Reset tất cả ảnh không phải là ảnh chính
                $product->productImages()->update(['is_primary' => false]);
                
                // Đặt ảnh chính mới
                $product->productImages()->where('id', $uploadedImageIds[$primaryImageIndex])->update(['is_primary' => true]);
            }
        }
        
        // Xử lý tồn kho ban đầu nếu có
        if ($request->has('initial_stock') && $request->initial_stock > 0) {
            $initialStock = (int) $request->initial_stock;
            $lowStockThreshold = (int) $request->low_stock_threshold ?? 5;
            $stockLocation = $request->stock_location;
            
            // Nếu có kích thước và màu sắc, tạo tồn kho cho mỗi tổ hợp
            if ($request->has('size_ids')) {
                $sizes = ProductSize::whereIn('id', $request->size_ids)->get();
            } else {
                $sizes = collect();
            }
            
            if ($request->has('color_ids')) {
                $colors = ProductColor::whereIn('id', $request->color_ids)->get();
            } else {
                $colors = collect();
            }
            
            if ($sizes->isNotEmpty() && $colors->isNotEmpty()) {
                // Phân bổ số lượng cho mỗi biến thể
                $variantCount = $sizes->count() * $colors->count();
                $stockPerVariant = max(1, intdiv($initialStock, $variantCount));
                $remainingStock = $initialStock;
                
                foreach ($sizes as $size) {
                    foreach ($colors as $color) {
                        $stockForThisVariant = min($stockPerVariant, $remainingStock);
                        $remainingStock -= $stockForThisVariant;
                        
                        if ($stockForThisVariant > 0) {
                            $this->createOrUpdateInventory($product, $size->name, $color->name, $stockForThisVariant, $lowStockThreshold, $stockLocation);
                        }
                    }
                }
            } elseif ($sizes->isNotEmpty()) {
                // Chỉ có kích thước
                $stockPerSize = max(1, intdiv($initialStock, $sizes->count()));
                $remainingStock = $initialStock;
                
                foreach ($sizes as $size) {
                    $stockForThisSize = min($stockPerSize, $remainingStock);
                    $remainingStock -= $stockForThisSize;
                    
                    if ($stockForThisSize > 0) {
                        $this->createOrUpdateInventory($product, $size->name, null, $stockForThisSize, $lowStockThreshold, $stockLocation);
                    }
                }
            } elseif ($colors->isNotEmpty()) {
                // Chỉ có màu sắc
                $stockPerColor = max(1, intdiv($initialStock, $colors->count()));
                $remainingStock = $initialStock;
                
                foreach ($colors as $color) {
                    $stockForThisColor = min($stockPerColor, $remainingStock);
                    $remainingStock -= $stockForThisColor;
                    
                    if ($stockForThisColor > 0) {
                        $this->createOrUpdateInventory($product, null, $color->name, $stockForThisColor, $lowStockThreshold, $stockLocation);
                    }
                }
            } else {
                // Không có biến thể, tạo một inventory mặc định
                $this->createOrUpdateInventory($product, null, null, $initialStock, $lowStockThreshold, $stockLocation);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'productImages'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $product = Product::with([
                'category', 
                'productImages', 
                'colors', 
                'sizes'
            ])->findOrFail($id);
            
            // Kiểm tra xem màu sắc và kích thước được tải đúng chưa
            \Log::info('Edit product ID: ' . $id);
            \Log::info('Loaded colors count: ' . 
                (isset($product->colors) && is_object($product->colors) ? $product->colors->count() : 
                (is_array($product->colors) ? count($product->colors) : 'null'))
            );
            \Log::info('Loaded sizes count: ' . 
                (isset($product->sizes) && is_object($product->sizes) ? $product->sizes->count() : 
                (is_array($product->sizes) ? count($product->sizes) : 'null'))
            );
            
            $categories = Category::where('is_active', true)->get();
            
            // Lấy danh sách màu sắc và kích thước từ các bảng riêng
            $allColors = ProductColor::orderBy('name')->get();
            $allSizes = ProductSize::orderBy('name')->get();
            
            // Đảm bảo các collection có giá trị hợp lệ
            $productColors = isset($product->colors) && is_object($product->colors) 
                ? $product->colors 
                : collect();
                
            $productSizes = isset($product->sizes) && is_object($product->sizes) 
                ? $product->sizes 
                : collect();
            
            // Lấy ID các màu và kích thước của sản phẩm
            $selectedColorIds = $productColors->pluck('id')->toArray();
            $selectedSizeIds = $productSizes->pluck('id')->toArray();
            
            // Nếu không tìm thấy dữ liệu quan hệ, hãy dựa vào dữ liệu trong cột colors và sizes
            if (empty($selectedColorIds) && is_array($product->colors)) {
                $colorNames = $product->colors;
                if (!empty($colorNames)) {
                    $foundColors = ProductColor::whereIn('name', $colorNames)->get();
                    $selectedColorIds = $foundColors->pluck('id')->toArray();
                    \Log::info('Using color names from product column: ' . implode(', ', $colorNames));
                }
            }
            
            if (empty($selectedSizeIds) && is_array($product->sizes)) {
                $sizeNames = $product->sizes;
                if (!empty($sizeNames)) {
                    $foundSizes = ProductSize::whereIn('name', $sizeNames)->get();
                    $selectedSizeIds = $foundSizes->pluck('id')->toArray();
                    \Log::info('Using size names from product column: ' . implode(', ', $sizeNames));
                }
            }
            
            \Log::info('selectedColorIds: ' . json_encode($selectedColorIds));
            \Log::info('selectedSizeIds: ' . json_encode($selectedSizeIds));
            
            return view('admin.products.edit', compact(
                'product', 
                'categories', 
                'allColors', 
                'allSizes', 
                'selectedColorIds', 
                'selectedSizeIds'
            ));
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tải trang chỉnh sửa sản phẩm: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('admin.products.index')
                ->with('error', 'Đã xảy ra lỗi khi tải thông tin sản phẩm. Vui lòng thử lại sau.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive,out_of_stock',
            'description' => 'nullable|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_ids' => 'nullable|array',
            'color_ids.*' => 'exists:product_colors,id',
            'size_ids' => 'nullable|array',
            'size_ids.*' => 'exists:product_sizes,id',
        ]);
        
        $productData = $request->except(['color_ids', 'size_ids', 'new_images', 'primary_image', 'delete_images']);
        $productData['featured'] = $request->has('featured');
        $productData['track_inventory'] = $request->has('track_inventory');
        
        $product->update($productData);
        
        // Cập nhật liên kết với màu sắc
        $product->colors()->detach();
        $colorNames = [];
        $colorCodes = [];
        if ($request->has('color_ids')) {
            foreach ($request->color_ids as $colorId) {
                $color = ProductColor::find($colorId);
                if ($color) {
                    $product->colors()->attach($colorId, [
                        'color_code' => $color->code
                    ]);
                    // Lưu tên và mã màu để cập nhật vào bảng products
                    $colorNames[] = $color->name;
                    $colorCodes[] = $color->code;
                }
            }
            // Cập nhật cột colors và color_codes trong bảng products
            $product->update([
                'colors' => $colorNames,
                'color_codes' => $colorCodes
            ]);
        } else {
            // Nếu không chọn màu nào, reset về null
            $product->update([
                'colors' => null,
                'color_codes' => null
            ]);
        }
        
        // Cập nhật liên kết với kích thước
        $product->sizes()->detach();
        $sizeNames = [];
        if ($request->has('size_ids')) {
            $product->sizes()->attach($request->size_ids);
            
            // Lấy tên kích thước và cập nhật vào bảng products
            $sizes = ProductSize::whereIn('id', $request->size_ids)->get();
            foreach ($sizes as $size) {
                $sizeNames[] = $size->name;
            }
            $product->update([
                'sizes' => $sizeNames
            ]);
        } else {
            // Nếu không chọn kích thước nào, reset về null
            $product->update([
                'sizes' => null
            ]);
        }
        
        // Xử lý ảnh sản phẩm
        if ($request->hasFile('new_images')) {
            $newImages = $request->file('new_images');
            \Log::info('Processing new images for product update', [
                'product_id' => $product->id,
                'image_count' => count($newImages)
            ]);
            $this->handleProductImages($product, $newImages);
        }
        
        // Xóa ảnh cũ nếu có yêu cầu
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            \Log::info('Deleting existing images for product', [
                'product_id' => $product->id,
                'images_to_delete' => $request->delete_images
            ]);
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Xóa file ảnh từ thư mục
                    if ($image->image_path && Storage::exists('public/' . $image->image_path)) {
                        Storage::delete('public/' . $image->image_path);
                    }
                    if ($image->thumbnail_path && Storage::exists('public/' . $image->thumbnail_path)) {
                        Storage::delete('public/' . $image->thumbnail_path);
                    }
                    if ($image->gallery_path && Storage::exists('public/' . $image->gallery_path)) {
                        Storage::delete('public/' . $image->gallery_path);
                    }
                    
                    // Xóa bản ghi từ database
                    $image->delete();
                }
            }
        }
        
        // Cập nhật ảnh chính nếu được chỉ định
        if ($request->has('primary_image')) {
            // Reset tất cả ảnh không phải là ảnh chính
            $product->productImages()->update(['is_primary' => false]);
            
            // Đặt ảnh chính mới
            $product->productImages()
                ->where('id', $request->primary_image)
                ->update(['is_primary' => true]);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Xóa tất cả hình ảnh sản phẩm từ storage
        foreach ($product->productImages as $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
            if ($image->gallery_path) {
                Storage::disk('public')->delete($image->gallery_path);
            }
        }
        
        // Xóa thư mục sản phẩm nếu tồn tại
        $productFolder = 'products/' . $product->id;
        if (Storage::disk('public')->exists($productFolder)) {
            Storage::disk('public')->deleteDirectory($productFolder);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công.');
    }
    
    /**
     * Xử lý upload nhiều ảnh cho sản phẩm
     * @return array IDs của ảnh đã tải lên
     */
    private function handleProductImages(Product $product, array $images)
    {
        $uploadedImageIds = [];
        
        // Kiểm tra xem sản phẩm đã có ảnh hay chưa
        $hasExistingImages = $product->productImages->isNotEmpty();
        
        \Log::info('Starting to handle product images', [
            'product_id' => $product->id,
            'image_count' => count($images),
            'has_existing_images' => $hasExistingImages
        ]);
        
        // Tạo instance của ImageManager
        $manager = new ImageManager(driver: \Intervention\Image\Drivers\Gd\Driver::class);
        
        foreach ($images as $index => $image) {
            try {
                // Tạo thư mục theo ID sản phẩm nếu chưa tồn tại
                $productFolder = 'products/' . $product->id;
                
                \Log::info('Processing image', [
                    'index' => $index,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'product_folder' => $productFolder
                ]);
                
                // Tạo các thư mục con nếu chưa tồn tại
                if (!Storage::disk('public')->exists($productFolder . '/originals')) {
                    Storage::disk('public')->makeDirectory($productFolder . '/originals');
                    \Log::info('Created directory', ['path' => $productFolder . '/originals']);
                }
                if (!Storage::disk('public')->exists($productFolder . '/thumbnails')) {
                    Storage::disk('public')->makeDirectory($productFolder . '/thumbnails');
                    \Log::info('Created directory', ['path' => $productFolder . '/thumbnails']);
                }
                if (!Storage::disk('public')->exists($productFolder . '/gallery')) {
                    Storage::disk('public')->makeDirectory($productFolder . '/gallery');
                    \Log::info('Created directory', ['path' => $productFolder . '/gallery']);
                }
                
                // Lưu hình gốc
                $fileName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $originalPath = $image->storeAs($productFolder . '/originals', $fileName, 'public');
                \Log::info('Stored original image', ['path' => $originalPath]);
                
                // Sử dụng hình gốc cho cả thumbnail và gallery
                $thumbnailPath = $originalPath;
                $galleryPath = $originalPath;
                \Log::info('Using original image for thumbnail and gallery', ['path' => $originalPath]);
                
                // Nếu chưa có ảnh nào hoặc đây là lần đầu upload và đây là ảnh đầu tiên
                // thì đặt ảnh này làm ảnh chính
                $isPrimary = (!$hasExistingImages && $index === 0);
                
                $newImage = $product->productImages()->create([
                    'image_path' => $originalPath,
                    'thumbnail_path' => $thumbnailPath,
                    'gallery_path' => $galleryPath,
                    'is_primary' => $isPrimary,
                    'sort_order' => $product->productImages->count() + 1
                ]);
                
                \Log::info('Created new product image record', [
                    'image_id' => $newImage->id,
                    'is_primary' => $isPrimary
                ]);
                
                $uploadedImageIds[$index] = $newImage->id;
            } catch (\Exception $e) {
                \Log::error('Error processing image', [
                    'index' => $index,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        \Log::info('Finished handling product images', [
            'product_id' => $product->id,
            'uploaded_image_count' => count($uploadedImageIds)
        ]);
        
        return $uploadedImageIds;
    }
    
    /**
     * Đặt ảnh chính cho sản phẩm
     */
    public function setPrimaryImage(Request $request, Product $product, $image)
    {
        try {
            \Log::info('setPrimaryImage called', [
                'product_id' => $product->id, 
                'image_id' => $image
            ]);
            
            // Kiểm tra xem ảnh có tồn tại hay không
            $productImage = $product->productImages()->find($image);
            if (!$productImage) {
                \Log::warning('Image not found', ['image_id' => $image]);
                return redirect()->back()->with('error', 'Không tìm thấy ảnh cần đặt làm ảnh đại diện.');
            }
            
            // Reset tất cả ảnh không phải là ảnh chính
            $product->productImages()->update(['is_primary' => false]);
            
            // Đặt ảnh chính mới
            $productImage->update(['is_primary' => true]);
            \Log::info('Set new primary image successfully', ['image_id' => $image]);
            
            return redirect()->back()->with('success', 'Đã cập nhật hình đại diện sản phẩm thành công.');
            
        } catch (\Exception $e) {
            \Log::error('Error in setPrimaryImage', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Lỗi khi cập nhật ảnh đại diện: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa hình ảnh sản phẩm
     */
    public function deleteImage($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            $product = $image->product;
            $isPrimary = $image->is_primary;
            
            \Log::info('Deleting product image', [
                'image_id' => $id,
                'product_id' => $product->id,
                'is_primary' => $isPrimary
            ]);
            
            // Xóa các hình ảnh từ storage
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
            if ($image->gallery_path) {
                Storage::disk('public')->delete($image->gallery_path);
            }
            
            // Xóa bản ghi khỏi database
            $image->delete();
            
            // Nếu đây là hình chính và có các hình khác, đặt hình đầu tiên làm hình chính
            if ($isPrimary && $product->productImages->count() > 0) {
                $product->productImages()->first()->update(['is_primary' => true]);
                \Log::info('Set new primary image after deletion', ['new_primary_id' => $product->productImages()->first()->id]);
            }
            
            return redirect()->back()->with('success', 'Đã xóa hình ảnh thành công.');
            
        } catch (\Exception $e) {
            \Log::error('Error in deleteImage', [
                'image_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Lỗi khi xóa ảnh: ' . $e->getMessage());
        }
    }
    
    /**
     * Tạo hoặc cập nhật inventory cho sản phẩm
     */
    private function createOrUpdateInventory(Product $product, $size, $color, $quantity, $lowStockThreshold, $location)
    {
        $inventory = $product->inventories()
            ->where('size', $size)
            ->where('color', $color)
            ->first();
        
        if ($inventory) {
            // Cập nhật inventory hiện có
            $inventory->update([
                'quantity' => $inventory->quantity + $quantity,
                'low_stock_threshold' => $lowStockThreshold,
                'location' => $location,
                'in_stock' => true,
            ]);
        } else {
            // Tạo inventory mới
            $product->inventories()->create([
                'size' => $size,
                'color' => $color,
                'quantity' => $quantity,
                'low_stock_threshold' => $lowStockThreshold,
                'location' => $location,
                'in_stock' => true,
            ]);
        }
    }

    /**
     * Đồng bộ hóa thuộc tính sản phẩm giữa bảng quan hệ và cột sizes, colors
     */
    public function syncProductAttributes()
    {
        try {
            $products = Product::with(['colors', 'sizes'])->get();
            $count = 0;
            
            foreach ($products as $product) {
                $updated = false;
                
                // Đồng bộ màu sắc
                if (isset($product->colors) && is_object($product->colors) && $product->colors->isNotEmpty()) {
                    $colorNames = $product->colors->pluck('name')->toArray();
                    $colorCodes = $product->colors->pluck('code')->toArray();
                    
                    $product->colors = $colorNames;
                    $product->color_codes = $colorCodes;
                    $updated = true;
                }
                
                // Đồng bộ kích thước
                if (isset($product->sizes) && is_object($product->sizes) && $product->sizes->isNotEmpty()) {
                    $sizeNames = $product->sizes->pluck('name')->toArray();
                    
                    $product->sizes = $sizeNames;
                    $updated = true;
                }
                
                if ($updated) {
                    $product->save();
                    $count++;
                }
            }
            
            return redirect()->route('admin.products.index')
                ->with('success', "Đã đồng bộ hóa thuộc tính cho {$count} sản phẩm.");
        } catch (\Exception $e) {
            \Log::error('Lỗi khi đồng bộ thuộc tính sản phẩm: ' . $e->getMessage());
            return redirect()->route('admin.products.index')
                ->with('error', 'Đã xảy ra lỗi khi đồng bộ thuộc tính sản phẩm: ' . $e->getMessage());
        }
    }
}
