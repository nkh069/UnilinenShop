@extends('layouts.shop')

@section('title', 'Giỏ hàng của bạn')

@section('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Animation CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .a{
        color: #ff4200; 
    }
    .cart-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .cart-header {
        margin-bottom: 2.5rem;
        position: relative;
    }
    
    .cart-header::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .cart-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
    }
    
    .cart-header h1 i {
        color: #ff4200;
        margin-right: 12px;
        font-size: 1.8rem;
    }
    
    .cart-count {
        color: #777;
        font-size: 1.1rem;
        font-weight: normal;
        margin-left: 10px;
        background-color: #f1f1f1;
        padding: 4px 12px;
        border-radius: 50px;
    }
    
    .clear-cart-btn {
        background: transparent;
        border: 1px solid #e0e0e0;
        color: #666;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.95rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
    }
    
    .clear-cart-btn:hover {
        background-color: #fff0eb;
        color: #ff4200;
        border-color: #ffcece;
        box-shadow: 0 3px 10px rgba(255, 66, 0, 0.1);
    }
    
    .cart-item {
        background-color: #fff;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        transition: all 0.4s ease;
        border: 1px solid #f1f1f1;
        display: flex;
        align-items: center;
        gap: 25px;
        position: relative;
        overflow: hidden;
    }
    
    .cart-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, #ff4200, transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .cart-item:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        transform: translateY(-5px);
    }
    
    .cart-item:hover::before {
        opacity: 1;
    }
    
    .cart-item-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        width: 140px;
        height: 140px;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    
    .cart-item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .cart-item-img-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.03);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .cart-item:hover .cart-item-img {
        transform: scale(1.1);
    }
    
    .cart-item:hover .cart-item-img-container::after {
        opacity: 1;
    }
    
    .cart-item-placeholder {
        width: 140px;
        height: 140px;
        background-color: #f5f5f5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 1.5rem;
    }
    
    .cart-item-details {
        flex: 1;
        min-width: 0;
        padding-right: 15px;
    }
    
    .cart-item-title {
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.3;
        font-size: 1.3rem;
    }
    
    .cart-item-title a {
        color: #222;
        text-decoration: none;
        transition: color 0.3s;
        position: relative;
        display: inline-block;
    }
    
    .cart-item-title a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        transition: width 0.3s ease;
    }
    
    .cart-item-title a:hover {
        color: #ff4200;
    }
    
    .cart-item-title a:hover::after {
        width: 100%;
    }
    
    .cart-item-meta {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .cart-item-meta .badge {
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 50px;
        background-color: #f0f0f0;
        color: #555;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
    }
    
    .cart-item-meta .badge:hover {
        background-color: #e9e9e9;
        transform: translateY(-2px);
    }
    
    .cart-item-meta .badge i {
        font-size: 0.8rem;
        color: #ff4200;
    }
    
    .cart-item-price {
        font-weight: 700;
        color: #ff4200;
        font-size: 1.2rem;
        white-space: nowrap;
        text-shadow: 0 1px 1px rgba(0,0,0,0.05);
    }
    
    .cart-item-original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.95rem;
        margin-left: 8px;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        border: 1px solid #eaeaea;
        border-radius: 50px;
        overflow: hidden;
        width: fit-content;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        background: #fff;
        transition: all 0.3s;
    }
    
    .quantity-selector:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    
    .quantity-btn {
        width: 42px;
        height: 42px;
        background: #f8f8f8;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        color: #444;
        transition: all 0.3s;
    }
    
    .quantity-btn:hover {
        background: #f0f0f0;
        color: #ff4200;
    }
    
    .quantity-btn:disabled {
        color: #ddd;
        cursor: not-allowed;
    }
    
    .quantity-input {
        width: 50px;
        height: 42px;
        border: none;
        border-left: 1px solid #f0f0f0;
        border-right: 1px solid #f0f0f0;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
        padding: 0;
        -moz-appearance: textfield;
        background-color: #fff;
        color: #333;
    }
    
    .quantity-input::-webkit-inner-spin-button, 
    .quantity-input::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        margin: 0;
    }
    
    .cart-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 15px;
    }
    
    .item-total {
        font-weight: 800;
        color: #222;
        font-size: 1.25rem;
        padding: 6px 15px;
        background-color: #f9f9f9;
        border-radius: 50px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.03);
    }
    
    .remove-item-btn {
        background: #fff;
        border: 1px solid #f0f0f0;
        color: #777;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
        padding: 8px 14px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .remove-item-btn:hover {
        color: #ff4200;
        background-color: #fff0eb;
        border-color: #ffd9cc;
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(255, 66, 0, 0.08);
    }
    
    .cart-summary {
        background-color: #fff;
        border-radius: 16px;
        padding: 30px;
        position: sticky;
        top: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        transition: transform 0.4s ease;
    }
    
    .cart-summary:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .cart-summary-header {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0f0f0;
        color: #222;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .cart-summary-header i {
        color: #ff4200;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        color: #555;
        font-size: 1rem;
    }
    
    .summary-row.total {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #f0f0f0;
        font-size: 1.2rem;
        color: #222;
    }
    
    .summary-label {
        font-weight: 500;
    }
    
    .summary-value {
        font-weight: 700;
    }
    
    .total-value {
        color: #ff4200;
        font-size: 1.5rem;
    }
    
    .checkout-btn {
        background: linear-gradient(90deg, #ff4200, #ff7848);
        color: white;
        border: none;
        width: 100%;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(255, 66, 0, 0.2);
    }
    
    .checkout-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 66, 0, 0.25);
        background: linear-gradient(90deg, #ff4200, #ff5a20);
    }
    
    .continue-shopping {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        padding: 10px;
    }
    
    .continue-shopping:hover {
        color: #ff4200;
        transform: translateX(-5px);
    }
    
    .continue-shopping i {
        margin-right: 5px;
        transition: transform 0.3s;
    }
    
    .continue-shopping:hover i {
        transform: translateX(-5px);
    }
    
    .coupon-section {
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #f0f0f0;
    }
    
    .coupon-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 15px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .coupon-title i {
        color: #ff4200;
    }
    
    .coupon-form {
        display: flex;
        gap: 10px;
    }
    
    .coupon-input {
        flex: 1;
        border: 1px solid #e0e0e0;
        border-radius: 50px;
        padding: 10px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .coupon-input:focus {
        outline: none;
        border-color: #ff4200;
        box-shadow: 0 0 0 3px rgba(255, 66, 0, 0.1);
    }
    
    .coupon-btn {
        background-color: #333;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .coupon-btn:hover {
        background-color: #ff4200;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .applied-coupon {
        background-color: #f9f9f9;
        border: 1px dashed #ddd;
        border-radius: 8px;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .coupon-code {
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .coupon-code i {
        color: #4CAF50;
    }
    
    .remove-coupon {
        background: none;
        border: none;
        color: #ff4200;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
    }
    
    .remove-coupon:hover {
        color: #e53935;
        transform: translateX(2px);
    }
    
    .empty-cart {
        text-align: center;
        padding: 60px 30px;
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }
    
    .empty-cart-icon {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 20px;
        display: inline-block;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    
    .empty-cart-title {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .empty-cart-text {
        color: #777;
        margin-bottom: 30px;
        font-size: 1.1rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .shopping-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        color: white;
        text-decoration: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(255, 66, 0, 0.2);
    }
    
    .shopping-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(255, 66, 0, 0.25);
        color: white;
        background: linear-gradient(90deg, #ff4200, #ff5a20);
    }
    
    @media (max-width: 992px) {
        .cart-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .cart-item-img-container {
            width: 100%;
            height: 250px;
        }
        
        .cart-item-actions {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 15px;
        }
    }
    
    @media (max-width: 768px) {
        .cart-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .cart-summary {
            margin-top: 30px;
        }
        
        .cart-container {
            padding: 40px 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="container cart-container">
    <!-- Toast thông báo -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080">
        @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0 animate__animated animate__fadeInRight" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0 animate__animated animate__fadeInRight" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Header -->
    <div class="cart-header animate__animated animate__fadeInDown">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="bi bi-cart3 me-2"></i>Giỏ hàng của bạn <span class="cart-count">(@if($cartItems) {{ count($cartItems) }} @else 0 @endif sản phẩm)</span></h1>
            @if($cartItems && count($cartItems) > 0)
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?')">
                @csrf
                <button type="submit" class="clear-cart-btn">
                    <i class="bi bi-trash me-1"></i>Xóa tất cả
                </button>
            </form>
            @endif
        </div>
    </div>
    
    @if($cartItems && count($cartItems) > 0)
    <div class="row">
        <!-- Danh sách sản phẩm -->
        <div class="col-lg-8">
            @foreach($cartItems as $index => $item)
            <div class="cart-item animate__animated animate__fadeInUp" style="animation-delay: {{ 0.1 * $index }}s;">
                <!-- Ảnh sản phẩm -->
                <div class="cart-item-img-container">
                    @if($item->product && $item->product->images->count() > 0)
                        <a href="{{ route('products.show', $item->product->slug) }}">
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="cart-item-img">
                        </a>
                    @else
                        <div class="cart-item-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Thông tin sản phẩm -->
                <div class="cart-item-details">
                    <h3 class="cart-item-title">
                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                    </h3>
                    
                    <div class="cart-item-meta">
                        @if($item->size)
                        <span class="badge">
                            <i class="bi bi-rulers"></i> Size: {{ $item->size }}
                        </span>
                        @endif
                        
                        @if($item->color)
                        <span class="badge">
                            <i class="bi bi-palette"></i> Màu: {{ $item->color }}
                        </span>
                        @endif
                        
                        <span class="badge">
                            <i class="bi bi-tag"></i> {{ $item->product->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </div>
                    
                    <div class="cart-item-price">
                        {{ number_format($item->price) }}₫
                        @if($item->product->original_price && $item->product->original_price > $item->price)
                        <span class="cart-item-original-price">{{ number_format($item->product->original_price) }}₫</span>
                        @endif
                    </div>
                </div>
                
                <!-- Hành động sản phẩm -->
                <div class="cart-item-actions">
                    <div class="item-total">{{ number_format($item->price * $item->quantity) }}₫</div>
                    
                    <form action="{{ route('cart.update') }}" method="POST" class="update-quantity-form">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn decrement" @if($item->quantity <= 1) disabled @endif>
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="quantity-input" readonly>
                            <button type="button" class="quantity-btn increment" @if($item->quantity >= 10) disabled @endif>
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </form>
                    
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" class="remove-item-btn" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="cart-summary animate__animated animate__fadeInRight">
                <div class="cart-summary-header">
                    <i class="bi bi-receipt"></i>Tóm tắt đơn hàng
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Tạm tính ({{ count($cartItems) }} sản phẩm)</span>
                    <span class="summary-value">{{ number_format($subtotal) }}₫</span>
                </div>
                
                @if($discount > 0)
                <div class="summary-row">
                    <span class="summary-label">Giảm giá</span>
                    <span class="summary-value text-success">-{{ number_format($discount) }}₫</span>
                </div>
                @endif
                
                <div class="summary-row">
                    <span class="summary-label">Thuế VAT (10%)</span>
                    <span class="summary-value">{{ number_format($tax) }}₫</span>
                </div>
                
                <div class="summary-row total">
                    <span class="summary-label">Tổng cộng</span>
                    <span class="summary-value total-value">{{ number_format($total) }}₫</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="btn checkout-btn">
                    <i class="bi bi-credit-card"></i>Tiến hành thanh toán
                </a>
                
                <a href="{{ route('products.index') }}" class="continue-shopping">
                    <i class="bi bi-arrow-left"></i>Tiếp tục mua sắm
                </a>
                
                <!-- Phần mã giảm giá -->
                <div class="coupon-section">
                    <div class="coupon-title">
                        <i class="bi bi-ticket-perforated"></i>Mã giảm giá
                    </div>
                    
                    @if(!session('coupon'))
                    <form action="{{ route('checkout.apply-coupon') }}" method="POST" class="coupon-form">
                        @csrf
                        <input type="text" name="code" class="coupon-input" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="coupon-btn">Áp dụng</button>
                    </form>
                    @else
                    <div class="applied-coupon">
                        <div>
                            <span class="coupon-code"><i class="bi bi-check-circle"></i> {{ session('coupon')->code }}</span>
                            <span class="text-success ms-2">-{{ number_format($discount) }}₫</span>
                        </div>
                        <form action="{{ route('checkout.remove-coupon') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-coupon">
                                <i class="bi bi-x-circle"></i>Xóa
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="empty-cart animate__animated animate__fadeIn">
        <i class="bi bi-cart-x empty-cart-icon"></i>
        <h2 class="empty-cart-title">Giỏ hàng của bạn đang trống</h2>
        <p class="empty-cart-text">Hãy thêm sản phẩm vào giỏ hàng và quay lại nơi này để thanh toán.</p>
        <a href="{{ route('products.index') }}" class="shopping-btn animate__animated animate__pulse animate__infinite">
            <i class="bi bi-bag"></i>Mua sắm ngay
        </a>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút tăng/giảm số lượng
        const decrementButtons = document.querySelectorAll('.decrement');
        const incrementButtons = document.querySelectorAll('.increment');
        
        decrementButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const quantityInput = form.querySelector('.quantity-input');
                const currentValue = parseInt(quantityInput.value);
                
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    if (currentValue - 1 <= 1) {
                        this.disabled = true;
                    }
                    form.submit();
                }
            });
        });
        
        incrementButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const quantityInput = form.querySelector('.quantity-input');
                const currentValue = parseInt(quantityInput.value);
                
                if (currentValue < 10) {
                    quantityInput.value = currentValue + 1;
                    form.querySelector('.decrement').disabled = false;
                    if (currentValue + 1 >= 10) {
                        this.disabled = true;
                    }
                    form.submit();
                }
            });
        });
        
        // Thêm hiệu ứng hover cho các sản phẩm
        const cartItems = document.querySelectorAll('.cart-item');
        
        cartItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.08)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.05)';
            });
        });
        
        // Toast notification cho các thông báo
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        const toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });
        });
        
        toastList.forEach(toast => toast.show());
    });
</script>
@endsection 