<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SyncProductStockQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ lại tổng tồn kho cho các sản phẩm dựa trên dữ liệu từ bảng inventories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu đồng bộ dữ liệu tồn kho...');
        
        // Lấy tất cả sản phẩm
        $products = Product::with('inventories')->get();
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();
        
        DB::beginTransaction();
        try {
            foreach ($products as $product) {
                // Tính tổng số lượng tồn kho từ bảng inventories
                $totalStock = $product->inventories()->sum('quantity');
                
                // Cập nhật lại tổng tồn kho cho sản phẩm
                $product->stock_quantity = $totalStock;
                
                // Cập nhật trạng thái sản phẩm dựa trên tồn kho
                if ($totalStock > 0 && $product->status === 'out_of_stock') {
                    $product->status = 'active';
                } elseif ($totalStock <= 0 && $product->status === 'active') {
                    $product->status = 'out_of_stock';
                }
                
                $product->save();
                $bar->advance();
            }
            
            DB::commit();
            $bar->finish();
            $this->newLine();
            $this->info('Đã đồng bộ xong dữ liệu tồn kho cho ' . count($products) . ' sản phẩm.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
