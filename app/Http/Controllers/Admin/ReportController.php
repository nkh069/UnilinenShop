<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Hiển thị báo cáo doanh thu
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Mảng tên tháng tiếng Việt
        $vietnameseMonths = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4', 5 => 'Tháng 5', 6 => 'Tháng 6',
            7 => 'Tháng 7', 8 => 'Tháng 8', 9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];
        
        // Nhóm doanh thu theo ngày, chỉ tính đơn hàng đã hoàn thành và đã giao
        $revenueData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereIn('status', ['completed', 'delivered'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Định dạng lại ngày để hiển thị rõ ràng hơn
        foreach ($revenueData as $data) {
            $date = Carbon::parse($data->date);
            $day = $date->format('d');
            $monthNumber = $date->format('n');
            $year = $date->format('Y');
            
            $data->formatted_date = $day . ' ' . $vietnameseMonths[$monthNumber] . ' ' . $year;
        }
        
        $totalRevenue = $revenueData->sum('total');
        $orderCount = Order::whereIn('status', ['completed', 'delivered'])
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                          ->count();
        
        return view('admin.reports.revenue', compact('revenueData', 'totalRevenue', 'orderCount', 'startDate', 'endDate'));
    }

    /**
     * Hiển thị báo cáo sản phẩm
     */
    public function products(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $topProducts = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            )
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();
        
        return view('admin.reports.products', compact('topProducts', 'startDate', 'endDate'));
    }

    /**
     * Hiển thị báo cáo bán hàng
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Mảng tên tháng tiếng Việt
        $vietnameseMonths = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4', 5 => 'Tháng 5', 6 => 'Tháng 6',
            7 => 'Tháng 7', 8 => 'Tháng 8', 9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];
        
        // Chuyển đổi sang đối tượng Carbon để xử lý ngày tháng
        $startDateObj = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        
        // Chỉ tính doanh thu từ đơn hàng đã hoàn thành và đã giao
        $salesData = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as order_count, SUM(total_amount) as total_sales')
            ->whereIn('status', ['completed', 'delivered'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Xử lý trường hợp có tháng không có doanh thu
        $allMonths = [];
        $currentDate = clone $startDateObj->startOfMonth();
        
        // Tạo mảng chứa tất cả các tháng trong khoảng thời gian lọc
        while ($currentDate <= $endDateObj) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthKey = $year . '-' . $month;
            $allMonths[$monthKey] = (object)[
                'year' => $year,
                'month' => $month,
                'order_count' => 0,
                'total_sales' => 0,
                'formatted_month' => $vietnameseMonths[$month] . ' ' . $year
            ];
            $currentDate->addMonth();
        }
        
        // Điền dữ liệu vào các tháng có doanh thu
        foreach ($salesData as $data) {
            $monthKey = $data->year . '-' . $data->month;
            if (isset($allMonths[$monthKey])) {
                $allMonths[$monthKey]->order_count = $data->order_count;
                $allMonths[$monthKey]->total_sales = $data->total_sales;
            }
        }
        
        // Chuyển đổi mảng kết hợp sang mảng tuần tự
        $salesDataComplete = array_values($allMonths);
        
        $totalSales = array_sum(array_column($salesDataComplete, 'total_sales'));
        $totalOrders = array_sum(array_column($salesDataComplete, 'order_count'));
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        return view('admin.reports.sales', compact('salesDataComplete', 'totalSales', 'totalOrders', 'averageOrderValue', 'startDate', 'endDate'));
    }

    /**
     * Hiển thị báo cáo khách hàng
     */
    public function customers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('MAX(orders.created_at) as last_order_date')
            )
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();
        
        $totalCustomers = User::where('role', 'customer')->count();
        
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
        
        $newCustomersData = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $customersByRegion = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select(
                DB::raw('users.province as region'),
                DB::raw('COUNT(DISTINCT users.id) as count')
            )
            ->where('users.role', 'customer')
            ->whereNotNull('users.province')
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->groupBy('region')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $averageCustomerValue = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                DB::raw('AVG(total_amount) as average')
            )
            ->where('users.role', 'customer')
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->first()->average ?? 0;
        
        return view('admin.reports.customers', compact(
            'topCustomers', 
            'totalCustomers',
            'newCustomers', 
            'newCustomersData',
            'customersByRegion',
            'averageCustomerValue',
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
