@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Quản lý đơn hàng</a></li>
        <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-1"></i>
                        Thông tin đơn hàng
                    </div>
                    <span class="badge {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'processing' ? 'bg-primary' : ($order->status == 'shipped' ? 'bg-info' : ($order->status == 'delivered' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-secondary')))) }} text-white">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Thông tin khách hàng</h5>
                            <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p><strong>Điện thoại:</strong> {{ $order->shipping_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin thanh toán</h5>
                            <p><strong>Phương thức:</strong> 
                                @switch($order->payment_method)
                                    @case('cod')
                                        Thanh toán khi nhận hàng
                                        @break
                                    @case('credit_card')
                                        Thẻ tín dụng
                                        @break
                                    @case('momo')
                                        Ví MoMo
                                        @break
                                    @case('bank_transfer')
                                        Chuyển khoản ngân hàng
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : ($order->payment_status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                            @if($order->payment_id)
                                <p><strong>Mã giao dịch:</strong> {{ $order->payment_id }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Địa chỉ giao hàng</h5>
                            <p>{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h5>Sản phẩm</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Size</th>
                                            <th>Màu</th>
                                            <th>Số lượng</th>
                                            <th class="text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->images->count() > 0)
                                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                        @endif
                                                        <div>
                                                            {{ $item->product_name }}
                                                            @if($item->product)
                                                                <br>
                                                                <small class="text-muted">SKU: {{ $item->sku }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($item->unit_price) }}₫</td>
                                                <td>{{ $item->size ?? 'N/A' }}</td>
                                                <td>{{ $item->color ?? 'N/A' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->subtotal) }}₫</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Tạm tính:</strong></td>
                                            <td class="text-end">{{ number_format($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount) }}₫</td>
                                        </tr>
                                        @if($order->discount_amount > 0)
                                            <tr>
                                                <td colspan="5" class="text-end"><strong>Giảm giá:</strong></td>
                                                <td class="text-end">-{{ number_format($order->discount_amount) }}₫</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Thuế:</strong></td>
                                            <td class="text-end">{{ number_format($order->tax_amount) }}₫</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                            <td class="text-end">{{ number_format($order->shipping_amount) }}₫</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                                            <td class="text-end"><strong>{{ number_format($order->total_amount) }}₫</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Ghi chú</h5>
                                <p>{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Chi tiết vận chuyển -->
            @if($order->shipment)
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-truck me-1"></i>
                        Thông tin vận chuyển
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Phương thức:</strong> {{ $order->shipment->shipping_method }}</p>
                                <p><strong>Trạng thái:</strong> 
                                    <span class="badge {{ $order->shipment->status == 'delivered' ? 'bg-success' : ($order->shipment->status == 'shipped' ? 'bg-info' : ($order->shipment->status == 'processing' ? 'bg-primary' : 'bg-warning')) }}">
                                        {{ ucfirst($order->shipment->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($order->shipment->tracking_number)
                                    <p><strong>Mã vận đơn:</strong> {{ $order->shipment->tracking_number }}</p>
                                @endif
                                @if($order->shipment->carrier)
                                    <p><strong>Đơn vị vận chuyển:</strong> {{ $order->shipment->carrier }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="timeline mt-3">
                            @if($order->shipment->shipped_at)
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text">
                                            {{ $order->shipment->shipped_at->format('d/m/Y') }}
                                        </div>
                                        <div class="timeline-item-marker-indicator bg-primary"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        Đơn hàng đã được gửi đi
                                    </div>
                                </div>
                            @endif
                            
                            @if($order->shipment->delivered_at)
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text ">
                                            {{ $order->shipment->delivered_at->format('d/m/Y') }}
                                        </div>
                                        <div class="timeline-item-marker-indicator bg-success"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        Đơn hàng đã được giao thành công
                                    </div>
                                </div>
                            @endif
                            
                            @if(!$order->shipment->shipped_at && !$order->shipment->delivered_at)
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="timeline-item-marker-indicator bg-warning"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        Đơn hàng đã được tạo và đang chờ xử lý
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Lịch sử thanh toán -->
            @if($order->payments && $order->payments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-money-bill me-1"></i>
                        Lịch sử thanh toán
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Phương thức</th>
                                        <th>Mã giao dịch</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : $payment->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @switch($payment->payment_method)
                                                    @case('cod')
                                                        Thanh toán khi nhận hàng
                                                        @break
                                                    @case('credit_card')
                                                        Thẻ tín dụng
                                                        @break
                                                    @case('momo')
                                                        Ví MoMo
                                                        @break
                                                    @case('bank_transfer')
                                                        Chuyển khoản ngân hàng
                                                        @break
                                                    @default
                                                        {{ $payment->payment_method }}
                                                @endswitch
                                            </td>
                                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                            <td>{{ number_format($payment->amount) }}₫</td>
                                            <td>
                                                <span class="badge {{ $payment->status == 'success' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-xl-4">
            <!-- Cập nhật trạng thái -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Cập nhật trạng thái
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
            
            <!-- Cập nhật thông tin vận chuyển -->
            @if($order->shipment)
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-truck me-1"></i>
                        Cập nhật vận chuyển
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.shipments.update', $order->shipment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="tracking_number" class="form-label">Mã vận đơn</label>
                                <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->shipment->tracking_number) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="carrier" class="form-label">Đơn vị vận chuyển</label>
                                <input type="text" class="form-control" id="carrier" name="carrier" value="{{ old('carrier', $order->shipment->carrier) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="shipment_status" class="form-label">Trạng thái vận chuyển</label>
                                <select class="form-select" id="shipment_status" name="status">
                                    <option value="pending" {{ $order->shipment->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processing" {{ $order->shipment->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ $order->shipment->status == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                                    <option value="delivered" {{ $order->shipment->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                    <option value="failed" {{ $order->shipment->status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="shipping_method" class="form-label">Phương thức vận chuyển</label>
                                <select class="form-select" id="shipping_method" name="shipping_method">
                                    <option value="standard" {{ $order->shipment->shipping_method == 'standard' ? 'selected' : '' }}>Tiêu chuẩn</option>
                                    <option value="express" {{ $order->shipment->shipping_method == 'express' ? 'selected' : '' }}>Nhanh</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            @endif
            
            <!-- Hoạt động đơn hàng -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock me-1"></i>
                    Hoạt động đơn hàng
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </div>
                                <div class="timeline-item-marker-indicator bg-primary"></div>
                            </div>
                            <div class="timeline-item-content">
                                Đơn hàng đã được tạo
                            </div>
                        </div>
                        
                        @if($order->status != 'pending')
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">
                                        {{ $order->updated_at->format('d/m/Y') }}
                                    </div>
                                    <div class="timeline-item-marker-indicator bg-info"></div>
                                </div>
                                <div class="timeline-item-content">
                                    Đơn hàng đã được cập nhật sang trạng thái {{ $order->status }}
                                </div>
                            </div>
                        @endif
                        
                        @if($order->cancelled_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">
                                        {{ $order->cancelled_at->format('d/m/Y') }}
                                    </div>
                                    <div class="timeline-item-marker-indicator bg-danger"></div>
                                </div>
                                <div class="timeline-item-content">
                                    Đơn hàng đã bị hủy
                                </div>
                            </div>
                        @endif
                        
                        @if($order->delivered_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">
                                        {{ $order->delivered_at->format('d/m/Y') }}
                                    </div>
                                    <div class="timeline-item-marker-indicator bg-success"></div>
                                </div>
                                <div class="timeline-item-content">
                                    Đơn hàng đã được giao thành công
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 35px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1rem;
        display: flex;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item-marker {
        position: absolute;
        left: -35px;
        top: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .timeline-item-marker-text {
        font-size: 0.7rem;
        color: #a2acba;
        margin-bottom: 0.25rem;
        width: 60px;
        text-align: center;
    }
    
    .timeline-item-marker-indicator {
        height: 12px;
        width: 12px;
        border-radius: 100%;
    }
    
    .timeline-item::before {
        content: "";
        position: absolute;
        height: 100%;
        border-left: 1px solid #e0e5ec;
        left: -29px;
        top: 6px;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-item-content {
        padding: 0;
        margin-bottom: -0.5rem;
        margin-left: 15px;
        flex: 1;
        padding-top: 15px;
    }
</style>
@endsection 