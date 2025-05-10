@extends('layouts.admin')

@section('title', 'Chi tiết vận chuyển #' . $shipment->tracking_number)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết vận chuyển</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shipments.index') }}">Quản lý vận chuyển</a></li>
        <li class="breadcrumb-item active">Chi tiết vận chuyển</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-8">
            <!-- Thông tin vận chuyển -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-truck me-1"></i>
                        Thông tin vận chuyển
                    </div>
                    <span class="badge {{ $shipment->status == 'delivered' ? 'bg-success' : ($shipment->status == 'shipped' ? 'bg-info' : ($shipment->status == 'processing' ? 'bg-primary' : ($shipment->status == 'failed' ? 'bg-danger' : 'bg-warning'))) }}">
                        @switch($shipment->status)
                            @case('pending')
                                Chờ xử lý
                                @break
                            @case('processing')
                                Đang xử lý
                                @break
                            @case('shipped')
                                Đã gửi hàng
                                @break
                            @case('delivered')
                                Đã giao hàng
                                @break
                            @case('failed')
                                Thất bại
                                @break
                            @default
                                {{ ucfirst($shipment->status) }}
                        @endswitch
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Thông tin cơ bản</h5>
                            <p><strong>Mã vận đơn:</strong> {{ $shipment->tracking_number ?? 'Chưa có' }}</p>
                            <p><strong>Đơn vị vận chuyển:</strong> {{ $shipment->carrier ?? 'Chưa xác định' }}</p>
                            <p><strong>Phương thức vận chuyển:</strong> {{ $shipment->shipping_method }}</p>
                            <p><strong>Chi phí vận chuyển:</strong> {{ number_format($shipment->shipping_cost) }}₫</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Thời gian</h5>
                            <p><strong>Ngày tạo:</strong> {{ $shipment->created_at->format('d/m/Y H:i') }}</p>
                            @if($shipment->shipped_at)
                                <p><strong>Ngày gửi hàng:</strong> {{ $shipment->shipped_at->format('d/m/Y H:i') }}</p>
                            @endif
                            @if($shipment->delivered_at)
                                <p><strong>Ngày giao hàng:</strong> {{ $shipment->delivered_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Thông tin đơn hàng</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <a href="{{ route('admin.orders.show', $shipment->order_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> Xem đơn hàng
                                    </a>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Mã đơn hàng:</strong> #{{ $shipment->order->order_number }}</p>
                                    <p class="mb-0"><strong>Khách hàng:</strong> {{ $shipment->order->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Địa chỉ giao hàng</h5>
                            <p>{{ $shipment->order->shipping_address }}, {{ $shipment->order->shipping_city }}, {{ $shipment->order->shipping_postal_code }}, {{ $shipment->order->shipping_country }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $shipment->order->shipping_phone }}</p>
                        </div>
                    </div>
                    
                    @if($shipment->notes)
                        <div class="row mb-3">
                            <div class="col-12">
                                <h5>Ghi chú</h5>
                                <p>{{ $shipment->notes }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($shipment->tracking_url)
                        <div class="row">
                            <div class="col-12">
                                <h5>Liên kết theo dõi</h5>
                                <a href="{{ $shipment->tracking_url }}" target="_blank" class="btn btn-outline-info">
                                    <i class="fas fa-external-link-alt me-1"></i> Theo dõi đơn hàng
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Lịch sử vận chuyển -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Lịch sử vận chuyển
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">
                                    {{ $shipment->created_at->format('d/m/Y') }}
                                </div>
                                <div class="timeline-item-marker-indicator bg-primary"></div>
                            </div>
                            <div class="timeline-item-content">
                                <strong>{{ $shipment->created_at->format('H:i') }}</strong> - Đơn vận chuyển đã được tạo
                            </div>
                        </div>
                        
                        @if($shipment->shipped_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">
                                        {{ $shipment->shipped_at->format('d/m/Y') }}
                                    </div>
                                    <div class="timeline-item-marker-indicator bg-info"></div>
                                </div>
                                <div class="timeline-item-content">
                                    <strong>{{ $shipment->shipped_at->format('H:i') }}</strong> - Đơn hàng đã được gửi đi
                                </div>
                            </div>
                        @endif
                        
                        @if($shipment->delivered_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">
                                        {{ $shipment->delivered_at->format('d/m/Y') }}
                                    </div>
                                    <div class="timeline-item-marker-indicator bg-success"></div>
                                </div>
                                <div class="timeline-item-content">
                                    <strong>{{ $shipment->delivered_at->format('H:i') }}</strong> - Đơn hàng đã được giao thành công
                                </div>
                            </div>
                        @endif
                        
                        @if($shipment->tracking_history && count($shipment->tracking_history) > 0)
                            @foreach($shipment->tracking_history as $history)
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text">
                                            {{ \Carbon\Carbon::parse($history['date'])->format('d/m/Y') }}
                                        </div>
                                        <div class="timeline-item-marker-indicator bg-secondary"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        <strong>{{ \Carbon\Carbon::parse($history['date'])->format('H:i') }}</strong> - {{ $history['description'] }}
                                        @if(isset($history['location']))
                                            <br><small>Vị trí: {{ $history['location'] }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Sản phẩm vận chuyển -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-box me-1"></i>
                    Sản phẩm vận chuyển
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>SKU</th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shipment->order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                @endif
                                                <span>{{ $item->product_name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->size ?? 'N/A' }}</td>
                                        <td>{{ $item->color ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Thông tin người vận chuyển -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person-badge me-1"></i>
                    Người vận chuyển
                </div>
                <div class="card-body">
                    @if($shipment->shipper)
                        <div class="text-center mb-3">
                            @if($shipment->shipper->avatar)
                                <img src="{{ asset('storage/' . $shipment->shipper->avatar) }}" alt="{{ $shipment->shipper->name }}" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 40px;">
                                    {{ substr($shipment->shipper->name, 0, 1) }}
                                </div>
                            @endif
                            <h5 class="mt-3">{{ $shipment->shipper->name }}</h5>
                        </div>
                        
                        <div class="mb-3">
                            <p><i class="bi bi-telephone me-2"></i> {{ $shipment->shipper->phone }}</p>
                            <p><i class="bi bi-envelope me-2"></i> {{ $shipment->shipper->email }}</p>
                            @if($shipment->shipper->id_card)
                                <p><i class="bi bi-card-text me-2"></i> {{ $shipment->shipper->id_card }}</p>
                            @endif
                            @if($shipment->shipper->company)
                                <p><i class="bi bi-building me-2"></i> {{ $shipment->shipper->company }}</p>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.shippers.show', $shipment->shipper->id) }}" class="btn btn-info btn-sm me-2">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changeShipperModal">
                                <i class="bi bi-arrow-repeat"></i> Đổi người vận chuyển
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person-x display-4 text-muted mb-3"></i>
                            <p class="text-muted">Chưa có người vận chuyển được phân công</p>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#assignShipperModal">
                                <i class="bi bi-person-plus"></i> Phân công ngay
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Cập nhật thông tin vận chuyển -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Cập nhật vận chuyển
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipments.update', $shipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Thêm trường ẩn để lưu giữ shipper_id -->
                        <input type="hidden" name="shipper_id" value="{{ $shipment->shipper_id }}">
                        
                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Mã vận đơn</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="carrier" class="form-label">Đơn vị vận chuyển</label>
                            <input type="text" class="form-control" id="carrier" name="carrier" value="{{ old('carrier', $shipment->carrier) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái vận chuyển</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" {{ $shipment->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $shipment->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $shipment->status == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                                <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="failed" {{ $shipment->status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_method" class="form-label">Phương thức vận chuyển</label>
                            <select class="form-select" id="shipping_method" name="shipping_method">
                                <option value="standard" {{ $shipment->shipping_method == 'standard' ? 'selected' : '' }}>Tiêu chuẩn</option>
                                <option value="express" {{ $shipment->shipping_method == 'express' ? 'selected' : '' }}>Nhanh</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tracking_url" class="form-label">Liên kết theo dõi</label>
                            <input type="url" class="form-control" id="tracking_url" name="tracking_url" value="{{ old('tracking_url', $shipment->tracking_url) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipped_at" class="form-label">Ngày gửi hàng</label>
                            <input type="datetime-local" class="form-control" id="shipped_at" name="shipped_at" value="{{ old('shipped_at', $shipment->shipped_at ? $shipment->shipped_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivered_at" class="form-label">Ngày giao hàng</label>
                            <input type="datetime-local" class="form-control" id="delivered_at" name="delivered_at" value="{{ old('delivered_at', $shipment->delivered_at ? $shipment->delivered_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú vận chuyển</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $shipment->notes) }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
            
            <!-- Thêm mốc theo dõi mới -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-1"></i>
                    Thêm mốc theo dõi mới
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipments.update', $shipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="add_tracking_history">
                        
                        <div class="mb-3">
                            <label for="history_date" class="form-label">Ngày cập nhật</label>
                            <input type="datetime-local" class="form-control" id="history_date" name="history_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="history_description" class="form-label">Mô tả</label>
                            <input type="text" class="form-control" id="history_description" name="history_description" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="history_location" class="form-label">Vị trí</label>
                            <input type="text" class="form-control" id="history_location" name="history_location">
                        </div>
                        
                        <button type="submit" class="btn btn-success">Thêm mốc theo dõi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal phân công shipper -->
<div class="modal fade" id="assignShipperModal" tabindex="-1" aria-labelledby="assignShipperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignShipperModalLabel">Phân công người vận chuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.shipments.assign-shipper', $shipment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="shipper_id" class="form-label">Chọn người vận chuyển</label>
                        <select class="form-select" id="shipper_id" name="shipper_id" required>
                            <option value="">-- Chọn người vận chuyển --</option>
                            @foreach($availableShippers as $shipper)
                                <option value="{{ $shipper->id }}">
                                    {{ $shipper->name }} - {{ $shipper->phone }} 
                                    @if($shipper->company) ({{ $shipper->company }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Phân công</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal thay đổi shipper -->
<div class="modal fade" id="changeShipperModal" tabindex="-1" aria-labelledby="changeShipperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeShipperModalLabel">Thay đổi người vận chuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.shipments.assign-shipper', $shipment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Bạn đang thay đổi người vận chuyển từ <strong>{{ $shipment->shipper->name ?? 'Chưa có' }}</strong> sang người khác.
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_shipper_id" class="form-label">Chọn người vận chuyển mới</label>
                        <select class="form-select" id="new_shipper_id" name="shipper_id" required>
                            <option value="">-- Chọn người vận chuyển --</option>
                            @foreach($availableShippers as $shipper)
                                <option value="{{ $shipper->id }}" {{ $shipment->shipper_id == $shipper->id ? 'selected' : '' }}>
                                    {{ $shipper->name }} - {{ $shipper->phone }} 
                                    @if($shipper->company) ({{ $shipper->company }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Xác nhận thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 25px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item-marker {
        position: absolute;
        left: -25px;
        top: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .timeline-item-marker-text {
        font-size: 0.7rem;
        color: #a2acba;
        margin-bottom: 0.25rem;
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
        left: -19px;
        top: 6px;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-item-content {
        padding: 0;
        margin-bottom: 0.5rem;
        margin-left: 15px;
        flex: 1;
        padding-top: 15px;
    }
</style>
@endsection 