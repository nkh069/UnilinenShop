<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('shop.orders.index', compact('orders'));
    }
    
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product', 'shipment'])
            ->firstOrFail();
            
        return view('shop.orders.show', compact('order'));
    }
    
    public function confirmation($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items', 'invoice'])
            ->firstOrFail();
            
        return view('shop.orders.confirmation', compact('order'));
    }
    
    public function cancel(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items'])
            ->firstOrFail();
            
        // Only allow cancellation for pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái đang chờ xử lý.');
        }
        
        $order->status = 'cancelled';
        $order->cancelled_at = now();
        $order->save();
        
        // Hoàn trả số lượng sản phẩm vào kho
        foreach ($order->items as $item) {
            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->where('color', $item->color)
                ->first();
            
            if ($inventory) {
                $inventory->quantity += $item->quantity;
                $inventory->save();
                
                // Tạo movement ghi lại lịch sử cập nhật kho
                \App\Models\InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'reason' => 'order_cancelled',
                    'reference' => 'Đơn hàng #' . $order->order_number . ' bị hủy',
                ]);
            }
        }
        
        // Update invoice
        if ($order->invoice) {
            $order->invoice->status = 'cancelled';
            $order->invoice->save();
        }
        
        return back()->with('success', 'Đơn hàng đã được hủy thành công và sản phẩm đã được hoàn trả vào kho.');
    }
    
    public function downloadInvoice($invoiceNumber)
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->whereHas('order', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['order.items', 'order.user'])
            ->firstOrFail();
            
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    public function trackOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['shipment'])
            ->firstOrFail();
            
        if (!$order->shipment) {
            return back()->with('error', 'Thông tin vận chuyển không có sẵn cho đơn hàng này.');
        }
        
        return view('shop.orders.tracking', compact('order'));
    }
    
    public function verifyOtp(Request $request, $orderNumber)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);
        
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $otpVerification = \App\Models\OtpVerification::where('user_id', Auth::id())
            ->where('otp_code', $request->otp_code)
            ->where('type', 'order_confirmation')
            ->where('is_verified', false)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$otpVerification) {
            return back()->with('error', 'Mã OTP không hợp lệ hoặc đã hết hạn.');
        }
        
        $otpVerification->verify();
        
        $order->status = 'processing';
        $order->save();
        
        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Đơn hàng đã được xác nhận thành công.');
    }
}
