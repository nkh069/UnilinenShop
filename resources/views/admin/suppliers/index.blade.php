@extends('layouts.admin')

@section('title', 'Quản lý nhà cung cấp')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý nhà cung cấp</h1>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp mới
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách nhà cung cấp</h6>
            
            <div class="d-flex">
                <!-- Form tìm kiếm -->
                <form action="{{ route('admin.suppliers.index') }}" method="GET" class="me-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Dropdown lọc trạng thái -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ request('status') == 'active' ? 'Đang hoạt động' : (request('status') == 'inactive' ? 'Không hoạt động' : 'Tất cả trạng thái') }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.suppliers.index', array_merge(request()->except(['status', 'page']), ['status' => ''])) }}">Tất cả trạng thái</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.suppliers.index', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}">Đang hoạt động</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.suppliers.index', array_merge(request()->except(['status', 'page']), ['status' => 'inactive'])) }}">Không hoạt động</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($suppliers->isEmpty())
                <div class="text-center py-5">
                    <h4>Không tìm thấy nhà cung cấp nào</h4>
                    <p class="text-muted">{{ request('search') ? 'Thử tìm kiếm với từ khóa khác hoặc' : 'Hãy' }} thêm nhà cung cấp mới.</p>
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp mới
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>
                                    <a href="{{ route('admin.suppliers.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark d-flex align-items-center">
                                        Tên nhà cung cấp
                                        @if(request('sort') == 'name')
                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('admin.suppliers.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'code', 'direction' => request('sort') == 'code' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark d-flex align-items-center">
                                        Mã
                                        @if(request('sort') == 'code')
                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Liên hệ</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="fw-bold text-decoration-none">
                                            {{ $supplier->name }}
                                        </a>
                                    </td>
                                    <td>{{ $supplier->code ?? 'N/A' }}</td>
                                    <td>
                                        {{ $supplier->contact_person ? $supplier->contact_person . ' - ' : '' }}
                                        {{ $supplier->phone ?? 'N/A' }}<br>
                                        <small>{{ $supplier->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        {{ $supplier->city }}, {{ $supplier->country }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $supplier->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $supplier->status == 'active' ? 'Đang hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-primary me-1" title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 