<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý mã giảm giá - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #343a40;
            color: white;
        }
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, .75);
            padding: 0.5rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }
        .sidebar .nav-link .bi {
            margin-right: 0.5rem;
        }
        main {
            padding-top: 30px;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            background-color: #fff;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0069d9;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .table {
            vertical-align: middle;
        }
        .search-form {
            max-width: 400px;
        }
        .coupon-code {
            background-color: #f8f9fa;
            padding: 0.3rem 0.5rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .badge-expired {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                <i class="bi bi-cart3"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.products.index') }}">
                                <i class="bi bi-box-seam"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                <i class="bi bi-diagram-3"></i> Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.inventory.index') }}">
                                <i class="bi bi-clipboard-data"></i> Kho hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.shipments.index') }}">
                                <i class="bi bi-truck"></i> Vận chuyển
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.invoices.index') }}">
                                <i class="bi bi-receipt"></i> Hóa đơn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.coupons.index') }}">
                                <i class="bi bi-ticket-perforated"></i> Mã giảm giá
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.revenue') }}">
                                <i class="bi bi-graph-up"></i> Báo cáo
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản lý mã giảm giá</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i> Thêm mã giảm giá mới
                        </a>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <form action="{{ route('admin.coupons.index') }}" method="GET" class="search-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm mã giảm giá..." name="search" value="{{ request('search') }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    <select class="form-select w-auto" name="status" onchange="this.form.submit()">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Chưa kích hoạt</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                                    </select>
                                    <select class="form-select w-auto" name="type" onchange="this.form.submit()">
                                        <option value="">Tất cả loại mã</option>
                                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                        <option value="free_shipping" {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Miễn phí vận chuyển</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coupons Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Mã</th>
                                        <th scope="col">Mô tả</th>
                                        <th scope="col">Loại</th>
                                        <th scope="col">Giá trị</th>
                                        <th scope="col">Đơn hàng tối thiểu</th>
                                        <th scope="col">Ngày hết hạn</th>
                                        <th scope="col">Đã sử dụng</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="coupon-code">{{ $coupon->code }}</span></td>
                                        <td>{{ $coupon->description }}</td>
                                        <td>
                                            @if($coupon->type === 'percentage')
                                                <span class="badge bg-primary">Phần trăm</span>
                                            @elseif($coupon->type === 'fixed')
                                                <span class="badge bg-info text-dark">Số tiền cố định</span>
                                            @elseif($coupon->type === 'free_shipping')
                                                <span class="badge bg-success">Miễn phí vận chuyển</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->type === 'percentage')
                                                {{ $coupon->value }}%
                                            @elseif($coupon->type === 'fixed')
                                                {{ number_format($coupon->value) }}đ
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->min_order_amount > 0 ? number_format($coupon->min_order_amount) . 'đ' : 'Không có' }}
                                        </td>
                                        <td>{{ $coupon->valid_until ? date('d/m/Y', strtotime($coupon->valid_until)) : 'Không giới hạn' }}</td>
                                        <td>{{ $coupon->used_count }}/{{ $coupon->max_uses ?: 'Không giới hạn' }}</td>
                                        <td>
                                            @if($coupon->valid_until && $coupon->valid_until < now())
                                                <span class="badge badge-expired">Đã hết hạn</span>
                                            @elseif($coupon->is_active)
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Chưa kích hoạt</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($coupon->is_active)
                                                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Vô hiệu hóa">
                                                            <i class="bi bi-toggle-off"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="is_active" value="1">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Kích hoạt">
                                                            <i class="bi bi-toggle-on"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">Không có mã giảm giá nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 