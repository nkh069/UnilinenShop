@extends('layouts.admin')

@section('title', 'Lịch sử tồn kho')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lịch sử tồn kho</h1>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.inventory.movements') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="product_id" class="form-label">Sản phẩm</label>
                        <select class="form-select" id="product_id" name="product_id">
                            <option value="">-- Tất cả sản phẩm --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Loại biến động</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">-- Tất cả loại --</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Nhập kho</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Xuất kho</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Điều chỉnh</option>
                            <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Trả hàng</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="source" class="form-label">Nguồn</label>
                        <select class="form-select" id="source" name="source">
                            <option value="">-- Tất cả nguồn --</option>
                            <option value="supplier" {{ request('source') == 'supplier' ? 'selected' : '' }}>Nhà cung cấp</option>
                            <option value="warehouse" {{ request('source') == 'warehouse' ? 'selected' : '' }}>Nhập hàng vào kho</option>
                            <option value="adjustment" {{ request('source') == 'adjustment' ? 'selected' : '' }}>Điều chỉnh tồn kho</option>
                            <option value="return" {{ request('source') == 'return' ? 'selected' : '' }}>Hàng trả lại</option>
                            <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="supplier_id" class="form-label">Nhà cung cấp</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">-- Tất cả nhà cung cấp --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="from_date" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="to_date" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Tìm theo mã, tên sản phẩm hoặc lý do..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.inventory.movements') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Xóa bộ lọc
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Lịch sử biến động tồn kho</h6>
        </div>
        <div class="card-body">
            @if($movements->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> Không tìm thấy dữ liệu biến động tồn kho nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Thời gian</th>
                                <th>Sản phẩm</th>
                                <th>Biến thể</th>
                                <th>Loại</th>
                                <th>Số lượng</th>
                                <th>Nguồn</th>
                                <th>Nhà cung cấp</th>
                                <th>Người thực hiện</th>
                                <th>Lý do</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->id }}</td>
                                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($movement->inventory && $movement->inventory->product)
                                            <a href="{{ route('admin.products.edit', $movement->inventory->product->id) }}" class="text-primary">
                                                {{ $movement->inventory->product->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">SKU: {{ $movement->inventory->product->sku }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($movement->inventory)
                                            @if($movement->inventory->size || $movement->inventory->color)
                                                {{ $movement->inventory->size ? 'Size: '.$movement->inventory->size : '' }}
                                                {{ $movement->inventory->size && $movement->inventory->color ? ' / ' : '' }}
                                                {{ $movement->inventory->color ? 'Màu: '.$movement->inventory->color : '' }}
                                            @else
                                                Mặc định
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $movement->type == 'in' ? 'bg-success' : ($movement->type == 'out' ? 'bg-danger' : 'bg-warning') }}">
                                            @if($movement->type == 'in')
                                                Nhập kho
                                            @elseif($movement->type == 'out')
                                                Xuất kho
                                            @elseif($movement->type == 'adjustment')
                                                Điều chỉnh
                                            @elseif($movement->type == 'return')
                                                Trả hàng
                                            @else
                                                {{ $movement->type }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="{{ $movement->type == 'in' ? 'text-success' : ($movement->type == 'out' ? 'text-danger' : 'text-warning') }} fw-bold">
                                            {{ $movement->type == 'in' ? '+' : ($movement->type == 'out' ? '-' : '') }}{{ $movement->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($movement->source)
                                            @if($movement->source == 'supplier')
                                                <span class="badge bg-primary">Nhà cung cấp</span>
                                            @elseif($movement->source == 'warehouse')
                                                <span class="badge bg-info">Nhập kho</span>
                                            @elseif($movement->source == 'adjustment')
                                                <span class="badge bg-warning">Điều chỉnh</span>
                                            @elseif($movement->source == 'return')
                                                <span class="badge bg-secondary">Trả hàng</span>
                                            @else
                                                <span class="badge bg-dark">{{ $movement->source }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($movement->supplier)
                                            <a href="{{ route('admin.suppliers.show', $movement->supplier->id) }}">
                                                {{ $movement->supplier->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($movement->user)
                                            {{ $movement->user->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $movements->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 