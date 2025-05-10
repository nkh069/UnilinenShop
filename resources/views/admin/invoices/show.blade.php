@extends('layouts.admin')

@section('title', 'Chi tiết hoá đơn - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Chi tiết hoá đơn: {{ $invoice->invoice_number }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Hoá đơn</a></li>
        <li class="breadcrumb-item active">Chi tiết</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-file-invoice me-1"></i> Chi tiết hoá đơn</div>
            <div>
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> Tải PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-1"></i> Thông tin hoá đơn
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 35%">Số hoá đơn:</th>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo:</th>
                                    <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày đến hạn:</th>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
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
                                </tr>
                                <tr>
                                    <th>Ghi chú:</th>
                                    <td>{{ $invoice->notes ?? 'Không có' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-user me-1"></i> Thông tin khách hàng
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 35%">Tên khách hàng:</th>
                                    <td>{{ $invoice->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $invoice->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td>{{ $invoice->user->phone ?? ($invoice->order->shipping_phone ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ:</th>
                                    <td>{{ $invoice->user->address ?? ($invoice->order->shipping_address ? $invoice->order->shipping_address.', '.$invoice->order->shipping_city : 'N/A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i> Thông tin đơn hàng
                </div>
                <div class="card-body">
                    @if($invoice->order)
                        <table class="table table-bordered mb-3">
                            <tr>
                                <th style="width: 25%">Mã đơn hàng:</th>
                                <td>{{ $invoice->order->order_number }}</td>
                            </tr>
                            <tr>
                                <th>Ngày đặt hàng:</th>
                                <td>{{ $invoice->order->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái đơn hàng:</th>
                                <td>
                                    @if($invoice->order->status == 'pending')
                                        <span class="badge bg-warning">Đang chờ xử lý</span>
                                    @elseif($invoice->order->status == 'processing')
                                        <span class="badge bg-info">Đang xử lý</span>
                                    @elseif($invoice->order->status == 'shipped')
                                        <span class="badge bg-primary">Đã giao hàng</span>
                                    @elseif($invoice->order->status == 'completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                    @elseif($invoice->order->status == 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <h5>Chi tiết sản phẩm</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->order->orderItems as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{ $item->product->name }}
                                                @if($item->options)
                                                    <br>
                                                    <small class="text-muted">
                                                        @foreach(json_decode($item->options, true) as $key => $value)
                                                            {{ ucfirst($key) }}: {{ $value }}
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->unit_price > 0 ? $item->unit_price : $item->product->getFinalPrice(), 0, ',', '.') }}₫</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format(($item->unit_price > 0 ? $item->unit_price : $item->product->getFinalPrice()) * $item->quantity, 0, ',', '.') }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Không tìm thấy thông tin đơn hàng liên quan.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-money-bill me-1"></i> Thông tin thanh toán
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 50%">Tổng tiền hàng:</th>
                                    <td class="text-end">{{ number_format($invoice->total_amount - $invoice->tax_amount - $invoice->shipping_amount + $invoice->discount_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Thuế (VAT):</th>
                                    <td class="text-end">{{ number_format($invoice->tax_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Phí vận chuyển:</th>
                                    <td class="text-end">{{ number_format($invoice->shipping_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Giảm giá:</th>
                                    <td class="text-end">-{{ number_format($invoice->discount_amount, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr class="table-primary">
                                    <th>Tổng cộng:</th>
                                    <td class="text-end fw-bold">{{ number_format($invoice->total_amount, 0, ',', '.') }}₫</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Chi tiết thanh toán</h5>
                                    <p>{{ $invoice->payment_details ?? 'Không có thông tin thanh toán.' }}</p>
                                    
                                    @if($invoice->status == 'unpaid')
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Hoá đơn này chưa được thanh toán.
                                        </div>
                                    @elseif($invoice->status == 'partially_paid')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i> Hoá đơn này mới được thanh toán một phần.
                                        </div>
                                    @elseif($invoice->status == 'paid')
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-1"></i> Hoá đơn này đã được thanh toán đầy đủ.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                
                <div>
                    <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hoá đơn này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Xóa hoá đơn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 