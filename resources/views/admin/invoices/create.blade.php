@extends('layouts.admin')

@section('title', 'Tạo hoá đơn mới')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tạo hoá đơn mới</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Hoá đơn</a></li>
        <li class="breadcrumb-item active">Tạo mới</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i> Tạo hoá đơn mới
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(count($orders) == 0)
                <div class="alert alert-warning">
                    <p class="mb-0">Không có đơn hàng nào hợp lệ để tạo hoá đơn. Chỉ đơn hàng có trạng thái "Đang xử lý", "Đã giao hàng", hoặc "Hoàn thành" và chưa có hoá đơn mới đủ điều kiện.</p>
                </div>
            @else
                <form action="{{ route('admin.invoices.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Chọn đơn hàng <span class="text-danger">*</span></label>
                        <select name="order_id" id="order_id" class="form-select @error('order_id') is-invalid @enderror" required>
                            <option value="">-- Chọn đơn hàng --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                    {{ $order->order_number }} - {{ $order->user->name }} - {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                </option>
                            @endforeach
                        </select>
                        @error('order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="issue_date" class="form-label">Ngày tạo hoá đơn <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control @error('issue_date') is-invalid @enderror" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Ngày đến hạn</label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="partially_paid" {{ old('status') == 'partially_paid' ? 'selected' : '' }}>Thanh toán một phần</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Tạo hoá đơn
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection 