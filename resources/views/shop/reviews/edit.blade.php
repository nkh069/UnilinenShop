@extends('layouts.shop')

@section('title', 'Chỉnh sửa đánh giá')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .edit-review-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .edit-review-header {
        margin-bottom: 2.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    .edit-review-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 15px;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .edit-review-header h1 {
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
    
    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .review-images {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }
    
    .review-image-container {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .review-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .remove-image-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(255, 255, 255, 0.8);
        color: #dc3545;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .remove-image-btn:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .alert {
        border-radius: 12px;
        padding: 18px 25px;
        margin-bottom: 25px;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-review-container {
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
<div class="container edit-review-container">
    <div class="edit-review-header animate__animated animate__fadeInDown">
        <h1 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa đánh giá</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Đánh giá của tôi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa đánh giá</li>
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
            <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
                @csrf
                @method('PUT')
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i>Chỉnh sửa đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-4">
                            <img src="{{ asset('storage/' . ($review->product->images->where('is_primary', true)->first()->thumbnail_path ?? $review->product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" 
                                alt="{{ $review->product->name }}" class="product-image me-3">
                            <div>
                                <h5 class="mb-1">{{ $review->product->name }}</h5>
                                <div class="mb-1 text-muted small">
                                    {{ $review->product->category->name ?? 'Không có danh mục' }} | {{ $review->product->brand }}
                                </div>
                                <div class="text-muted small">
                                    <strong>Đơn hàng:</strong> #{{ $review->order->order_number }}
                                    <span class="mx-2">|</span> 
                                    <strong>Ngày mua:</strong> {{ $review->order->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Đánh giá sao</label>
                            <div class="rating-input d-flex flex-wrap mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                <div class="form-check me-4 mb-2">
                                    <input class="form-check-input" type="radio" name="rating" id="rating_{{ $i }}" value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="rating_{{ $i }}">
                                        {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                    </label>
                                </div>
                                @endfor
                            </div>
                            @error("rating")
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">Nhận xét</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ old('comment', $review->comment) }}</textarea>
                            @error("comment")
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tối thiểu 10 ký tự.</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="pros" class="form-label">Ưu điểm (không bắt buộc)</label>
                                <textarea class="form-control" id="pros" name="pros" rows="2">{{ old('pros', $review->pros) }}</textarea>
                                @error("pros")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="cons" class="form-label">Nhược điểm (không bắt buộc)</label>
                                <textarea class="form-control" id="cons" name="cons" rows="2">{{ old('cons', $review->cons) }}</textarea>
                                @error("cons")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Hình ảnh hiện tại</label>
                            @if($review->images->count() > 0)
                            <div class="review-images">
                                @foreach($review->images as $image)
                                <div class="review-image-container">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="Hình ảnh đánh giá">
                                    <button type="button" class="remove-image-btn" data-image-id="{{ $image->id }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <input type="hidden" name="remove_images[]" id="remove_image_{{ $image->id }}" value="0">
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted">Không có hình ảnh nào.</p>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Thêm hình ảnh mới (không bắt buộc)</label>
                            <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                            @error("images")
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error("images.*")
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tối đa 5 ảnh, mỗi ảnh không quá 2MB. Định dạng hỗ trợ: JPG, PNG, GIF.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Cập nhật đánh giá
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card animate__animated animate__fadeInRight">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-3">Đánh giá của bạn nên cung cấp thông tin hữu ích cho người mua khác.</li>
                        <li class="mb-3">Chia sẻ trải nghiệm thực tế của bạn với sản phẩm.</li>
                        <li class="mb-3">Nêu rõ ưu, nhược điểm để người mua có cái nhìn khách quan.</li>
                        <li class="mb-3">Hình ảnh thực tế sẽ giúp đánh giá của bạn thuyết phục hơn.</li>
                        <li class="mb-0">Bạn có thể xóa hình ảnh đã tải lên hoặc thêm hình ảnh mới.</li>
                    </ul>
                </div>
            </div>
            
            <div class="card animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-award me-2"></i>Điểm thưởng</h5>
                </div>
                <div class="card-body">
                    <p>Mỗi đánh giá sản phẩm sẽ giúp bạn nhận được 100 điểm thưởng. Điểm thưởng có thể được dùng để:</p>
                    <ul class="mb-0">
                        <li class="mb-3">Đổi các mã giảm giá độc quyền</li>
                        <li class="mb-3">Tích lũy để nhận quà tặng</li>
                        <li class="mb-0">Đổi lấy ưu đãi vận chuyển</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle image removal
        const removeButtons = document.querySelectorAll('.remove-image-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-image-id');
                const hiddenInput = document.getElementById('remove_image_' + imageId);
                
                if (hiddenInput.value === '0') {
                    hiddenInput.value = '1';
                    this.closest('.review-image-container').style.opacity = '0.3';
                    button.innerHTML = '<i class="bi bi-arrow-counterclockwise"></i>';
                } else {
                    hiddenInput.value = '0';
                    this.closest('.review-image-container').style.opacity = '1';
                    button.innerHTML = '<i class="bi bi-x"></i>';
                }
            });
        });
    });
</script>
@endsection 