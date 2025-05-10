@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
        <h1 class="h3 mb-0 text-gray-800">Chi tiết sản phẩm</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Cột hình ảnh sản phẩm -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                @if($product->productImages->isNotEmpty())
                    <div class="position-relative">
                        <!-- Gallery cho ảnh sản phẩm -->
                        <div class="product-gallery">
                            <!-- Ảnh chính -->
                            <div class="main-image-container rounded-top overflow-hidden position-relative">
                                @php
                                    $primaryImage = $product->productImages->where('is_primary', true)->first();
                                    $displayImage = $primaryImage ? $primaryImage : $product->productImages->first();
                                @endphp
                                
                                <!-- Nút điều hướng trái phải trên ảnh chính -->
                                @if($product->productImages->count() > 1)
                                <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y rounded-circle nav-arrow ms-2" id="prevImage">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y rounded-circle nav-arrow me-2" id="nextImage">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                                @endif
                                
                                <img id="mainProductImage" src="{{ asset('storage/' . $displayImage->image_path) }}" 
                                    class="d-block w-100 main-product-image" alt="{{ $product->name }}">
                                
                                @if($displayImage->is_primary)
                                <div class="position-absolute bottom-0 start-0 m-3">
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-star-fill me-1"></i> Ảnh chính
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Nút phóng to ảnh -->
                                <a href="{{ asset('storage/' . $displayImage->image_path) }}" 
                                   data-fancybox="product-gallery" 
                                   class="position-absolute top-0 end-0 m-3 btn btn-sm btn-light rounded-circle zoom-btn">
                                    <i class="bi bi-fullscreen"></i>
                                </a>
                    </div>
                    
                            <!-- Gallery thumbnails -->
                            <div class="card-body pt-3">
                                <h5 class="card-title d-flex align-items-center border-bottom pb-3 mb-3">
                                    <i class="bi bi-images me-2 text-primary"></i> Thư viện ảnh sản phẩm
                                </h5>
                                
                                <div class="gallery-thumbnails-container">
                                    <div class="gallery-thumbnails d-flex justify-content-center flex-wrap">
                                        @foreach($product->productImages as $index => $image)
                                        <div class="gallery-thumbnail-item mx-2 mb-2" data-index="{{ $index }}">
                                            <div class="position-relative thumbnail-container {{ ($displayImage->id == $image->id) ? 'active' : '' }}" 
                                                data-image-path="{{ asset('storage/' . $image->image_path) }}"
                                                data-is-primary="{{ $image->is_primary ? 'true' : 'false' }}">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                    class="img-thumbnail" alt="{{ $product->name }}">
                                                @if($image->is_primary)
                                                <div class="position-absolute top-0 end-0">
                                                    <span class="badge bg-success rounded-circle p-1">
                                                        <i class="bi bi-star-fill"></i>
                                                    </span>
                                                </div>
                                                @endif
                                                <!-- Liên kết fancybox ẩn -->
                                                <a href="{{ asset('storage/' . $image->image_path) }}" 
                                                   data-fancybox="product-gallery" 
                                                   class="d-none">
                                                    {{ $product->name }} - Ảnh {{ $index + 1 }}
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                        <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        <p class="text-muted mt-3 mb-0">Không có hình ảnh</p>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary mt-3">
                            <i class="bi bi-plus-circle"></i> Thêm hình ảnh
                        </a>
                    </div>
                            @endif
                        </div>
                    </div>
                    
        <!-- Cột thông tin sản phẩm -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle me-2"></i> Thông tin sản phẩm
                        </h5>
                        <span class="badge {{ 
                            $product->status == 'active' ? 'bg-success' : 
                            ($product->status == 'inactive' ? 'bg-danger' : 'bg-warning') 
                        }} fs-6 px-3 py-2">
                            {{ $product->status == 'active' ? 'Đang bán' : 
                               ($product->status == 'inactive' ? 'Ngừng bán' : 'Hết hàng') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="fs-3 fw-bold mb-1">{{ $product->name }}</h2>
                        <p class="text-muted mb-0">SKU: <span class="badge bg-light text-dark">{{ $product->sku }}</span></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="price-section">
                                <span class="fs-6 text-muted">Giá niêm yết:</span>
                                <span class="fs-4 fw-bold d-block">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                    
                    @if($product->sale_price)
                                <div class="mt-2">
                                    <span class="fs-6 text-muted">Giá khuyến mãi:</span>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-4 fw-bold text-danger d-block">{{ number_format($product->sale_price, 0, ',', '.') }} VNĐ</span>
                                        <span class="badge bg-danger ms-2">
                                            Giảm {{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                        </span>
                                    </div>
                    </div>
                    @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-md-0 mt-3">
                                <p class="mb-1"><i class="bi bi-tag me-2 text-primary"></i> <strong>Danh mục:</strong> 
                                    <span class="badge bg-secondary">{{ $product->category->name ?? 'Không có' }}</span>
                                </p>
                                <p class="mb-1"><i class="bi bi-award me-2 text-primary"></i> <strong>Thương hiệu:</strong> 
                                    {{ $product->brand ?? 'Không có' }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-star me-2 text-primary"></i> <strong>Nổi bật:</strong>
                                    @if($product->featured)
                                        <i class="bi bi-check-circle-fill text-success"></i> Có
                                    @else
                                        <i class="bi bi-x-circle text-danger"></i> Không
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row gx-3 mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="mb-3">Kích thước</h6>
                                    <div>
                            @if(is_array($product->sizes) && count($product->sizes) > 0)
                                @foreach($product->sizes as $size)
                                                <span class="badge bg-primary me-1 mb-1 p-2">{{ $size }}</span>
                                @endforeach
                            @else
                                            <span class="text-muted">Không có</span>
                            @endif
                        </div>
                    </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="mb-3">Màu sắc</h6>
                                    <div>
                            @if(is_array($product->colors) && count($product->colors) > 0)
                                @foreach($product->colors as $color)
                                                <span class="badge bg-secondary me-1 mb-1 p-2">{{ $color }}</span>
                                @endforeach
                            @else
                                            <span class="text-muted">Không có</span>
                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-palette me-2 text-primary"></i> <strong>Chất liệu:</strong> 
                                {{ $product->material ?? 'Không có' }}
                            </p>
                    </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-box-seam me-2 text-primary"></i> <strong>Trọng lượng:</strong> 
                                {{ $product->weight ? $product->weight . ' gram' : 'Không có' }}
                            </p>
                    </div>
                    </div>
                    
                    <div class="d-flex justify-content-between small text-muted mt-4 pt-3 border-top">
                        <span><i class="bi bi-calendar me-1"></i> Ngày tạo: {{ $product->created_at->format('d/m/Y H:i') }}</span>
                        <span><i class="bi bi-clock-history me-1"></i> Cập nhật: {{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin mô tả -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-file-text me-2"></i> Mô tả sản phẩm
                    </h5>
                </div>
                <div class="card-body">
                    @if($product->description)
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Sản phẩm chưa có mô tả</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Thông tin tồn kho -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-box-seam me-2"></i> Thông tin tồn kho
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalStock = $product->inventories()->sum('quantity');
                        $inventories = $product->inventories;
                        $firstInventory = $inventories->first();
                        $lowStockThreshold = $firstInventory ? $firstInventory->low_stock_threshold : 5;
                    @endphp
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="card-title text-muted">Tổng tồn kho</h6>
                                    <h3 class="mb-0 {{ $totalStock <= $lowStockThreshold ? 'text-danger' : 'text-success' }}">{{ $totalStock }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="card-title text-muted">Trạng thái</h6>
                                    @if($totalStock <= 0)
                                        <span class="badge bg-danger fs-6">Hết hàng</span>
                                    @elseif($totalStock <= $lowStockThreshold)
                                        <span class="badge bg-warning fs-6">Sắp hết hàng</span>
                                    @else
                                        <span class="badge bg-success fs-6">Còn hàng</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($inventories->isNotEmpty())
                        <h6 class="border-bottom pb-2 mb-3">Chi tiết tồn kho theo biến thể</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Kích thước</th>
                                        <th>Màu sắc</th>
                                        <th>Số lượng</th>
                                        <th>Vị trí</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventories as $inventory)
                                    <tr>
                                        <td>{{ $inventory->size ?? 'Mặc định' }}</td>
                                        <td>{{ $inventory->color ?? 'Mặc định' }}</td>
                                        <td>{{ $inventory->quantity }}</td>
                                        <td>{{ $inventory->location ?? 'N/A' }}</td>
                                        <td>
                                            @if($inventory->quantity <= 0)
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @elseif($inventory->quantity <= $inventory->low_stock_threshold)
                                                <span class="badge bg-warning">Sắp hết</span>
                                            @else
                                                <span class="badge bg-success">Còn hàng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-success me-2">
                                <i class="bi bi-plus-circle me-1"></i> Nhập thêm hàng
                            </a>
                            <a href="{{ route('admin.inventory.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil-square me-1"></i> Điều chỉnh tồn kho
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i> Chưa có thông tin tồn kho cho sản phẩm này.
                            <div class="mt-2">
                                <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm tồn kho
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('styles')
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style>
    .thumbnail-container {
        transition: all 0.2s ease;
        border: 2px solid transparent;
        cursor: pointer;
        width: 100%;
        height: 80px;
        overflow: hidden;
        border-radius: 4px;
    }
    .thumbnail-container:hover {
        border-color: #0d6efd;
    }
    .thumbnail-container.active {
        border-color: #0d6efd;
    }
    .thumbnail-container img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    .product-description {
        line-height: 1.7;
    }
    
    /* Gallery styling */
    .main-image-container {
        position: relative;
        height: 500px;
        overflow: hidden;
        background-color: #f8f9fa;
        margin-bottom: 15px;
    }
    .main-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .zoom-btn {
        opacity: 0.7;
        transition: opacity 0.2s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .zoom-btn:hover {
        opacity: 1;
    }
    .gallery-thumbnails-container {
        width: 100%;
    }
    .gallery-thumbnails {
        transition: transform 0.3s ease;
    }
    .gallery-thumbnail-item {
        flex: 0 0 80px;
    }
    .nav-arrow {
        opacity: 0.7;
        transition: opacity 0.2s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .nav-arrow:hover {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo Fancybox
        Fancybox.bind('[data-fancybox="product-gallery"]', {
            caption: function (fancybox, slide) {
                return `${slide.triggerEl.getAttribute('alt') || 'Ảnh sản phẩm'} - ${fancybox.getSlide().index + 1} / ${fancybox.carousel.slides.length}`;
            }
        });
        
        // Xử lý thumbnail click
        const thumbnails = document.querySelectorAll('.thumbnail-container');
        const mainImage = document.getElementById('mainProductImage');
        const primaryBadge = document.querySelector('.main-image-container .badge');
        let currentIndex = 0;
        
        thumbnails.forEach((thumb, index) => {
            if (thumb.classList.contains('active')) {
                currentIndex = index;
            }
            
            thumb.addEventListener('click', function() {
                currentIndex = index;
                updateMainImage(this);
            });
        });
        
        function updateMainImage(thumbnail) {
            // Cập nhật ảnh chính
            mainImage.src = thumbnail.dataset.imagePath;
            
            // Cập nhật badge primary
            if (thumbnail.dataset.isPrimary === 'true') {
                primaryBadge.classList.remove('d-none');
            } else {
                primaryBadge.classList.add('d-none');
            }
            
            // Cập nhật active state
            thumbnails.forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }
        
        // Xử lý nút điều hướng
        const prevButton = document.getElementById('prevImage');
        const nextButton = document.getElementById('nextImage');
        
        if (prevButton && nextButton) {
            prevButton.addEventListener('click', function() {
                navigateGallery('prev');
            });
            
            nextButton.addEventListener('click', function() {
                navigateGallery('next');
            });
        }
        
        function navigateGallery(direction) {
            const totalImages = thumbnails.length;
            
            if (direction === 'next') {
                currentIndex = (currentIndex + 1) % totalImages;
            } else {
                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
            }
            
            updateMainImage(thumbnails[currentIndex]);
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                navigateGallery('prev');
            } else if (e.key === 'ArrowRight') {
                navigateGallery('next');
            }
        });
    });
</script>
@endpush 