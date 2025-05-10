@extends('layouts.admin')

@section('title', 'Quản lý tồn kho')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý tồn kho</h1>
        <div>
            <a href="{{ route('admin.inventory.add-stock-form') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Nhập hàng
            </a>
            <a href="{{ route('admin.inventory.adjust') }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil-square"></i> Điều chỉnh tồn kho
            </a>
            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-danger">
                <i class="bi bi-exclamation-triangle"></i> Sản phẩm sắp hết
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách tồn kho sản phẩm</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Tùy chọn:</div>
                    <a class="dropdown-item" href="{{ route('admin.inventory.index') }}">Làm mới</a>
                    <a class="dropdown-item" href="#">Xuất danh sách</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Sản phẩm</th>
                            <th>SKU</th>
                            <th>Danh mục</th>
                            <th class="text-center">Tổng tồn kho</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        @php
                            $totalStock = $product->inventories()->sum('quantity');
                            // Lấy ngưỡng cảnh báo (nếu có)
                            $firstInventory = $product->inventories()->first();
                            $lowStockThreshold = $firstInventory ? $firstInventory->low_stock_threshold : 5;
                        @endphp
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->productImages()->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $product->productImages()->where('is_primary', true)->first()->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 24px;">
                                            <i class="bi bi-image text-secondary"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-primary font-weight-bold">{{ $product->name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($totalStock <= 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @elseif($totalStock <= $lowStockThreshold)
                                    <span class="badge bg-warning text-dark">Sắp hết ({{ $totalStock }})</span>
                                @else
                                    <span class="badge bg-success">{{ $totalStock }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->status == 'active')
                                    <span class="badge bg-success">Đang bán</span>
                                @elseif($product->status == 'inactive')
                                    <span class="badge bg-secondary">Ngừng bán</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}" class="btn btn-sm btn-primary me-1" title="Nhập hàng">
                                    <i class="bi bi-plus-circle"></i>
                                </a>
                                <a href="{{ route('admin.inventory.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-warning me-1" title="Điều chỉnh tồn kho">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-info view-inventory" data-product-id="{{ $product->id }}" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi tiết tồn kho -->
<div class="modal fade" id="inventoryDetailModal" tabindex="-1" aria-labelledby="inventoryDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryDetailModalLabel">Chi tiết tồn kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="inventoryDetails">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải thông tin tồn kho...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý sự kiện click vào nút xem chi tiết tồn kho
        const viewButtons = document.querySelectorAll('.view-inventory');
        const modal = new bootstrap.Modal(document.getElementById('inventoryDetailModal'));
        const inventoryDetails = document.getElementById('inventoryDetails');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                
                // Hiển thị loading
                inventoryDetails.innerHTML = `
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải thông tin tồn kho...</p>
                    </div>
                `;
                
                // Hiển thị modal
                modal.show();
                
                // Lấy dữ liệu sản phẩm (đoạn này trong thực tế sẽ gọi AJAX)
                fetch(`/admin/inventory/${productId}`)
                    .then(response => response.text())
                    .then(html => {
                        inventoryDetails.innerHTML = html;
                    })
                    .catch(error => {
                        inventoryDetails.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> Có lỗi xảy ra khi tải dữ liệu: ${error.message}
                            </div>
                        `;
                    });
            });
        });
    });
</script>
@endpush 