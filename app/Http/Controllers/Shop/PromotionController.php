<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index()
    {
        // Lấy danh sách các mã giảm giá còn hiệu lực và công khai
        $coupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->where('valid_until', '>', Carbon::now())
                    ->orWhereNull('valid_until');
            })
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shop.promotions.index', compact('coupons'));
    }
}
