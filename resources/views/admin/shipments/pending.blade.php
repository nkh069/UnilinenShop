@php
function getStatusBadgeClass($status) {
    switch($status) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'shipped':
            return 'primary';
        case 'delivered':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}

function getStatusStyle($status) {
    switch($status) {
        case 'pending':
            return 'background-color: #FFC107; color: #000; border: none;';
        case 'processing':
            return 'background-color: #17A2B8; color: #fff; border: none;';
        case 'shipped':
            return 'background-color: #0D6EFD; color: #fff; border: none;';
        case 'delivered':
            return 'background-color: #28A745; color: #fff; border: none;';
        case 'cancelled':
            return 'background-color: #DC3545; color: #fff; border: none;';
        default:
            return 'background-color: #6C757D; color: #fff; border: none;';
    }
}
@endphp

@extends('layouts.admin')

@section('title', 'Đơn Vận Chuyển Đang Chờ Xử Lý')

@section('styles')
<style>
    .badge-warning, .badge-info {
        color: #212529 !important;
    }
    .badge-primary, .badge-success, .badge-danger, .badge-secondary {
        color: #ffffff !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Đơn Vận Chuyển Đang Chờ Xử Lý</h3>
                        <div>
                            <a href="{{ route('admin.shipments.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list"></i> Tất cả đơn vận chuyển
                            </a>
                            <a href="{{ route('admin.shipments.unassigned') }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-user-slash"></i> Chưa phân công
        </a>
    </div>
                    </div>
    </div>
        <div class="card-body">
                    <form method="GET" action="{{ route('admin.shipments.pending') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">Tìm kiếm</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Tracking number, order number, tên khách hàng..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.shipments.pending') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync"></i> Làm mới
                                </a>
                            </div>
                        </div>
                    </form>

            @if($shipments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Tracking #</th>
                                    <th>Đơn hàng #</th>
                                    <th>Shipper</th>
                            <th>Khách hàng</th>
                                    <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                                    <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                                @foreach($shipments as $index => $shipment)
                        <tr>
                                    <td>{{ $index + $shipments->firstItem() }}</td>
                            <td>{{ $shipment->tracking_number }}</td>
                            <td>
                                        <a href="{{ route('admin.orders.show', $shipment->order->id) }}">
                                            {{ $shipment->order->order_number }}
                                </a>
                            </td>
                                    <td>
                                        @if($shipment->shipper)
                                            {{ $shipment->shipper->name }} <br>
                                            <small>{{ $shipment->shipper->phone }}</small>
                                @else
                                            <span class="badge" style="background-color: #FFC107; color: #000; border: none; font-weight: bold;">Chưa phân công</span>
                                @endif
                            </td>
                                    <td>
                                        {{ optional($shipment->order->user)->name }} <br>
                                        <small>{{ optional($shipment->order->user)->email }}</small>
                                    </td>
                                    <td>
                                        {{ $shipment->order->shipping_address }}, 
                                        {{ $shipment->order->shipping_city }}, 
                                        {{ $shipment->order->shipping_state }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ getStatusBadgeClass($shipment->status) }}" style="{{ getStatusStyle($shipment->status) }} font-weight: bold;">
                                            {{ ucfirst($shipment->status) }}
                                        </span>
                                    </td>
                            <td>{{ $shipment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                        <div class="btn-group">
                                            @if(!$shipment->shipper_id)
                                            <button type="button" class="btn btn-primary btn-sm assign-shipper-btn" 
                                                    data-toggle="modal" 
                                                    data-target="#assignShipperModal" 
                                                    data-shipment-id="{{ $shipment->id }}"
                                                    data-tracking-number="{{ $shipment->tracking_number }}">
                                                <i class="fas fa-user-plus"></i> Phân công
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-warning btn-sm change-shipper-btn" 
                                                    data-toggle="modal" 
                                                    data-target="#changeShipperModal" 
                                                    data-shipment-id="{{ $shipment->id }}"
                                                    data-tracking-number="{{ $shipment->tracking_number }}"
                                                    data-current-shipper="{{ $shipment->shipper->name }}">
                                                <i class="fas fa-exchange-alt"></i> Đổi shipper
                                            </button>
                                            @endif
                                            <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $shipments->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có đơn vận chuyển nào đang chờ xử lý.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
                                </div>
                                
<!-- Modal để phân công shipper -->
<div class="modal fade" id="assignShipperModal" tabindex="-1" role="dialog" aria-labelledby="assignShipperModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                <h5 class="modal-title" id="assignShipperModalLabel">Phân công shipper cho đơn vận chuyển</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                                            </div>
            <form id="assignShipperForm" method="POST" action="">
                                                @csrf
                                                <div class="modal-body">
                    <p>Bạn đang phân công shipper cho đơn vận chuyển: <strong id="trackingNumber"></strong></p>
                    
                    <div class="form-group">
                        <label for="shipper_id">Chọn shipper</label>
                        <select class="form-control" id="shipper_id" name="shipper_id" required>
                            <option value="">-- Chọn shipper --</option>
                            @foreach($availableShippers as $shipper)
                            <option value="{{ $shipper->id }}">{{ $shipper->name }} ({{ $shipper->phone }})</option>
                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận phân công</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

<!-- Modal để đổi shipper -->
<div class="modal fade" id="changeShipperModal" tabindex="-1" role="dialog" aria-labelledby="changeShipperModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeShipperModalLabel">Đổi shipper cho đơn vận chuyển</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changeShipperForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p>Bạn đang đổi shipper cho đơn vận chuyển: <strong id="changeTrackingNumber"></strong></p>
                    <p>Shipper hiện tại: <strong id="currentShipper"></strong></p>
                    
                    <div class="form-group">
                        <label for="new_shipper_id">Chọn shipper mới</label>
                        <select class="form-control" id="new_shipper_id" name="shipper_id" required>
                            <option value="">-- Chọn shipper --</option>
                            @foreach($availableShippers as $shipper)
                            <option value="{{ $shipper->id }}">{{ $shipper->name }} ({{ $shipper->phone }})</option>
                            @endforeach
                        </select>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Xác nhận thay đổi</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
$(function() {
    $('.assign-shipper-btn').on('click', function() {
        const shipmentId = $(this).data('shipment-id');
        const trackingNumber = $(this).data('tracking-number');
        
        $('#trackingNumber').text(trackingNumber);
        $('#assignShipperForm').attr('action', `/admin/shipments/${shipmentId}/assign-shipper`);
    });
    
    $('.change-shipper-btn').on('click', function() {
        const shipmentId = $(this).data('shipment-id');
        const trackingNumber = $(this).data('tracking-number');
        const currentShipper = $(this).data('current-shipper');
        
        $('#changeTrackingNumber').text(trackingNumber);
        $('#currentShipper').text(currentShipper);
        $('#changeShipperForm').attr('action', `/admin/shipments/${shipmentId}/change-shipper`);
    });
});
</script>
@endpush 