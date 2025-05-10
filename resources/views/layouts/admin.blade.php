<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản trị - UniLinen Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #3d5a80;
            --secondary-color: #98c1d9;
            --accent-color: #ee6c4d;
            --dark-color: #293241;
            --light-color: #e0fbfc;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--dark-color);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1030;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .navbar-brand {
            color: white;
            font-weight: 700;
            margin: 0;
        }
        
        .sidebar .navbar-brand span {
            color: var(--accent-color);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-item {
            width: 100%;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.05);
            border-left-color: var(--accent-color);
        }
        
        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.05);
            border-left-color: var(--accent-color);
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-nav .dropdown-menu {
            position: static !important;
            background-color: rgba(0,0,0,0.2);
            border: none;
            border-radius: 0;
            margin: 0;
            padding: 0;
            width: 100%;
            transform: none !important;
        }
        
        .sidebar-nav .dropdown-item {
            color: rgba(255,255,255,0.8);
            padding: 0.5rem 1rem 0.5rem 3.25rem;
        }
        
        .sidebar-nav .dropdown-item:hover,
        .sidebar-nav .dropdown-item:focus {
            color: white;
            background-color: rgba(255,255,255,0.05);
        }
        
        .sidebar-nav .dropdown-toggle::after {
            margin-left: auto;
        }
        
        /* Toggle Button for Mobile */
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1040;
            display: none;
            background-color: var(--dark-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            transition: margin-left 0.3s;
        }
        
        /* Top Navigation */
        .top-navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: var(--header-height);
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1020;
            padding: 0 1rem;
        }
        
        .content-wrapper {
            margin-top: var(--header-height);
        }
        
        /* Footer */
        .admin-footer {
            background-color: white;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 0.875rem;
            color: #6c757d;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 500;
        }
        
        /* Tables */
        .table th {
            font-weight: 500;
            background-color: rgba(0,0,0,0.02);
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .main-content, 
            .top-navbar,
            .admin-footer {
                margin-left: 0;
            }
            
            body.sidebar-open .main-content,
            body.sidebar-open .top-navbar,
            body.sidebar-open .admin-footer {
                margin-left: var(--sidebar-width);
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">UniLinen<span>Shop</span></a>
            <button class="btn btn-sm text-white d-lg-none" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <ul class="sidebar-nav nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tổng quan</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box"></i>
                    <span>Sản phẩm</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
                    <i class="bi bi-palette"></i>
                    <span>Thuộc tính sản phẩm</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-list-ul"></i>
                    <span>Danh mục</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-cart"></i>
                    <span>Đơn hàng</span>
                </a>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-people"></i>
                    <span>Người dùng</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="usersDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Tất cả người dùng</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.create') }}"><i class="bi bi-person-plus"></i> Thêm người dùng</a></li>
                </ul>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="#" id="inventoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-box-seam"></i>
                    <span>Kho hàng</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="inventoryDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.inventory.index') }}"><i class="bi bi-box-seam"></i> Quản lý kho</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.inventory.low-stock') }}"><i class="bi bi-exclamation-triangle"></i> Sắp hết hàng</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.inventory.movements') }}"><i class="bi bi-clock-history"></i> Lịch sử tồn kho</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">
                    <i class="bi bi-building"></i>
                    <span>Nhà cung cấp</span>
                </a>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}" href="#" id="shipmentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-truck"></i>
                    <span>Vận chuyển</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="shipmentsDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.shipments.index') }}"><i class="bi bi-truck"></i> Tất cả đơn vận chuyển</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.shipments.pending') }}"><i class="bi bi-clock-history"></i> Đang xử lý</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.shipments.unassigned') }}"><i class="bi bi-question-circle"></i> Chưa phân công</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.shippers.*') ? 'active' : '' }}" href="{{ route('admin.shippers.index') }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Người vận chuyển</span>
                </a>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}" href="#" id="invoicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-receipt"></i>
                    <span>Hóa đơn</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="invoicesDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.invoices.index') }}"><i class="bi bi-receipt"></i> Tất cả hóa đơn</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.invoices.create') }}"><i class="bi bi-plus-circle"></i> Tạo hóa đơn mới</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Mã giảm giá</span>
                </a>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bar-chart"></i>
                    <span>Báo cáo</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.reports.revenue') }}"><i class="bi bi-graph-up"></i> Doanh thu</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.reports.products') }}"><i class="bi bi-box"></i> Sản phẩm bán chạy</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="bi bi-house"></i>
                    <span>Về trang chủ</span>
                </a>
            </li>
        </ul>
        
        <div class="mt-auto p-3 border-top border-secondary">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Hồ sơ</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Top Navigation -->
    <div class="top-navbar d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0">{{ ucwords(str_replace('.', ' ', request()->route()->getName())) }}</h5>
        </div>
        <div>
            <span class="text-muted">{{ now()->format('l, d F Y') }}</span>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="admin-footer">
        <div class="container-fluid">
            <p class="mb-0">&copy; {{ date('Y') }} UniLinen Shop. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const body = document.body;
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    body.classList.toggle('sidebar-open');
                });
            }
            
            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    body.classList.remove('sidebar-open');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);
                
                if (window.innerWidth < 992 && !isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    body.classList.remove('sidebar-open');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 