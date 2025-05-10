@extends('layouts.admin')

@section('title', 'Sản phẩm sắp hết hàng')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sản phẩm sắp hết hàng</h1>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>SKU</th>
                            <th>Trạng thái</th>
                            <th>Giá bán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->images && $product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}" 
                                        class="me-3" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <div class="me-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name ?? 'Không có danh mục' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>
                                @if($product->status == 'active')
                                <span class="badge bg-success">Hoạt động</span>
                                @elseif($product->status == 'inactive')
                                <span class="badge bg-warning">Không hoạt động</span>
                                @else
                                <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <a href="{{ route('admin.inventory.show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-box-seam display-4 text-muted mb-3"></i>
                <p class="h5 text-muted">Không có sản phẩm nào sắp hết hàng</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 