@extends('layouts.admin')

@section('title', 'Test Cập Nhật Sản Phẩm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Test Cập Nhật Sản Phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Form Test Đơn Giản -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Form Test Cập Nhật</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="test-edit-form">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="test_name" name="name" value="{{ $product->name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="test_price" class="form-label">Giá <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="test_price" name="price" value="{{ $product->price }}">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                <input type="hidden" name="sku" value="{{ $product->sku }}">
                <input type="hidden" name="status" value="{{ $product->status }}">
                <input type="hidden" name="description" value="{{ $product->description }}">
                
                <div class="text-center">
                    <button type="submit" class="btn btn-warning">Cập nhật test</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Test form DOM loaded');
        const form = document.getElementById('test-edit-form');
        
        if (form) {
            console.log('Test form found. Action:', form.action);
            form.addEventListener('submit', function(e) {
                console.log('Test form is being submitted...');
                // Không ngăn submit để test
            });
        } else {
            console.log('Test form not found');
        }
    });
</script>
@endpush 