@extends('layouts.admin')

@section('title', 'Quản lý thuộc tính sản phẩm')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-2 text-gray-800">Quản lý thuộc tính sản phẩm</h1>
    <p class="mb-4">Quản lý màu sắc và kích thước cho tất cả sản phẩm</p>

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

    <div class="row">
        <!-- Màu sắc -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Màu sắc</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addColorModal">
                        <i class="bi bi-plus-circle"></i> Thêm màu
                    </button>
                </div>
                <div class="card-body">
                    @if(isset($colors) && count($colors) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Màu</th>
                                    <th>Mã màu</th>
                                    <th>Số sản phẩm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colors as $color)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="color-sample me-2" style="background-color: #{{ $color->code }};"></span>
                                            {{ $color->name }}
                                        </div>
                                    </td>
                                    <td>#{{ $color->code }}</td>
                                    <td>
                                        @if(isset($colorStats[$color->name]))
                                            {{ $colorStats[$color->name]['count'] ?? 0 }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-color" 
                                                data-color="{{ $color->name }}"
                                                data-color-code="{{ $color->code }}"
                                                data-bs-toggle="modal" data-bs-target="#editColorModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-color" 
                                                data-color="{{ $color->name }}"
                                                data-bs-toggle="modal" data-bs-target="#deleteColorModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="mb-0 text-center">Chưa có màu sắc nào</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kích thước -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Kích thước</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSizeModal">
                        <i class="bi bi-plus-circle"></i> Thêm kích thước
                    </button>
                </div>
                <div class="card-body">
                    @if(isset($sizes) && count($sizes) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kích thước</th>
                                    <th>Số sản phẩm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                <tr>
                                    <td class="align-middle">{{ $size->name }}</td>
                                    <td class="align-middle">
                                        @if(isset($sizeStats[$size->name]))
                                            {{ $sizeStats[$size->name] ?? 0 }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-sm btn-warning edit-size-btn" 
                                                data-size="{{ $size->name }}"
                                                data-bs-toggle="modal" data-bs-target="#editSizeModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-size-btn"
                                                data-size="{{ $size->name }}"
                                                data-bs-toggle="modal" data-bs-target="#deleteSizeModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="mb-0 text-center">Chưa có kích thước nào</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Màu -->
<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addColorModalLabel">Thêm màu mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.add-color') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_color" class="form-label">Tên màu</label>
                        <input type="text" class="form-control" id="new_color" name="new_color" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_code" class="form-label">Mã màu (HEX)</label>
                        <div class="input-group">
                            <span class="input-group-text">#</span>
                            <input type="text" class="form-control" id="color_code" name="color_code" 
                                   pattern="[0-9A-Fa-f]{6}" maxlength="6">
                        </div>
                        <div class="form-text">Nhập mã màu 6 ký tự hoặc chọn từ bảng màu bên dưới. Có thể để trống nếu không cần mã màu.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bảng màu cơ bản</label>
                        <div class="color-palette d-flex flex-wrap gap-2">
                            <button type="button" class="color-option" data-color="FF0000" style="background-color: #FF0000;"></button>
                            <button type="button" class="color-option" data-color="00FF00" style="background-color: #00FF00;"></button>
                            <button type="button" class="color-option" data-color="0000FF" style="background-color: #0000FF;"></button>
                            <button type="button" class="color-option" data-color="FFFF00" style="background-color: #FFFF00;"></button>
                            <button type="button" class="color-option" data-color="FF00FF" style="background-color: #FF00FF;"></button>
                            <button type="button" class="color-option" data-color="00FFFF" style="background-color: #00FFFF;"></button>
                            <button type="button" class="color-option" data-color="000000" style="background-color: #000000;"></button>
                            <button type="button" class="color-option" data-color="FFFFFF" style="background-color: #FFFFFF; border: 1px solid #ddd;"></button>
                            <button type="button" class="color-option" data-color="808080" style="background-color: #808080;"></button>
                            <button type="button" class="color-option" data-color="800000" style="background-color: #800000;"></button>
                            <button type="button" class="color-option" data-color="808000" style="background-color: #808000;"></button>
                            <button type="button" class="color-option" data-color="008000" style="background-color: #008000;"></button>
                            <button type="button" class="color-option" data-color="800080" style="background-color: #800080;"></button>
                            <button type="button" class="color-option" data-color="008080" style="background-color: #008080;"></button>
                            <button type="button" class="color-option" data-color="000080" style="background-color: #000080;"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm màu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cập nhật Màu -->
<div class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editColorModalLabel">Cập nhật màu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.update-color') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="old_color" name="old_color">
                    <div class="mb-3">
                        <label for="new_color" class="form-label">Tên màu mới</label>
                        <input type="text" class="form-control" id="new_color" name="new_color" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_code" class="form-label">Mã màu (HEX)</label>
                        <div class="input-group">
                            <span class="input-group-text">#</span>
                            <input type="text" class="form-control" id="color_code" name="color_code" 
                                   pattern="[0-9A-Fa-f]{6}" maxlength="6">
                        </div>
                        <div class="form-text">Nhập mã màu 6 ký tự hoặc chọn từ bảng màu bên dưới. Có thể để trống nếu không cần mã màu.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bảng màu cơ bản</label>
                        <div class="color-palette d-flex flex-wrap gap-2">
                            <button type="button" class="color-option" data-color="FF0000" style="background-color: #FF0000;"></button>
                            <button type="button" class="color-option" data-color="00FF00" style="background-color: #00FF00;"></button>
                            <button type="button" class="color-option" data-color="0000FF" style="background-color: #0000FF;"></button>
                            <button type="button" class="color-option" data-color="FFFF00" style="background-color: #FFFF00;"></button>
                            <button type="button" class="color-option" data-color="FF00FF" style="background-color: #FF00FF;"></button>
                            <button type="button" class="color-option" data-color="00FFFF" style="background-color: #00FFFF;"></button>
                            <button type="button" class="color-option" data-color="000000" style="background-color: #000000;"></button>
                            <button type="button" class="color-option" data-color="FFFFFF" style="background-color: #FFFFFF; border: 1px solid #ddd;"></button>
                            <button type="button" class="color-option" data-color="808080" style="background-color: #808080;"></button>
                            <button type="button" class="color-option" data-color="800000" style="background-color: #800000;"></button>
                            <button type="button" class="color-option" data-color="808000" style="background-color: #808000;"></button>
                            <button type="button" class="color-option" data-color="008000" style="background-color: #008000;"></button>
                            <button type="button" class="color-option" data-color="800080" style="background-color: #800080;"></button>
                            <button type="button" class="color-option" data-color="008080" style="background-color: #008080;"></button>
                            <button type="button" class="color-option" data-color="000080" style="background-color: #000080;"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Màu -->
<div class="modal fade" id="deleteColorModal" tabindex="-1" aria-labelledby="deleteColorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteColorModalLabel">Xác nhận xóa màu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.remove-color') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="delete_color" name="color">
                    <p>Bạn có chắc chắn muốn xóa màu "<span id="color_name"></span>" không?</p>
                    <p class="text-danger">Lưu ý: Hành động này sẽ xóa màu này khỏi tất cả sản phẩm đang sử dụng.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm Kích thước -->
<div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSizeModalLabel">Thêm kích thước mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.add-size') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_size" class="form-label">Kích thước</label>
                        <input type="text" class="form-control" id="new_size" name="new_size" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa Kích thước -->
<div class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSizeModalLabel">Chỉnh sửa kích thước</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.update-size') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="old_size" name="old_size">
                    <div class="mb-3">
                        <label for="new_size_edit" class="form-label">Kích thước mới</label>
                        <input type="text" class="form-control" id="new_size_edit" name="new_size" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Kích thước -->
<div class="modal fade" id="deleteSizeModal" tabindex="-1" aria-labelledby="deleteSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSizeModalLabel">Xác nhận xóa kích thước</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attributes.remove-size') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="delete_size" name="size">
                    <p>Bạn có chắc chắn muốn xóa kích thước "<span id="size_name"></span>" không?</p>
                    <p class="text-danger">Lưu ý: Hành động này sẽ xóa kích thước này khỏi tất cả sản phẩm đang sử dụng.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .color-sample {
        width: 30px;
        height: 30px;
        display: inline-block;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
    
    .color-palette {
        margin-top: 10px;
    }
    
    .color-option {
        width: 40px;
        height: 40px;
        border: 2px solid transparent;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .color-option:hover {
        transform: scale(1.1);
    }
    
    .color-option.selected {
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý cho modal chỉnh sửa màu
        const editColorButtons = document.querySelectorAll('.edit-color');
        editColorButtons.forEach(button => {
            button.addEventListener('click', function() {
                const color = this.dataset.color;
                const colorCode = this.dataset.colorCode;
                
                document.getElementById('old_color').value = color;
                document.getElementById('new_color').value = color;
                document.getElementById('color_code').value = colorCode;
                
                // Đánh dấu màu được chọn trong bảng màu
                document.querySelectorAll('.color-option').forEach(option => {
                    option.classList.remove('selected');
                    if (option.dataset.color === colorCode) {
                        option.classList.add('selected');
                    }
                });
            });
        });

        // Xử lý cho modal xóa màu
        const deleteColorButtons = document.querySelectorAll('.delete-color');
        deleteColorButtons.forEach(button => {
            button.addEventListener('click', function() {
                const color = this.dataset.color;
                document.getElementById('delete_color').value = color;
                document.getElementById('color_name').textContent = color;
            });
        });

        // Xử lý cho modal chỉnh sửa kích thước
        const editSizeButtons = document.querySelectorAll('.edit-size-btn');
        editSizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const size = this.getAttribute('data-size');
                document.getElementById('old_size').value = size;
                document.getElementById('new_size_edit').value = size;
            });
        });

        // Xử lý cho modal xóa kích thước
        const deleteSizeButtons = document.querySelectorAll('.delete-size-btn');
        deleteSizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const size = this.getAttribute('data-size');
                document.getElementById('delete_size').value = size;
                document.getElementById('size_name').textContent = size;
            });
        });

        // Validate mã màu
        document.querySelectorAll('input[name="color_code"]').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9A-Fa-f]/g, '').toUpperCase();
                
                // Cập nhật trạng thái chọn trong bảng màu
                document.querySelectorAll('.color-option').forEach(option => {
                    option.classList.remove('selected');
                    if (option.dataset.color === this.value) {
                        option.classList.add('selected');
                    }
                });
            });
        });
        
        // Xử lý chọn màu từ bảng màu
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                const colorCode = this.dataset.color;
                
                // Cập nhật input mã màu
                document.querySelectorAll('input[name="color_code"]').forEach(input => {
                    input.value = colorCode;
                });
                
                // Cập nhật trạng thái chọn
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
    });
</script>
@endpush 