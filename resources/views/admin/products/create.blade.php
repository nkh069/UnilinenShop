@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm sản phẩm mới</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
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
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand') }}">
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
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
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
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" min="0" step="1000">
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
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                                        <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
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
                                    <input type="text" class="form-control @error('material') is-invalid @enderror" id="material" name="material" value="{{ old('material') }}">
                                    @error('material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Trọng lượng (gram)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" min="0">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
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
                                    <input type="file" name="images[]" id="product-images" multiple 
                                        class="form-control @error('images.*') is-invalid @enderror"
                                        accept="image/*">
                                    <small class="form-text text-muted">Chấp nhận: jpeg, png, jpg, gif (tối đa 2MB)</small>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-2"></i> <strong>Hướng dẫn:</strong>
                                    <ul class="mb-0 ps-3 mt-2">
                                        <li><b>Hình đại diện:</b> Ảnh đầu tiên sẽ tự động được chọn làm hình đại diện</li>
                                        <li><b>Kích thước hình ảnh:</b> Hệ thống sẽ tự động tạo các phiên bản hình ảnh:
                                            <ul class="ps-3 mt-1">
                                                <li>Hình đại diện (thumbnail): Tối ưu cho hiển thị trong danh sách</li>
                                                <li>Hình gallery: Tối ưu cho hiển thị chi tiết sản phẩm</li>
                                                <li>Hình gốc: Lưu trữ để đảm bảo chất lượng</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div id="image-preview-container" class="d-flex flex-wrap gap-2 mt-3"></div>
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
                                                    <input class="form-check-input" type="checkbox" id="size_{{ $size->id }}" name="size_ids[]" value="{{ $size->id }}" {{ (is_array(old('size_ids')) && in_array($size->id, old('size_ids'))) ? 'checked' : '' }}>
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
                                                    <input class="form-check-input" type="checkbox" id="color_{{ $color->id }}" name="color_ids[]" value="{{ $color->id }}" {{ (is_array(old('color_ids')) && in_array($color->id, old('color_ids'))) ? 'checked' : '' }}>
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
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> Sau khi tạo sản phẩm, bạn có thể quản lý tồn kho chi tiết cho từng kích thước và màu sắc.
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="initial_stock" class="form-label">Số lượng tồn kho ban đầu</label>
                                        <input type="number" class="form-control" id="initial_stock" name="initial_stock" value="{{ old('initial_stock', 0) }}" min="0">
                                        <small class="form-text text-muted">Nhập số lượng tổng ban đầu</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="low_stock_threshold" class="form-label">Ngưỡng cảnh báo sắp hết hàng</label>
                                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="1">
                                        <small class="form-text text-muted">Khi tồn kho thấp hơn ngưỡng này, sẽ có cảnh báo</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="stock_location" class="form-label">Vị trí lưu trữ</label>
                                    <input type="text" class="form-control" id="stock_location" name="stock_location" value="{{ old('stock_location') }}" placeholder="Ví dụ: Kho A, Kệ B12">
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="track_inventory" name="track_inventory" value="1" {{ old('track_inventory') ? 'checked' : '' }} checked>
                                    <label class="form-check-label" for="track_inventory">
                                        Theo dõi tồn kho
                                    </label>
                                    <small class="d-block text-muted">Nếu bật, hệ thống sẽ tự động cập nhật tồn kho khi có đơn hàng</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="reset" class="btn btn-secondary me-md-2">Xóa dữ liệu</button>
                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Xem trước ảnh khi chọn file
        const imageInput = document.getElementById('product-images');
        const previewContainer = document.getElementById('image-preview-container');
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                
                const files = this.files;
                if (files) {
                    // Kiểm tra số lượng file
                    if (files.length > 5) {
                        alert('Bạn chỉ có thể tải lên tối đa 5 ảnh.');
                        this.value = '';
                        return;
                    }
                    
                    // Hiển thị tiêu đề cho khu vực chọn ảnh chính
                    if (files.length > 0) {
                        const titleElement = document.createElement('div');
                        titleElement.className = 'mb-3 w-100';
                        titleElement.innerHTML = '<h6 class="fw-bold">Chọn ảnh chính để hiển thị trên danh sách sản phẩm:</h6>';
                        previewContainer.appendChild(titleElement);
                    }
                    
                    // Tạo container cho preview dạng grid
                    const previewGrid = document.createElement('div');
                    previewGrid.className = 'row';
                    previewContainer.appendChild(previewGrid);
                    
                    // Hiển thị ảnh preview
                    [...files].forEach((file, index) => {
                        if (!file.type.match('image.*')) {
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Tạo card cho mỗi ảnh
                            const colDiv = document.createElement('div');
                            colDiv.className = 'col-6 col-md-4 col-lg-3 mb-3';
                            
                            const cardDiv = document.createElement('div');
                            cardDiv.className = 'card h-100';
                            
                            // Hiển thị ảnh
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'card-img-top';
                            img.style.height = '150px';
                            img.style.objectFit = 'cover';
                            
                            // Card body cho radio button
                            const cardBody = document.createElement('div');
                            cardBody.className = 'card-body p-2';
                            
                            // Radio container
                            const radioDiv = document.createElement('div');
                            radioDiv.className = 'form-check';
                            
                            // Radio button
                            const radioInput = document.createElement('input');
                            radioInput.type = 'radio';
                            radioInput.name = 'primary_image';
                            radioInput.value = index;
                            radioInput.id = `primary_image_${index}`;
                            radioInput.className = 'form-check-input';
                            if (index === 0) {
                                radioInput.checked = true;
                            }
                            
                            // Label
                            const radioLabel = document.createElement('label');
                            radioLabel.className = 'form-check-label';
                            radioLabel.htmlFor = `primary_image_${index}`;
                            radioLabel.innerText = 'Đặt làm ảnh chính';
                            
                            // Footer
                            const cardFooter = document.createElement('div');
                            cardFooter.className = 'card-footer p-2';
                            cardFooter.innerHTML = `<small class="text-muted">Ảnh ${index + 1}</small>`;
                            
                            // Nếu là ảnh đầu tiên, thêm badge ảnh chính
                            if (index === 0) {
                                const primaryBadge = document.createElement('div');
                                primaryBadge.className = 'position-absolute top-0 start-0 bg-success text-white px-2 py-1';
                                primaryBadge.innerHTML = '<i class="bi bi-star-fill me-1"></i> Ảnh chính';
                                cardDiv.appendChild(primaryBadge);
                            }
                            
                            // Ghép các phần lại với nhau
                            radioDiv.appendChild(radioInput);
                            radioDiv.appendChild(radioLabel);
                            cardBody.appendChild(radioDiv);
                            
                            cardDiv.appendChild(img);
                            cardDiv.appendChild(cardBody);
                            cardDiv.appendChild(cardFooter);
                            
                            colDiv.appendChild(cardDiv);
                            previewGrid.appendChild(colDiv);
                            
                            // Thêm event listener cho radio button
                            radioInput.addEventListener('change', function() {
                                // Xóa tất cả badge ảnh chính
                                document.querySelectorAll('.position-absolute.bg-success').forEach(badge => {
                                    badge.remove();
                                });
                                
                                // Thêm badge mới cho ảnh được chọn
                                if (this.checked) {
                                    const primaryBadge = document.createElement('div');
                                    primaryBadge.className = 'position-absolute top-0 start-0 bg-success text-white px-2 py-1';
                                    primaryBadge.innerHTML = '<i class="bi bi-star-fill me-1"></i> Ảnh chính';
                                    this.closest('.card').appendChild(primaryBadge);
                                }
                            });
                        }
                        
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    });
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