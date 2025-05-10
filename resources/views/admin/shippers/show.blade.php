@extends('layouts.admin')

@section('title', 'Chi tiết người vận chuyển: ' . $shipper->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết người vận chuyển</h1>
        <div>
            <a href="{{ route('admin.shippers.edit', $shipper->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.shippers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <!-- Thông tin shipper -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">Thông tin cá nhân</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($shipper->avatar)
                            <img src="{{ asset('storage/' . $shipper->avatar) }}" alt="{{ $shipper->name }}" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 60px;">
                                {{ substr($shipper->name, 0, 1) }}
                            </div>
                        @endif
                        <h5 class="mt-3">{{ $shipper->name }}</h5>
                        
                        <div class="mt-2">
                            @if($shipper->status)
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-danger">Ngừng hoạt động</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Thông tin liên hệ</h6>
                        <p><i class="bi bi-telephone me-2"></i> {{ $shipper->phone }}</p>
                        <p><i class="bi bi-envelope me-2"></i> {{ $shipper->email }}</p>
                        @if($shipper->id_card)
                            <p><i class="bi bi-card-text me-2"></i> {{ $shipper->id_card }} <small class="text-muted">(CCCD)</small></p>
                        @endif
                        <p><i class="bi bi-building me-2"></i> {{ $shipper->company ?? 'Chưa có' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Địa chỉ</h6>
                        <p>{{ $shipper->address ?? 'Chưa có' }}</p>
                        <p>
                            {{ $shipper->city ?? '' }}
                            {{ $shipper->province ? ', '.$shipper->province : '' }}
                            {{ $shipper->postal_code ? ', '.$shipper->postal_code : '' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Thời gian</h6>
                        <p><strong>Ngày tạo:</strong> {{ $shipper->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Cập nhật lần cuối:</strong> {{ $shipper->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($shipper->notes)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Ghi chú</h6>
                        <p>{{ $shipper->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Đánh giá hiệu suất -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">Đánh giá hiệu suất</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4 font-weight-bold">{{ number_format($shipper->rating, 1) }}</div>
                        <div class="d-flex justify-content-center mt-2">
                            @php
                                $rating = $shipper->rating ?? 0;
                                $fullStars = floor($rating);
                                $halfStar = $rating - $fullStars >= 0.5;
                            @endphp
                            
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="bi bi-star-fill text-warning fs-4"></i>
                                @elseif($i == $fullStars + 1 && $halfStar)
                                    <i class="bi bi-star-half text-warning fs-4"></i>
                                @else
                                    <i class="bi bi-star text-warning fs-4"></i>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 text-center border-end">
                            <div class="h4">{{ $shipments->count() }}</div>
                            <div class="text-muted">Đơn đã xử lý</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="h4">
                                {{ $shipments->where('status', 'delivered')->count() }}
                            </div>
                            <div class="text-muted">Đơn thành công</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-lg-7">
            <!-- Danh sách đơn vận chuyển -->
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Đơn vận chuyển được gán</h6>
                </div>
                <div class="card-body">
                    @if($shipments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã vận đơn</th>
                                    <th>Đơn hàng</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày giao</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->tracking_number ?? 'Chưa có' }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $shipment->order_id) }}">
                                            {{ $shipment->order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $shipment->order->shipping_address }}, 
                                            {{ $shipment->order->shipping_city }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($shipment->status == 'pending')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($shipment->status == 'processing')
                                            <span class="badge bg-info">Đang chuẩn bị</span>
                                        @elseif($shipment->status == 'shipped')
                                            <span class="badge bg-primary">Đang vận chuyển</span>
                                        @elseif($shipment->status == 'delivered')
                                            <span class="badge bg-success">Đã giao hàng</span>
                                        @elseif($shipment->status == 'failed')
                                            <span class="badge bg-danger">Thất bại</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($shipment->delivered_at)
                                            {{ $shipment->delivered_at->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $shipments->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-truck display-4 text-muted mb-3 d-block"></i>
                        <p class="text-muted">Người vận chuyển này chưa được gán đơn vận chuyển nào</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 