@extends('layouts.admin')

@section('title', 'Nhập hàng vào kho')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nhập hàng vào kho</h1>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Nhập hàng vào kho</h6>
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
            
            <form method="POST" action="{{ route('admin.inventory.add-stock') }}" id="add-stock-form">
                @csrf
                
                <div class="mb-3">
                    <label for="product_id" class="form-label">Sản phẩm <span class="text-danger">*</span></label>
                    @if($product)
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $product->name }} (SKU: {{ $product->sku }})" disabled>
                            <a href="{{ route('admin.inventory.add-stock-form') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Chọn sản phẩm khác
                            </a>
                        </div>
                    @else
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (SKU: {{ $p->sku }})</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="mt-3 text-center">
                            <button type="button" id="select-product-btn" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Chọn sản phẩm này
                            </button>
                        </div>
                    @endif
                </div>
                
                @if($product && isset($product->inventories) && $product->inventories->count() > 0)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Thêm số lượng vào biến thể hiện có</h5>
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i> <strong>Hướng dẫn:</strong> Sử dụng các nút (+1, +5, +10) để nhập nhanh số lượng vào kho. Mỗi lần nhấp vào sẽ tự động cập nhật tồn kho. Nếu cần thêm một biến thể chưa có (màu/kích thước mới), hãy nhấn nút "<i class="bi bi-plus-circle"></i> Thêm biến thể mới".
                        </div>
                        
                        <div class="mb-3 d-flex justify-content-end">
                            <button type="button" id="add-new-variant-btn" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Thêm biến thể mới với màu sắc/kích thước khác">
                                <i class="bi bi-plus-circle"></i> Thêm biến thể mới
                            </button>
                        </div>
                        
                        <!-- Bảng biến thể hiện có -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Màu sắc</th>
                                        <th>Kích thước</th>
                                        <th>Số lượng hiện tại</th>
                                        <th>Thao tác nhanh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->inventories as $inventory)
                                        <tr>
                                            <td>{{ $inventory->color ?? 'Mặc định' }}</td>
                                            <td>{{ $inventory->size ?? 'Mặc định' }}</td>
                                            <td>{{ $inventory->quantity }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.inventory.process-stock-direct', ['product_id' => $product->id, 'variant_id' => $inventory->id, 'quantity' => 1]) }}" class="btn btn-sm btn-success">+1</a>
                                                    <a href="{{ route('admin.inventory.process-stock-direct', ['product_id' => $product->id, 'variant_id' => $inventory->id, 'quantity' => 5]) }}" class="btn btn-sm btn-success">+5</a>
                                                    <a href="{{ route('admin.inventory.process-stock-direct', ['product_id' => $product->id, 'variant_id' => $inventory->id, 'quantity' => 10]) }}" class="btn btn-sm btn-success">+10</a>
                                                    <form action="{{ route('admin.inventory.delete-variant', $inventory->id) }}" method="POST" class="d-inline delete-variant-form ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
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
                    </div>
                @elseif($product)
                    <div class="mb-4">
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Thông báo:</strong> Sản phẩm này chưa có biến thể nào. Vui lòng tạo biến thể mới.
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-body text-center py-5">
                                <h5 class="mb-3">Chưa có biến thể nào cho sản phẩm này</h5>
                                <p class="text-muted mb-4">Hãy thêm biến thể mới để quản lý tồn kho theo màu sắc, kích thước</p>
                                <button type="button" id="add-first-variant-btn" class="btn btn-primary btn-lg">
                                    <i class="bi bi-plus-circle"></i> Tạo biến thể đầu tiên
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mb-3 mt-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h5 class="mb-0">Thêm biến thể mới</h5>
                        <div>
                            <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="new_variant" name="new_variant" value="1">
                        <label class="form-check-label" for="new_variant">
                                    Tạo biến thể mới cho sản phẩm này
                        </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="new_variant_fields" style="display: none;" class="card card-body bg-light mb-4 border-primary">
                    <div class="alert alert-primary mb-3">
                        <i class="bi bi-info-circle"></i> <strong>Lưu ý:</strong> Điền thông tin biến thể mới bên dưới. Nếu sản phẩm không có biến thể màu sắc/kích thước, bạn có thể để trống các trường này.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Màu sắc</label>
                            <select class="form-select @error('color') is-invalid @enderror" id="color" name="color">
                                <option value="">-- Không chọn màu --</option>
                                @if($product)
                                    @if(isset($product->colors) && is_object($product->colors) && $product->colors->isNotEmpty())
                                        @foreach($product->colors as $color)
                                            <option value="{{ $color->name }}">{{ $color->name }}</option>
                                        @endforeach
                                    @elseif(isset($product->colors) && is_array($product->colors) && !empty($product->colors))
                                        @foreach($product->colors as $color)
                                            <option value="{{ $color }}">{{ $color }}</option>
                                        @endforeach
                                    @endif
                                @endif
                                <option value="custom">Màu khác (nhập tay)</option>
                            </select>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="text" class="form-control mt-2 @error('custom_color') is-invalid @enderror" id="custom_color" name="custom_color" placeholder="Nhập màu khác" style="display: none;">
                            @error('custom_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">Kích thước</label>
                            <select class="form-select @error('size') is-invalid @enderror" id="size" name="size">
                                <option value="">-- Không chọn size --</option>
                                @if($product)
                                    @if(isset($product->sizes) && is_object($product->sizes) && $product->sizes->isNotEmpty())
                                        @foreach($product->sizes as $size)
                                            <option value="{{ $size->name }}">{{ $size->name }}</option>
                                        @endforeach
                                    @elseif(isset($product->sizes) && is_array($product->sizes) && !empty($product->sizes))
                                        @foreach($product->sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    @endif
                                @endif
                                <option value="custom">Kích thước khác (nhập tay)</option>
                            </select>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="text" class="form-control mt-2 @error('custom_size') is-invalid @enderror" id="custom_size" name="custom_size" placeholder="Nhập kích thước khác" style="display: none;">
                            @error('custom_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Số lượng nhập <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="low_stock_threshold" class="form-label">Ngưỡng còn ít hàng</label>
                            <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="1">
                            @error('low_stock_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="source" class="form-label">Nguồn nhập hàng</label>
                            <select class="form-select @error('source') is-invalid @enderror" id="source" name="source">
                                <option value="">-- Chọn nguồn nhập --</option>
                                <option value="supplier">Nhà cung cấp</option>
                                <option value="warehouse">Nhập hàng vào kho</option>
                                <option value="adjustment">Điều chỉnh tồn kho</option>
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
                            <div class="mt-2">
                                <a href="{{ route('admin.suppliers.create') }}" target="_blank" class="text-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp mới
                                </a>
                            </div>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Vị trí trong kho</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" placeholder="VD: Kệ A1, Kho Hà Nội..." value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 border-top pt-3">
                        <button type="button" id="cancel-variant-btn" class="btn btn-outline-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Hủy
                        </button>
                        <button type="button" id="confirm-variant-btn" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Xác nhận thêm biến thể
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary me-md-2">
                        <i class="bi bi-x-circle"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Xử lý hiển thị form tạo biến thể mới
    $('#new_variant, #add-new-variant-btn, #add-first-variant-btn').on('click', function() {
        $('#new_variant').prop('checked', true);
        $('#new_variant_fields').show();
    });
    
    // Xử lý chọn sản phẩm
    $('#select-product-btn').on('click', function() {
        var productId = $('#product_id').val();
        if (productId) {
            window.location.href = '{{ route("admin.inventory.add-stock-form") }}?product_id=' + productId;
                } else {
                    alert('Vui lòng chọn một sản phẩm');
                }
            });
    
    // Xử lý nhập thông tin tùy chỉnh
    $('#color').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom_color').show();
        } else {
            $('#custom_color').hide();
        }
    });
    
    $('#size').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom_size').show();
                } else {
            $('#custom_size').hide();
        }
    });
    
    // Xử lý hiển thị phần chọn nhà cung cấp
    $('#source').on('change', function() {
        if ($(this).val() === 'supplier') {
            $('#supplier_section').show();
                } else {
            $('#supplier_section').hide();
            $('#supplier_id').val('');
        }
    });
    
    // Khởi tạo
    if ($('#color').val() === 'custom') {
        $('#custom_color').show();
    }
    
    if ($('#size').val() === 'custom') {
        $('#custom_size').show();
    }
    
    if ($('#source').val() === 'supplier') {
        $('#supplier_section').show();
        }
    });
</script>
@endpush 