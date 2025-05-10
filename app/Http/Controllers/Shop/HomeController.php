<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Sản phẩm nổi bật
        $featuredProducts = Product::where('featured', true)
            ->where('status', 'active')
            ->with(['images', 'category', 'reviews'])
            ->take(8)
            ->get();
            
        // Danh mục sản phẩm    
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();
        
        // Sản phẩm mới
        $newArrivals = Product::where('status', 'active')
            ->with(['images', 'category', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Sản phẩm bán chạy nhất - Cách 1: Sử dụng subquery
        $bestSellingProductIds = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(8)
            ->pluck('product_id');
            
        $bestSellingProducts = Product::whereIn('id', $bestSellingProductIds)
            ->where('status', 'active')
            ->with(['images', 'category', 'reviews'])
            ->get()
            ->sortBy(function($product) use ($bestSellingProductIds) {
                return array_search($product->id, $bestSellingProductIds->toArray());
            });
            
        return view('shop.home', compact('featuredProducts', 'categories', 'newArrivals', 'bestSellingProducts'));
    }
} 