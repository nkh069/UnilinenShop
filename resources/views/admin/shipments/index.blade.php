@extends('layouts.admin')

@section('title', 'Quản lý vận chuyển')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý vận chuyển</h1>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Lọc đơn vận chuyển</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.shipments.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Mã vận chuyển, tên khách hàng...">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã vận chuyển</th>
                            <th>Đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Địa chỉ</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments ?? [] as $shipment)
                        <tr>
                            <td>
                                <strong>{{ $shipment->tracking_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $shipment->order_id) }}">
                                    {{ $shipment->order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    <span>{{ $shipment->order->user->name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $shipment->order->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <small>
                                    {{ $shipment->address }}, {{ $shipment->city }}, {{ $shipment->province }}
                                </small>
                            </td>
                            <td>{{ $shipment->shipping_method }}</td>
                            <td>
                                @if($shipment->status == 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($shipment->status == 'processing')
                                <span class="badge bg-info">Đang chuẩn bị</span>
                                @elseif($shipment->status == 'shipped')
                                <span class="badge bg-primary">Đang vận chuyển</span>
                                @elseif($shipment->status == 'delivered')
                                <span class="badge bg-success">Đã giao hàng</span>
                                @elseif($shipment->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                                @else
                                <span class="badge bg-secondary">{{ $shipment->status }}</span>
                                @endif
                            </td>
                            <td>{{ $shipment->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $shipment->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                                
                                <!-- Update Status Modal -->
                                <div class="modal fade" id="updateStatusModal{{ $shipment->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $shipment->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateStatusModalLabel{{ $shipment->id }}">Cập nhật trạng thái vận chuyển</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.shipments.update', $shipment->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Trạng thái</label>
                                                        <select class="form-select" id="status" name="status" required>
                                                            <option value="pending" {{ $shipment->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                            <option value="processing" {{ $shipment->status == 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                                            <option value="shipped" {{ $shipment->status == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                                                            <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                                            <option value="cancelled" {{ $shipment->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Ghi chú</label>
                                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-truck display-4 text-muted mb-3 d-block"></i>
                                <p class="h5 text-muted">Không có đơn vận chuyển nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($shipments) && $shipments->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $shipments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 