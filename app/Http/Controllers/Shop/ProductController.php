<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')->with(['images', 'category']);
        
        // Filter by category
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                if ($category->parent_id === null) {
                    // Main category - get all products from its subcategories
                    $childCategoryIds = $category->children()->pluck('id')->toArray();
                    $query->whereIn('category_id', $childCategoryIds);
                } else {
                    // Subcategory
                    $query->where('category_id', $category->id);
                }
            }
        }
        
        // Filter by search term
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by brands
        if ($request->has('brands')) {
            $query->whereIn('brand', $request->brands);
        }
        
        // Filter by price range
        if ($request->has('price_from') && is_numeric($request->price_from)) {
            $query->where(function($q) use ($request) {
                $q->where('price', '>=', $request->price_from)
                  ->orWhere(function($q2) use ($request) {
                      $q2->whereNotNull('sale_price')
                        ->where('sale_price', '>=', $request->price_from);
                  });
            });
        }
        
        if ($request->has('price_to') && is_numeric($request->price_to)) {
            $query->where(function($q) use ($request) {
                $q->where('price', '<=', $request->price_to)
                  ->orWhere(function($q2) use ($request) {
                      $q2->whereNotNull('sale_price')
                        ->where('sale_price', '<=', $request->price_to);
                  });
            });
        }
        
        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('sale_price', 'asc')
                          ->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('sale_price', 'desc')
                          ->orderBy('price', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        
        // Lấy danh sách các thương hiệu để hiển thị trong bộ lọc
        $brands = Product::where('status', 'active')
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->toArray();
        
        // Xác định tiêu đề cho trang và tên danh mục
        $title = 'Tất cả sản phẩm';
        $categoryName = null; // Khởi tạo biến với giá trị mặc định
        
        if ($request->has('category') && isset($category)) {
            $title = $category->name;
            $categoryName = $category->name;
        }
        
        return view('shop.products.index', compact('products', 'categories', 'brands', 'title', 'categoryName'));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['images', 'category', 'inventories', 'colors', 'sizes', 'reviews' => function($query) {
                $query->where('is_approved', true)->with('user');
            }])
            ->withCount('reviews')
            ->firstOrFail();
            
        // Tính trung bình đánh giá
        $averageRating = $product->reviews->avg('rating') ?? 0;
        $product->reviews_avg_rating = $averageRating;
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with('images')
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('shop.products.show', compact('product', 'averageRating', 'relatedProducts'));
    }
}
