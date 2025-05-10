<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Category;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        // Tìm kiếm theo mã hoặc mô tả
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->input('status') !== '') {
            if ($request->input('status') === 'expired') {
                $query->where('valid_until', '<', now())
                      ->whereNotNull('valid_until');
            } else {
                $isActive = (int) $request->input('status');
                $query->where('is_active', $isActive)
                      ->where(function ($q) {
                          $q->where('valid_until', '>', now())
                            ->orWhereNull('valid_until');
                      });
            }
        }
        
        // Lọc theo loại
        if ($request->has('type') && $request->input('type') !== '') {
            $query->where('type', $request->input('type'));
        }
        
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.coupons.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);
        
        $coupon = new Coupon();
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->min_order_amount = $request->min_order_amount ?? 0;
        $coupon->max_uses = $request->max_uses;
        $coupon->category_id = $request->category_id;
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->is_public = $request->has('is_public') ? 1 : 0;
        $coupon->valid_from = $request->valid_from;
        $coupon->valid_until = $request->valid_until;
        $coupon->save();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = Coupon::with('category', 'usages.user', 'usages.order')->findOrFail($id);
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.coupons.edit', compact('coupon', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        // Nếu chỉ cập nhật trạng thái kích hoạt
        if ($request->has('is_active') && count($request->all()) <= 3) { // csrf_token, _method và is_active
            $coupon->is_active = $request->input('is_active') ? 1 : 0;
            $coupon->save();
            
            $message = $coupon->is_active ? 'Mã giảm giá đã được kích hoạt.' : 'Mã giảm giá đã bị vô hiệu hóa.';
            return redirect()->route('admin.coupons.index')->with('success', $message);
        }
        
        // Cập nhật đầy đủ
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);
        
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->min_order_amount = $request->min_order_amount ?? 0;
        $coupon->max_uses = $request->max_uses;
        $coupon->category_id = $request->category_id;
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->is_public = $request->has('is_public') ? 1 : 0;
        $coupon->valid_from = $request->valid_from;
        $coupon->valid_until = $request->valid_until;
        $coupon->save();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công.');
    }
}
