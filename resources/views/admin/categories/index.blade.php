@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý danh mục</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle"></i> Thêm danh mục mới
        </button>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tên danh mục</th>
                            <th width="25%">Mô tả</th>
                            <th width="15%">Số sản phẩm</th>
                            <th width="15%">Trạng thái</th>
                            <th width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories ?? [] as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ Str::limit($category->description, 80) }}</td>
                            <td>{{ $category->products_count ?? 0 }}</td>
                            <td>
                                @if($category->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                                @else
                                <span class="badge bg-danger">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-box"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm thuộc danh mục này sẽ không còn phân loại.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Sửa danh mục</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="edit_name{{ $category->id }}" class="form-label">Tên danh mục</label>
                                                        <input type="text" class="form-control" id="edit_name{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_description{{ $category->id }}" class="form-label">Mô tả</label>
                                                        <textarea class="form-control" id="edit_description{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_status{{ $category->id }}" class="form-label">Trạng thái</label>
                                                        <select class="form-select" id="edit_status{{ $category->id }}" name="status">
                                                            <option value="1" {{ $category->is_active ? 'selected' : '' }}>Hiển thị</option>
                                                            <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Ẩn</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-diagram-3 display-4 text-muted mb-3 d-block"></i>
                                <p class="h5 text-muted">Không có danh mục nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($categories) && $categories->hasPages())
            <div class="pagination-container mt-4">
                <style>
                    .pagination-container {
                        display: flex;
                        justify-content: flex-end;
                    }
                    .pagination {
                        display: flex;
                        padding-left: 0;
                        list-style: none;
                        border-radius: 0.25rem;
                    }
                    .page-item:first-child .page-link {
                        margin-left: 0;
                        border-top-left-radius: 0.25rem;
                        border-bottom-left-radius: 0.25rem;
                    }
                    .page-item:last-child .page-link {
                        border-top-right-radius: 0.25rem;
                        border-bottom-right-radius: 0.25rem;
                    }
                    .page-item.active .page-link {
                        z-index: 3;
                        color: #fff;
                        background-color: #3d5a80;
                        border-color: #3d5a80;
                    }
                    .page-item.disabled .page-link {
                        color: #6c757d;
                        pointer-events: none;
                        cursor: auto;
                        background-color: #fff;
                        border-color: #dee2e6;
                    }
                    .page-link {
                        position: relative;
                        display: block;
                        padding: 0.5rem 0.75rem;
                        margin-left: -1px;
                        line-height: 1.25;
                        color: #3d5a80;
                        background-color: #fff;
                        border: 1px solid #dee2e6;
                    }
                    .page-link:hover {
                        z-index: 2;
                        color: #0056b3;
                        text-decoration: none;
                        background-color: #e9ecef;
                        border-color: #dee2e6;
                    }
                </style>
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" selected>Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 