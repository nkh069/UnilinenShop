@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết người dùng</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
        <li class="breadcrumb-item active">Chi tiết</li>
    </ol>

    <div class="row">
        <!-- Thông tin cá nhân -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Thông tin cá nhân
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto" style="width: 150px; height: 150px; font-size: 60px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'staff')
                                <span class="badge bg-warning text-dark">Nhân viên</span>
                            @else
                                <span class="badge bg-info text-dark">Khách hàng</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Email</h6>
                        <p>{{ $user->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Số điện thoại</h6>
                        <p>{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Địa chỉ</h6>
                        <p>{{ $user->address ?? 'Chưa cập nhật' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Ngày đăng ký</h6>
                        <p>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Trạng thái tài khoản</h6>
                        <p>
                            @if($user->status === 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Bị khóa</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa thông tin
                        </a>
                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash me-1"></i> Xóa người dùng
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Đơn hàng gần đây -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Đơn hàng gần đây
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info text-dark">Đang xử lý</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-primary">Đang giao</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Đã giao</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                                Xem tất cả đơn hàng <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Người dùng này chưa có đơn hàng nào.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Thống kê hoạt động -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Thống kê hoạt động
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Tổng đơn hàng</h6>
                                            <h2 class="mb-0">{{ $user->orders->count() }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Tổng chi tiêu</h6>
                                            <h2 class="mb-0">{{ number_format($user->orders->sum('total_amount'), 0, ',', '.') }}₫</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-warning text-dark mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Đánh giá sản phẩm</h6>
                                            <h2 class="mb-0">{{ $user->reviews->count() }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-star fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-info text-dark mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Ngày hoạt động</h6>
                                            <h2 class="mb-0">{{ $user->created_at->diffInDays(now()) + 1 }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-calendar-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa người dùng -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa người dùng <strong>{{ $user->name }}</strong>?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác và sẽ xóa tất cả dữ liệu liên quan đến người dùng này.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa người dùng</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 