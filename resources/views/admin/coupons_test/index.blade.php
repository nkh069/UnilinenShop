@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá test')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý mã giảm giá test</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Mã giảm giá</a></li>
        <li class="breadcrumb-item active">Mã giảm giá test</li>
    </ol>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted">Đây là danh sách các mã giảm giá dùng để test. Các mã này sẽ không hoạt động khi khách hàng áp dụng.</p>
            <a href="{{ route('admin.coupons_test.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mã giảm giá test
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách mã giảm giá test
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mã</th>
                            <th>Mô tả</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Đơn hàng tối thiểu</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td><span class="badge bg-dark">{{ $coupon->code }}</span></td>
                            <td>{{ Str::limit($coupon->description, 50) }}</td>
                            <td>
                                @if($coupon->type == 'percentage')
                                <span class="badge bg-info">Phần trăm</span>
                                @else
                                <span class="badge bg-primary">Số tiền cố định</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->type == 'percentage')
                                {{ $coupon->value }}%
                                @else
                                {{ number_format($coupon->value) }}đ
                                @endif
                            </td>
                            <td>{{ $coupon->min_order_amount > 0 ? number_format($coupon->min_order_amount) . 'đ' : 'Không' }}</td>
                            <td>{{ $coupon->valid_until ? date('d/m/Y H:i', strtotime($coupon->valid_until)) : 'Không giới hạn' }}</td>
                            <td>
                                @if(!$coupon->is_active)
                                <span class="badge bg-secondary">Không hoạt động</span>
                                @elseif($coupon->valid_until && $coupon->valid_until < now())
                                <span class="badge bg-danger">Hết hạn</span>
                                @else
                                <span class="badge bg-success">Hoạt động</span>
                                @endif
                                <br>
                                <small class="text-danger">Sẽ không áp dụng được</small>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.coupons_test.edit', $coupon->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons_test.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá test này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Không có mã giảm giá test nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($coupons) && $coupons->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $coupons->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 