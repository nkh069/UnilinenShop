@extends('layouts.admin')

@section('title', 'Thông tin nhà cung cấp')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thông tin nhà cung cấp</h1>
        <div>
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Chi tiết nhà cung cấp</h6>
            <span class="badge {{ $supplier->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                {{ $supplier->status == 'active' ? 'Đang hoạt động' : 'Không hoạt động' }}
            </span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary">Thông tin cơ bản</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px">ID:</th>
                            <td>{{ $supplier->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên:</th>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Mã:</th>
                            <td>{{ $supplier->code ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Người liên hệ:</th>
                            <td>{{ $supplier->contact_person ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Mã số thuế:</th>
                            <td>{{ $supplier->tax_id ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Website:</th>
                            <td>
                                @if($supplier->website)
                                    <a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a>
                                @else
                                    Chưa cập nhật
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary">Thông tin liên hệ</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px">Email:</th>
                            <td>
                                @if($supplier->email)
                                    <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                @else
                                    Chưa cập nhật
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Điện thoại:</th>
                            <td>
                                @if($supplier->phone)
                                    <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                @else
                                    Chưa cập nhật
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $supplier->address ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Thành phố:</th>
                            <td>{{ $supplier->city ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Tỉnh/Thành:</th>
                            <td>{{ $supplier->state ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Mã bưu chính:</th>
                            <td>{{ $supplier->postal_code ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Quốc gia:</th>
                            <td>{{ $supplier->country ?? 'Vietnam' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($supplier->description)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary">Mô tả</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $supplier->description }}
                    </div>
                </div>
            </div>
            @endif

            @if($supplier->notes)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary">Ghi chú</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $supplier->notes }}
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary">Thông tin thêm</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px">Ngày tạo:</th>
                            <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối:</th>
                            <td>{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây từ nhà cung cấp này -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Đơn nhập hàng gần đây</h6>
        </div>
        <div class="card-body">
            @if($inventoryMovements && $inventoryMovements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ngày nhập</th>
                                <th>Sản phẩm</th>
                                <th>Biến thể</th>
                                <th>Số lượng</th>
                                <th>Loại</th>
                                <th>Lý do</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryMovements as $movement)
                            <tr>
                                <td>{{ $movement->id }}</td>
                                <td>{{ $movement->created_at->format('d/m/Y') }}</td>
                                <td>{{ $movement->inventory->product->name ?? 'N/A' }}</td>
                                <td>
                                    @if($movement->inventory->color || $movement->inventory->size)
                                        {{ $movement->inventory->color ?? '' }} 
                                        @if($movement->inventory->color && $movement->inventory->size) - @endif
                                        {{ $movement->inventory->size ?? '' }}
                                    @else
                                        Mặc định
                                    @endif
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>
                                    @if($movement->type == 'in')
                                    <span class="badge bg-success">Nhập kho</span>
                                    @elseif($movement->type == 'out')
                                    <span class="badge bg-danger">Xuất kho</span>
                                    @else
                                    <span class="badge bg-info">{{ $movement->type }}</span>
                                    @endif
                                </td>
                                <td>{{ $movement->reason }}</td>
                                <td>
                                    <a href="{{ route('admin.inventory.show', $movement->inventory->product_id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Chưa có đơn nhập hàng nào từ nhà cung cấp này.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 