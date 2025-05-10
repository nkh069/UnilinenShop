<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    public function index()
    {
        // Lấy danh sách màu sắc và kích thước
        $colors = ProductColor::orderBy('name')->get();
        $sizes = ProductSize::orderBy('name')->get();
        
        // Lấy danh sách tất cả sản phẩm
        $products = Product::with(['colors', 'sizes'])->get();
        
        // Thống kê màu sắc
        $allColors = [];
        $colorStats = [];
        
        // Thống kê kích thước
        $allSizes = [];
        $sizeStats = [];
        
        foreach ($products as $product) {
            // Xử lý màu sắc
            if (isset($product->colors)) {
                $isCollection = is_object($product->colors) && method_exists($product->colors, 'isNotEmpty');
                $colorsIsNotEmpty = $isCollection ? $product->colors->isNotEmpty() : (!empty($product->colors) && is_array($product->colors));
                
                if ($colorsIsNotEmpty) {
                    // Xử lý khác nhau tùy thuộc vào loại dữ liệu
                    if ($isCollection) {
                        // Nếu là collection từ relationship
                        foreach ($product->colors as $color) {
                            if (!in_array($color->name, $allColors)) {
                                $allColors[] = $color->name;
                                $colorStats[$color->name] = [
                                    'count' => 1,
                                    'code' => $color->code
                                ];
                            } else {
                                $colorStats[$color->name]['count']++;
                            }
                        }
                    } else {
                        // Nếu là mảng (từ cột json)
                        foreach ($product->colors as $colorName) {
                            if (!in_array($colorName, $allColors)) {
                                $allColors[] = $colorName;
                                $colorStats[$colorName] = [
                                    'count' => 1,
                                    'code' => null  // Không có mã màu trong mảng chuỗi
                                ];
                            } else {
                                $colorStats[$colorName]['count']++;
                            }
                        }
                    }
                }
            }
            
            // Xử lý kích thước
            if (isset($product->sizes)) {
                $isCollection = is_object($product->sizes) && method_exists($product->sizes, 'isNotEmpty');
                $sizesIsNotEmpty = $isCollection ? $product->sizes->isNotEmpty() : (!empty($product->sizes) && is_array($product->sizes));
                
                if ($sizesIsNotEmpty) {
                    // Xử lý khác nhau tùy thuộc vào loại dữ liệu
                    if ($isCollection) {
                        // Nếu là collection từ relationship
                        foreach ($product->sizes as $size) {
                            if (!in_array($size->name, $allSizes)) {
                                $allSizes[] = $size->name;
                                $sizeStats[$size->name] = 1;
                            } else {
                                $sizeStats[$size->name]++;
                            }
                        }
                    } else {
                        // Nếu là mảng (từ cột json)
                        foreach ($product->sizes as $sizeName) {
                            if (!in_array($sizeName, $allSizes)) {
                                $allSizes[] = $sizeName;
                                $sizeStats[$sizeName] = 1;
                            } else {
                                $sizeStats[$sizeName]++;
                            }
                        }
                    }
                }
            }
        }
        
        // Sắp xếp theo thứ tự bảng chữ cái
        sort($allColors);
        sort($allSizes);
        
        return view('admin.attributes.index', compact(
            'colors', 
            'sizes', 
            'allColors', 
            'colorStats', 
            'allSizes', 
            'sizeStats'
        ));
    }
    
    public function addColor(Request $request)
    {
        $request->validate([
            'new_color' => 'required|string|max:50',
            'color_code' => 'nullable|string|max:7',
        ]);
        
        // Chuẩn hóa mã màu nếu có
        $colorCode = $request->color_code ? str_replace('#', '', strtoupper($request->color_code)) : null;
        
        // Kiểm tra xem màu này đã tồn tại chưa
        $existingColor = ProductColor::where('name', $request->new_color)
            ->orWhere(function($query) use ($colorCode) {
                if ($colorCode) {
                    $query->where('code', $colorCode);
                }
            })
            ->first();
        
        if ($existingColor) {
            return redirect()->route('admin.attributes.index')
                ->with('error', 'Màu sắc hoặc mã màu này đã tồn tại!');
        }
        
        // Tạo màu mới
        $color = ProductColor::create([
            'name' => $request->new_color,
            'code' => $colorCode
        ]);
        
        // Thêm màu này vào tất cả sản phẩm
        $products = Product::all();
        foreach ($products as $product) {
            $product->colors()->attach($color->id, [
                'color_code' => $colorCode
            ]);
        }
        
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Đã thêm màu sắc mới thành công');
    }
    
    public function removeColor(Request $request)
    {
        $request->validate([
            'color' => 'required|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $colorName = $request->color;
            $color = ProductColor::where('name', $colorName)->first();
            
            if ($color) {
                // Cập nhật các tồn kho có màu này trước khi xóa
                DB::table('inventories')
                    ->where('color', $colorName)
                    ->update(['color' => null]);
                    
                // Cập nhật cột colors trong products
                $products = Product::whereJsonContains('colors', $colorName)->get();
                
                foreach ($products as $product) {
                    if (is_array($product->colors)) {
                        $colors = $product->colors;
                        $colors = array_filter($colors, function($c) use ($colorName) {
                            return $c !== $colorName;
                        });
                        $product->colors = array_values($colors);
                        $product->save();
                    }
                }
                
                // Xóa màu
                $color->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.attributes.index')
                ->with('success', "Đã xóa màu '{$colorName}' thành công");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.attributes.index')
                ->with('error', "Lỗi khi xóa màu: {$e->getMessage()}");
        }
    }
    
    public function addSize(Request $request)
    {
        $request->validate([
            'new_size' => 'required|string|max:10',
        ]);
        
        // Kiểm tra xem kích thước này đã tồn tại chưa
        $existingSize = ProductSize::where('name', $request->new_size)->first();
        
        if ($existingSize) {
            return redirect()->route('admin.attributes.index')
                ->with('error', 'Kích thước này đã tồn tại!');
        }
        
        // Tạo kích thước mới
        $size = ProductSize::create([
            'name' => $request->new_size
        ]);
        
        // Thêm kích thước này vào tất cả sản phẩm
        $products = Product::all();
        foreach ($products as $product) {
            $product->sizes()->attach($size->id);
        }
        
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Đã thêm kích thước mới thành công');
    }
    
    public function removeSize(Request $request)
    {
        $request->validate([
            'size' => 'required|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $sizeName = $request->size;
            $size = ProductSize::where('name', $sizeName)->first();
            
            if ($size) {
                // Cập nhật các tồn kho có kích thước này trước khi xóa
                DB::table('inventories')
                    ->where('size', $sizeName)
                    ->update(['size' => null]);
                    
                // Cập nhật cột sizes trong products
                $products = Product::whereJsonContains('sizes', $sizeName)->get();
                
                foreach ($products as $product) {
                    if (is_array($product->sizes)) {
                        $sizes = $product->sizes;
                        $sizes = array_filter($sizes, function($s) use ($sizeName) {
                            return $s !== $sizeName;
                        });
                        $product->sizes = array_values($sizes);
                        $product->save();
                    }
                }
                
                // Xóa kích thước
                $size->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.attributes.index')
                ->with('success', "Đã xóa kích thước '{$sizeName}' thành công");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.attributes.index')
                ->with('error', "Lỗi khi xóa kích thước: {$e->getMessage()}");
        }
    }
    
    public function updateColor(Request $request)
    {
        $request->validate([
            'old_color' => 'required|string',
            'new_color' => 'required|string|max:50',
            'color_code' => 'nullable|string|max:6',
        ]);
        
        DB::beginTransaction();
        
        try {
            $color = ProductColor::where('name', $request->old_color)->first();
            
            if ($color) {
                // Chuẩn hóa mã màu nếu có
                $colorCode = $request->color_code ? strtoupper($request->color_code) : null;
                
                // Cập nhật tên và mã màu
                $color->update([
                    'name' => $request->new_color,
                    'code' => $colorCode
                ]);
                
                // Cập nhật màu trong bảng inventory
                DB::table('inventories')
                    ->where('color', $request->old_color)
                    ->update(['color' => $request->new_color]);
                
                // Cập nhật màu trong cột colors của products
                $products = Product::whereJsonContains('colors', $request->old_color)->get();
                
                foreach ($products as $product) {
                    if (is_array($product->colors)) {
                        $colors = $product->colors;
                        $index = array_search($request->old_color, $colors);
                        
                        if ($index !== false) {
                            $colors[$index] = $request->new_color;
                            $product->colors = $colors;
                            $product->save();
                        }
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.attributes.index')
                ->with('success', "Đã cập nhật màu '{$request->old_color}' thành '{$request->new_color}'");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.attributes.index')
                ->with('error', "Lỗi khi cập nhật màu: {$e->getMessage()}");
        }
    }
    
    public function updateSize(Request $request)
    {
        $request->validate([
            'old_size' => 'required|string',
            'new_size' => 'required|string|max:10',
        ]);
        
        DB::beginTransaction();
        
        try {
            $size = ProductSize::where('name', $request->old_size)->first();
            
            if ($size) {
                // Cập nhật tên kích thước
                $size->update([
                    'name' => $request->new_size
                ]);
                
                // Cập nhật kích thước trong bảng inventory
                DB::table('inventories')
                    ->where('size', $request->old_size)
                    ->update(['size' => $request->new_size]);
                
                // Cập nhật kích thước trong cột sizes của products
                $products = Product::whereJsonContains('sizes', $request->old_size)->get();
                
                foreach ($products as $product) {
                    if (is_array($product->sizes)) {
                        $sizes = $product->sizes;
                        $index = array_search($request->old_size, $sizes);
                        
                        if ($index !== false) {
                            $sizes[$index] = $request->new_size;
                            $product->sizes = $sizes;
                            $product->save();
                        }
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.attributes.index')
                ->with('success', "Đã cập nhật kích thước '{$request->old_size}' thành '{$request->new_size}'");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.attributes.index')
                ->with('error', "Lỗi khi cập nhật kích thước: {$e->getMessage()}");
        }
    }
} 