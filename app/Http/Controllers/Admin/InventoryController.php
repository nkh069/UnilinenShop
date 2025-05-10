<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\ProductColor;
use App\Models\ProductSize;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'images'])->orderBy('name')->paginate(15);
        return view('admin.inventory.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with([
            'category', 
            'productImages', 
            'inventories',
            'colors',
            'sizes'
        ])->findOrFail($id);
        
        // Lấy tất cả kích thước và màu sắc từ mối quan hệ
        $productColors = isset($product->colors) && is_object($product->colors) 
            ? $product->colors 
            : collect();
            
        $productSizes = isset($product->sizes) && is_object($product->sizes) 
            ? $product->sizes 
            : collect();
        
        // Nếu không có dữ liệu từ relationship, lấy từ cột mảng
        if ($productColors->isEmpty() && is_array($product->colors)) {
            $colorNames = $product->colors;
            if (!empty($colorNames)) {
                $productColors = ProductColor::whereIn('name', $colorNames)->get();
            }
        }
        
        if ($productSizes->isEmpty() && is_array($product->sizes)) {
            $sizeNames = $product->sizes;
            if (!empty($sizeNames)) {
                $productSizes = ProductSize::whereIn('name', $sizeNames)->get();
            }
        }
        
        // Lấy tất cả inventory hiện có của sản phẩm
        $inventories = $product->inventories;
        
        // Tạo ma trận hiển thị tồn kho theo kích thước và màu sắc
        $inventoryMatrix = [];
        
        foreach ($inventories as $inventory) {
            $size = $inventory->size;
            $color = $inventory->color;
            
            // Nếu size hoặc color là null, sử dụng một giá trị mặc định
            $size = $size ?? 'Mặc định';
            $color = $color ?? 'Mặc định';
            
            if (!isset($inventoryMatrix[$size])) {
                $inventoryMatrix[$size] = [];
            }
            
            $inventoryMatrix[$size][$color] = $inventory;
        }
        
        // Hiển thị view partial cho modal
        return view('admin.inventory.partials.product-inventory-details', compact(
            'product', 
            'productSizes', 
            'productColors', 
            'inventories', 
            'inventoryMatrix'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        return view('admin.inventory.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);
        
        $product = Product::findOrFail($id);
        $product->stock_quantity = $request->stock_quantity;
        $product->save();
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Tồn kho sản phẩm đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Display low stock products.
     */
    public function lowStock()
    {
        // Lấy danh sách các sản phẩm có trạng thái hết hàng hoặc không hoạt động
        $outOfStockProducts = Product::with(['category', 'images'])
            ->where('status', 'out_of_stock')
            ->orWhere('status', 'inactive')
            ->get();
        
        // Lấy danh sách các sản phẩm có tồn kho thấp (dưới ngưỡng low_stock_threshold)
        $productsWithLowStock = Product::with(['category', 'images', 'inventories'])
            ->where('status', 'active')
            ->whereHas('inventories', function($query) {
                $query->whereRaw('quantity <= low_stock_threshold');
            })
            ->get();
        
        // Gộp hai danh sách và loại bỏ trùng lặp
        $products = $outOfStockProducts->concat($productsWithLowStock)
            ->unique('id')
            ->sortBy('name');
        
        // Phân trang kết quả (tự tạo)
        $page = request()->get('page', 1);
        $perPage = 15;
        $total = $products->count();
        
        $products = $products->forPage($page, $perPage);
        
        // Tạo đối tượng phân trang thủ công
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $products,
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );
        
        return view('admin.inventory.low-stock', compact('products'));
    }
    
    /**
     * Display inventory movements history.
     */
    public function movements(Request $request)
    {
        $query = \App\Models\InventoryMovement::with(['inventory.product', 'user', 'supplier'])
            ->orderBy('created_at', 'desc');
            
        // Lọc theo sản phẩm
        if ($request->has('product_id') && $request->product_id) {
            $query->whereHas('inventory', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }
        
        // Lọc theo loại biến động (in, out, adjustment, return)
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Lọc theo nguồn (supplier, warehouse, adjustment, return, other)
        if ($request->has('source') && $request->source) {
            $query->where('source', $request->source);
        }
        
        // Lọc theo nhà cung cấp
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        // Lọc theo ngày (từ ngày)
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        // Lọc theo ngày (đến ngày)
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Tìm kiếm theo lý do
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('inventory.product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }
        
        $movements = $query->paginate(20);
        
        // Lấy danh sách sản phẩm cho dropdown lọc
        $products = Product::orderBy('name')->get();
        
        // Lấy danh sách nhà cung cấp cho dropdown lọc
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        
        return view('admin.inventory.movements', compact('movements', 'products', 'suppliers'));
    }
    
    /**
     * Create inventory movement record.
     */
    public function createMovement(Request $request)
    {
        // Logic để tạo hồ sơ chuyển động kho
        return redirect()->back()->with('success', 'Đã cập nhật thông tin kho hàng.');
    }

    /**
     * Display the form to add stock to a product.
     */
    public function addStock(Request $request, $inventoryId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        $inventory = Inventory::findOrFail($inventoryId);
        
        try {
            DB::beginTransaction();
            
            // Lưu số lượng ban đầu để log
            $originalQuantity = $inventory->quantity;
            
            // Cập nhật số lượng tồn kho
            $inventory->quantity += $request->quantity;
            
            // Cập nhật trạng thái dựa trên số lượng
            if ($inventory->quantity > 0 && $inventory->product) {
                $inventory->product->status = 'active';
                $inventory->product->save();
            }
            
            $inventory->save();
            
            // Ghi nhận lịch sử nhập kho
            $this->recordInventoryMovement(
                $inventory,
                $request->quantity,
                'in',
                $request->notes ?? 'Nhập hàng vào kho',
                'manual',
                $request->supplier_id
            );
            
            DB::commit();
            
            // Ghi log hành động
            activity('inventory')
                ->performedOn($inventory)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_quantity' => $originalQuantity,
                    'new_quantity' => $inventory->quantity,
                    'adjustment' => $request->quantity,
                    'notes' => $request->notes
                ])
                ->log('Đã nhập thêm ' . $request->quantity . ' sản phẩm vào kho');
            
            return redirect()->route('admin.inventory.show', $inventoryId)
                ->with('success', 'Đã nhập thêm ' . $request->quantity . ' sản phẩm vào kho.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi nhập hàng vào kho:', [
                'error' => $e->getMessage(),
                'inventory_id' => $inventoryId,
                'quantity' => $request->quantity
            ]);
            
            return redirect()->back()
                ->with('error', 'Không thể nhập hàng: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the form to adjust inventory.
     */
    public function adjust(Request $request)
    {
        $product = null;
        
        if ($request->has('product_id')) {
            $product = Product::with(['inventories'])->findOrFail($request->product_id);
        }
        
        $products = Product::orderBy('name')->get();
        
        return view('admin.inventory.adjust', compact('product', 'products'));
    }

    /**
     * Process the inventory adjustment.
     */
    public function processAdjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255',
            'source' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        
        $inventory = Inventory::with('product')->findOrFail($request->inventory_id);
        $currentQuantity = $inventory->quantity;
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $currentQuantity;
        
        if ($difference == 0) {
            return redirect()->back()->with('info', 'Không có thay đổi về số lượng.');
        }
        
        // Xử lý lý do khác nếu được chọn
        $reason = $request->reason;
        if ($reason === 'other' && $request->has('custom_reason')) {
            $reason = $request->custom_reason;
        }
        
        // Cập nhật số lượng
        $inventory->update([
            'quantity' => $newQuantity,
            'in_stock' => $newQuantity > 0
        ]);
        
        // Cập nhật tổng số lượng và trạng thái sản phẩm
        $product = $inventory->product;
        $product->stock_quantity = $product->inventories()->sum('quantity');
        
        // Cập nhật trạng thái sản phẩm dựa trên tồn kho
        if ($product->stock_quantity > 0 && $product->status === 'out_of_stock') {
            $product->status = 'active';
        } elseif ($product->stock_quantity <= 0 && $product->status === 'active') {
            $product->status = 'out_of_stock';
        }
        
        $product->save();
        
        // Ghi nhận lịch sử điều chỉnh
        $this->recordInventoryMovement(
            $inventory, 
            abs($difference), 
            $difference > 0 ? 'in' : 'out', 
            $reason . " (Điều chỉnh từ $currentQuantity sang $newQuantity)",
            $request->input('source'),
            $request->input('supplier_id')
        );
        
        return redirect()->route('admin.inventory.index', ['product_id' => $inventory->product_id])
            ->with('success', 'Đã điều chỉnh tồn kho thành công.');
    }

    /**
     * Add stock to an inventory and record the movement.
     */
    private function addStockToInventory(Inventory $inventory, int $quantity, ?string $notes, ?string $source = null, ?int $supplier_id = null)
    {
        // Cập nhật số lượng trong inventory
        $inventory->quantity += $quantity;
        $inventory->in_stock = $inventory->quantity > 0;
        $inventory->save();
        
        // Ghi nhận lịch sử nhập kho
        $this->recordInventoryMovement($inventory, $quantity, 'in', $notes, $source, $supplier_id);
    }

    /**
     * Record an inventory movement.
     */
    private function recordInventoryMovement(Inventory $inventory, int $quantity, string $type, ?string $notes, ?string $source = null, ?int $supplier_id = null)
    {
        try {
            // Kiểm tra movement đã tồn tại chưa để tránh trùng lặp
            $existingMovement = $inventory->movements()
                ->whereDate('created_at', now()->toDateString())
                ->where('type', $type)
                ->where('quantity', $quantity)
                ->first();
                
            if ($existingMovement) {
                \Log::info('Đã tồn tại bản ghi chuyển động tồn kho:', ['movement_id' => $existingMovement->id]);
                return $existingMovement;
            }
            
            $data = [
                'inventory_id' => $inventory->id,
                'quantity' => $quantity,
                'type' => $type,
                'reason' => $notes ?? 'Nhập hàng vào kho',
                'user_id' => auth()->id() ?? 1,
                'source' => $source,
                'supplier_id' => $supplier_id
            ];
            
            \Log::info('Ghi nhận chuyển động tồn kho:', ['inventory_id' => $inventory->id, 'data' => $data]);
            
            // Tạo trực tiếp đối tượng InventoryMovement thay vì qua relationship
            $movement = new \App\Models\InventoryMovement();
            $movement->inventory_id = $inventory->id;
            $movement->quantity = $quantity;
            $movement->type = $type;
            $movement->reason = $notes ?? 'Nhập hàng vào kho';
            $movement->user_id = auth()->id() ?? 1;
            $movement->source = $source;
            $movement->supplier_id = $supplier_id;
            $movement->save();
            
            \Log::info('Đã tạo bản ghi chuyển động tồn kho mới:', ['movement_id' => $movement->id]);
            
            return $movement;
        } catch (\Exception $e) {
            \Log::error('Lỗi khi ghi nhận chuyển động tồn kho:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'inventory_id' => $inventory->id,
                'quantity' => $quantity,
                'type' => $type
            ]);
            // Vẫn giữ tồn kho nhưng ghi log lỗi
            return null;
        }
    }

    /**
     * Display the form to add stock to a product.
     */
    public function addStockForm(Request $request)
    {
        $product = null;
        $products = Product::orderBy('name')->get();
        
        if ($request->has('product_id')) {
            $product = Product::with(['category', 'images'])->findOrFail($request->product_id);
        }
        
        return view('admin.inventory.add-stock', compact('product', 'products'));
    }

    /**
     * Delete an inventory variant.
     */
    public function deleteVariant(Request $request, $id)
    {
        try {
            $inventory = Inventory::with('product')->findOrFail($id);
            $productId = $inventory->product_id;
            $variantInfo = "Biến thể: " . ($inventory->color ?? 'N/A') . " - " . ($inventory->size ?? 'N/A');
            
            DB::beginTransaction();
            
            // Ghi nhận movement trước khi xóa
            if ($inventory->quantity > 0) {
                $this->recordInventoryMovement(
                    $inventory,
                    $inventory->quantity,
                    'out',
                    "Xóa biến thể khỏi hệ thống"
                );
            }
            
            // Xóa inventory
            $inventory->delete();
            
            // Cập nhật tổng stock của sản phẩm
            $product = Product::findOrFail($productId);
            $product->stock_quantity = $product->inventories()->sum('quantity');
            
            // Cập nhật trạng thái sản phẩm dựa trên tồn kho
            if ($product->stock_quantity > 0 && $product->status === 'out_of_stock') {
                $product->status = 'active';
            } elseif ($product->stock_quantity <= 0 && $product->status === 'active') {
                $product->status = 'out_of_stock';
            }
            
            $product->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Đã xóa ' . $variantInfo . ' thành công');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Log debug information to a separate file
     */
    private function logDebug($message, $data = [])
    {
        // Đảm bảo thư mục logs/debug tồn tại
        $logDir = storage_path('logs/debug');
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        $logFile = $logDir . '/inventory_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $logData = [
            'timestamp' => $timestamp,
            'message' => $message,
            'data' => $data
        ];
        
        // Add to regular Laravel log
        \Log::info($message, $data);
        
        // Also add to our debug log
        $logContent = json_encode($logData, JSON_PRETTY_PRINT) . "\n\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);
    }

    /**
     * Add stock directly via URL parameter (for emergency cases)
     */
    public function addStockDirect(Request $request)
    {
        $this->logDebug('Thêm hàng trực tiếp qua URL - Dữ liệu:', $request->all());
        
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id');
        $quantity = (int)$request->input('quantity', 1);
        
        if (!$productId || !$variantId || $quantity <= 0) {
            return redirect()->back()->with('error', 'Dữ liệu không hợp lệ để thêm hàng');
        }
        
        DB::beginTransaction();
        
        try {
            $product = Product::findOrFail($productId);
            $inventory = Inventory::findOrFail($variantId);
            
            if ($inventory->product_id != $product->id) {
                throw new \Exception('Biến thể không thuộc về sản phẩm này');
            }
            
            // Cập nhật số lượng
            $oldQuantity = $inventory->quantity;
            $inventory->quantity += $quantity;
            $inventory->in_stock = true;
            $inventory->save();
            
            // Ghi nhận lịch sử
            $this->recordInventoryMovement($inventory, $quantity, 'in', 'Nhập hàng nhanh qua URL');
            
            // Cập nhật tổng số lượng sản phẩm
            $product->stock_quantity = $product->inventories()->sum('quantity');
            
            // Cập nhật trạng thái
            if ($product->stock_quantity > 0 && $product->status === 'out_of_stock') {
                $product->status = 'active';
            }
            
            $product->save();
            
            DB::commit();
            
            $this->logDebug('Đã thêm hàng trực tiếp thành công:', [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $inventory->quantity,
                'added' => $quantity
            ]);
            
            return redirect()->back()->with('success', "Đã thêm $quantity sản phẩm vào biến thể {$inventory->color} - {$inventory->size}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logDebug('Lỗi khi thêm hàng trực tiếp:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Lỗi khi thêm hàng: ' . $e->getMessage());
        }
    }
    
    /**
     * Add new variant directly
     */
    public function addVariantDirect(Request $request)
    {
        $this->logDebug('Thêm biến thể mới trực tiếp - Dữ liệu:', $request->all());
        
        $productId = $request->input('product_id');
        $size = $request->input('size', '');
        $color = $request->input('color', '');
        $quantity = (int)$request->input('quantity', 1);
        $lowStockThreshold = (int)$request->input('low_stock_threshold', 5);
        $location = $request->input('location', '');
        
        if (!$productId || $quantity <= 0) {
            return redirect()->back()->with('error', 'Dữ liệu không hợp lệ để thêm biến thể');
        }
        
        DB::beginTransaction();
        
        try {
            $product = Product::findOrFail($productId);
            
            // Kiểm tra xem biến thể đã tồn tại chưa
            $existingInventory = $product->inventories()
                ->where('size', $size)
                ->where('color', $color)
                ->first();
                
            if ($existingInventory) {
                // Cập nhật inventory hiện có
                $oldQuantity = $existingInventory->quantity;
                $existingInventory->quantity += $quantity;
                $existingInventory->in_stock = true;
                $existingInventory->save();
                
                // Ghi log movement
                $this->recordInventoryMovement($existingInventory, $quantity, 'in', 'Thêm số lượng cho biến thể đã tồn tại');
                
                $this->logDebug('Đã cập nhật biến thể hiện có:', [
                    'variant_id' => $existingInventory->id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $existingInventory->quantity,
                    'added' => $quantity
                ]);
                
                $variantMessage = "Đã thêm $quantity sản phẩm vào biến thể {$existingInventory->color} - {$existingInventory->size}";
            } else {
                // Tạo inventory mới
                $inventory = new Inventory();
                $inventory->product_id = $product->id;
                $inventory->size = $size;
                $inventory->color = $color;
                $inventory->quantity = $quantity;
                $inventory->low_stock_threshold = $lowStockThreshold;
                $inventory->location = $location;
                $inventory->in_stock = true;
                $inventory->save();
                
                // Ghi log movement
                $this->recordInventoryMovement($inventory, $quantity, 'in', 'Tạo biến thể mới');
                
                $this->logDebug('Đã tạo biến thể mới:', [
                    'variant_id' => $inventory->id,
                    'color' => $color,
                    'size' => $size,
                    'quantity' => $quantity
                ]);
                
                $variantMessage = "Đã tạo biến thể mới {$color} - {$size} với số lượng $quantity";
            }
            
            // Cập nhật tổng số lượng sản phẩm
            $product->stock_quantity = $product->inventories()->sum('quantity');
            
            // Cập nhật trạng thái
            if ($product->stock_quantity > 0 && $product->status === 'out_of_stock') {
                $product->status = 'active';
            }
            
            $product->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', $variantMessage);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logDebug('Lỗi khi thêm biến thể mới:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Lỗi khi thêm biến thể: ' . $e->getMessage());
        }
    }
}


