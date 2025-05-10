@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="product-edit-form">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Thương hiệu</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000">
                                        <span class="input-group-text">VNĐ</span>
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">Mã SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Đang bán</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                                        <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="material" class="form-label">Chất liệu</label>
                                    <input type="text" class="form-control @error('material') is-invalid @enderror" id="material" name="material" value="{{ old('material', $product->material) }}">
                                    @error('material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Trọng lượng (gram)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">
                                Sản phẩm nổi bật
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Ảnh sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product-images" class="form-label">Thêm ảnh sản phẩm</label>
                                    <input type="file" name="new_images[]" id="product-images" multiple 
                                        class="form-control @error('new_images.*') is-invalid @enderror"
                                        accept="image/*">
                                    <small class="form-text text-muted">Chấp nhận: jpeg, png, jpg, gif (tối đa 2MB)</small>
                                    @error('new_images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-2"></i> <strong>Hướng dẫn:</strong>
                                    <ul class="mb-0 ps-3 mt-2">
                                        <li><b>Hình đại diện:</b> Chọn một ảnh làm hình đại diện bằng cách chọn nút "Đặt làm ảnh đại diện"</li>
                                        <li><b>Kích thước hình ảnh:</b> Hệ thống sẽ tự động tạo các phiên bản hình ảnh:
                                            <ul class="ps-3 mt-1">
                                                <li>Hình đại diện (thumbnail): Tối ưu cho hiển thị trong danh sách</li>
                                                <li>Hình gallery: Tối ưu cho hiển thị chi tiết sản phẩm</li>
                                                <li>Hình gốc: Lưu trữ để đảm bảo chất lượng</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                
                                @if($product->productImages->isNotEmpty())
                                <div class="mb-4">
                                    <h6 class="mb-3">Hình ảnh hiện tại</h6>
                                    
                                    <div class="alert alert-warning mb-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i> <strong>Lưu ý:</strong> Sau khi cập nhật ảnh đại diện, hãy làm mới trang trước khi thực hiện các thay đổi khác.
                                    </div>
                                    
                                    <div class="row g-3">
                                        @foreach($product->productImages as $image)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 {{ $image->is_primary ? 'border-primary' : 'border' }}">
                                                <div class="position-relative">
                                                    @if($image->is_primary)
                                                    <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                                        <i class="bi bi-star-fill me-1"></i> Ảnh đại diện
                                                    </span>
                                                    @endif
                                                    <img src="{{ asset('storage/' . ($image->thumbnail_path ?? $image->image_path)) }}" 
                                                        class="card-img-top" alt="Product Image"
                                                        style="height: 180px; object-fit: cover;">
                                                </div>
                                                <div class="card-body p-2">
                                                    <div class="d-flex flex-column gap-2">
                                                        @if(!$image->is_primary)
                                                        <a href="{{ route('admin.products.set-primary-image', ['product' => $product->id, 'image' => $image->id]) }}" 
                                                           class="btn btn-sm btn-outline-primary w-100"
                                                           onclick="return confirm('Bạn có chắc chắn muốn đặt ảnh này làm ảnh đại diện?');">
                                                            <i class="bi bi-star me-1"></i> Đặt làm ảnh đại diện
                                                        </a>
                                                        @endif
                                                        
                                                        <a href="{{ route('admin.products.delete-image', $image->id) }}"
                                                           class="btn btn-sm btn-outline-danger w-100" 
                                                           onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này?');">
                                                            <i class="bi bi-trash me-1"></i> Xóa ảnh
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header">Kích thước & Màu sắc</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="sizes" class="form-label">Kích thước</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(isset($allSizes) && count($allSizes) > 0)
                                            @foreach($allSizes as $size)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="size_{{ $size->id }}" name="size_ids[]" value="{{ $size->id }}" 
                                                        {{ (isset($selectedSizeIds) && in_array($size->id, $selectedSizeIds)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="size_{{ $size->id }}">{{ $size->name }}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted small">Chưa có kích thước nào. <a href="{{ route('admin.attributes.index') }}" target="_blank">Thêm mới</a></p>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.attributes.index') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-gear"></i> Quản lý kích thước
                                        </a>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="colors" class="form-label">Màu sắc</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(isset($allColors) && count($allColors) > 0)
                                            @foreach($allColors as $color)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="color_{{ $color->id }}" name="color_ids[]" value="{{ $color->id }}" 
                                                        {{ (isset($selectedColorIds) && in_array($color->id, $selectedColorIds)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="color_{{ $color->id }}">
                                                        {{ $color->name }}
                                                        <span class="color-sample ms-1" style="background-color: #{{ $color->code }};"></span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted small">Chưa có màu nào. <a href="{{ route('admin.attributes.index') }}" target="_blank">Thêm mới</a></p>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.attributes.index') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-gear"></i> Quản lý màu sắc
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Quản lý tồn kho</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-2"></i> Bạn có thể quản lý tồn kho chi tiết cho từng kích thước và màu sắc bằng cách truy cập vào <a href="{{ route('admin.inventory.index', ['product_id' => $product->id]) }}" class="alert-link">trang quản lý tồn kho</a>.
                                </div>
                                
                                @php
                                    // Lấy tổng tồn kho hiện tại
                                    $totalStock = $product->inventories()->sum('quantity');
                                    
                                    // Lấy ngưỡng cảnh báo và vị trí mặc định từ inventory đầu tiên nếu có
                                    $firstInventory = $product->inventories()->first();
                                    $lowStockThreshold = $firstInventory ? $firstInventory->low_stock_threshold : 5;
                                    $stockLocation = $firstInventory ? $firstInventory->location : '';
                                @endphp
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Tổng tồn kho hiện tại</h6>
                                                <h3 class="mb-0 {{ $totalStock <= $lowStockThreshold ? 'text-danger' : 'text-success' }}">{{ $totalStock }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Ngưỡng cảnh báo</h6>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $lowStockThreshold) }}" min="1">
                                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Áp dụng cho tất cả biến thể">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Vị trí lưu trữ</h6>
                                                <input type="text" class="form-control" id="stock_location" name="stock_location" value="{{ old('stock_location', $stockLocation) }}" placeholder="Ví dụ: Kho A, Kệ B12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="track_inventory" name="track_inventory" value="1" {{ $product->track_inventory ? 'checked' : '' }}>
                                    <label class="form-check-label" for="track_inventory">
                                        Theo dõi tồn kho
                                    </label>
                                    <small class="d-block text-muted">Nếu bật, hệ thống sẽ tự động cập nhật tồn kho khi có đơn hàng</small>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-plus-circle me-1"></i> Nhập thêm hàng
                                    </a>
                                    <a href="{{ route('admin.inventory.adjust', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil-square me-1"></i> Điều chỉnh tồn kho
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-md-2">Hủy</a>
                    
                    <!-- Nút submit gốc -->
                    <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                    
                    <!-- Nút submit thay thế sử dụng JavaScript -->
                    <button type="button" class="btn btn-success" onclick="submitForm()">Lưu (JS)</button>
                    
                    <!-- Nút submit thay thế sử dụng AJAX -->
                    <button type="button" class="btn btn-info ms-2" onclick="submitAjax()">Lưu (AJAX)</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hàm trợ giúp submit form
    function submitForm() {
        console.log('Manual form submission via button click');
        const form = document.getElementById('product-edit-form');
        if (form) {
            // Hiển thị tất cả dữ liệu form trước khi submit
            const formData = new FormData(form);
            
            // Kiểm tra xem form có đủ các trường bắt buộc không
            const requiredFields = ['name', 'category_id', 'price', 'sku', 'status'];
            let missingFields = [];
            
            for (let field of requiredFields) {
                if (!formData.get(field) || formData.get(field).trim() === '') {
                    missingFields.push(field);
                }
            }
            
            if (missingFields.length > 0) {
                alert('Các trường sau không được để trống: ' + missingFields.join(', '));
                return;
            }
            
            // Đảm bảo phương thức là PUT
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput && methodInput.value !== 'PUT') {
                console.warn('Method input value was not PUT. Setting to PUT.');
                methodInput.value = 'PUT';
            }
            
            // Log dữ liệu form cho mục đích debug
            console.log('Form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Thêm các trường ẩn nếu cần
            if (!formData.has('_token')) {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = tokenMeta.content;
                    form.appendChild(tokenInput);
                }
            }
            
            // Hiển thị thông báo xác nhận
            if (confirm('Bạn có chắc chắn muốn cập nhật sản phẩm này?')) {
                // Submit form
                console.log('Submitting form...');
                form.submit();
            }
        } else {
            console.error('Form not found!');
        }
    }
    
    // Hàm gửi form bằng AJAX
    function submitAjax() {
        console.log('AJAX form submission');
        const form = document.getElementById('product-edit-form');
        if (!form) {
            console.error('Form not found!');
            return;
        }
        
        const formData = new FormData(form);
        const url = form.action;
        
        // Log thông tin gửi đi
        console.log('AJAX request to:', url);
        console.log('AJAX method:', 'POST');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Hiển thị thông báo đang xử lý
        alert('Đang xử lý cập nhật...');
        
        // Tạo và gửi AJAX request
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log('Response:', data);
            alert('Cập nhật thành công!');
            window.location.href = "{{ route('admin.products.index') }}";
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi khi cập nhật: ' + error.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Debug form submission
        console.log('DOM Loaded, setting up form handlers');
        const form = document.getElementById('product-edit-form');
        if (form) {
            console.log('Found form with ID:', form.id);
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            
            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted...');
                // e.preventDefault(); // Uncomment để ngăn form submit và debug
            });
        } else {
            console.log('Form not found!');
        }
        
        // Tính giá khuyến mãi khi nhập % giảm giá
        const priceInput = document.getElementById('price');
        const discountInput = document.getElementById('discount_percent');
        const salePriceInput = document.getElementById('sale_price');
        
        function calculateSalePrice() {
            const price = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            
            if (price > 0 && discount > 0) {
                const salePrice = price - (price * discount / 100);
                salePriceInput.value = Math.round(salePrice / 1000) * 1000;
            }
        }
        
        if (discountInput) {
            discountInput.addEventListener('input', calculateSalePrice);
            priceInput.addEventListener('input', calculateSalePrice);
        }
        
        // Hiển thị ảnh khi chọn file
        initializeImagePreview();

        // Hiển thị nút cập nhật ảnh chính khi radio button được chọn
        const updatePrimaryBtn = document.getElementById('update-primary-btn');
        const primaryRadios = document.querySelectorAll('input[name="set_primary_image"]');
        
        // Ban đầu ẩn nút cập nhật
        updatePrimaryBtn.classList.add('d-none');
        
        // Ghi nhớ lựa chọn ban đầu
        let initialSelection = '';
        primaryRadios.forEach(radio => {
            if (radio.checked) {
                initialSelection = radio.value;
            }
        });
        
        // Khi thay đổi lựa chọn
        primaryRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value !== initialSelection) {
                    updatePrimaryBtn.classList.remove('d-none');
                } else {
                    updatePrimaryBtn.classList.add('d-none');
                }
            });
        });
    });

    // Hiển thị ảnh khi chọn file
    function initializeImagePreview() {
        const imageInput = document.getElementById('product-images');
        const previewContainer = document.createElement('div');
        previewContainer.id = 'image-preview-container';
        previewContainer.className = 'd-flex flex-wrap gap-2 mt-3';
        
        if (imageInput) {
            // Thêm container preview sau input
            imageInput.parentNode.appendChild(previewContainer);
            
            imageInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                
                const files = this.files;
                if (files) {
                    // Kiểm tra số lượng file
                    const maxFiles = 5 - {{ $product->productImages->count() }};
                    if (files.length > maxFiles) {
                        alert(`Bạn chỉ có thể tải lên thêm ${maxFiles} ảnh.`);
                        this.value = '';
                        return;
                    }
                    
                    // Hiển thị tiêu đề cho khu vực chọn ảnh chính
                    if (files.length > 0) {
                        const titleElement = document.createElement('div');
                        titleElement.className = 'mb-2 w-100';
                        titleElement.innerHTML = '<small class="fw-bold">Chọn ảnh chính cho ảnh mới:</small>';
                        previewContainer.appendChild(titleElement);
                    }
                    
                    // Hiển thị ảnh preview
                    [...files].forEach((file, index) => {
                        if (!file.type.match('image.*')) {
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'position-relative border rounded overflow-hidden d-flex flex-column align-items-center m-1';
                            imgContainer.style.width = '110px';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-100 object-fit-cover';
                            img.style.height = '100px';
                            
                            // Thêm radio button để chọn ảnh chính
                            const radioContainer = document.createElement('div');
                            radioContainer.className = 'mt-1 mb-1 text-center';
                            
                            const radioInput = document.createElement('input');
                            radioInput.type = 'radio';
                            radioInput.name = 'primary_image';
                            radioInput.value = index;
                            radioInput.id = `new_primary_image_${index}`;
                            radioInput.className = 'form-check-input me-1';
                            if (index === 0) {
                                radioInput.checked = true;
                            }
                            
                            const radioLabel = document.createElement('label');
                            radioLabel.className = 'form-check-label small';
                            radioLabel.htmlFor = `new_primary_image_${index}`;
                            radioLabel.innerText = 'Ảnh chính';
                            
                            radioContainer.appendChild(radioInput);
                            radioContainer.appendChild(radioLabel);
                            
                            // Thêm nhãn "Mới"
                            const newLabel = document.createElement('div');
                            newLabel.className = 'position-absolute top-0 start-0 bg-info text-white small px-1';
                            newLabel.innerText = 'Mới';
                            
                            imgContainer.appendChild(img);
                            imgContainer.appendChild(radioContainer);
                            imgContainer.appendChild(newLabel);
                            previewContainer.appendChild(imgContainer);
                        }
                        
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    }
</script>
@endpush

@push('styles')
<style>
    .color-sample {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
</style>
@endpush 