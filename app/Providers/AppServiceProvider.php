<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sử dụng Bootstrap cho phân trang
        Paginator::useBootstrap();
        
        // Thiết lập múi giờ và ngôn ngữ mặc định cho Carbon
        Carbon::setLocale(config('app.locale'));
        
        // Thiết lập định dạng hiển thị ngày tháng kiểu Việt Nam
        Carbon::macro('formatVN', function () {
            return $this->format('d/m/Y');
        });
        
        Carbon::macro('formatVNDateTime', function () {
            return $this->format('d/m/Y H:i');
        });
    }
}
