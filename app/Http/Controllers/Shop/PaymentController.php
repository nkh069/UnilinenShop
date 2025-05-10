<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if the order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Đơn hàng này đã được thanh toán.');
        }
        
        // Route based on payment method
        switch ($order->payment_method) {
            case 'credit_card':
                return $this->processCreditCard($order);
            case 'momo':
                return $this->processMomo($order);
            case 'bank_transfer':
                return $this->processBankTransfer($order);
            case 'paypal':
                return $this->processPaypal($order);
            default:
                return redirect()->route('orders.show', $order->order_number)
                    ->with('error', 'Phương thức thanh toán không được hỗ trợ.');
        }
    }
    
    protected function processCreditCard(Order $order)
    {
        // In a real application, integrate with a payment gateway like Stripe
        // For demo purposes, we'll just show a form
        return view('shop.payments.credit-card', compact('order'));
    }
    
    protected function processMomo(Order $order)
    {
        // Momo Payment Integration
        // For demo purposes, we'll just show a simulation
        return view('shop.payments.momo', compact('order'));
    }
    
    protected function processBankTransfer(Order $order)
    {
        // Show bank details
        return view('shop.payments.bank-transfer', compact('order'));
    }
    
    protected function processPaypal(Order $order)
    {
        // PayPal Integration
        // For demo purposes, we'll just show a simulation
        return view('shop.payments.paypal', compact('order'));
    }
    
    public function completePayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // In a real application, validate payment with gateway
        // For demo purposes, we'll just update the order
        
        $order->payment_status = 'paid';
        $order->payment_id = 'PAYMENT-' . strtoupper(uniqid());
        $order->save();
        
        // Update the invoice
        if ($order->invoice) {
            $order->invoice->status = 'paid';
            $order->invoice->save();
        }
        
        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Thanh toán đã được hoàn tất thành công!');
    }
    
    public function cancelPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->payment_status = 'failed';
        $order->save();
        
        return redirect()->route('orders.show', $order->order_number)
            ->with('error', 'Thanh toán đã bị hủy.');
    }
    
    public function paymentCallback(Request $request)
    {
        // This method would handle callbacks from payment gateways
        Log::info('Payment Callback', $request->all());
        
        // Extract order ID from the callback data
        // The format will depend on the payment gateway
        $orderId = $request->input('order_id');
        $paymentStatus = $request->input('status');
        $paymentId = $request->input('payment_id');
        
        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Invalid order ID']);
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
        
        if ($paymentStatus === 'success') {
            $order->payment_status = 'paid';
            $order->payment_id = $paymentId;
            $order->save();
            
            // Update invoice
            if ($order->invoice) {
                $order->invoice->status = 'paid';
                $order->invoice->save();
            }
            
            return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
        } else {
            $order->payment_status = 'failed';
            $order->save();
            
            return response()->json(['success' => false, 'message' => 'Payment failed']);
        }
    }
}
