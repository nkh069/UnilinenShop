@extends('layouts.admin')

@section('title', 'Báo cáo khách hàng')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo cáo khách hàng</h1>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Lọc dữ liệu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.customers') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? now()->subMonths(6)->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate ?? now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số khách hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Khách hàng mới trong kỳ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newCustomers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Giá trị trung bình mỗi khách hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageCustomerValue ?? 0, 0, ',', '.') }}đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Khách hàng mới theo thời gian</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="newCustomersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố khách hàng theo tỉnh/thành</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="customersByRegionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small" id="regionLegend">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top khách hàng có giá trị đơn hàng cao nhất</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên khách hàng</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng giá trị</th>
                            <th>Đơn hàng gần nhất</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers ?? [] as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->order_count }}</td>
                            <td>{{ number_format($customer->total_spent, 0, ',', '.') }}đ</td>
                            <td>{{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var newCustomersData = @json($newCustomersData ?? []);
    var customersByRegion = @json($customersByRegion ?? []);
    
    // Chart cho khách hàng mới
    var newCustomersLabels = newCustomersData.map(item => {
        var date = new Date(item.date);
        return date.toLocaleDateString('vi-VN');
    });
    var newCustomersValues = newCustomersData.map(item => item.count);
    
    var ctx1 = document.getElementById('newCustomersChart').getContext('2d');
    var newCustomersChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: newCustomersLabels,
            datasets: [{
                label: 'Khách hàng mới',
                data: newCustomersValues,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // Chart cho phân bố khách hàng theo tỉnh/thành
    if(customersByRegion.length > 0) {
        var regions = customersByRegion.map(item => item.region);
        var regionCounts = customersByRegion.map(item => item.count);
        var regionColors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
            '#5a5c69', '#6610f2', '#6f42c1', '#fd7e14', '#20c9a6'
        ];
        
        var ctx2 = document.getElementById('customersByRegionChart').getContext('2d');
        var customersByRegionChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: regions,
                datasets: [{
                    data: regionCounts,
                    backgroundColor: regionColors.slice(0, regions.length),
                    hoverBackgroundColor: regionColors.slice(0, regions.length).map(color => color + 'dd'),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
        
        // Tạo legend tùy chỉnh
        var legendHtml = '';
        for (var i = 0; i < regions.length; i++) {
            legendHtml += '<span class="mr-2"><i class="fas fa-circle" style="color: ' + 
                regionColors[i] + '"></i> ' + regions[i] + '</span>';
        }
        document.getElementById('regionLegend').innerHTML = legendHtml;
    }
</script>
@endpush
