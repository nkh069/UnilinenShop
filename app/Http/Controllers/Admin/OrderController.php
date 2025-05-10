<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Lọc theo ngày
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sắp xếp
        $query->orderBy('created_at', 'desc');
        
        $orders = $query->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Không cần tạo đơn hàng thủ công từ admin
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Không cần tạo đơn hàng thủ công từ admin
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'orderItems.product', 'payments', 'shipment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string'
        ]);
        
        $order = Order::with('user')->findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        
        // Thêm ghi chú nếu có
        if ($request->has('notes') && !empty($request->notes)) {
            $order->notes = $request->notes;
        }
        
        // Cập nhật timestamps dựa trên trạng thái
        if ($oldStatus != 'delivered' && $request->status == 'delivered') {
            $order->delivered_at = now();
            
            // Cập nhật shipment
            if ($order->shipment) {
                $order->shipment->status = 'delivered';
                $order->shipment->delivered_at = now();
                $order->shipment->save();
            }
            
            // Gửi email thông báo đơn hàng đã giao thành công và khuyến khích đánh giá
            try {
                // Chỉ gửi thông báo nếu đơn hàng đã thanh toán hoặc là COD
                if ($order->payment_status == 'paid' || $order->payment_method == 'cod') {
                    \Mail::to($order->user->email)->send(new \App\Mail\OrderDelivered($order));
                    
                    // Hoặc sử dụng thông báo trong ứng dụng
                    $order->user->notify(new \App\Notifications\OrderDeliveredNotification($order));
                }
            } catch (\Exception $e) {
                // Log lỗi nhưng không dừng quá trình cập nhật trạng thái
                \Log::error('Không thể gửi email đơn hàng đã giao: ' . $e->getMessage());
            }
        } elseif ($oldStatus != 'cancelled' && $request->status == 'cancelled') {
            $order->cancelled_at = now();
        }
        
        $order->save();
        
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Không nên xóa đơn hàng, chỉ thay đổi trạng thái
    }
}
