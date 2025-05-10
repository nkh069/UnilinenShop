<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Invoice;
use App\Models\OtpVerification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Xử lý mua ngay từ trang chi tiết sản phẩm
        if ($request->has('buy_now') && $request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;
            $size = $request->size;
            $color = $request->color;
            
            // Kiểm tra tồn kho
            $inventory = Inventory::where('product_id', $product->id)
                ->where('size', $size)
                ->where('color', $color)
                ->first();
                
            if (!$inventory || $inventory->quantity < $quantity) {
                return redirect()->route('products.show', $product->slug)
                    ->with('error', 'Số lượng sản phẩm không đủ trong kho.');
            }
            
            // Tạo giỏ hàng tạm thời chỉ với sản phẩm này
            $price = $product->getFinalPrice();
            if ($price <= 0) {
                $price = $product->price > 0 ? $product->price : 89000; // Giá mặc định nếu không có giá
            }
            
            $tempCart = [
                $product->id . '-' . $size . '-' . $color => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'size' => $size,
                    'color' => $color,
                    'image' => $product->images->first() ? $product->images->first()->image_path : null,
                ]
            ];
            
            // Sử dụng giỏ hàng tạm thời thay vì giỏ hàng trong session
            $cart = $tempCart;
        } else {
            // Sử dụng giỏ hàng từ session như bình thường
            $cart = Session::get('cart', []);
            
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }
        }
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $coupon = Session::get('coupon');
        $discount = 0;
        
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValid()) {
                // Kiểm tra nếu mã giảm giá áp dụng cho danh mục cụ thể
                if ($couponModel->category_id) {
                    $categoryAmount = 0;
                    foreach ($cart as $item) {
                        $product = Product::find($item['id']);
                        if ($product && $product->category_id == $couponModel->category_id) {
                            $categoryAmount += $item['price'] * $item['quantity'];
                        }
                    }
                    
                    // Chỉ tính giảm giá cho sản phẩm thuộc danh mục được chỉ định
                    $discount = $couponModel->calculateDiscount($categoryAmount);
                } else {
                    // Giảm giá cho toàn bộ đơn hàng
                    $discount = $couponModel->calculateDiscount($subtotal);
                }
            } else {
                // Xóa mã giảm giá không hợp lệ
                Session::forget('coupon');
            }
        }
        
        $shippingCost = 30000; // Fixed shipping cost (VND)
        $tax = ($subtotal - $discount) * 0.10; // 10% tax
        $total = $subtotal - $discount + $shippingCost + $tax;
        
        // Đánh dấu là đơn hàng mua ngay nếu có
        $isBuyNow = $request->has('buy_now');
        
        return view('shop.checkout', compact('cart', 'subtotal', 'discount', 'shippingCost', 'tax', 'total', 'coupon', 'isBuyNow'));
    }
    
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
        
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
        
        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }
        
        if (!$coupon->isValid()) {
            return back()->with('error', 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực.');
        }
        
        $cart = Session::get('cart', []);
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        if ($subtotal < $coupon->min_order_amount) {
            return back()->with('error', "Đơn hàng phải có giá trị tối thiểu " . number_format($coupon->min_order_amount, 0, ',', '.') . " VND để sử dụng mã giảm giá này.");
        }
        
        // Kiểm tra nếu mã áp dụng cho danh mục cụ thể
        if ($coupon->category_id) {
            $categoryApplicable = false;
            foreach ($cart as $item) {
                // Kiểm tra xem sản phẩm có thuộc danh mục được áp dụng không
                $product = Product::find($item['id']);
                if ($product && $product->category_id == $coupon->category_id) {
                    $categoryApplicable = true;
                    break;
                }
            }
            
            if (!$categoryApplicable) {
                return back()->with('error', 'Mã giảm giá chỉ áp dụng cho sản phẩm thuộc danh mục nhất định.');
            }
        }
        
        // Tính giảm giá dựa trên loại
        $discount = $coupon->calculateDiscount($subtotal);
        
        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);
        
        return back()->with('success', 'Mã giảm giá đã được áp dụng. Giảm ' . number_format($discount, 0, ',', '.') . ' VND.');
    }
    
    public function removeCoupon()
    {
        Session::forget('coupon');
        return back()->with('success', 'Mã giảm giá đã được xóa.');
    }
    
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,credit_card,momo,bank_transfer,paypal',
            'notes' => 'nullable|string',
        ]);
        
        // Xác định có phải đơn hàng mua ngay không
        $isBuyNow = $request->has('is_buy_now') && $request->is_buy_now == 1;
        
        if ($isBuyNow) {
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            $size = $request->size;
            $color = $request->color;
            
            // Tìm sản phẩm
            $product = Product::with('images')->findOrFail($productId);
            
            // Kiểm tra tồn kho
            $inventory = Inventory::where('product_id', $product->id)
                ->where('size', $size)
                ->where('color', $color)
                ->first();
                
            if (!$inventory || $inventory->quantity < $quantity) {
                return redirect()->route('products.show', $product->slug)
                    ->with('error', 'Số lượng sản phẩm không đủ trong kho.');
            }
            
            // Tạo cart tạm thời
            $price = $product->getFinalPrice();
            if ($price <= 0) {
                $price = $product->price > 0 ? $product->price : 89000; // Giá mặc định nếu không có giá
            }
            
            $cart = [
                $product->id . '-' . $size . '-' . $color => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'size' => $size,
                    'color' => $color,
                    'image' => $product->images->first() ? $product->images->first()->image_path : null,
                ]
            ];
        } else {
            $cart = Session::get('cart', []);
            
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }
        }
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Get applied coupon if any
        $coupon = Session::get('coupon');
        $discount = 0;
        $couponId = null;
        
        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValid()) {
                $discount = $couponModel->calculateDiscount($subtotal);
                $couponId = $couponModel->id;
            }
        }
        
        $shippingCost = 30000; // Fixed shipping cost (VND)
        $tax = ($subtotal - $discount) * 0.10; // 10% tax
        $total = $subtotal - $discount + $shippingCost + $tax;
        
        DB::beginTransaction();
        
        try {
            // Create order
            $order = new Order();
            $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            $order->user_id = Auth::check() ? Auth::id() : 1; // Guest orders assigned to default user
            $order->total_amount = $total;
            $order->tax_amount = $tax;
            $order->shipping_amount = $shippingCost;
            $order->discount_amount = $discount;
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->shipping_address = $request->address;
            $order->shipping_city = $request->city;
            $order->shipping_country = $request->country;
            $order->shipping_postal_code = $request->postal_code;
            $order->shipping_phone = $request->phone;
            $order->notes = $request->notes;
            $order->save();
            
            // Create order items and update inventory
            foreach ($cart as $itemId => $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['id'];
                $orderItem->product_name = $item['name'];
                $orderItem->sku = $item['id']; // This should be updated with actual SKU
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $item['price'] > 0 ? $item['price'] : 89000; // Đảm bảo giá luôn > 0
                $orderItem->subtotal = $orderItem->unit_price * $item['quantity'];
                $orderItem->size = $item['size'];
                $orderItem->color = $item['color'];
                $orderItem->save();
                
                // Update inventory
                $inventory = Inventory::where('product_id', $item['id'])
                    ->where('size', $item['size'])
                    ->where('color', $item['color'])
                    ->first();
                    
                if ($inventory) {
                    $inventory->quantity -= $item['quantity'];
                    $inventory->save();
                }
            }
            
            // Create shipment
            $shipment = new Shipment();
            $shipment->order_id = $order->id;
            $shipment->status = 'pending';
            $shipment->shipping_method = 'standard';
            $shipment->shipping_cost = $shippingCost;
            $shipment->save();
            
            // Create invoice
            $invoice = new Invoice();
            $invoice->invoice_number = 'INV-' . strtoupper(Str::random(8));
            $invoice->order_id = $order->id;
            $invoice->user_id = $order->user_id;
            $invoice->total_amount = $total;
            $invoice->tax_amount = $tax;
            $invoice->shipping_amount = $shippingCost;
            $invoice->discount_amount = $discount;
            $invoice->issue_date = now();
            $invoice->due_date = now()->addDays(7);
            $invoice->status = 'unpaid';
            $invoice->save();
            
            // Apply coupon usage if used
            if ($couponId) {
                $couponUsage = new CouponUsage();
                $couponUsage->coupon_id = $couponId;
                $couponUsage->user_id = $order->user_id;
                $couponUsage->order_id = $order->id;
                $couponUsage->discount_amount = $discount;
                $couponUsage->save();
                
                // Update coupon usage count
                $couponModel = Coupon::find($couponId);
                $couponModel->used_count += 1;
                $couponModel->save();
                
                // Clear session coupon
                Session::forget('coupon');
            }
            
            // Send OTP verification for order if user is authenticated
            if (Auth::check()) {
                $otp = rand(100000, 999999);
                
                OtpVerification::create([
                    'user_id' => Auth::id(),
                    'otp_code' => $otp,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone,
                    'type' => 'order_confirmation',
                    'expires_at' => now()->addMinutes(15),
                ]);
                
                // TODO: Send OTP via email/SMS
            }
            
            DB::commit();
            
            // Clear cart only if not a buy now order
            if (!$isBuyNow) {
                Session::forget('cart');
            }
            
            // Redirect to payment gateway if not COD
            if ($request->payment_method != 'cod') {
                return redirect()->route('payment.process', ['order' => $order->id]);
            }
            
            return redirect()->route('order.confirmation', ['id' => $order->id])
                ->with('success', 'Đơn hàng của bạn đã được đặt thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }
}
