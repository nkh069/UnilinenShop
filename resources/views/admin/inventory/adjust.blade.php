@extends('layouts.admin')

@section('title', 'Điều chỉnh tồn kho')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Điều chỉnh tồn kho</h1>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin điều chỉnh</h6>
        </div>
        <div class="card-body">
            @if(!$product)
            <form id="product-select-form" action="{{ route('admin.inventory.adjust') }}" method="GET">
                <div class="mb-4">
                    <label for="product_id" class="form-label">Chọn sản phẩm <span class="text-danger">*</span></label>
                    <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (SKU: {{ $p->sku }})</option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Chọn sản phẩm này
                        </button>
                    </div>
                </div>
            </form>
            @elseif($product->inventories->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Sản phẩm này chưa có thông tin tồn kho. Vui lòng <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}">thêm tồn kho</a> trước khi điều chỉnh.
            </div>
            @else
            <form id="adjust-stock-form" action="{{ route('admin.inventory.process-adjust') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text mb-1"><strong>SKU:</strong> {{ $product->sku }}</p>
                                <p class="card-text mb-1"><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                                <p class="card-text mb-0">
                                    <strong>Trạng thái:</strong> 
                                    @if($product->status == 'active')
                                    <span class="badge bg-success">Đang bán</span>
                                    @elseif($product->status == 'inactive')
                                    <span class="badge bg-secondary">Ngừng bán</span>
                                    @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tồn kho hiện tại</h5>
                                @php
                                $totalStock = $product->inventories()->sum('quantity');
                                @endphp
                                <h2 class="mb-0 {{ $totalStock > 0 ? 'text-success' : 'text-danger' }}">{{ $totalStock }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="inventory_id" class="form-label">Chọn biến thể cần điều chỉnh <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select id="inventory_id" name="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                            <option value="">-- Chọn biến thể --</option>
                            @foreach($product->inventories as $inventory)
                            <option value="{{ $inventory->id }}" data-quantity="{{ $inventory->quantity }}">
                                @if($inventory->size || $inventory->color)
                                    {{ $inventory->size ? 'Size: '.$inventory->size : '' }}
                                    {{ $inventory->size && $inventory->color ? ' / ' : '' }}
                                    {{ $inventory->color ? 'Màu: '.$inventory->color : '' }}
                                @else
                                    Mặc định
                                @endif
                                (Hiện tại: {{ $inventory->quantity }})
                            </option>
                            @endforeach
                        </select>
                        <button id="delete-variant-btn" type="button" class="btn btn-danger" disabled>
                            <i class="bi bi-trash"></i> Xóa biến thể
                        </button>
                    </div>
                    @error('inventory_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div id="adjust-form" class="d-none">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="current_quantity" class="form-label">Số lượng hiện tại</label>
                            <input type="number" id="current_quantity" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Số lượng mới <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="0" required>
                            @error('quantity')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do điều chỉnh <span class="text-danger">*</span></label>
                        <select name="reason" id="reason" class="form-select @error('reason') is-invalid @enderror" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Kiểm kê thực tế">Kiểm kê thực tế</option>
                            <option value="Mất hàng">Mất hàng</option>
                            <option value="Hàng bị hư hỏng">Hàng bị hư hỏng</option>
                            <option value="Hàng trả về">Hàng trả về</option>
                            <option value="Sử dụng nội bộ">Sử dụng nội bộ</option>
                            <option value="Đồng bộ hệ thống">Đồng bộ hệ thống</option>
                            <option value="other">Lý do khác</option>
                        </select>
                        @error('reason')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="other-reason" class="mb-3 d-none">
                        <label for="custom_reason" class="form-label">Lý do khác</label>
                        <textarea name="custom_reason" id="custom_reason" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="source" class="form-label">Nguồn điều chỉnh</label>
                            <select class="form-select @error('source') is-invalid @enderror" id="source" name="source">
                                <option value="">-- Chọn nguồn nhập --</option>
                                <option value="adjustment" selected>Điều chỉnh tồn kho</option>
                                <option value="warehouse">Nhập hàng vào kho</option>
                                <option value="supplier">Nhà cung cấp</option>
                                <option value="return">Hàng trả lại</option>
                                <option value="other">Nguồn khác</option>
                            </select>
                            @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="supplier_section" style="display: none;">
                            <label for="supplier_id" class="form-label">Nhà cung cấp</label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} @if($supplier->code) ({{ $supplier->code }}) @endif</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu điều chỉnh
                        </button>
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary ms-2">Hủy</a>
                    </div>
                </div>
            </form>
            
            <!-- Form xóa biến thể -->
            <form id="delete-variant-form" action="" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý chọn inventory
        const inventorySelect = document.getElementById('inventory_id');
        const adjustForm = document.getElementById('adjust-form');
        const currentQuantityInput = document.getElementById('current_quantity');
        const newQuantityInput = document.getElementById('quantity');
        const deleteVariantBtn = document.getElementById('delete-variant-btn');
        const deleteVariantForm = document.getElementById('delete-variant-form');
        
        if (inventorySelect) {
            inventorySelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const currentQuantity = selectedOption.dataset.quantity;
                    
                    // Hiển thị form điều chỉnh
                    adjustForm.classList.remove('d-none');
                    
                    // Cập nhật số lượng hiện tại
                    currentQuantityInput.value = currentQuantity;
                    newQuantityInput.value = currentQuantity;
                    
                    // Kích hoạt nút xóa variant
                    deleteVariantBtn.disabled = false;
                    deleteVariantForm.action = '{{ url("admin/inventory/variant") }}/' + this.value;
                } else {
                    // Ẩn form điều chỉnh khi không chọn variant
                    adjustForm.classList.add('d-none');
                    deleteVariantBtn.disabled = true;
                }
            });
        }
        
        // Xử lý form nhập lý do khác
        const reasonSelect = document.getElementById('reason');
        const otherReasonDiv = document.getElementById('other-reason');
        
        if (reasonSelect) {
            reasonSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    otherReasonDiv.classList.remove('d-none');
                } else {
                    otherReasonDiv.classList.add('d-none');
                }
            });
        }
        
        // Xử lý nút xóa variant
        if (deleteVariantBtn) {
            deleteVariantBtn.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa biến thể này không?')) {
                    deleteVariantForm.submit();
                }
            });
        }
        
        // Xử lý hiển thị phần chọn nhà cung cấp
        const sourceSelect = document.getElementById('source');
        const supplierSection = document.getElementById('supplier_section');
        
        if (sourceSelect) {
            sourceSelect.addEventListener('change', function() {
                if (this.value === 'supplier') {
                    supplierSection.style.display = 'block';
                } else {
                    supplierSection.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush 