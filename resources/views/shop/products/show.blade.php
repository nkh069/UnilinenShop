@extends('layouts.shop')

@section('title', $product->name)
@section('meta_description', $product->meta_description)

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    /* Breadcrumb styling */
    .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
    }

    /* Product Image Styling */
    .product-main-image {
        width: 100%;
        max-height: 450px;
        object-fit: contain;
        margin-bottom: 15px;
        cursor: zoom-in;
    }
    
    /* Thumbnail styling */
    .product-thumbnails {
        display: flex;
        gap: 8px;
        margin-bottom: 30px;
        margin-left: 100px;
        overflow-x: auto;
        padding-bottom: 10px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }
    
    .product-thumbnail {
        width: 70px;
        height: 70px;
        border: 1px solid #ddd;
        padding: 3px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 8px;
    }
    
    .product-thumbnail:hover {
        border-color: #0d6efd;
    }
    
    .product-thumbnail.active {
        border: 2px solid #0d6efd;
    }
    
    .product-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Product details styling */
    .product-title {
        font-size: 24px;
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: bold;
        color: #000;
        margin-bottom: 20px;
    }
    
    .product-original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 18px;
    }
    
    .product-discount {
        background: #dc3545;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 14px;
        margin-left: 10px;
    }
    
    /* Quantity input styling */
    .quantity-input {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .quantity-input button {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border: 1px solid #ced4da;
        font-size: 16px;
    }
    
    .quantity-input input {
        width: 60px;
        height: 40px;
        text-align: center;
        border: 1px solid #ced4da;
        border-left: none;
        border-right: none;
    }
    
    /* Action buttons */
    .product-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
    }
    
    .btn-add-to-cart,
    .btn-buy-now {
        padding: 10px 20px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 14px;
    }
    
    .btn-add-to-cart {
        background-color: white;
        color: #0d6efd;
        border: 1px solid #0d6efd;
    }
    
    .btn-add-to-cart:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn-buy-now {
        background-color: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }
    
    /* Product information */
    .product-info {
        margin-bottom: 30px;
    }
    
    .product-info h3 {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: 500;
    }
    
    .product-info p {
        margin-bottom: 5px;
        color: #6c757d;
    }
    
    /* Tabs styling */
    .product-tab {
        border-bottom: 1px solid #dee2e6;
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .tab-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        font-weight: 500;
        color: #555;
        background-color: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .tab-link:hover {
        color: #007bff;
    }
    
    .tab-link.active {
        color: #007bff;
        border-bottom: 2px solid #007bff;
    }
    
    .tab-link i {
        margin-right: 8px;
        font-size: 18px;
    }
    
    .tab-link .review-count {
        background-color: #f0f0f0;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
        margin-left: 5px;
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
        min-height: 200px;
    }
    
    #description.tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Product rating */
    .product-rating {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .rating-stars {
        color: #ffc107;
        margin-right: 10px;
    }
    
    /* Review styling */
    .review-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s;
        margin-bottom: 20px;
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .review-card .card-body {
        padding: 20px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .review-meta {
        margin-bottom: 5px;
    }
    
    .review-user {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .review-date {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .review-rating {
        color: #ffc107;
        font-size: 1.1rem;
    }
    
    .review-content {
        color: #555;
        line-height: 1.5;
    }
    
    .review-form-card {
        border: none;
        background-color: #f9f9f9;
        border-radius: 0;
        box-shadow: none;
        margin-top: 20px;
    }
    
    /* CSS cho form đánh giá đơn giản */
    .review-form-section {
        background-color: #f8f8f8;
        padding: 15px 0;
        margin-top: 20px;
    }

    .review-form-section h5 {
        display: flex;
        align-items: center;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .review-form-section h5 i {
        margin-right: 10px;
    }

    .review-form-section .form-label {
        font-weight: normal;
        margin-bottom: 10px;
        display: block;
    }

    .review-rating-group {
        margin-bottom: 20px;
    }

    .review-rating-option {
        display: flex;
        align-items: center;
        margin-right: 15px;
        margin-bottom: 5px;
    }

    .review-rating-option input[type="radio"] {
        margin-right: 5px;
    }

    .review-rating-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .review-form-section textarea {
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 0;
        padding: 10px;
        width: 100%;
        margin-bottom: 15px;
        min-height: 100px;
    }

    .review-form-section .form-text {
        color: #777;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 15px;
    }

    .review-form-section .file-upload {
        margin-bottom: 15px;
    }

    .review-form-section .btn-submit {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 8px 20px;
        font-size: 14px;
    }

    /* Đánh giá tab style */
    .reviews-tab-header {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    
    .reviews-tab-header i {
        font-size: 1.25rem;
        margin-right: 8px;
        color: #666;
    }
    
    .reviews-tab-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    .review-empty-state {
        padding: 25px;
        text-align: center;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 25px;
        background-color: #f9f9f9;
    }
    
    .review-empty-state i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: #adb5bd;
        display: block;
    }
    
    /* Cải thiện hiển thị đánh giá */
    .reviews-list {
        display: block;
        width: 100%;
    }
    
    .review-item {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f9f9f9;
        transition: all 0.2s ease;
    }
    
    .review-item:hover {
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    
    /* Kiểu đánh giá đơn giản */
    .simple-review-form {
        background-color: #f8f8f8;
        padding: 20px;
    }
    
    .star-rating-group {
        margin-bottom: 20px;
    }
    
    .star-rating-label {
        font-weight: normal;
        margin-bottom: 10px;
        display: block;
    }
    
    .star-rating-options {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    
    .star-rating-option {
        margin-right: 15px;
        display: flex;
        align-items: center;
    }
    
    .star-rating-option input[type="radio"] {
        margin-right: 5px;
    }
    
    .simple-review-form textarea {
        width: 100%;
        border: 1px solid #ddd;
        padding: 8px 12px;
        margin-bottom: 15px;
        min-height: 100px;
        background-color: #f0f0f0;
    }
    
    .simple-review-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: normal;
    }
    
    .form-field-container {
        margin-bottom: 15px;
    }
    
    .submit-review-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
    }
    
    .submit-review-btn i {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="container py-4 px-4 md:px-8">
    <!-- Breadcrumbs -->
    <!-- <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Sản phẩm</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
     -->
    <!-- TEST MARKER -->
    <div style="display: none; background: red; padding: 10px; color: white; margin-bottom: 20px;">TEST MARKER - THIS SHOULD BE HIDDEN</div>
    
    <!-- Product Content - Two Column Layout -->
    <div class="row" style="display: flex; flex-wrap: wrap;">
        <!-- LEFT COLUMN - Product Gallery (6 columns on desktop) -->
        <div class="col-md-6 col-12" style="float: left; width: 50%; padding-right: 15px; box-sizing: border-box;">
            <div class="product-gallery">
                @if($product->images && $product->images->isNotEmpty())
                    <a href="{{ asset('storage/' . $product->images->first()->image_path) }}" data-fancybox="product-gallery">
                    <img id="main-product-image" src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="product-main-image rounded" 
                         style="width: 100%; max-height: 450px; object-fit: contain; cursor: zoom-in;">
                    </a>
                    
                    <!-- Product Thumbnails -->
                    <div class="product-thumbnails" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px;">
                        @foreach($product->images as $index => $image)
                            <div class="product-thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 data-image="{{ asset('storage/' . $image->image_path) }}"
                                 data-fancybox-href="{{ asset('storage/' . $image->image_path) }}"
                                 style="width: 70px; height: 70px; border: 1px solid #ddd; cursor: pointer; padding: 3px; {{ $index === 0 ? 'border: 2px solid #0d6efd;' : '' }}">
                                <img src="{{ asset('storage/' . ($image->thumbnail_path ?? $image->image_path)) }}" 
                                     alt="{{ $product->name }}" 
                                     style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="placeholder-image" style="width: 100%; height: 450px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-image text-secondary" viewBox="0 0 16 16">
                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- RIGHT COLUMN - Product Details (6 columns on desktop) -->
        <div class="col-md-6 col-12" style="float: left; width: 50%; padding-left: 15px; box-sizing: border-box;">
            <!-- Product Title -->
            <h1 style="font-size: 24px; font-weight: 500; margin-bottom: 15px;">{{ $product->name }}</h1>
            
            <!-- Product Rating -->
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <div style="color: #ffc107; margin-right: 10px;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($product->reviews_avg_rating ?? 0))
                            <i class="bi bi-star-fill"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor
                </div>
                <span style="color: #6c757d;">{{ $product->reviews_count ?? 0 }} đánh giá</span>
            </div>
            
            <!-- Product Price -->
            <div style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">
                @if($product->sale_price && $product->sale_price < $product->price)
                    {{ number_format($product->sale_price) }}₫
                    <span style="text-decoration: line-through; color: #6c757d; font-size: 18px;">{{ number_format($product->price) }}₫</span>
                    <span style="background: #dc3545; color: white; padding: 2px 8px; border-radius: 4px; font-size: 14px; margin-left: 10px;">
                        -{{ ceil((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                @else
                    {{ number_format($product->price) }}₫
                @endif
            </div>
            <!-- Debug section - Thêm vào đầu trang hoặc nơi bạn muốn hiển thị -->
<!-- <div style="background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;">
    <h5>Thông tin debug:</h5>
    <ul>
        <li>stock_quantity: {{ $product->stock_quantity }}</li>
        <li>status: {{ $product->status }}</li>
        <li>isInStock: {{ $product->isInStock() ? 'true' : 'false' }}</li>
        <li>ID: {{ $product->id }}</li>
        <li>Số lượng biến thể: {{ $product->inventories->count() }}</li>
        @if($product->inventories->count() > 0)
            <li>
                <strong>Chi tiết biến thể:</strong>
                <ul>
                    @foreach($product->inventories as $inv)
                        <li>
                            Size: {{ $inv->size ?? 'N/A' }}, 
                            Color: {{ $inv->color ?? 'N/A' }}, 
                            Quantity: {{ $inv->quantity }},
                            In Stock: {{ $inv->in_stock ? 'Có' : 'Không' }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    </ul>
</div> -->
            <!-- In Stock Status -->
            <p id="stock-status" style="margin-bottom: 20px;">
                @php
                    $inventoryQuantity = isset($product->inventories) ? $product->inventories->sum('quantity') : 0;
                @endphp
                @if($inventoryQuantity > 0)
                    <span style="color: #198754;"><i class="bi bi-check-circle me-1"></i> Còn <span id="variant-quantity">{{ $inventoryQuantity }}</span> sản phẩm</span>
                @else
                    <span style="color: #dc3545;"><i class="bi bi-x-circle me-1"></i> Hết hàng</span>
                @endif
            </p>
            
            @php
                // Get product attributes
                $colorAttributes = $product->colors ?? collect();
                $sizeAttributes = $product->sizes ?? collect();
                
                // Get inventory attributes if exist
                $productInventories = $product->inventories ?? collect();
                $inventoryColors = $productInventories->pluck('color')->unique()->filter();
                $inventorySizes = $productInventories->pluck('size')->unique()->filter();
                
                // Check if we need to use inventory variants instead of product attributes
                $colorAttributesEmpty = is_object($colorAttributes) ? $colorAttributes->isEmpty() : (empty($colorAttributes) || !is_array($colorAttributes));
                $sizeAttributesEmpty = is_object($sizeAttributes) ? $sizeAttributes->isEmpty() : (empty($sizeAttributes) || !is_array($sizeAttributes));
                
                $useInventoryVariants = ($colorAttributesEmpty && $inventoryColors->isNotEmpty()) ||
                    ($sizeAttributesEmpty && $inventorySizes->isNotEmpty());
            @endphp
            
            <!-- Product Attributes -->
            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Color Options -->
                @if(isset($colorAttributes) && (is_object($colorAttributes) ? $colorAttributes->count() > 0 : (!empty($colorAttributes) && is_array($colorAttributes))))
                <div style="margin-bottom: 20px;">
                    <label for="color" style="display: block; font-weight: 500; margin-bottom: 10px;">Màu sắc:</label>
                    <div class="color-options" style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($colorAttributes as $index => $color)
                            @php
                                $colorName = is_object($color) ? $color->name : $color;
                                $colorId = is_object($color) ? $color->id : $index;
                                $colorCode = '';
                                
                                // Nếu color là một đối tượng
                                if (is_object($color)) {
                                    $colorCode = $color->code ?? 'CCCCCC';
                                } 
                                // Nếu không phải đối tượng, kiểm tra trong bảng color_codes
                                else {
                                    if (isset($product->color_codes) && is_array($product->color_codes) && isset($product->colors) && is_array($product->colors)) {
                                        $colorIndex = array_search($colorName, $product->colors);
                                        if ($colorIndex !== false && isset($product->color_codes[$colorIndex])) {
                                            $colorCode = $product->color_codes[$colorIndex];
                                        }
                                    }
                                    
                                    // Nếu không tìm thấy mã màu, hiển thị màu trắng
                                    if (empty($colorCode)) {
                                        $colorCode = 'FFFFFF';
                                    }
                                }
                            @endphp
                            <div class="color-option" data-color="{{ $colorName }}">
                                <input type="radio" name="color" id="color-{{ $colorId }}" value="{{ $colorName }}" style="display: none;" required>
                                <label for="color-{{ $colorId }}" style="display: block; width: 40px; height: 40px; border-radius: 50%; background-color: #{{ $colorCode }}; cursor: pointer; border: 2px solid #ddd; position: relative;" 
                                       title="{{ $colorName }}">
                                    <span class="color-check" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: {{ $colorCode == 'FFFFFF' ? '#000' : '#fff' }}; font-size: 20px;">
                                        <i class="bi bi-check"></i>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="color-name" style="margin-top: 8px; font-size: 14px;"></div>
                </div>
                @endif

                <!-- Size Options -->
                @if(isset($sizeAttributes) && (is_object($sizeAttributes) ? $sizeAttributes->count() > 0 : (!empty($sizeAttributes) && is_array($sizeAttributes))))
                <div style="margin-bottom: 20px;">
                    <label for="size" style="display: block; font-weight: 500; margin-bottom: 10px;">Kích thước:</label>
                    <div class="size-options" style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($sizeAttributes as $index => $size)
                            @php
                                $sizeName = is_object($size) ? $size->name : $size;
                                $sizeId = is_object($size) ? $size->id : $index;
                            @endphp
                            <div class="size-option" data-size="{{ $sizeName }}">
                                <input type="radio" name="size" id="size-{{ $sizeId }}" value="{{ $sizeName }}" style="display: none;" required>
                                <label for="size-{{ $sizeId }}" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; border: 1px solid #ddd; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 14px; transition: all 0.2s;">
                                    {{ $sizeName }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                </div>
            @endif
            </form>
            
            <!-- Hiển thị biến thể từ inventories nếu không có attributes -->
            @if($useInventoryVariants)
                <div style="margin-bottom: 20px; border: 1px solid #f0f0f0; padding: 15px; border-radius: 5px;">
                    <!-- Hiển thị kích thước từ inventories -->
                    @if(isset($sizeAttributes) && (is_object($sizeAttributes) ? $sizeAttributes->isEmpty() : (empty($sizeAttributes) || !is_array($sizeAttributes))) && $inventorySizes->isNotEmpty())
                        <div style="margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px;">Kích thước</h6>
                            <div class="inventory-sizes" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach($inventorySizes as $index => $size)
                                    <div class="inventory-size-option">
                                        <input type="radio" name="inventory_size" id="inventory-size-{{ $index }}" value="{{ $size }}" style="display: none;" {{ $index === 0 ? 'checked' : '' }} required>
                                        <label for="inventory-size-{{ $index }}" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; {{ $index === 0 ? 'background-color: #0d6efd; color: white; border-color: #0d6efd;' : '' }}">
                                            {{ $size }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Hiển thị màu sắc từ inventories -->
                    @if(isset($colorAttributes) && (is_object($colorAttributes) ? $colorAttributes->isEmpty() : (empty($colorAttributes) || !is_array($colorAttributes))) && $inventoryColors->isNotEmpty())
                        <div style="margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px;">Màu sắc</h6>
                            <div class="inventory-colors" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach($inventoryColors as $index => $color)
                                    <div class="inventory-color-option" data-color="{{ $color }}">
                                        <input type="radio" name="inventory_color" id="inventory-color-{{ $index }}" value="{{ $color }}" style="display: none;" {{ $index === 0 ? 'checked' : '' }} required>
                                        <label for="inventory-color-{{ $index }}" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; {{ $index === 0 ? 'background-color: #0d6efd; color: white; border-color: #0d6efd;' : '' }}">
                                            {{ $color }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Quantity Selector -->
            <div style="margin-bottom: 20px;">
                <p style="margin-bottom: 10px;">Số lượng</p>
                <div style="display: flex; align-items: center;">
                    <button type="button" class="btn-quantity-decrease" style="width: 40px; height: 40px; background: #f8f9fa; border: 1px solid #ced4da; font-size: 16px;">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" readonly style="width: 60px; height: 40px; text-align: center; border: 1px solid #ced4da; border-left: none; border-right: none;">
                    <button type="button" class="btn-quantity-increase" style="width: 40px; height: 40px; background: #f8f9fa; border: 1px solid #ced4da; font-size: 16px;">+</button>
                </div>
            </div>
            
            <!-- Add to Cart and Buy Now -->
            <div style="display: flex; gap: 10px; margin-bottom: 30px;">
                <form action="{{ route('cart.add') }}" method="POST" style="flex: 1;" id="cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1" id="cart_quantity">
                    <input type="hidden" name="inventory_id" id="inventory_id" value="">
                    @php
                        $defaultSize = '';
                        $defaultColor = '';
                        
                        if (isset($sizeAttributes) && !empty($sizeAttributes)) {
                            if (is_object($sizeAttributes) && method_exists($sizeAttributes, 'first') && $sizeAttributes->isNotEmpty()) {
                                $firstSize = $sizeAttributes->first();
                                $defaultSize = is_object($firstSize) ? $firstSize->name : $firstSize;
                            } elseif (is_array($sizeAttributes) && count($sizeAttributes) > 0) {
                                $defaultSize = is_object($sizeAttributes[0]) ? $sizeAttributes[0]->name : $sizeAttributes[0];
                            }
                        }
                        
                        if (isset($colorAttributes) && !empty($colorAttributes)) {
                            if (is_object($colorAttributes) && method_exists($colorAttributes, 'first') && $colorAttributes->isNotEmpty()) {
                                $firstColor = $colorAttributes->first();
                                $defaultColor = is_object($firstColor) ? $firstColor->name : $firstColor;
                            } elseif (is_array($colorAttributes) && count($colorAttributes) > 0) {
                                $defaultColor = is_object($colorAttributes[0]) ? $colorAttributes[0]->name : $colorAttributes[0];
                            }
                        }
                    @endphp
                    <input type="hidden" name="size" id="selected_size" value="{{ $defaultSize }}">
                    <input type="hidden" name="color" id="selected_color" value="{{ $defaultColor }}">
                    <button type="submit" style="width: 100%; padding: 10px 20px; background-color: white; color: #0d6efd; border: 1px solid #0d6efd; font-weight: 500; text-transform: uppercase; font-size: 14px;">Thêm vào giỏ hàng</button>
                </form>
                
                <form action="{{ route('checkout.index') }}" method="GET" style="flex: 1;" id="buy-now-form">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1" id="buy_now_quantity">
                    <input type="hidden" name="inventory_id" id="buy_now_inventory_id" value="">
                    <input type="hidden" name="color" id="buy_now_color" value="{{ $defaultColor }}">
                    <input type="hidden" name="size" id="buy_now_size" value="{{ $defaultSize }}">
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" style="width: 100%; padding: 10px 20px; background-color: #dc3545; color: white; border: 1px solid #dc3545; font-weight: 500; text-transform: uppercase; font-size: 14px;">Mua ngay</button>
                </form>
            </div>
            
            <!-- Product Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; margin-bottom: 10px; font-weight: 500;">Thông tin sản phẩm</h3>
                
                @if($product->short_description)
                    <p style="margin-bottom: 10px; color: #6c757d;">{{ $product->short_description }}</p>
                @endif
                
                <div style="margin-top: 15px;">
                    @if($product->sku)
                        <p style="margin-bottom: 5px;"><strong>Mã sản phẩm:</strong> {{ $product->sku }}</p>
                    @endif
                    
                    @if($product->category)
                        <p style="margin-bottom: 5px;"><strong>Danh mục:</strong> <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></p>
                    @endif
                    
                    @if($product->tags && $product->tags->isNotEmpty())
                        <p style="margin-bottom: 5px;">
                            <strong>Tags:</strong> 
                            @foreach($product->tags as $tag)
                                <a href="{{ route('products.index', ['tag' => $tag->slug]) }}" class="text-decoration-none">
                                    #{{ $tag->name }}
                                </a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div style="clear: both;"></div>
    
    <!-- Product Tabs -->
    <div class="product-tab">
        <button class="tab-link active" data-tab="description">
            <i class="bi bi-file-text"></i> Mô tả sản phẩm
        </button>
        <button class="tab-link" data-tab="specs">
            <i class="bi bi-list-check"></i> Thông số kỹ thuật
        </button>
        <button class="tab-link" data-tab="reviews">
            <i class="bi bi-star"></i> Đánh giá <span class="review-count">{{ $product->reviews_count ?? 0 }}</span>
        </button>
    </div>
    
    <!-- Tab Content -->
    <div id="description" class="tab-content active" style="display: block; padding: 20px 0;">
        <div class="product-description">
            {!! $product->description !!}
        </div>
    </div>
    
    <div id="specs" class="tab-content" style="display: none; padding: 20px 0;">
        @if($product->specifications && $product->specifications->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                        @foreach($product->specifications as $spec)
                        <tr>
                            <th style="width: 30%">{{ $spec->name }}</th>
                            <td>{{ $spec->value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Không có thông số kỹ thuật.</p>
        @endif
    </div>
    
    <div id="reviews" class="tab-content" style="display: none; padding: 20px 0;">
        <div class="reviews-tab-header">
            <i class="bi bi-star"></i>
            <h4 class="reviews-tab-title">Đánh giá ({{ $product->reviews_count ?? 0 }})</h4>
        </div>
        
        @if(isset($product->reviews) && $product->reviews->count() > 0)
            <div class="reviews-list">
                @foreach($product->reviews as $review)
                    <div class="review-item" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; background-color: #f9f9f9; padding: 15px; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div>
                                <div style="font-weight: 500; margin-bottom: 5px;">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</div>
                                <div class="rating" style="color: #FFC107; margin-bottom: 5px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div style="color: #6c757d; font-size: 14px;">
                                {{ $review->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <p style="margin-bottom: 10px; font-size: 15px;">{{ $review->comment }}</p>
                            
                            @if($review->pros)
                                <div style="margin-top: 10px;">
                                    <span style="font-weight: 500; color: #198754;"><i class="bi bi-plus-circle me-1"></i> Ưu điểm:</span>
                                    <p style="margin: 5px 0 0 20px; color: #198754;">{{ $review->pros }}</p>
                                </div>
                            @endif
                            
                            @if($review->cons)
                                <div style="margin-top: 10px;">
                                    <span style="font-weight: 500; color: #dc3545;"><i class="bi bi-dash-circle me-1"></i> Nhược điểm:</span>
                                    <p style="margin: 5px 0 0 20px; color: #dc3545;">{{ $review->cons }}</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($review->images)
                            <div style="margin-top: 10px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @php
                                        $reviewImages = is_string($review->images) ? json_decode($review->images) : $review->images;
                                    @endphp
                                    @foreach($reviewImages as $image)
                                        <a href="{{ asset('storage/' . $image) }}" data-fancybox="review-{{ $review->id }}-images">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Review image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if($review->is_verified_purchase)
                            <div style="margin-top: 10px; font-size: 12px; color: #0d6efd;">
                                <i class="bi bi-patch-check-fill me-1"></i> Đã mua hàng xác thực
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="review-empty-state">
                <i class="bi bi-chat-square-text"></i>
                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
        @endif
        
        <!-- Review Form -->
        @auth
            @php
                $hasReviewed = isset($product->reviews) ? $product->reviews->where('user_id', auth()->id())->count() > 0 : false;
            @endphp
            
            @if($hasReviewed)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Bạn đã đánh giá sản phẩm này. Cảm ơn về phản hồi của bạn!
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i> Bạn cần mua và nhận sản phẩm trước khi đánh giá. Điều này giúp đảm bảo các đánh giá là đáng tin cậy.
                </div>
            @endif
        @else
            <div class="alert alert-info">
                <i class="bi bi-lock me-2"></i> Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để viết đánh giá.
            </div>
        @endauth
    </div>
    
    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts && $relatedProducts->isNotEmpty())
    <div style="margin-top: 40px;">
        <h3 style="margin-bottom: 20px;">Sản phẩm liên quan</h3>
        <div style="display: flex; overflow-x: auto; gap: 20px; padding-bottom: 15px;">
            @foreach($relatedProducts as $relatedProduct)
                <div style="min-width: 250px; max-width: 250px;">
                    <div class="card h-100 product-card">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none">
                            @if($relatedProduct->featuredImage)
                                <img src="{{ asset('storage/' . $relatedProduct->featuredImage->thumbnail_path) }}" 
                                    class="card-img-top" 
                                    alt="{{ $relatedProduct->name }}"
                                    style="height: 200px; object-fit: cover;">
                            @elseif($relatedProduct->images && $relatedProduct->images->isNotEmpty())
                                <img src="{{ asset('storage/' . ($relatedProduct->images->first()->thumbnail_path ?? $relatedProduct->images->first()->image_path)) }}" 
                                    class="card-img-top" 
                                    alt="{{ $relatedProduct->name }}"
                                    style="height: 200px; object-fit: cover;">
                            @else
                                <div style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-image text-secondary" viewBox="0 0 16 16">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 1rem;">{{ $relatedProduct->name }}</h5>
                                <div style="display: flex; align-items: center; margin-top: 10px;">
                                    @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                        <span style="font-weight: bold; color: #dc3545;">{{ number_format($relatedProduct->sale_price) }}₫</span>
                                        <small style="color: #6c757d; text-decoration: line-through; margin-left: 8px;">{{ number_format($relatedProduct->price) }}₫</small>
                                    @else
                                        <span style="font-weight: bold;">{{ number_format($relatedProduct->price) }}₫</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    $(document).ready(function() {
        // Tab switching functionality
        $('.tab-link').on('click', function(e) {
            e.preventDefault();
            
            // Hide all tab contents
            $('.tab-content').hide();
            
            // Remove active class from all tabs
            $('.tab-link').removeClass('active');
            
            // Add active class to clicked tab
            $(this).addClass('active');
            
            // Show the corresponding tab content
            const tabId = $(this).data('tab');
            $('#' + tabId).fadeIn(300);
            
            // Kiểm tra nếu tab là reviews
            if (tabId === 'reviews') {
                // Đảm bảo hiển thị đúng cách
                $('#reviews').css('display', 'block');
                
                // Có thể thực hiện thêm các hành động đặc biệt cho tab đánh giá
                $('.reviews-list').show();
            }
        });
        
        // Color selection
        $('.color-option input[type="radio"]').on('change', function() {
            // Remove active class from all options
            $('.color-option label').css('border-color', '#ddd');
            $('.color-check').hide();
            
            // Add active class to selected option
            $(this).siblings('label').css('border-color', '#0d6efd');
            $(this).siblings('label').find('.color-check').show();
            
            // Update selected color display
            $('.color-name').text($(this).closest('.color-option').data('color'));
            
            // Update hidden inputs
            $('#selected_color, #buy_now_color').val($(this).val());
            
            updateSelectedVariant();
        });
        
        // Size selection
        $('.size-option input[type="radio"]').on('change', function() {
            // Remove active class from all options
            $('.size-option label').css({
                'background-color': 'transparent',
                'color': '#212529',
                'border-color': '#ddd'
            });
            
            // Add active class to selected option
            $(this).siblings('label').css({
                'background-color': '#0d6efd',
                'color': 'white',
                'border-color': '#0d6efd'
            });
            
            // Update hidden inputs
            $('#selected_size, #buy_now_size').val($(this).val());
            
            updateSelectedVariant();
        });
        
        // Select first option by default
        if ($('.color-option input[type="radio"]').length > 0) {
            $('.color-option input[type="radio"]').first().prop('checked', true).trigger('change');
        }
        
        if ($('.size-option input[type="radio"]').length > 0) {
            $('.size-option input[type="radio"]').first().prop('checked', true).trigger('change');
        }
        
        function updateSelectedVariant() {
            // Get selected color and size
            const selectedColor = $('input[name="color"]:checked').val();
            const selectedSize = $('input[name="size"]:checked').val();
            
            // Find matching inventory and update inventory ID and quantity
            // This would need to match with your backend data structure
            // For example: inventories = [{id: 1, color: 'Red', size: 'M', quantity: 10}, ...]
            
            // For demonstration purposes:
            const inventories = @json($product->inventories);
            const matchingInventory = inventories.find(inv => 
                ((!selectedColor || inv.color === selectedColor) && 
                 (!selectedSize || inv.size === selectedSize))
            );
            
            if (matchingInventory) {
                $('#inventory_id, #buy_now_inventory_id').val(matchingInventory.id);
                $('#variant-quantity').text(matchingInventory.quantity);
                
                // Update stock status
                if (matchingInventory.quantity > 0) {
                    $('#stock-status').html('<span style="color: #198754;"><i class="bi bi-check-circle me-1"></i> Còn ' + matchingInventory.quantity + ' sản phẩm</span>');
                    $('.buy-button, .add-to-cart-button').prop('disabled', false);
                } else {
                    $('#stock-status').html('<span style="color: #dc3545;"><i class="bi bi-x-circle me-1"></i> Hết hàng</span>');
                    $('.buy-button, .add-to-cart-button').prop('disabled', true);
                }
                } else {
                $('#inventory_id, #buy_now_inventory_id').val('');
                $('#stock-status').html('<span style="color: #ffc107;"><i class="bi bi-exclamation-circle me-1"></i> Không có sẵn với lựa chọn này</span>');
                $('.buy-button, .add-to-cart-button').prop('disabled', true);
            }
        }
        
        // Initialize thumbnail gallery
        $('.product-thumbnail').click(function() {
            const imgSrc = $(this).data('image');
            const fancyboxHref = $(this).data('fancybox-href');
            
            // Cập nhật ảnh chính
            $('#main-product-image').attr('src', imgSrc);
            
            // Cập nhật href cho fancybox
            $('#main-product-image').closest('a').attr('href', fancyboxHref);
            
            // Cập nhật trạng thái active
            $('.product-thumbnail').removeClass('active').css('border', '1px solid #ddd');
            $(this).addClass('active').css('border', '2px solid #0d6efd');
        });
        
        // Initialize image zoom with Fancybox
        Fancybox.bind('[data-fancybox="product-gallery"]', {
            // Cấu hình cho Fancybox
            loop: true,
            buttons: ["zoom", "slideShow", "fullScreen", "close"],
            animationEffect: "fade"
        });
    });
</script>
@endpush