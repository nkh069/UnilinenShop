<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RevenueReport;
use Carbon\Carbon;

class UpdateRevenueStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thống kê doanh thu cho dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật thống kê doanh thu...');

        // Tính tổng doanh thu từ đơn hàng hoàn thành
        $today = Carbon::now();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        
        // Tạo báo cáo hàng ngày
        $dailyReport = $this->createOrUpdateDailyReport($today);
        
        // Tạo báo cáo hàng tháng
        $monthlyReport = $this->createOrUpdateMonthlyReport($today);
        
        $this->info('Đã cập nhật thống kê doanh thu thành công!');
        $this->info("Tổng doanh thu hiện tại: " . number_format($totalRevenue, 0, ',', '.') . "đ");
        
        return Command::SUCCESS;
    }
    
    /**
     * Tạo hoặc cập nhật báo cáo doanh thu ngày
     */
    private function createOrUpdateDailyReport(Carbon $date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();
        
        // Lấy dữ liệu đơn hàng trong ngày
        $orders = Order::whereIn('status', ['completed', 'delivered'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $totalRevenue = $orders->sum('total_amount');
        $taxCollected = $orders->sum('tax_amount');
        $shippingFees = $orders->sum('shipping_amount');
        $discounts = $orders->sum('discount_amount');
        $ordersCount = $orders->count();
        
        // Tính tổng số sản phẩm đã bán
        $productsSold = 0;
        foreach ($orders as $order) {
            $productsSold += $order->items()->sum('quantity');
        }
        
        // Tìm báo cáo ngày đã có hoặc tạo mới
        $report = RevenueReport::firstOrNew([
            'report_date' => $date->format('Y-m-d'),
            'period_type' => 'daily',
        ]);
        
        $report->start_date = $startDate;
        $report->end_date = $endDate;
        $report->total_revenue = $totalRevenue;
        $report->cost_of_goods = 0; // Cần tính sau dựa trên giá vốn sản phẩm
        $report->gross_profit = $totalRevenue;
        $report->tax_collected = $taxCollected;
        $report->shipping_fees = $shippingFees;
        $report->discounts = $discounts;
        $report->orders_count = $ordersCount;
        $report->products_sold = $productsSold;
        $report->generated_by = 1; // Admin ID
        
        $report->save();
        
        return $report;
    }
    
    /**
     * Tạo hoặc cập nhật báo cáo doanh thu tháng
     */
    private function createOrUpdateMonthlyReport(Carbon $date)
    {
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        
        // Lấy dữ liệu đơn hàng trong tháng
        $orders = Order::whereIn('status', ['completed', 'delivered'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $totalRevenue = $orders->sum('total_amount');
        $taxCollected = $orders->sum('tax_amount');
        $shippingFees = $orders->sum('shipping_amount');
        $discounts = $orders->sum('discount_amount');
        $ordersCount = $orders->count();
        
        // Tính tổng số sản phẩm đã bán
        $productsSold = 0;
        foreach ($orders as $order) {
            $productsSold += $order->items()->sum('quantity');
        }
        
        // Tìm báo cáo tháng đã có hoặc tạo mới
        $report = RevenueReport::firstOrNew([
            'report_date' => $startDate->format('Y-m-d'),
            'period_type' => 'monthly',
        ]);
        
        $report->start_date = $startDate;
        $report->end_date = $endDate;
        $report->total_revenue = $totalRevenue;
        $report->cost_of_goods = 0; // Cần tính sau dựa trên giá vốn sản phẩm
        $report->gross_profit = $totalRevenue;
        $report->tax_collected = $taxCollected;
        $report->shipping_fees = $shippingFees;
        $report->discounts = $discounts;
        $report->orders_count = $ordersCount;
        $report->products_sold = $productsSold;
        $report->generated_by = 1; // Admin ID
        
        $report->save();
        
        return $report;
    }
}
