@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý sản phẩm</h1>
        <div>
            <a href="{{ route('admin.products.sync-attributes') }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-sync-alt"></i> Đồng bộ thuộc tính
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Tìm kiếm sản phẩm</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Tên sản phẩm / SKU</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Danh mục</label>
                    <select class="form-select" id="category" name="category_id">
                        <option value="">Tất cả danh mục</option>
                        <!-- Thêm các option danh mục từ database -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                    </select>
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
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->productImages && $product->productImages->isNotEmpty())
                                        @php
                                            $imagePath = null;
                                            $primaryImage = $product->productImages->where('is_primary', true)->first();
                                            if ($primaryImage) {
                                                $imagePath = $primaryImage->image_path;
                                            } else {
                                                $imagePath = $product->productImages->first()->image_path;
                                            }
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" 
                                            class="me-3 rounded" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="me-3 bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name ?? 'Không có danh mục' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? 'Không có danh mục' }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                            <td>
                                @if($product->status == 'active')
                                <span class="badge bg-success">Hoạt động</span>
                                @elseif($product->status == 'inactive')
                                <span class="badge bg-warning">Không hoạt động</span>
                                @elseif($product->status == 'out_of_stock')
                                <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-box display-4 text-muted mb-3 d-block"></i>
                                <p class="h5 text-muted">Không có sản phẩm nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($products) && $products->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 