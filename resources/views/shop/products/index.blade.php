@extends('layouts.shop')

@section('title', $title ?? 'Sản phẩm')

@section('content')
<!-- Breadcrumb -->
<div class="bg-neutral-100 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-2 text-sm text-neutral-600">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition">Trang chủ</a>
            <span class="font-bold">›</span>
            @if(isset($categoryName))
                <span class="font-medium">{{ $categoryName }}</span>
            @else
                <span class="font-medium">Tất cả sản phẩm</span>
            @endif
        </div>
    </div>
</div>

<!-- Products Section -->
<div class="container mx-auto px-4 py-12">
    <!-- Page Title -->
    <div class="mb-10 text-center" data-aos="fade-up">
        <h1 class="text-3xl md:text-4xl font-serif font-semibold text-neutral-900 mb-4">{{ $title }}</h1>
        <p class="text-neutral-600 max-w-2xl mx-auto">Khám phá bộ sưu tập sản phẩm thời trang chất lượng cao và phong cách của chúng tôi.</p>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full lg:w-1/4 xl:w-1/5" data-aos="fade-right">
            <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-neutral-900 border-b border-neutral-100 pb-3 mb-6">Bộ lọc sản phẩm</h3>
                
                <!-- Filter Form -->
                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-medium text-neutral-800 mb-3">Danh mục</h4>
                        <div class="space-y-2">
                            @foreach($categories->where('parent_id', null) as $category)
                                <div class="mb-3">
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                       class="block font-medium {{ request('category') == $category->slug ? 'text-primary-600' : 'text-neutral-800 hover:text-primary-600' }} transition">
                                        {{ $category->name }}
                                    </a>
                                    
                                    @if($category->children->count() > 0)
                                        <div class="ml-4 mt-1 space-y-1">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('products.index', ['category' => $child->slug]) }}" 
                                                   class="block text-sm py-1 {{ request('category') == $child->slug ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-medium text-neutral-800 mb-3">Khoảng giá</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-neutral-500 mb-1 block">Từ</label>
                                <input type="number" name="price_from" value="{{ request('price_from') }}" 
                                       class="w-full border border-neutral-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                       placeholder="0 ₫">
                            </div>
                            <div>
                                <label class="text-xs text-neutral-500 mb-1 block">Đến</label>
                                <input type="number" name="price_to" value="{{ request('price_to') }}" 
                                       class="w-full border border-neutral-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                       placeholder="1.000.000 ₫">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Brands -->
                    @if(count($brands) > 0)
                    <div class="mb-6">
                        <h4 class="font-medium text-neutral-800 mb-3">Thương hiệu</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                            @foreach($brands as $brand)
                                <div class="flex items-center">
                                    <input type="checkbox" name="brands[]" value="{{ $brand }}" 
                                           id="brand-{{ Str::slug($brand) }}"
                                           {{ in_array($brand, request('brands', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                    <label for="brand-{{ Str::slug($brand) }}" class="ml-2 text-sm text-neutral-700">
                                        {{ $brand }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out mb-2">
                        Áp dụng bộ lọc
                    </button>
                    
                    <!-- Reset Filters -->
                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-neutral-500 hover:text-primary-600 transition">
                        Xóa tất cả bộ lọc
                    </a>
                </form>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="w-full lg:w-3/4 xl:w-4/5" data-aos="fade-up">
            <!-- Top bar: Sort, Results Count -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-white rounded-xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center mb-4 md:mb-0">
                    <span class="text-neutral-600 text-sm">Hiển thị {{ $products->total() }} sản phẩm</span>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                    <!-- Search input -->
                    <div class="relative w-full sm:w-auto">
                        <input type="text" name="search" form="filter-form" value="{{ request('search') }}" 
                               placeholder="Tìm kiếm sản phẩm..." 
                               class="w-full sm:w-64 border border-neutral-200 rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    
                    <!-- Sort dropdown -->
                    <div class="relative w-full sm:w-auto">
                        <select name="sort" form="filter-form" 
                                class="w-full sm:w-auto appearance-none border border-neutral-200 rounded-md px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                onchange="document.getElementById('filter-form').submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                            <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên: A-Z</option>
                            <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Tên: Z-A</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            @if($products->isEmpty())
                <div class="bg-white rounded-xl border border-neutral-100 shadow-sm p-12 text-center" data-aos="fade-up">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-neutral-300 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-medium text-neutral-800 mb-3">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-neutral-600 mb-6">Không có sản phẩm nào phù hợp với bộ lọc bạn đã chọn.</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-6 rounded-md transition duration-300 ease-in-out">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl border border-neutral-100 shadow-sm overflow-hidden group transition duration-300 hover:shadow-md" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                                <!-- Product Image -->
                                <div class="relative h-64 overflow-hidden bg-neutral-50">
                                    @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->thumbnail_path ?? $product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-neutral-100 flex items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Sale Badge -->
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                            -{{ number_format((1 - $product->sale_price / $product->price) * 100, 0) }}%
                                        </div>
                                    @endif
                                    
                                    <!-- Quick Actions -->
                                    <div class="absolute right-3 top-3 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-0 translate-x-10 transition-all duration-300">
                                        <button type="button" class="bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-primary-500 hover:text-white transition-colors duration-200" title="Yêu thích">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <button type="button" class="bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-primary-500 hover:text-white transition-colors duration-200" title="Xem nhanh">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Product Info -->
                            <div class="p-4">
                                <!-- Category -->
                                <div class="text-xs text-neutral-500 mb-1">
                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                </div>
                                
                                <!-- Product Name -->
                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <h3 class="font-medium text-neutral-800 hover:text-primary-600 text-sm sm:text-base mb-2 line-clamp-2 h-12 transition-colors duration-200">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                                
                                <!-- Rating Stars -->
                                <div class="flex items-center gap-2 my-2">
                                    <div class="flex items-center">
                                        @php
                                            $rating = $product->reviews->avg('rating') ?? 0;
                                            $fullStars = floor($rating);
                                            $halfStar = $rating - $fullStars >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        @endphp
                                        
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                        @endfor
                                        
                                        @if($halfStar)
                                            <i class="bi bi-star-half text-yellow-400 text-sm"></i>
                                        @endif
                                        
                                        @for($i = 0; $i < $emptyStars; $i++)
                                            <i class="bi bi-star text-gray-300 text-sm"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count">
                                        ({{ $product->reviews->count() }})
                                    </span>
                                </div>
                                
                                <!-- Price -->
                                <div class="mt-2 flex items-baseline space-x-2">
                                    @if($product->sale_price)
                                        <span class="text-red-600 font-semibold text-lg">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                                        <span class="text-neutral-400 text-sm line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    @else
                                        <span class="text-neutral-800 font-semibold text-lg">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    @endif
                                </div>
                                
                                <!-- Add to Cart Button -->
                                <div class="mt-4">
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <!-- <button type="submit" class="w-full bg-neutral-800 hover:bg-primary-600 text-white font-medium py-2.5 px-4 rounded-md text-sm transition duration-300 ease-in-out flex items-center justify-center">
                                            <i class="far fa-shopping-bag mr-2"></i>
                                            Thêm vào giỏ1
                                        </button> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-12">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recently Viewed Section -->
<section class="bg-neutral-50 py-12 mt-8">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-serif font-semibold text-neutral-900 mb-8 text-center">Có thể bạn sẽ thích</h2>
        
        <div class="swiper product-swiper">
            <div class="swiper-wrapper pb-8">
                @foreach(range(1, 8) as $index)
                <div class="swiper-slide">
                    <div class="bg-white rounded-xl border border-neutral-100 shadow-sm overflow-hidden group transition duration-300 hover:shadow-md h-full">
                        <a href="#" class="block relative">
                            <div class="relative h-64 overflow-hidden bg-neutral-50">
                                <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                
                                <div class="absolute right-3 top-3 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-0 translate-x-10 transition-all duration-300">
                                    <button type="button" class="bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-primary-500 hover:text-white transition-colors duration-200" title="Yêu thích">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button type="button" class="bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-primary-500 hover:text-white transition-colors duration-200" title="Xem nhanh">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                        
                        <div class="p-4">
                            <div class="text-xs text-neutral-500 mb-1">
                                Danh mục
                            </div>
                            
                            <a href="#" class="block">
                                <h3 class="font-medium text-neutral-800 hover:text-primary-600 text-sm sm:text-base mb-2 line-clamp-2 h-12 transition-colors duration-200">
                                    Sản phẩm mẫu #{{ $index }}
                                </h3>
                            </a>
                            
                            <div class="mt-2">
                                <span class="text-neutral-800 font-semibold text-lg">{{ number_format(rand(100, 1000) * 1000, 0, ',', '.') }}₫</span>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" class="w-full bg-neutral-800 hover:bg-primary-600 text-white font-medium py-2.5 px-4 rounded-md text-sm transition duration-300 ease-in-out flex items-center justify-center">
                                    <i class="far fa-shopping-bag mr-2"></i>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    /* Rating styles */
    .bi-star-fill {
        color: #ffba00 !important;
        font-size: 0.9rem;
        margin-right: 1px;
        filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
        transition: all 0.3s ease;
    }

    .bi-star-half {
        color: #ffba00 !important;
        font-size: 0.9rem;
        margin-right: 1px;
        filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
        transition: all 0.3s ease;
    }

    .bi-star {
        color: #e0e0e0 !important;
        font-size: 0.9rem;
        margin-right: 1px;
        transition: all 0.3s ease;
    }
    
    .group:hover .bi-star-fill,
    .group:hover .bi-star-half {
        transform: scale(1.1);
    }
    
    /* Rating count badge */
    .rating-count {
        background-color: #f8f8f8;
        color: #666;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.1rem 0.5rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .group:hover .rating-count {
        background-color: #f0f0f0;
        color: #333;
    }
</style>
@endsection 