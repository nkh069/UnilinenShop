<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponTest;
use App\Models\Category;
use Carbon\Carbon;

class CouponTestController extends Controller
{
    public function index()
    {
        $coupons = CouponTest::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.coupons_test.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.coupons_test.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons_test',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);
        
        $coupon = new CouponTest();
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->min_order_amount = $request->min_order_amount ?? 0;
        $coupon->max_uses = $request->max_uses;
        $coupon->category_id = $request->category_id;
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->valid_from = $request->valid_from;
        $coupon->valid_until = $request->valid_until;
        $coupon->save();
        
        return redirect()->route('admin.coupons_test.index')
            ->with('success', 'Mã giảm giá test đã được tạo thành công.');
    }

    public function edit($id)
    {
        $coupon = CouponTest::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.coupons_test.edit', compact('coupon', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $coupon = CouponTest::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|unique:coupons_test,code,' . $id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);
        
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->min_order_amount = $request->min_order_amount ?? 0;
        $coupon->max_uses = $request->max_uses;
        $coupon->category_id = $request->category_id;
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->valid_from = $request->valid_from;
        $coupon->valid_until = $request->valid_until;
        $coupon->save();
        
        return redirect()->route('admin.coupons_test.index')
            ->with('success', 'Mã giảm giá test đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $coupon = CouponTest::findOrFail($id);
        $coupon->delete();
        
        return redirect()->route('admin.coupons_test.index')
            ->with('success', 'Mã giảm giá test đã được xóa thành công.');
    }
}
