@extends('layouts.admin')

@section('title', 'Quản lý hoá đơn')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý hoá đơn</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Hoá đơn</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div><i class="fas fa-table me-1"></i> Danh sách hoá đơn</div>
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tạo hoá đơn mới
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('admin.invoices.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo số hoá đơn" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Thanh toán một phần</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" placeholder="Từ ngày" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" placeholder="Đến ngày" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search"></i> Tìm kiếm</button>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Đặt lại</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Số hoá đơn</th>
                            <th>Khách hàng</th>
                            <th>Đơn hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày tạo</th>
                            <th>Ngày đến hạn</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($invoices) > 0)
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->user->name }}</td>
                                    <td>{{ $invoice->order ? $invoice->order->order_number : 'N/A' }}</td>
                                    <td>{{ number_format($invoice->total_amount, 0, ',', '.') }}₫</td>
                                    <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($invoice->status == 'paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($invoice->status == 'unpaid')
                                            <span class="badge bg-danger">Chưa thanh toán</span>
                                        @elseif($invoice->status == 'partially_paid')
                                            <span class="badge bg-warning">Thanh toán một phần</span>
                                        @elseif($invoice->status == 'cancelled')
                                            <span class="badge bg-secondary">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hoá đơn này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">Không có hoá đơn nào</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 