@extends('layouts.shop')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .order-container {
        padding: 60px 30px;
        background-color: #f8f9fa;
        min-height: 80vh;
    }
    
    .order-header {
        margin-bottom: 2.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    .order-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 15px;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff4200, #ff7848);
        border-radius: 2px;
    }
    
    .order-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        color: #222;
        letter-spacing: -0.5px;
    }
    
    .order-details {
        background-color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 35px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }
    
    .section-title {
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .section-title i {
        color: #ff4200;
        font-size: 1.3rem;
    }
    
    .order-status-badge {
        display: inline-block;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        margin-left: 18px;
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
    
    .order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        margin-bottom: 40px;
        padding: 0 15px;
    }
    
    .meta-item {
        flex: 1;
        min-width: 250px;
        padding: 12px;
        background-color: #fafafa;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    
    .meta-label {
        font-weight: 600;
        color: #555;
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    
    .meta-value {
        color: #333;
        font-size: 1.05rem;
    }
    
    .product-item {
        display: flex;
        align-items: center;
        padding: 28px 15px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 5px;
    }
    
    .product-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .product-image {
        width: 110px;
        height: 110px;
        border-radius: 12px;
        overflow: hidden;
        margin-right: 30px;
        flex-shrink: 0;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-details {
        flex-grow: 1;
        padding-right: 20px;
    }
    
    .product-name {
        font-weight: 600;
        font-size: 1.15rem;
        color: #333;
        margin-bottom: 10px;
    }
    
    .product-attrs {
        font-size: 0.95rem;
        color: #777;
        margin-bottom: 15px;
    }
    
    .product-attrs span {
        margin-right: 22px;
        background-color: #f8f9fa;
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    .product-price {
        font-weight: 600;
        color: #ff4200;
        font-size: 1.05rem;
    }
    
    .product-quantity {
        font-weight: 600;
        color: #333;
        margin: 0 28px;
    }
    
    .product-total {
        font-weight: 700;
        color: #ff4200;
        font-size: 1.15rem;
        text-align: right;
        width: 140px;
        flex-shrink: 0;
        padding-right: 15px;
    }
    
    .order-summary {
        background-color: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        position: sticky;
        top: 20px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        color: #555;
        font-size: 1.05rem;
        padding: 5px 10px;
    }
    
    .summary-row.total {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px dashed #f0f0f0;
        font-size: 1.25rem;
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
        font-size: 1.6rem;
    }
    
    .shipment-timeline {
        margin-top: 40px;
        padding: 0 15px;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 40px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #ff4200;
        z-index: 1;
    }
    
    .timeline-item::after {
        content: '';
        position: absolute;
        left: 8px;
        top: 23px;
        width: 2px;
        height: calc(100% - 23px);
        background-color: #ddd;
    }
    
    .timeline-item:last-child::after {
        display: none;
    }
    
    .timeline-date {
        color: #777;
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    
    .timeline-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }
    
    .timeline-desc {
        color: #555;
        font-size: 1rem;
    }
    
    .action-buttons {
        margin-top: 40px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 0 15px;
    }
    
    .btn-primary-outline {
        background-color: transparent;
        color: #0d6efd;
        border: 1px solid #0d6efd;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .btn-primary-outline:hover {
        background-color: #e7f1ff;
        transform: translateY(-2px);
    }
    
    .btn-danger-outline {
        background-color: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .btn-danger-outline:hover {
        background-color: #feebee;
        transform: translateY(-2px);
    }
    
    .alert {
        margin-bottom: 25px;
        border-radius: 12px;
        padding: 16px 24px;
    }
    
    /* Thêm responsive padding cho container */
    @media (max-width: 768px) {
        .order-container {
            padding: 40px 15px;
        }
        
        .order-details {
            padding: 25px;
        }
        
        .order-summary {
            padding: 25px;
            margin-top: 30px;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
        
        .product-total {
            width: 100px;
        }
    }
</style>
@endsection

@section('content')
<div class="container order-container">
    <!-- Thông báo -->
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
    
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(in_array($order->status, ['delivered', 'completed']))
    <div class="alert alert-primary alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
        <i class="bi bi-star-fill me-2"></i>
        <strong>Đơn hàng của bạn đã giao thành công!</strong> Hãy đánh giá sản phẩm để giúp những người mua khác và nhận 100 điểm thưởng cho mỗi đánh giá.
        <a href="{{ route('orders.review', $order->order_number) }}" class="alert-link">Đánh giá ngay</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Header -->
    <div class="order-header animate__animated animate__fadeInDown">
        <div class="d-flex align-items-center justify-content-between">
            <h1>
                <i class="bi bi-box me-2"></i>Đơn hàng #{{ $order->order_number }}
                <span class="order-status-badge status-{{ $order->status }}">
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
                </span>
            </h1>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Trở về danh sách đơn hàng
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Chi tiết đơn hàng -->
            <div class="order-details animate__animated animate__fadeInUp">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>Thông tin đơn hàng
                </h3>
                
                <div class="order-meta">
                    <div class="meta-item">
                        <div class="meta-label">Ngày đặt hàng</div>
                        <div class="meta-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-label">Phương thức thanh toán</div>
                        <div class="meta-value">
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
                    
                    <div class="meta-item">
                        <div class="meta-label">Trạng thái thanh toán</div>
                        <div class="meta-value">
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
                </div>
                
                <!-- Địa chỉ giao hàng -->
                <h3 class="section-title">
                    <i class="bi bi-geo-alt"></i>Địa chỉ giao hàng
                </h3>
                
                <div class="order-meta">
                    <div class="meta-item" style="flex-basis: 100%;">
                        <div class="meta-value">
                            <p style="margin-bottom: 0;">
                                <strong>Địa chỉ:</strong> {{ $order->shipping_address }}<br>
                                <strong>Thành phố:</strong> {{ $order->shipping_city }}<br>
                                <strong>Quốc gia:</strong> {{ $order->shipping_country }}<br>
                                <strong>Mã bưu điện:</strong> {{ $order->shipping_postal_code }}<br>
                                <strong>Số điện thoại:</strong> {{ $order->shipping_phone }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Sản phẩm -->
                <h3 class="section-title">
                    <i class="bi bi-box2"></i>Sản phẩm đã đặt
                </h3>
                
                <div class="products-list">
                    @foreach($order->items as $item)
                    <div class="product-item">
                        <div class="product-image">
                            @if($item->product && $item->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product_name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $item->product_name }}">
                            @endif
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $item->product_name }}</div>
                            <div class="product-attrs">
                                @if($item->size)
                                    <span>Size: {{ $item->size }}</span>
                                @endif
                                @if($item->color)
                                    <span>Màu: {{ $item->color }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="product-price">{{ number_format($item->unit_price) }}₫</div>
                                <div class="product-quantity">× {{ $item->quantity }}</div>
                            </div>
                        </div>
                        <div class="product-total">{{ number_format($item->subtotal) }}₫</div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Tracking thông tin vận chuyển -->
                @if($order->shipment)
                <h3 class="section-title mt-4">
                    <i class="bi bi-truck"></i>Thông tin vận chuyển
                </h3>
                
                <div class="shipment-info">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <div class="meta-label">Phương thức vận chuyển</div>
                            <div class="meta-value">{{ $order->shipment->shipping_method }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="meta-label">Trạng thái</div>
                            <div class="meta-value">
                                @switch($order->shipment->status)
                                    @case('pending')
                                        <span class="text-warning"><i class="bi bi-clock"></i> Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="text-primary"><i class="bi bi-gear"></i> Đang xử lý</span>
                                        @break
                                    @case('shipped')
                                        <span class="text-info"><i class="bi bi-truck"></i> Đang vận chuyển</span>
                                        @break
                                    @case('delivered')
                                        <span class="text-success"><i class="bi bi-check-circle"></i> Đã giao hàng</span>
                                        @break
                                    @case('failed')
                                        <span class="text-danger"><i class="bi bi-x-circle"></i> Thất bại</span>
                                        @break
                                    @default
                                        {{ $order->shipment->status }}
                                @endswitch
                            </div>
                        </div>
                    </div>
                    
                    @if($order->shipment->tracking_number)
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <div class="meta-label">Mã vận đơn</div>
                            <div class="meta-value">{{ $order->shipment->tracking_number }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="meta-label">Đơn vị vận chuyển</div>
                            <div class="meta-value">{{ $order->shipment->carrier ?? 'Chưa xác định' }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="shipment-timeline">
                        @if($order->shipment->shipped_at)
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $order->shipment->shipped_at->format('d/m/Y H:i') }}</div>
                            <div class="timeline-title">Đơn hàng đã được gửi đi</div>
                            <div class="timeline-desc">Đơn hàng của bạn đã được giao cho đơn vị vận chuyển.</div>
                        </div>
                        @endif
                        
                        @if($order->shipment->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $order->shipment->delivered_at->format('d/m/Y H:i') }}</div>
                            <div class="timeline-title">Đơn hàng đã được giao</div>
                            <div class="timeline-desc">Đơn hàng của bạn đã được giao thành công.</div>
                        </div>
                        @endif
                        
                        @if(!$order->shipment->shipped_at && !$order->shipment->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            <div class="timeline-title">Đơn hàng đã được tạo</div>
                            <div class="timeline-desc">Đơn hàng của bạn đã được đặt thành công và đang chờ xử lý.</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Nút hành động -->
                <div class="action-buttons">
                    <a href="{{ route('orders.track', $order->order_number) }}" class="btn btn-primary-outline">
                        <i class="bi bi-geo-alt me-2"></i> Theo dõi đơn hàng
                    </a>
                    
                    @if($order->invoice)
                    <a href="{{ route('invoices.download', $order->invoice->invoice_number) }}" class="btn btn-primary-outline">
                        <i class="bi bi-file-earmark-text me-2"></i> Tải hóa đơn
                    </a>
                    @endif
                    
                    @if(in_array($order->status, ['delivered', 'completed']))
                    <a href="{{ route('orders.review', $order->order_number) }}" class="btn btn-primary-outline">
                        <i class="bi bi-star me-2"></i> Đánh giá sản phẩm
                    </a>
                    @endif
                    
                    @if($order->status == 'pending' || $order->status == 'processing')
                    <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger-outline" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                            <i class="bi bi-x-circle me-2"></i> Hủy đơn hàng
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Tóm tắt đơn hàng -->
            <div class="order-summary animate__animated animate__fadeInRight">
                <h3 class="section-title">
                    <i class="bi bi-receipt"></i>Tóm tắt đơn hàng
                </h3>
                
                <div class="summary-row">
                    <span class="summary-label">Tạm tính ({{ $order->items->sum('quantity') }} sản phẩm)</span>
                    <span class="summary-value">{{ number_format($order->total_amount + $order->discount_amount - $order->tax_amount - $order->shipping_amount) }}₫</span>
                </div>
                
                @if($order->discount_amount > 0)
                <div class="summary-row">
                    <span class="summary-label">Giảm giá</span>
                    <span class="summary-value text-success">-{{ number_format($order->discount_amount) }}₫</span>
                </div>
                @endif
                
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-value">{{ number_format($order->shipping_amount) }}₫</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Thuế (10%)</span>
                    <span class="summary-value">{{ number_format($order->tax_amount) }}₫</span>
                </div>
                
                <div class="summary-row total">
                    <span class="summary-label">Tổng cộng</span>
                    <span class="summary-value total-value">{{ number_format($order->total_amount) }}₫</span>
                </div>
                
                @if($order->invoice)
                <a href="{{ route('invoices.download', $order->invoice->invoice_number) }}" target="_blank" class="btn btn-primary w-100 mt-4">
                    <i class="bi bi-download"></i> Tải hóa đơn PDF
                </a>
                @endif
                
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 