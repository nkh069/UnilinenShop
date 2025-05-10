<?php
// Thêm hiển thị căn cước công dân trong tooltip khi hover trên người dùng
?>
@extends('layouts.admin')

@section('title', 'Quản lý người vận chuyển')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý người vận chuyển</h1>
        <a href="{{ route('admin.shippers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Tìm kiếm</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.shippers.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tên, email, số điện thoại, CCCD...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.shippers.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                    </a>
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
                            <th>ID</th>
                            <th>Thông tin</th>
                            <th>Liên hệ</th>
                            <th>Công ty</th>
                            <th>Đánh giá</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippers ?? [] as $shipper)
                        <tr>
                            <td>{{ $shipper->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($shipper->avatar)
                                    <img src="{{ asset('storage/' . $shipper->avatar) }}" alt="{{ $shipper->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 40px; height: 40px;">
                                        {{ substr($shipper->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $shipper->name }}</strong>
                                        @if($shipper->id_card)
                                            <div><small class="text-muted">CCCD: {{ $shipper->id_card }}</small></div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-envelope me-1"></i> {{ $shipper->email }}
                                </div>
                                <div>
                                    <i class="bi bi-telephone me-1"></i> {{ $shipper->phone }}
                                </div>
                            </td>
                            <td>{{ $shipper->company ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $rating = $shipper->rating ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = $rating - $fullStars >= 0.5;
                                    @endphp
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @elseif($i == $fullStars + 1 && $halfStar)
                                            <i class="bi bi-star-half text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                    
                                    <span class="ms-1">({{ number_format($rating, 1) }})</span>
                                </div>
                            </td>
                            <td>
                                @if($shipper->status)
                                <span class="badge bg-success">Đang hoạt động</span>
                                @else
                                <span class="badge bg-danger">Ngừng hoạt động</span>
                                @endif
                            </td>
                            <td>{{ $shipper->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.shippers.show', $shipper->id) }}" class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.shippers.edit', $shipper->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.shippers.destroy', $shipper->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa shipper này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-people display-4 text-muted mb-3 d-block"></i>
                                <p class="h5 text-muted">Không có người vận chuyển nào</p>
                                <a href="{{ route('admin.shippers.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-plus-circle"></i> Thêm mới ngay
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($shippers) && $shippers->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $shippers->appends(request()->all())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 