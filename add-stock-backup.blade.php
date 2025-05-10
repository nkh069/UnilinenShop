@extends('layouts.admin')

@section('title', 'Nhập hàng vào kho')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Nhập hàng vào kho</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.inventory.add-stock') }}" id="add-stock-form">
                @csrf
                
                <div class="mb-3">
                    <label for="product_id" class="form-label">Sản phẩ1m</label>
                    @if($product)
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                    @else
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                
                @if($product && isset($product->inventories) && $product->inventories->count() > 0)
                    <div class="mb-3">
                        <h5>Thêm số lượng vào biến thể hiện có</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Màu sắc</th>
                                        <th>Kích thước</th>
                                        <th>Số lượng hiện tại</th>
                                        <th>Số lượng nhập thêm</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->inventories as $inventory)
                                        <tr>
                                            <td>{{ $inventory->color ?? 'Mặc định' }}</td>
                                            <td>{{ $inventory->size ?? 'Mặc định' }}</td>
                                            <td>{{ $inventory->quantity }}</td>
                                            <td>
                                                <input type="number" class="form-control variant-quantity" name="variant[{{ $inventory->id }}]" min="0" value="0">
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.inventory.delete-variant', $inventory->id) }}" method="POST" class="d-inline delete-variant-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                        <i class="bi bi-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                <div class="mb-3 mt-4">
                    <h5>Thêm biến thể mới</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="new_variant" name="new_variant" value="1">
                        <label class="form-check-label" for="new_variant">
                            Thêm biến thể mới
                        </label>
                    </div>
                </div>
                
                <div id="new_variant_fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color">
                                <option value="">-- Chọn màu sắc --</option>
                                @if($product)
                                    @if(isset($product->colors) && is_object($product->colors) && $product->colors->isNotEmpty())
                                        @foreach($product->colors as $color)
                                            <option value="{{ $color->name }}">{{ $color->name }}</option>
                                        @endforeach
                                    @elseif(is_array($product->colors) && !empty($product->colors))
                                        @foreach($product->colors as $color)
                                            <option value="{{ $color }}">{{ $color }}</option>
                                        @endforeach
                                    @endif
                                @endif
                                <option value="custom">Màu khác (nhập tay)</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="custom_color" name="custom_color" placeholder="Nhập màu khác" style="display: none;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">Kích thước</label>
                            <select class="form-select" id="size" name="size">
                                <option value="">-- Chọn kích thước --</option>
                                @if($product)
                                    @if(isset($product->sizes) && is_object($product->sizes) && $product->sizes->isNotEmpty())
                                        @foreach($product->sizes as $size)
                                            <option value="{{ $size->name }}">{{ $size->name }}</option>
                                        @endforeach
                                    @elseif(is_array($product->sizes) && !empty($product->sizes))
                                        @foreach($product->sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    @endif
                                @endif
                                <option value="custom">Kích thước khác (nhập tay)</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="custom_size" name="custom_size" placeholder="Nhập kích thước khác" style="display: none;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="low_stock_threshold" class="form-label">Ngưỡng hàng thấp</label>
                            <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" min="1" value="5">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Vị trí trong kho</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary me-md-2">Hủy</a>
                    <button type="submit" class="btn btn-primary" id="submit-button">Nhập hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý hiện/ẩn form thêm biến thể mới
        const newVariantCheckbox = document.getElementById('new_variant');
        const newVariantFields = document.getElementById('new_variant_fields');
        
        if (newVariantCheckbox && newVariantFields) {
            newVariantCheckbox.addEventListener('change', function() {
                newVariantFields.style.display = this.checked ? 'block' : 'none';
            });
        }
        
        // Nếu có product_id trong URL, chuyển đến trang add-stock với product_id
        const productSelect = document.getElementById('product_id');
        if (productSelect) {
            productSelect.addEventListener('change', function() {
                if (this.value) {
                    window.location.href = `{{ route('admin.inventory.add-stock-form') }}?product_id=${this.value}`;
                }
            });
        }

        // Xử lý hiện/ẩn ô nhập màu tùy chỉnh
        const colorSelect = document.getElementById('color');
        const customColorInput = document.getElementById('custom_color');
        
        if (colorSelect && customColorInput) {
            colorSelect.addEventListener('change', function() {
                customColorInput.style.display = this.value === 'custom' ? 'block' : 'none';
                
                if (this.value === 'custom') {
                    customColorInput.setAttribute('required', 'required');
                } else {
                    customColorInput.removeAttribute('required');
                }
            });
        }
        
        // Xử lý hiện/ẩn ô nhập kích thước tùy chỉnh
        const sizeSelect = document.getElementById('size');
        const customSizeInput = document.getElementById('custom_size');
        
        if (sizeSelect && customSizeInput) {
            sizeSelect.addEventListener('change', function() {
                customSizeInput.style.display = this.value === 'custom' ? 'block' : 'none';
                
                if (this.value === 'custom') {
                    customSizeInput.setAttribute('required', 'required');
                } else {
                    customSizeInput.removeAttribute('required');
                }
            });
        }
        
        // Kiểm tra form trước khi submit
        const addStockForm = document.getElementById('add-stock-form');
        const submitButton = document.getElementById('submit-button');
        
        if (addStockForm && submitButton) {
            addStockForm.addEventListener('submit', function(e) {
                const newVariant = document.getElementById('new_variant').checked;
                const variantQuantities = document.querySelectorAll('.variant-quantity');
                
                let hasQuantity = false;
                
                // Kiểm tra nếu đã chọn thêm số lượng vào biến thể hiện có
                if (variantQuantities.length > 0) {
                    variantQuantities.forEach(function(input) {
                        if (parseInt(input.value) > 0) {
                            hasQuantity = true;
                        }
                    });
                }
                
                // Kiểm tra nếu đã chọn thêm biến thể mới
                if (newVariant) {
                    const quantity = document.getElementById('quantity').value;
                    if (parseInt(quantity) > 0) {
                        hasQuantity = true;
                    }
                }
                
                if (!hasQuantity) {
                    e.preventDefault();
                    alert('Vui lòng nhập số lượng cho ít nhất một biến thể');
                }
            });
        }
    });
</script>
@endpush 