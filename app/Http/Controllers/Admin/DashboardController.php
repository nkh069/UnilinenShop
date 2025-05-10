<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\RevenueReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Tính tổng số đơn hàng
        $totalOrders = Order::count();
        
        // Tính tổng doanh thu - chỉ tính các đơn hàng đã hoàn thành và đã giao
        // Đơn hàng đang giao chưa được tính vào doanh thu thực tế
        $totalRevenue = Order::whereIn('status', ['completed', 'delivered'])
                            ->sum('total_amount');
        
        // Tính tổng số người dùng (khách hàng)
        $totalUsers = User::where('role', 'customer')->count();
        
        // Tính tổng số sản phẩm
        $totalProducts = Product::count();
        
        // Thống kê đơn hàng theo trạng thái
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        // Mảng tên tháng tiếng Việt
        $vietnameseMonths = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4', 5 => 'Tháng 5', 6 => 'Tháng 6',
            7 => 'Tháng 7', 8 => 'Tháng 8', 9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];
        
        // Lấy dữ liệu doanh thu 6 tháng gần nhất
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(5)->startOfMonth(); // Lấy 6 tháng, bao gồm tháng hiện tại
        
        // Doanh thu thực tế chỉ tính đơn hàng đã hoàn thành và đã giao
        $monthlyRevenue = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereIn('status', ['completed', 'delivered'])
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Xử lý trường hợp có tháng không có doanh thu
        $allMonths = [];
        $currentDate = clone $startDate;
        
        // Tạo mảng chứa tất cả các tháng, kể cả tháng không có doanh thu
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthKey = $year . '-' . $month;
            $allMonths[$monthKey] = [
                'year' => $year,
                'month' => $month,
                'month_name' => $vietnameseMonths[$month] . ' ' . $year,
                'total' => 0
            ];
            $currentDate->addMonth();
        }
        
        // Điền dữ liệu doanh thu vào các tháng có doanh thu
        foreach ($monthlyRevenue as $revenue) {
            $monthKey = $revenue->year . '-' . $revenue->month;
            if (isset($allMonths[$monthKey])) {
                $allMonths[$monthKey]['total'] = $revenue->total;
            }
        }
        
        // Chuyển đổi mảng kết hợp sang mảng tuần tự cho view
        $revenueData = [];
        foreach ($allMonths as $data) {
            $revenueData[] = [
                'month' => $data['month_name'],
                'total' => $data['total']
            ];
        }
        
        // Lấy các sản phẩm đã hết hàng hoặc không hoạt động
        $lowStockProducts = Product::where('status', 'inactive')
            ->orWhere('status', 'out_of_stock')
            ->limit(5)
            ->get();
        
        // Lấy 5 đơn hàng gần đây nhất 
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'totalProducts',
            'orderStats',
            'revenueData',
            'lowStockProducts',
            'recentOrders'
        ));
    }
} 