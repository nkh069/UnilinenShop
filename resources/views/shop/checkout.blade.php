@extends('layouts.shop')

@section('title', 'Thanh toán đơn hàng')

@section('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Animation CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .checkout-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    @media (max-width: 768px) {
        .checkout-container {
            padding: 40px 15px;
        }
    }
    
    .checkout-header {
        margin-bottom: 2rem;
        position: relative;
    }
    
    .checkout-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .checkout-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .checkout-form {
        background-color: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .form-section-title {
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-section-title i {
        color: #ff4200;
    }
    
    .form-control {
        border-radius: 50px;
        padding: 12px 20px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(255, 66, 0, 0.1);
        border-color: #ff4200;
    }
    
    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }
    
    .order-summary {
        background-color: white;
        border-radius: 16px;
        padding: 30px;
        position: sticky;
        top: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        transition: transform 0.4s ease;
    }
    
    .order-summary:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .summary-header {
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
    
    .summary-header i {
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
    
    .payment-methods {
        margin-top: 20px;
    }
    
    .payment-method-item {
        border: 1px solid #eaeaea;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .payment-method-item:hover {
        border-color: #ff4200;
        background-color: #fff8f6;
    }
    
    .payment-method-item.active {
        border-color: #ff4200;
        background-color: #fff0eb;
    }
    
    .payment-method-item .payment-logo {
        height: 30px;
        object-fit: contain;
        margin-right: 10px;
    }
    
    .place-order-btn {
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
    
    .place-order-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 66, 0, 0.25);
        background: linear-gradient(90deg, #ff4200, #ff5a20);
    }
    
    .back-to-cart {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        padding: 10px;
    }
    
    .back-to-cart:hover {
        color: #ff4200;
        transform: translateX(-5px);
    }
    
    .back-to-cart i {
        margin-right: 5px;
        transition: transform 0.3s;
    }
    
    .back-to-cart:hover i {
        transform: translateX(-5px);
    }
    
    .cart-items-preview {
        margin-top: 20px;
    }
    
    .cart-item-preview {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .cart-item-preview:last-child {
        border-bottom: none;
    }
    
    .item-info {
        display: flex;
        align-items: center;
    }
    
    .item-quantity {
        background-color: #f0f0f0;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 10px;
    }
    
    .item-name {
        font-weight: 500;
        color: #333;
    }
    
    .item-attributes {
        font-size: 0.8rem;
        color: #777;
    }
    
    .item-price {
        font-weight: 600;
        color: #ff4200;
    }
    
    /* Coupon section styles */
    .coupon-section {
        padding: 15px 0;
        border-top: 1px dashed #e0e0e0;
        border-bottom: 1px dashed #e0e0e0;
    }
    
    .coupon-title {
        color: #333;
        font-size: 0.95rem;
    }
    
    .applied-coupon .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }
    
    .applied-coupon .btn-outline-danger {
        border-radius: 50%;
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 991px) {
        .order-summary {
            margin-top: 30px;
            position: relative;
        }
    }
</style>
@endsection

@section('content')
<div class="container checkout-container">
    <!-- Header -->
    <div class="checkout-header animate__animated animate__fadeInDown">
        <h1><i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng</h1>
    </div>
    
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <form action="{{ route('checkout.place-order') }}" method="POST" id="checkout-form" class="checkout-form animate__animated animate__fadeInUp">
                @csrf
                
                @if(isset($isBuyNow) && $isBuyNow)
                    <!-- Hidden fields for buy now -->
                    <input type="hidden" name="is_buy_now" value="1">
                    @foreach($cart as $itemId => $item)
                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                        <input type="hidden" name="quantity" value="{{ $item['quantity'] }}">
                        <input type="hidden" name="size" value="{{ $item['size'] }}">
                        <input type="hidden" name="color" value="{{ $item['color'] }}">
                    @endforeach
                @endif
                
                <!-- Thông tin khách hàng -->
                <div class="mb-4">
                    <h3 class="form-section-title">
                        <i class="bi bi-person-circle"></i>
                        Thông tin khách hàng
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Địa chỉ giao hàng -->
                <div class="mb-4">
                    <h3 class="form-section-title">
                        <i class="bi bi-geo-alt"></i>
                        Địa chỉ giao hàng
                    </h3>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">Thành phố</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">Mã bưu điện</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">Quốc gia</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', 'Việt Nam') }}" required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Phương thức thanh toán -->
                <div class="mb-4">
                    <h3 class="form-section-title">
                        <i class="bi bi-wallet2"></i>
                        Phương thức thanh toán
                    </h3>
                    
                    <div class="payment-methods">
                        <div class="payment-method-item active">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label d-flex align-items-center" for="cod">
                                    <img src="https://cdn-icons-png.flaticon.com/512/1554/1554401.png" alt="COD" class="payment-logo">
                                    <div>
                                        <div class="fw-bold">Thanh toán khi nhận hàng (COD)</div>
                                        <div class="small text-muted">Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-item">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label d-flex align-items-center" for="bank_transfer">
                                    <img src="https://cdn-icons-png.flaticon.com/512/6214/6214166.png" alt="Bank Transfer" class="payment-logo">
                                    <div>
                                        <div class="fw-bold">Chuyển khoản ngân hàng</div>
                                        <div class="small text-muted">Chuyển khoản qua ngân hàng của bạn</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-item">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                                <label class="form-check-label d-flex align-items-center" for="momo">
                                    <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" class="payment-logo">
                                    <div>
                                        <div class="fw-bold">Thanh toán qua MoMo</div>
                                        <div class="small text-muted">Sử dụng ví điện tử MoMo để thanh toán</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-item">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card">
                                <label class="form-check-label d-flex align-items-center" for="credit_card">
                                    <img src="https://cdn-icons-png.flaticon.com/512/179/179457.png" alt="Credit Card" class="payment-logo">
                                    <div>
                                        <div class="fw-bold">Thẻ tín dụng/Ghi nợ</div>
                                        <div class="small text-muted">Thanh toán an toàn với thẻ của bạn</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ghi chú đơn hàng -->
                <div class="mb-4">
                    <h3 class="form-section-title">
                        <i class="bi bi-pencil-square"></i>
                        Ghi chú đơn hàng
                    </h3>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Nhập ghi chú cho đơn hàng (nếu có)">{{ old('notes') }}</textarea>
                    </div>
                </div>
            
                <!-- Nút đặt hàng (mobile) -->
                <div class="d-lg-none">
                    <button type="submit" class="place-order-btn">
                        <i class="bi bi-check-circle"></i>Đặt hàng
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="back-to-cart">
                        <i class="bi bi-arrow-left"></i>Quay lại giỏ hàng
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary animate__animated animate__fadeInRight">
                <div class="summary-header">
                    <i class="bi bi-receipt"></i>Tóm tắt đơn hàng
                </div>
                
                <!-- Cart Items Preview -->
                <div class="cart-items-preview">
                    @foreach($cart as $itemId => $item)
                    <div class="cart-item-preview">
                        <div class="item-info">
                            <div class="item-quantity">{{ $item['quantity'] }}</div>
                            <div>
                                <div class="item-name">{{ $item['name'] }}</div>
                                <div class="item-attributes">
                                    @if(isset($item['size']) && $item['size']) Size: {{ $item['size'] }} @endif
                                    @if(isset($item['color']) && $item['color']) Màu: {{ $item['color'] }} @endif
                                </div>
                            </div>
                        </div>
                        <div class="item-price">{{ number_format($item['price'] * $item['quantity']) }}₫</div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Coupon Section -->
                <div class="coupon-section mb-3">
                    <div class="coupon-title mb-2">
                        <i class="bi bi-tag-fill text-danger me-1"></i>
                        <span class="fw-bold">Mã giảm giá</span>
                    </div>
                    
                    @if(session('error'))
                    <div class="alert alert-danger py-2 mb-2">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    @if(session('success'))
                    <div class="alert alert-success py-2 mb-2">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if(isset($coupon))
                    <div class="applied-coupon mb-2">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <div>
                                <span class="badge bg-success me-2">{{ $coupon['code'] }}</span>
                                <span class="small">
                                    @if($coupon['type'] == 'percentage')
                                    Giảm {{ $coupon['value'] }}%
                                    @else
                                    Giảm {{ number_format($coupon['value']) }}₫
                                    @endif
                                </span>
                            </div>
                            <a href="{{ route('checkout.remove-coupon') }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-x"></i>
                            </a>
                        </div>
                    </div>
                    @else
                    <form action="{{ route('checkout.apply-coupon') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control form-control-sm me-2" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Áp dụng</button>
                    </form>
                    @endif
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Tạm tính</span>
                    <span class="summary-value">{{ number_format($subtotal) }}₫</span>
                </div>
                
                @if($discount > 0)
                <div class="summary-row">
                    <span class="summary-label">Giảm giá</span>
                    <span class="summary-value text-success">-{{ number_format($discount) }}₫</span>
                </div>
                @endif
                
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-value">{{ number_format($shippingCost) }}₫</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Thuế (10%)</span>
                    <span class="summary-value">{{ number_format($tax) }}₫</span>
                </div>
                
                <div class="summary-row total">
                    <span class="summary-label">Tổng cộng</span>
                    <span class="summary-value total-value">{{ number_format($total) }}₫</span>
                </div>
                
                <!-- Nút đặt hàng (desktop) -->
                <div class="d-none d-lg-block">
                    <button type="submit" form="checkout-form" class="place-order-btn">
                        <i class="bi bi-check-circle"></i>Đặt hàng
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="back-to-cart">
                        <i class="bi bi-arrow-left"></i>Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method selection
        const paymentItems = document.querySelectorAll('.payment-method-item');
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        
        paymentItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                paymentItems.forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Find and check the radio input within the clicked item
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });
        
        // Submit form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            
            if (!name || !email || !phone || !address) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin cần thiết.');
            }
        });
    });
</script>
@endsection 