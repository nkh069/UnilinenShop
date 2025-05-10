@extends('layouts.shop')

@section('title', 'Đặt hàng thành công')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .confirmation-container {
        padding: 60px 0;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .confirmation-card {
        background-color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        text-align: center;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        box-shadow: 0 10px 20px rgba(76, 175, 80, 0.2);
    }
    
    .success-icon i {
        font-size: 50px;
        color: white;
    }
    
    .confirmation-title {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: #222;
    }
    
    .confirmation-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 30px;
    }
    
    .order-info {
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        padding: 25px;
        margin: 30px 0;
        text-align: left;
        background-color: #fafafa;
    }
    
    .order-heading {
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .order-heading i {
        color: #ff4200;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 15px;
    }
    
    .info-label {
        font-weight: 600;
        width: 180px;
        color: #555;
    }
    
    .info-value {
        color: #333;
        flex: 1;
    }
    
    .product-list {
        margin-top: 20px;
    }
    
    .product-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .product-item:last-child {
        border-bottom: none;
    }
    
    .product-name {
        font-weight: 500;
        color: #333;
    }
    
    .product-details {
        color: #777;
        font-size: 0.9rem;
    }
    
    .product-price {
        font-weight: 600;
        color: #ff4200;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.2rem;
        font-weight: 700;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #f0f0f0;
    }
    
    .action-buttons {
        margin-top: 40px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }
    
    .btn-primary-gradient {
        background: linear-gradient(90deg, #ff4200, #ff7848);
        color: white;
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(255, 66, 0, 0.2);
    }
    
    .btn-primary-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 66, 0, 0.25);
    }
    
    .btn-secondary-outline {
        background-color: transparent;
        color: #6c757d;
        border: 1px solid #6c757d;
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s;
    }
    
    .btn-secondary-outline:hover {
        background-color: #f8f9fa;
        transform: translateY(-3px);
    }
    
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="container confirmation-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="confirmation-card animate__animated animate__fadeIn">
                <div class="success-icon animate__animated animate__bounceIn">
                    <i class="bi bi-check-lg"></i>
                </div>
                
                <h1 class="confirmation-title">Đặt hàng thành công!</h1>
                <p class="confirmation-subtitle">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được ghi nhận và đang được xử lý.</p>
                
                <div class="order-info">
                    <h3 class="order-heading">
                        <i class="bi bi-info-circle"></i>Thông tin đơn hàng
                    </h3>
                    
                    <div class="info-row">
                        <div class="info-label">Mã đơn hàng:</div>
                        <div class="info-value">{{ $order->order_number }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Ngày đặt hàng:</div>
                        <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Phương thức thanh toán:</div>
                        <div class="info-value">
                            @switch($order->payment_method)
                                @case('cod')
                                    <i class="bi bi-cash"></i> Thanh toán khi nhận hàng
                                    @break
                                @case('bank_transfer')
                                    <i class="bi bi-bank"></i> Chuyển khoản ngân hàng
                                    @break
                                @case('momo')
                                    <i class="bi bi-wallet2"></i> Ví điện tử MoMo
                                    @break
                                @case('credit_card')
                                    <i class="bi bi-credit-card"></i> Thẻ tín dụng/Ghi nợ
                                    @break
                                @default
                                    {{ $order->payment_method }}
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Địa chỉ giao hàng:</div>
                        <div class="info-value">
                            {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_country }}, {{ $order->shipping_postal_code }}
                        </div>
                    </div>
                    
                    <h3 class="order-heading mt-4">
                        <i class="bi bi-box2"></i>Sản phẩm đặt mua
                    </h3>
                    
                    <div class="product-list">
                        @foreach($order->items as $item)
                        <div class="product-item">
                            <div>
                                <div class="product-name">{{ $item->product_name }} × {{ $item->quantity }}</div>
                                <div class="product-details">
                                    @if($item->size) Size: {{ $item->size }} @endif
                                    @if($item->color) Màu: {{ $item->color }} @endif
                                </div>
                            </div>
                            <div class="product-price">{{ number_format($item->subtotal) }}₫</div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mt-3">
                        <div>Giảm giá:</div>
                        <div class="text-success">-{{ number_format($order->discount_amount) }}₫</div>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mt-2">
                        <div>Phí vận chuyển:</div>
                        <div>{{ number_format($order->shipping_amount) }}₫</div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-2">
                        <div>Thuế (10%):</div>
                        <div>{{ number_format($order->tax_amount) }}₫</div>
                    </div>
                    
                    <div class="total-row">
                        <div>Tổng cộng:</div>
                        <div class="text-danger">{{ number_format($order->total_amount) }}₫</div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="bi bi-envelope"></i> Một email xác nhận đơn hàng đã được gửi đến <strong>{{ Auth::user()->email ?? 'địa chỉ email của bạn' }}</strong>. Vui lòng kiểm tra hộp thư của bạn.
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary-gradient">
                        <i class="bi bi-eye"></i> Xem chi tiết đơn hàng
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary-outline">
                        <i class="bi bi-bag"></i> Tiếp tục mua sắm
                    </a>
                    @if($order->invoice)
                    <a href="{{ route('invoices.download', $order->invoice->invoice_number) }}" target="_blank" class="btn btn-secondary-outline">
                        <i class="bi bi-download"></i> Tải hóa đơn
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 