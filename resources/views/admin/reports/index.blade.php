@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo cáo</h1>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Báo cáo doanh thu</h6>
                </div>
                <div class="card-body">
                    <p>Xem báo cáo chi tiết về doanh thu theo thời gian, hiển thị tổng doanh thu, số lượng đơn hàng và biểu đồ phân tích doanh thu.</p>
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/2621/2621118.png" alt="Báo cáo doanh thu" style="width: 100px; height: 100px;" class="mb-3">
                    </div>
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-primary btn-block">
                        <i class="bi bi-graph-up"></i> Xem báo cáo doanh thu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Báo cáo sản phẩm</h6>
                </div>
                <div class="card-body">
                    <p>Xem báo cáo về các sản phẩm bán chạy nhất, số lượng bán ra và doanh thu từ từng sản phẩm.</p>
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050235.png" alt="Báo cáo sản phẩm" style="width: 100px; height: 100px;" class="mb-3">
                    </div>
                    <a href="{{ route('admin.reports.products') }}" class="btn btn-success btn-block">
                        <i class="bi bi-boxes"></i> Xem báo cáo sản phẩm
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Báo cáo khách hàng</h6>
                </div>
                <div class="card-body">
                    <p>Xem báo cáo về khách hàng thân thiết, tần suất mua hàng và giá trị đơn hàng trung bình của từng khách hàng.</p>
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/2706/2706891.png" alt="Báo cáo khách hàng" style="width: 100px; height: 100px;" class="mb-3">
                    </div>
                    <a href="{{ route('admin.reports.customers') }}" class="btn btn-info btn-block">
                        <i class="bi bi-people"></i> Xem báo cáo khách hàng
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Báo cáo tồn kho</h6>
                </div>
                <div class="card-body">
                    <p>Xem báo cáo về tình trạng tồn kho, sản phẩm sắp hết hàng và giá trị tồn kho hiện tại.</p>
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/1554/1554401.png" alt="Báo cáo tồn kho" style="width: 100px; height: 100px;" class="mb-3">
                    </div>
                    <a href="#" class="btn btn-warning btn-block">
                        <i class="bi bi-box-seam"></i> Xem báo cáo tồn kho
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 