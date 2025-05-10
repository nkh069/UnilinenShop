@extends('layouts.shop')

@section('title', 'Đánh giá của tôi')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .reviews-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .reviews-header {
        margin-bottom: 2.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    .reviews-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 15px;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .reviews-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .card-body {
        padding: 25px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .review-product {
        display: flex;
        align-items: center;
    }
    
    .review-product-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .review-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .review-product-details h5 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .review-date {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 5px;
    }
    
    .review-order {
        font-size: 0.9rem;
        color: #555;
    }
    
    .review-rating {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .review-rating i {
        color: #ffc107;
        font-size: 1.1rem;
        margin-right: 3px;
    }
    
    .review-text {
        margin-bottom: 20px;
        color: #333;
        line-height: 1.6;
    }
    
    .review-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .review-meta-item {
        flex: 1;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-right: 15px;
    }
    
    .review-meta-item:last-child {
        margin-right: 0;
    }
    
    .review-meta-label {
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .review-meta-text {
        color: #333;
        font-size: 0.95rem;
    }
    
    .review-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .review-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .review-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s;
    }
    
    .review-image img:hover {
        transform: scale(1.05);
    }
    
    .review-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 12px;
        padding: 18px 25px;
        margin-bottom: 25px;
        font-weight: 500;
    }
    
    .empty-reviews {
        text-align: center;
        padding: 50px 0;
    }
    
    .empty-reviews i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .empty-reviews h3 {
        font-weight: 700;
        color: #555;
        margin-bottom: 15px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .reviews-container {
            padding: 40px 15px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .review-product-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }
        
        .review-meta {
            flex-direction: column;
        }
        
        .review-meta-item {
            margin-right: 0;
            margin-bottom: 10px;
        }
        
        .review-image {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endsection

@section('content')
<div class="container reviews-container">
    <div class="reviews-header animate__animated animate__fadeInDown">
        <h1><i class="bi bi-star-fill me-2"></i>Đánh giá của tôi</h1>
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
    
    @if($reviews->isEmpty())
    <div class="empty-reviews animate__animated animate__fadeIn">
        <i class="bi bi-star"></i>
        <h3>Bạn chưa đánh giá sản phẩm nào</h3>
        <p class="text-muted">Hãy mua sắm và đánh giá sản phẩm để nhận thêm điểm thưởng!</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
            <i class="bi bi-bag me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    @else
    <div class="row">
        @foreach($reviews as $review)
        <div class="col-lg-6 mb-4 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
            <div class="card">
                <div class="card-body">
                    <div class="review-header">
                        <div class="review-product">
                            <div class="review-product-image">
                                <img src="{{ asset('storage/' . ($review->product->images->where('is_primary', true)->first()->thumbnail_path ?? $review->product->images->where('is_primary', true)->first()->image_path ?? 'products/default.jpg')) }}" 
                                    alt="{{ $review->product->name }}">
                            </div>
                            <div class="review-product-details">
                                <h5>{{ $review->product->name }}</h5>
                                <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                                <div class="review-order">
                                    @if($review->order)
                                        Đơn hàng: #{{ $review->order->order_number }}
                                    @else
                                        Đơn hàng: N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                        <span class="ms-2">({{ $review->rating }}/5)</span>
                    </div>
                    
                    <div class="review-text">
                        {{ $review->comment }}
                    </div>
                    
                    @if($review->pros || $review->cons)
                    <div class="review-meta">
                        @if($review->pros)
                        <div class="review-meta-item">
                            <div class="review-meta-label"><i class="bi bi-hand-thumbs-up me-2"></i>Ưu điểm</div>
                            <div class="review-meta-text">{{ $review->pros }}</div>
                        </div>
                        @endif
                        
                        @if($review->cons)
                        <div class="review-meta-item">
                            <div class="review-meta-label"><i class="bi bi-hand-thumbs-down me-2"></i>Nhược điểm</div>
                            <div class="review-meta-text">{{ $review->cons }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    @if(isset($review->images) && ((is_object($review->images) && $review->images->count() > 0) || (is_array($review->images) && count($review->images) > 0)))
                    <div class="review-images">
                        @foreach($review->images as $image)
                        <div class="review-image">
                            <img src="{{ asset('storage/' . (is_object($image) ? $image->image : $image)) }}" alt="Hình ảnh đánh giá">
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="review-actions">
                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Sửa
                        </a>
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                <i class="bi bi-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $reviews->links() }}
    </div>
    @endif
</div>
@endsection 