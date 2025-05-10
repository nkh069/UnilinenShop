@extends('layouts.shop')

@section('title', 'Đơn hàng của tôi')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .orders-container {
        padding: 60px 20px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .orders-header {
        margin-bottom: 2rem;
        position: relative;
        padding-left: 10px;
    }
    
    .orders-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 10px;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .orders-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .order-card {
        background-color: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }
    
    .order-number {
        font-weight: 700;
        font-size: 1.2rem;
        color: #333;
    }
    
    .order-date {
        color: #777;
        font-size: 0.9rem;
    }
    
    .order-status {
        font-weight: 600;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        margin-left: 15px;
    }
    
    .status-pending {
        background-color: #fff8e1;
        color: #ffa000;
    }
    
    .status-processing {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    
    .status-shipped {
        background-color: #e8f5e9;
        color: #388e3c;
    }
    
    .status-delivered {
        background-color: #e8f5e9;
        color: #388e3c;
    }
    
    .status-cancelled {
        background-color: #feebee;
        color: #c62828;
    }
    
    .status-refunded {
        background-color: #f5f5f5;
        color: #616161;
    }
    
    .order-info {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin-bottom: 25px;
        padding: 0 10px;
    }
    
    .info-item {
        flex: 1;
        min-width: 200px;
        padding: 10px;
    }
    
    .info-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .info-value {
        color: #333;
    }
    
    .order-total {
        text-align: right;
        font-size: 1.2rem;
        font-weight: 700;
        color: #ff4200;
        padding-right: 15px;
    }
    
    .order-actions {
        margin-top: 25px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-right: 10px;
    }
    
    .btn-view {
        background-color: #0d6efd;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .btn-view:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }
    
    .btn-cancel {
        background-color: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background-color: #feebee;
        transform: translateY(-2px);
    }
    
    .empty-orders {
        text-align: center;
        padding: 60px 0;
        margin: 0 20px;
    }
    
    .empty-orders i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 25px;
    }
    
    .empty-orders h3 {
        font-weight: 700;
        color: #555;
        margin-bottom: 20px;
    }
    
    .btn-shop {
        background: linear-gradient(90deg, #ff4200, #ff7848);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        margin-top: 25px;
        transition: all 0.3s;
    }
    
    .btn-shop:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 66, 0, 0.15);
    }
    
    /* Thêm responsive padding cho container */
    @media (max-width: 768px) {
        .orders-container {
            padding: 40px 15px;
        }
        
        .order-card {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container orders-container">
    <!-- Header -->
    <div class="orders-header animate__animated animate__fadeInDown">
        <h1><i class="bi bi-box-seam me-2"></i>Đơn hàng của tôi</h1>
    </div>
    
    @if(count($orders) > 0)
        @foreach($orders as $order)
            <div class="order-card animate__animated animate__fadeInUp">
                <div class="order-header">
                    <div class="order-number">
                        Đơn hàng #{{ $order->order_number }}
                    </div>
                    <div class="order-date">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="order-status status-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending')
                                <i class="bi bi-clock"></i> Chờ xử lý
                                @break
                            @case('processing')
                                <i class="bi bi-gear"></i> Đang xử lý
                                @break
                            @case('shipped')
                                <i class="bi bi-truck"></i> Đang giao hàng
                                @break
                            @case('delivered')
                                <i class="bi bi-check-circle"></i> Đã giao hàng
                                @break
                            @case('cancelled')
                                <i class="bi bi-x-circle"></i> Đã hủy
                                @break
                            @case('refunded')
                                <i class="bi bi-arrow-counterclockwise"></i> Đã hoàn tiền
                                @break
                            @default
                                {{ $order->status }}
                        @endswitch
                    </div>
                </div>
                
                <div class="order-info">
                    <div class="info-item">
                        <div class="info-label">Phương thức thanh toán</div>
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
                    
                    <div class="info-item">
                        <div class="info-label">Trạng thái thanh toán</div>
                        <div class="info-value">
                            @switch($order->payment_status)
                                @case('pending')
                                    <span class="text-warning"><i class="bi bi-clock"></i> Chờ thanh toán</span>
                                    @break
                                @case('paid')
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Đã thanh toán</span>
                                    @break
                                @case('failed')
                                    <span class="text-danger"><i class="bi bi-x-circle"></i> Thanh toán thất bại</span>
                                    @break
                                @case('refunded')
                                    <span class="text-secondary"><i class="bi bi-arrow-counterclockwise"></i> Đã hoàn tiền</span>
                                    @break
                                @default
                                    {{ $order->payment_status }}
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Số lượng sản phẩm</div>
                        <div class="info-value">{{ $order->items->sum('quantity') }} sản phẩm</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Tổng tiền</div>
                        <div class="info-value order-total">{{ number_format($order->total_amount) }}₫</div>
                    </div>
                </div>
                
                <div class="order-actions">
                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-view">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                    
                    @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-cancel" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                <i class="bi bi-x-circle"></i> Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-orders animate__animated animate__fadeIn">
            <i class="bi bi-box"></i>
            <h3>Bạn chưa có đơn hàng nào</h3>
            <p>Hãy tiếp tục mua sắm để tạo đơn hàng mới.</p>
            <a href="{{ route('products.index') }}" class="btn btn-shop">
                <i class="bi bi-bag"></i> Mua sắm ngay
            </a>
        </div>
    @endif
</div>
@endsection 