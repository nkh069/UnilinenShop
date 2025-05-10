@extends('layouts.shop')

@section('title', 'Thanh toán qua thẻ tín dụng')

@section('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Animation CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .payment-container {
        padding: 60px 0;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .payment-header {
        margin-bottom: 2rem;
        position: relative;
    }
    
    .payment-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .payment-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .payment-card {
        background-color: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        transition: transform 0.3s;
    }
    
    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
    
    .credit-card-icons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .credit-card-icons img {
        height: 30px;
        opacity: 0.7;
        transition: all 0.3s;
    }
    
    .credit-card-icons img:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .credit-card-form {
        position: relative;
    }
    
    .card-preview {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #4158D0, #C850C0);
        border-radius: 20px;
        padding: 20px;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.5s;
        overflow: hidden;
    }
    
    .card-preview::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to right,
            rgba(255, 255, 255, 0.1),
            rgba(255, 255, 255, 0.05)
        );
        transform: rotate(30deg);
        pointer-events: none;
    }
    
    .card-type {
        position: absolute;
        top: 20px;
        right: 20px;
        height: 40px;
    }
    
    .card-number {
        font-size: 1.4rem;
        color: white;
        letter-spacing: 4px;
        margin-top: 60px;
        font-family: monospace;
        text-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .card-holder {
        color: white;
        font-size: 0.9rem;
        position: absolute;
        bottom: 50px;
        left: 20px;
        text-transform: uppercase;
    }
    
    .card-holder-name {
        font-size: 1.1rem;
        margin-top: 5px;
        font-weight: 600;
    }
    
    .card-expire {
        color: white;
        font-size: 0.9rem;
        position: absolute;
        bottom: 50px;
        right: 20px;
        text-align: right;
    }
    
    .card-expire-date {
        font-size: 1.1rem;
        margin-top: 5px;
        font-weight: 600;
    }
    
    .card-chip {
        width: 50px;
        height: 40px;
        background: linear-gradient(135deg, #ddd, #999);
        border-radius: 8px;
        position: absolute;
        top: 30px;
        left: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .card-chip::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 70%;
        background: linear-gradient(135deg, #b5b5b5, #e0e0e0);
        border-radius: 4px;
    }
    
    .card-chip::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50%;
        height: 30%;
        background: linear-gradient(135deg, #999, #ccc);
        border-radius: 2px;
    }
    
    .submit-button {
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
    
    .submit-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 66, 0, 0.25);
        background: linear-gradient(90deg, #ff4200, #ff5a20);
    }
    
    .back-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        padding: 10px;
    }
    
    .back-link:hover {
        color: #ff4200;
        transform: translateX(-5px);
    }
    
    .back-link i {
        margin-right: 5px;
        transition: transform 0.3s;
    }
    
    .back-link:hover i {
        transform: translateX(-5px);
    }
    
    .secure-payment {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f0f9ff;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
        border-left: 4px solid #0ea5e9;
    }
    
    .secure-payment i {
        color: #0ea5e9;
        font-size: 1.3rem;
    }
    
    .secure-payment p {
        margin: 0;
        font-size: 0.9rem;
        color: #555;
    }
    
    @media (max-width: 767px) {
        .card-preview {
            height: 180px;
        }
        
        .card-number {
            font-size: 1.2rem;
            margin-top: 50px;
        }
    }
</style>
@endsection

@section('content')
<div class="container payment-container">
    <!-- Header -->
    <div class="payment-header animate__animated animate__fadeInDown">
        <h1><i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng</h1>
    </div>
    
    <div class="row">
        <div class="col-lg-7">
            <div class="payment-card animate__animated animate__fadeInUp">
                <h3 class="mb-4">Thông tin thẻ thanh toán</h3>
                
                <div class="credit-card-icons">
                    <img src="https://www.svgrepo.com/show/328132/visa.svg" alt="Visa">
                    <img src="https://www.svgrepo.com/show/328121/mastercard.svg" alt="MasterCard">
                    <img src="https://www.svgrepo.com/show/328147/american-express.svg" alt="American Express">
                    <img src="https://www.svgrepo.com/show/328180/jcb.svg" alt="JCB">
                </div>
                
                <div class="credit-card-form">
                    <!-- Card Preview -->
                    <div class="card-preview" id="cardPreview">
                        <div class="card-chip"></div>
                        <img src="https://www.svgrepo.com/show/328132/visa.svg" alt="Card Type" class="card-type" id="cardTypeImg">
                        <div class="card-number" id="cardNumberPreview">**** **** **** ****</div>
                        <div class="card-holder">
                            <div>CHỦ THẺ</div>
                            <div class="card-holder-name" id="cardHolderPreview">TÊN CHỦ THẺ</div>
                        </div>
                        <div class="card-expire">
                            <div>THÁNG/NĂM</div>
                            <div class="card-expire-date">
                                <span id="monthPreview">MM</span>/<span id="yearPreview">YY</span>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('payment.complete', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Số thẻ</label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" required pattern="[0-9\s]{13,19}" maxlength="19">
                        </div>
                        
                        <div class="mb-3">
                            <label for="cardName" class="form-label">Tên chủ thẻ</label>
                            <input type="text" class="form-control" id="cardName" placeholder="Nhập tên in trên thẻ" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày hết hạn</label>
                                <div class="d-flex gap-3">
                                    <select class="form-select" id="expMonth" required>
                                        <option value="" selected disabled>Tháng</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                    <select class="form-select" id="expYear" required>
                                        <option value="" selected disabled>Năm</option>
                                        @for ($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ substr($i, 2) }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">Mã bảo mật (CVV/CVC)</label>
                                <input type="password" class="form-control" id="cvv" placeholder="***" required maxlength="4" pattern="[0-9]{3,4}">
                            </div>
                        </div>
                        
                        <div class="secure-payment">
                            <i class="bi bi-shield-lock"></i>
                            <p>Thông tin thanh toán của bạn được bảo mật an toàn với mã hóa chuẩn SSL.</p>
                        </div>
                        
                        <button type="submit" class="submit-button">
                            <i class="bi bi-lock"></i>Thanh toán {{ number_format($order->total_amount) }}₫
                        </button>
                        
                        <a href="{{ route('orders.show', $order->order_number) }}" class="back-link">
                            <i class="bi bi-arrow-left"></i>Quay lại đơn hàng
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="payment-card animate__animated animate__fadeInRight">
                <h3 class="mb-4">Thông tin đơn hàng #{{ $order->order_number }}</h3>
                
                <div class="order-details">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng tiền hàng:</span>
                        <span class="fw-bold">{{ number_format($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount) }}₫</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Giảm giá:</span>
                        <span class="fw-bold text-success">-{{ number_format($order->discount_amount) }}₫</span>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-bold">{{ number_format($order->shipping_amount) }}₫</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Thuế VAT (10%):</span>
                        <span class="fw-bold">{{ number_format($order->tax_amount) }}₫</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h5">Tổng thanh toán:</span>
                        <span class="h5 text-danger">{{ number_format($order->total_amount) }}₫</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5 class="mb-3">Thông tin giao hàng</h5>
                    <p class="mb-1"><strong>Người nhận:</strong> {{ auth()->user()->name }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    <p class="mb-1"><strong>Thành phố:</strong> {{ $order->shipping_city }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Card preview animations
        const cardNumber = document.getElementById('cardNumber');
        const cardName = document.getElementById('cardName');
        const expMonth = document.getElementById('expMonth');
        const expYear = document.getElementById('expYear');
        
        const cardNumberPreview = document.getElementById('cardNumberPreview');
        const cardHolderPreview = document.getElementById('cardHolderPreview');
        const monthPreview = document.getElementById('monthPreview');
        const yearPreview = document.getElementById('yearPreview');
        const cardTypeImg = document.getElementById('cardTypeImg');
        const cardPreview = document.getElementById('cardPreview');
        
        // Format card number with spaces
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue;
            
            // Update preview
            cardNumberPreview.textContent = formattedValue || '**** **** **** ****';
            
            // Detect card type
            if (value.startsWith('4')) {
                cardTypeImg.src = 'https://www.svgrepo.com/show/328132/visa.svg';
                cardPreview.style.background = 'linear-gradient(135deg, #0099cc, #004B91)';
            } else if (value.startsWith('5')) {
                cardTypeImg.src = 'https://www.svgrepo.com/show/328121/mastercard.svg';
                cardPreview.style.background = 'linear-gradient(135deg, #FF5F00, #CC0000)';
            } else if (value.startsWith('3')) {
                cardTypeImg.src = 'https://www.svgrepo.com/show/328147/american-express.svg';
                cardPreview.style.background = 'linear-gradient(135deg, #00CCFF, #0066CC)';
            } else if (value.startsWith('35')) {
                cardTypeImg.src = 'https://www.svgrepo.com/show/328180/jcb.svg';
                cardPreview.style.background = 'linear-gradient(135deg, #007B40, #D4C556)';
            } else {
                cardTypeImg.src = 'https://www.svgrepo.com/show/328132/visa.svg';
                cardPreview.style.background = 'linear-gradient(135deg, #4158D0, #C850C0)';
            }
        });
        
        cardName.addEventListener('input', function(e) {
            cardHolderPreview.textContent = e.target.value.toUpperCase() || 'TÊN CHỦ THẺ';
        });
        
        expMonth.addEventListener('change', function(e) {
            monthPreview.textContent = e.target.value || 'MM';
        });
        
        expYear.addEventListener('change', function(e) {
            yearPreview.textContent = e.target.value || 'YY';
        });
    });
</script>
@endsection 