@extends('layouts.shop')

@section('title', 'Đánh giá sản phẩm')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .review-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .review-header {
        margin-bottom: 2.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    .review-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 15px;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .review-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .breadcrumb {
        padding: 12px 0;
        margin-bottom: 0;
    }
    
    .breadcrumb-item a {
        color: #555;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .breadcrumb-item a:hover {
        color: #ff4200;
    }
    
    .breadcrumb-item.active {
        color: #888;
    }
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
        background-color: white;
    }
    
    .card-header.bg-primary {
        background: linear-gradient(90deg, #0d6efd, #0a58ca) !important;
    }
    
    .card-header.bg-light {
        background-color: #f8f9fa !important;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .card-header h5 {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .form-control {
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 8px;
    }
    
    .card.border {
        border: 1px solid #e0e0e0 !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    
    .card.border .card-header {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .btn {
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #0a58ca);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(90deg, #0a58ca, #084298);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
    }
    
    .btn-secondary {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        color: #444;
    }
    
    .btn-secondary:hover {
        background-color: #e9ecef;
        border-color: #ddd;
        color: #333;
        transform: translateY(-2px);
    }
    
    .rating-input {
        margin-bottom: 20px;
    }
    
    .form-check-input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-top: 2px;
    }
    
    .form-check-label {
        padding-left: 8px;
        font-size: 1.05rem;
    }
    
    .text-warning {
        color: #ffc107 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .text-success {
        color: #198754 !important;
    }
    
    .badge {
        padding: 8px 12px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .badge.bg-success {
        background-color: #198754 !important;
    }
    
    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    hr {
        margin: 25px 0;
        opacity: 0.15;
    }
    
    .list-unstyled li {
        margin-bottom: 15px;
    }
    
    .alert {
        border-radius: 12px;
        padding: 18px 25px;
        margin-bottom: 25px;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .review-container {
            padding: 40px 15px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            padding: 10px 20px;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endsection

@section('content')
<div class="container review-container">
    <div class="review-header animate__animated animate__fadeInDown">
        <h1 class="mb-3">Đánh giá sản phẩm</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng của tôi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.show', $order->order_number) }}">Đơn hàng #{{ $order->order_number }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đánh giá sản phẩm</li>
            </ol>
        </nav>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('orders.add-review', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
                @csrf
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i>Đánh giá sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Hãy chia sẻ trải nghiệm của bạn về sản phẩm đã mua. Đánh giá của bạn sẽ giúp người khác đưa ra quyết định mua sắm tốt hơn.</p>
                        
                        @foreach($unreviewed as $index => $item)
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <strong><i class="bi bi-box me-2"></i>Sản phẩm #{{ $index + 1 }}: {{ $item->product->name }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="d-flex mb-4">
                                    <img src="{{ asset('storage/' . ($item->product->images->where('is_primary', true)->first()->thumbnail_path ?? $item->product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" 
                                        alt="{{ $item->product->name }}" class="product-image me-3">
                                    <div>
                                        <h5 class="mb-1">{{ $item->product->name }}</h5>
                                        <div class="mb-1 text-muted small">
                                            {{ $item->product->category->name ?? 'Không có danh mục' }} | {{ $item->product->brand }}
                                        </div>
                                        <div class="text-muted small">
                                            <strong>Số lượng mua:</strong> {{ $item->quantity }}
                                            @if($item->size)
                                            <span class="mx-2">|</span> <strong>Kích cỡ:</strong> {{ $item->size }}
                                            @endif
                                            @if($item->color)
                                            <span class="mx-2">|</span> <strong>Màu sắc:</strong> {{ $item->color }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="product_id[{{ $index }}]" value="{{ $item->product_id }}">
                                
                                <div class="mb-4">
                                    <label class="form-label">Đánh giá sao</label>
                                    <div class="rating-input d-flex flex-wrap mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="radio" name="rating[{{ $index }}]" id="rating{{ $index }}_{{ $i }}" value="{{ $i }}" {{ old("rating.$index") == $i ? 'checked' : ($i == 5 ? 'checked' : '') }} required>
                                            <label class="form-check-label" for="rating{{ $index }}_{{ $i }}">
                                                {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                            </label>
                                        </div>
                                        @endfor
                                    </div>
                                    @error("rating.$index")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="comment{{ $index }}" class="form-label">Nhận xét</label>
                                    <textarea class="form-control" id="comment{{ $index }}" name="comment[{{ $index }}]" rows="3" required>{{ old("comment.$index") }}</textarea>
                                    @error("comment.$index")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Tối thiểu 10 ký tự.</div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="pros{{ $index }}" class="form-label">Ưu điểm (không bắt buộc)</label>
                                        <textarea class="form-control" id="pros{{ $index }}" name="pros[{{ $index }}]" rows="2">{{ old("pros.$index") }}</textarea>
                                        @error("pros.$index")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label for="cons{{ $index }}" class="form-label">Nhược điểm (không bắt buộc)</label>
                                        <textarea class="form-control" id="cons{{ $index }}" name="cons[{{ $index }}]" rows="2">{{ old("cons.$index") }}</textarea>
                                        @error("cons.$index")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="images{{ $index }}" class="form-label">Hình ảnh (không bắt buộc)</label>
                                    <input class="form-control" type="file" id="images{{ $index }}" name="images[{{ $index }}][]" multiple accept="image/*">
                                    @error("images.$index")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error("images.$index.*")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Tối đa 5 ảnh, mỗi ảnh không quá 2MB. Định dạng hỗ trợ: JPG, PNG, GIF.</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Gửi đánh giá
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card animate__animated animate__fadeInRight">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Mã đơn hàng:</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ngày đặt:</span>
                            <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Trạng thái:</span>
                            @if($order->status == 'delivered')
                            <span class="badge bg-success">Đã giao hàng</span>
                            @elseif($order->status == 'completed')
                            <span class="badge bg-success">Hoàn thành</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tổng tiền:</span>
                            <strong class="text-danger">{{ number_format($order->total_amount) }} VNĐ</strong>
                        </div>
                    </div>
                    
                    <hr>
                    
                    @if($reviewed->isNotEmpty())
                    <div class="mb-0">
                        <div class="mb-3"><strong>Sản phẩm đã đánh giá:</strong></div>
                        <ul class="list-unstyled">
                            @foreach($reviewed as $item)
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . ($item->product->images->where('is_primary', true)->first()->thumbnail_path ?? $item->product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" 
                                        alt="{{ $item->product->name }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    <div>
                                        <div class="fw-medium">{{ $item->product->name }}</div>
                                        <div class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Đã đánh giá</div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Lưu ý</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-3">Vui lòng viết đánh giá chân thực và khách quan.</li>
                        <li class="mb-3">Mỗi sản phẩm chỉ được đánh giá một lần.</li>
                        <li class="mb-3">Đánh giá của bạn sẽ được hiển thị công khai trên trang sản phẩm.</li>
                        <li class="mb-3">Bạn có thể chỉnh sửa hoặc xóa đánh giá sau này trong phần "Đánh giá của tôi".</li>
                        <li class="mb-0">Nội dung không phù hợp có thể bị từ chối xuất bản.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 