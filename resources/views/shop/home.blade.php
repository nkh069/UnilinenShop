@extends('layouts.shop')

@section('title', 'Trang Chủ - Cửa Hàng Thời Trang')

@section('content')
<style>
/* Product Card Styles - Modern Version */
.product-card {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    background: linear-gradient(to bottom, #ffffff, #f9f9f9);
    display: flex;
    flex-direction: column;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.07);
    border-color: rgba(74, 108, 247, 0.2);
}

.product-image {
    overflow: hidden;
    position: relative;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    height: 280px; /* Cố định chiều cao phần ảnh */
}

.product-image::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.03));
    z-index: 1;
}

.product-image img {
    transition: transform 0.7s cubic-bezier(0.33, 1, 0.68, 1);
    will-change: transform;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-card:hover .product-image img {
    transform: scale(1.08);
}

.product-action {
    position: absolute;
    bottom: -60px;
    left: 0;
    right: 0;
    background: rgba(255,255,255,0.95);
    padding: 15px;
    transition: all 0.4s cubic-bezier(0.33, 1, 0.68, 1);
    opacity: 0;
    z-index: 10;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border-top: 1px solid rgba(0,0,0,0.05);
}

.product-card:hover .product-action {
    bottom: 0;
    opacity: 1;
}

.product-action .btn {
    transition: all 0.3s ease;
    border-radius: 6px;
    font-weight: 500;
    letter-spacing: 0.3px;
    padding: 8px 12px;
}

.product-action .btn-outline-dark:hover {
    background-color: #343a40;
    color: white;
}

.product-action .btn-primary {
    background: linear-gradient(135deg, #4a6cf7, #2a4adf);
    border: none;
    box-shadow: 0 3px 10px rgba(42, 74, 223, 0.2);
}

.product-action .btn-primary:hover {
    background: linear-gradient(135deg, #5d7df8, #3a5af0);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(42, 74, 223, 0.3);
}

.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 3;
}

.product-tag {
    z-index: 2;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 12px;
    padding: 5px 12px;
    margin-bottom: 8px;
    border-radius: 30px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    display: inline-flex;
    align-items: center;
}

.product-tag.bg-danger {
    background: linear-gradient(135deg, #ff6b6b, #ee5253) !important;
}

.product-tag.bg-success {
    background: linear-gradient(135deg, #1dd1a1, #10ac84) !important;
}

.product-tag.bg-primary {
    background: linear-gradient(135deg, #4a6cf7, #2a4adf) !important;
}

.product-tag i {
    margin-right: 4px;
    font-size: 10px;
}

.product-content {
    padding: 18px;
    display: flex;
    flex-direction: column;
    height: 190px; /* Cố định chiều cao phần nội dung */
}

.product-title {
    margin-bottom: 8px;
    height: 44px; /* Cố định chiều cao phần tiêu đề */
}

.product-title h5 {
    font-size: 16px;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-weight: 600;
    color: #333;
    transition: color 0.3s ease;
    margin: 0;
}

.product-card:hover .product-title h5 {
    color: #4a6cf7;
}

.product-brand {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 11px;
    background: linear-gradient(135deg, #4a6cf7, #2a4adf);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    margin-bottom: 6px;
    height: 16px; /* Cố định chiều cao phần thương hiệu */
}

/* Cải thiện hiển thị đánh giá */
.product-rating {
    display: flex;
    align-items: center;
    margin: 10px 0;
    gap: 8px;
    padding: 3px 0;
    transition: all 0.3s ease;
}

.product-rating .bi-star-fill {
    color: #ffba00;
    font-size: 15px;
    margin-right: 1px;
    filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
    transition: all 0.3s ease;
}

.product-rating .bi-star-half {
    color: #ffba00;
    font-size: 15px;
    margin-right: 1px;
    filter: drop-shadow(0 0 1px rgba(255, 186, 0, 0.3));
    transition: all 0.3s ease;
}

.product-rating .bi-star {
    color: #e0e0e0;
    font-size: 15px;
    margin-right: 1px;
    transition: all 0.3s ease;
}

.product-rating span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f8f8;
    color: #666;
    font-size: 12px;
    font-weight: 500;
    padding: 2px 8px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.product-card:hover .product-rating .bi-star-fill,
.product-card:hover .product-rating .bi-star-half {
    color: #ffa000;
    transform: scale(1.1);
}

.product-card:hover .product-rating span {
    background-color: #f0f0f0;
    color: #333;
}

.product-price {
    margin-top: auto; /* Đẩy giá xuống cuối cùng trong phần nội dung */
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    height: 28px; /* Cố định chiều cao phần giá */
}

.new-price {
    font-size: 18px;
    font-weight: 700;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.old-price {
    font-size: 14px;
    opacity: 0.6;
}

.price {
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.wishlist-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 3;
    background: rgba(255,255,255,0.9);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    opacity: 0;
    transform: translateY(-10px) scale(0.9);
    transition: all 0.4s cubic-bezier(0.33, 1, 0.68, 1);
}

.product-card:hover .wishlist-btn {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.wishlist-btn:hover {
    background: #fff;
    transform: scale(1.1) !important;
}

.wishlist-btn:hover .bi-heart {
    color: #e74c3c;
    transform: scale(1.2);
}

.wishlist-btn .bi-heart {
    transition: all 0.3s ease;
    font-size: 18px;
}

.scroll-btn {
    transition: all 0.3s cubic-bezier(0.33, 1, 0.68, 1);
    opacity: 0.9;
    z-index: 10 !important;
    width: 52px !important;
    height: 52px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    border: 2px solid #4a6cf7;
}

.scroll-btn.left {
    left: -20px;
}

.scroll-btn.right {
    right: -20px;
}

.scroll-btn:hover {
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    background: #4a6cf7;
    color: white;
}

.scroll-btn:hover i {
    color: white;
}

.scroll-btn i {
    font-size: 24px;
    color: #4a6cf7;
}

.position-relative {
    position: relative !important;
    overflow: visible; /* Điều chỉnh để nút hiện thị rõ ràng hơn */
    padding: 0 20px;
}

.products-horizontal {
    display: flex !important;
    flex-wrap: nowrap !important;
    overflow-x: auto !important;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    padding: 10px 5px 20px 5px;
}

.products-horizontal::-webkit-scrollbar {
    height: 6px;
}
.products-horizontal::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.products-horizontal::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #4a6cf7, #2a4adf);
    border-radius: 10px;
}
.products-horizontal::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, #5d7df8, #3a5af0);
}

.product-card-wrapper {
    flex: 0 0 auto !important;
    width: 280px !important;
    margin-right: 25px !important;
    padding-bottom: 15px;
    transition: all 0.3s ease;
    height: 500px; /* Cố định chiều cao tổng thể của product card */
}

.section-title h2 {
    position: relative;
    display: inline-block;
    font-weight: 700;
}

.section-title h2:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #4a6cf7, #2a4adf);
    border-radius: 3px;
}

.featured-products, .new-arrivals {
    position: relative;
}

.featured-products:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 300px;
    background: linear-gradient(180deg, rgba(74, 108, 247, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
    z-index: 0;
}

/* Category Cards Styling */
.category-card {
    border: 1px solid rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: rgba(74, 108, 247, 0.2);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.9;
    transition: all 0.4s ease;
}

.category-card:hover .category-overlay {
    background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.8));
}

/* Feature Box Styling */
.feature-box {
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    transition: all 0.3s ease;
    background-color: white;
}

.feature-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: rgba(74, 108, 247, 0.2);
}
</style>

<!-- Sản phẩm bán chạy -->
<section class="py-5 bg-light">
    <div class="container px-6" style="border-radius: 12px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding-top: 2rem; padding-bottom: 2rem;">
        <div class="section-title text-center mb-5">
            <h2 class="mb-3">Sản phẩm bán chạy</h2>
            <p>Những lựa chọn hàng đầu được khách hàng yêu thích</p>
                    </div>
        
        <div class="position-relative">
            <div class="products-horizontal d-flex flex-nowrap overflow-auto pb-3">
                @if($bestSellingProducts->count() > 0)
                    @foreach($bestSellingProducts as $product)
                    <div class="product-card-wrapper flex-shrink-0">
                        <div class="product-card bg-white rounded overflow-hidden">
                            <div class="product-image">
                                <!-- Product badges -->
                                <div class="product-badge">
                                    @if($product->sale_price)
                                    <div class="product-tag bg-danger text-white">
                                        <i class="bi bi-tag-fill"></i> Sale
                </div>
                                    @endif
                                    <div class="product-tag bg-primary text-white">
                                        <i class="bi bi-graph-up"></i> Bán chạy
            </div>
                                </div>
                                <!-- Product image -->
                                <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->thumbnail_path ?? $product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" alt="{{ $product->name }}" class="img-fluid">
                                
                                <!-- Product actions -->
                                <div class="product-action">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary flex-grow-1 me-2">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                        <button class="btn btn-outline-dark wishlist-btn" onclick="addToWishlist({{ $product->id }})">
                                            <i class="bi bi-heart"></i>
                                        </button>
        </div>
    </div>
</div>

                            <!-- Product content -->
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="product-content p-3">
                                @if($product->brand)
                                <div class="product-brand">{{ $product->brand }}</div>
                                @endif
                                
                                <div class="product-title">
                                    <h5>{{ $product->name }}</h5>
        </div>
        
                                <div class="product-rating">
                                    @php
                                        $rating = $product->reviews->avg('rating') ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = ceil($rating - $fullStars);
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp
                                    
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="bi bi-star-fill"></i>
                                    @endfor
                                    
                                    @if($halfStar)
                                        <i class="bi bi-star-half"></i>
                                    @endif
                                    
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="bi bi-star"></i>
                                    @endfor
                                    
                                    <span>{{ $product->reviews->count() }} đánh giá</span>
                        </div>
                                
                                <div class="product-price d-flex align-items-center">
                                    @if($product->sale_price)
                                    <span class="new-price me-2">{{ number_format($product->sale_price) }} VNĐ</span>
                                    <span class="old-price text-decoration-line-through">{{ number_format($product->price) }} VNĐ</span>
                                    @else
                                    <span class="price fw-bold">{{ number_format($product->price) }} VNĐ</span>
                                    @endif
                    </div>
                            </div>
                            </a>
                </div>
            </div>
            @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>Chưa có dữ liệu sản phẩm bán chạy. Vui lòng quay lại sau.</p>
                    </div>
                @endif
            </div>
            
            <!-- Nút cuộn trái phải -->
            <button class="scroll-btn left" id="scroll-left-bestselling">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="scroll-btn right" id="scroll-right-bestselling">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Xem Tất Cả Sản Phẩm</a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products py-5 bg-light">
    <div class="container px-6" style="border-radius: 12px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding-top: 2rem; padding-bottom: 2rem;">
        <div class="section-title text-center mb-5">
            <h2 class="mb-3">Sản Phẩm Nổi Bật</h2>
            <p>Những sản phẩm được ưa chuộng nhất tại cửa hàng chúng tôi</p>
        </div>
        
        <div class="position-relative">
            <div class="products-horizontal d-flex flex-nowrap overflow-auto pb-3">
                @foreach($featuredProducts as $product)
                <div class="product-card-wrapper flex-shrink-0">
                    <div class="product-card bg-white rounded overflow-hidden">
                        <div class="product-image">
                            <!-- Product badges -->
                            <div class="product-badge">
                            @if($product->sale_price)
                                <div class="product-tag bg-danger text-white">
                                    <i class="bi bi-tag-fill"></i> Sale
                                </div>
                            @endif
                            @if($product->created_at->diffInDays(now()) < 7)
                                <div class="product-tag bg-success text-white">
                                    <i class="bi bi-stars"></i> Mới
                                </div>
                            @endif
                            </div>
                            <!-- Product image -->
                            <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->thumbnail_path ?? $product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" alt="{{ $product->name }}" class="img-fluid">
                            
                            <!-- Product actions -->
                            <div class="product-action">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary flex-grow-1 me-2">
                                        <i class="bi bi-eye"></i> Xem chi tiết
                                    </a>
                                    <button class="btn btn-outline-dark wishlist-btn" onclick="addToWishlist({{ $product->id }})">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                            </div>
                        </div>
                        
                        <!-- Product content -->
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                        <div class="product-content p-3">
                            @if($product->brand)
                            <div class="product-brand">{{ $product->brand }}</div>
                            @endif
                            
                            <div class="product-title">
                                <h5>{{ $product->name }}</h5>
                            </div>
                            
                            <div class="product-rating">
                                @php
                                    $rating = $product->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStar = ceil($rating - $fullStars);
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp
                                
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                                
                                @if($halfStar)
                                    <i class="bi bi-star-half"></i>
                                @endif
                                
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star"></i>
                                @endfor
                                
                                <span>{{ $product->reviews->count() }} đánh giá</span>
                            </div>
                            
                            <div class="product-price d-flex align-items-center">
                                @if($product->sale_price)
                                <span class="new-price me-2">{{ number_format($product->sale_price) }} VNĐ</span>
                                <span class="old-price text-decoration-line-through">{{ number_format($product->price) }} VNĐ</span>
                                @else
                                <span class="price fw-bold">{{ number_format($product->price) }} VNĐ</span>
                                @endif
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Nút cuộn trái phải -->
            <button class="scroll-btn left" id="scroll-left-featured">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="scroll-btn right" id="scroll-right-featured">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Xem Tất Cả Sản Phẩm</a>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="new-arrivals py-5">
    <div class="container px-6" style="border-radius: 12px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding-top: 2rem; padding-bottom: 2rem;">
        <div class="section-title text-center mb-5">
            <h2 class="mb-3">Sản Phẩm Mới</h2>
            <p>Khám phá những sản phẩm mới nhất của chúng tôi</p>
        </div>
        
        <div class="position-relative">
            <div class="products-horizontal d-flex flex-nowrap overflow-auto pb-3">
                @foreach($newArrivals as $product)
                <div class="product-card-wrapper flex-shrink-0">
                    <div class="product-card bg-white rounded overflow-hidden">
                        <div class="product-image">
                            <!-- Product badges -->
                            <div class="product-badge">
                            @if($product->sale_price)
                                <div class="product-tag bg-danger text-white">
                                    <i class="bi bi-tag-fill"></i> Sale
                                </div>
                            @endif
                            @if($product->created_at->diffInDays(now()) < 7)
                                <div class="product-tag bg-success text-white">
                                    <i class="bi bi-stars"></i> Mới
                                </div>
                            @endif
                            </div>
                            <!-- Product image -->
                            <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->thumbnail_path ?? $product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" alt="{{ $product->name }}" class="img-fluid">
                            
                            <!-- Product actions -->
                            <div class="product-action">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary flex-grow-1 me-2">
                                        <i class="bi bi-eye"></i> Xem chi tiết
                                    </a>
                                    <button class="btn btn-outline-dark wishlist-btn" onclick="addToWishlist({{ $product->id }})">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                            </div>
                        </div>
                        
                        <!-- Product content -->
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                        <div class="product-content p-3">
                            @if($product->brand)
                            <div class="product-brand">{{ $product->brand }}</div>
                            @endif
                            
                            <div class="product-title">
                                <h5>{{ $product->name }}</h5>
                            </div>
                            
                            <div class="product-rating">
                                @php
                                    $rating = $product->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStar = ceil($rating - $fullStars);
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp
                                
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                                
                                @if($halfStar)
                                    <i class="bi bi-star-half"></i>
                                @endif
                                
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="bi bi-star"></i>
                                @endfor
                                
                                <span>{{ $product->reviews->count() }} đánh giá</span>
                            </div>
                            
                            <div class="product-price d-flex align-items-center">
                                @if($product->sale_price)
                                <span class="new-price me-2">{{ number_format($product->sale_price) }} VNĐ</span>
                                <span class="old-price text-decoration-line-through">{{ number_format($product->price) }} VNĐ</span>
                                @else
                                <span class="price fw-bold">{{ number_format($product->price) }} VNĐ</span>
                                @endif
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Nút cuộn trái phải -->
            <button class="scroll-btn left" id="scroll-left-new">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="scroll-btn right" id="scroll-right-new">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Xem Tất Cả Sản Phẩm</a>
        </div>
    </div>
</section>

<!-- Feature Boxes -->
<section class="feature-boxes py-5">
    <div class="container px-6">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-box p-4">
                    <i class="bi bi-truck fs-1 mb-3 text-primary"></i>
                    <h5>Miễn Phí Vận Chuyển</h5>
                    <p class="text-muted">Cho đơn hàng trên 500K</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-box p-4">
                    <i class="bi bi-arrow-repeat fs-1 mb-3 text-primary"></i>
                    <h5>Đổi Trả Dễ Dàng</h5>
                    <p class="text-muted">Trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-box p-4">
                    <i class="bi bi-shield-check fs-1 mb-3 text-primary"></i>
                    <h5>Thanh Toán An Toàn</h5>
                    <p class="text-muted">Bảo mật 100%</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-box p-4">
                    <i class="bi bi-headset fs-1 mb-3 text-primary"></i>
                    <h5>Hỗ Trợ 24/7</h5>
                    <p class="text-muted">Tư vấn trực tuyến</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Add to wishlist function
    function addToWishlist(productId) {
        // Prevent the click event from propagating to parent elements
        // This prevents the card from redirecting to product detail page
        event.stopPropagation();
        
        // Implement wishlist logic here
        alert('Đã thêm sản phẩm vào danh sách yêu thích: ' + productId);
    }
    
    // Horizontal scroll buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Tạo CSS động để đảm bảo hiển thị
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            .products-horizontal {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                padding-bottom: 15px;
            }
            .product-card-wrapper {
                flex: 0 0 auto !important;
                width: 280px !important;
                margin-right: 20px !important;
            }
        `;
        document.head.appendChild(styleElement);

        // Xử lý nút trượt cho sản phẩm bán chạy
        const bestSellingContainer = document.querySelector('.py-5.bg-light .products-horizontal');
        const scrollLeftBestSelling = document.getElementById('scroll-left-bestselling');
        const scrollRightBestSelling = document.getElementById('scroll-right-bestselling');

        if (scrollLeftBestSelling && scrollRightBestSelling && bestSellingContainer) {
            console.log('Best selling navigation initialized');
            scrollLeftBestSelling.addEventListener('click', function() {
                bestSellingContainer.scrollBy({ left: -300, behavior: 'smooth' });
            });

            scrollRightBestSelling.addEventListener('click', function() {
                bestSellingContainer.scrollBy({ left: 300, behavior: 'smooth' });
            });
        } else {
            console.log('Best selling navigation elements not found');
        }
        
        // Xử lý nút trượt cho sản phẩm nổi bật
        const featuredContainer = document.querySelector('.featured-products .products-horizontal');
        const scrollLeftFeatured = document.getElementById('scroll-left-featured');
        const scrollRightFeatured = document.getElementById('scroll-right-featured');
        
        if (scrollLeftFeatured && scrollRightFeatured && featuredContainer) {
            console.log('Featured navigation initialized');
            scrollLeftFeatured.addEventListener('click', function() {
                featuredContainer.scrollBy({ left: -300, behavior: 'smooth' });
            });
            
            scrollRightFeatured.addEventListener('click', function() {
                featuredContainer.scrollBy({ left: 300, behavior: 'smooth' });
            });
        } else {
            console.log('Featured navigation elements not found');
        }
        
        // Xử lý nút trượt cho sản phẩm mới
        const newContainer = document.querySelector('.new-arrivals .products-horizontal');
        const scrollLeftNew = document.getElementById('scroll-left-new');
        const scrollRightNew = document.getElementById('scroll-right-new');
        
        if (scrollLeftNew && scrollRightNew && newContainer) {
            console.log('New arrivals navigation initialized');
            scrollLeftNew.addEventListener('click', function() {
                newContainer.scrollBy({ left: -300, behavior: 'smooth' });
            });
            
            scrollRightNew.addEventListener('click', function() {
                newContainer.scrollBy({ left: 300, behavior: 'smooth' });
            });
        } else {
            console.log('New arrivals navigation elements not found');
        }
    });
</script>
@endsection 