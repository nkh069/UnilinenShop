<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        
        // Khởi tạo các biến để hiển thị trong view
        $cartItems = [];
        $subtotal = 0;
        
        // Nếu giỏ hàng không rỗng, chuyển đổi dữ liệu từ session sang object
        foreach ($cart as $itemId => $item) {
            $product = Product::with('images')->find($item['id']);
            if ($product) {
                $cartItem = new \stdClass();
                $cartItem->id = $itemId;
                $cartItem->product = $product;
                $cartItem->quantity = $item['quantity'];
                $cartItem->price = $item['price'];
                $cartItem->size = $item['size'] ?? null;
                $cartItem->color = $item['color'] ?? null;
                
                $cartItems[] = $cartItem;
                $subtotal += $item['price'] * $item['quantity'];
            }
        }
        
        // Kiểm tra xem có mã giảm giá nào được áp dụng không
        $coupon = Session::get('coupon');
        $discount = 0;
        
        if ($coupon) {
            // Tính toán giảm giá dựa trên mã giảm giá
            $discount = $coupon['value'] ?? 0;
        }
        
        // Tính thuế và tổng tiền
        $tax = ($subtotal - $discount) * 0.1; // 10% thuế
        $total = $subtotal - $discount + $tax;
        
        return view('shop.cart', compact('cartItems', 'subtotal', 'discount', 'tax', 'total'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);
        
        $product = Product::with('images')->findOrFail($request->product_id);
        
        // Check inventory
        $inventory = Inventory::where('product_id', $product->id);
        
        if ($request->size) {
            $inventory->where('size', $request->size);
        }
        
        if ($request->color) {
            $inventory->where('color', $request->color);
        }
        
        $inventory = $inventory->first();
            
        if (!$inventory || $inventory->quantity < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ.');
        }
        
        $cart = Session::get('cart', []);
        
        $itemId = $product->id;
        if ($request->size) $itemId .= '-' . $request->size;
        if ($request->color) $itemId .= '-' . $request->color;
        
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $request->quantity;
        } else {
            $productPrice = $product->getFinalPrice();
            if ($productPrice <= 0) {
                $productPrice = $product->price > 0 ? $product->price : 89000; // Giá mặc định nếu không có giá
            }
            
            $cart[$itemId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $productPrice,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
                'image' => $product->images->first() ? $product->images->first()->image_path : null,
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->id])) {
            // Check inventory
            $productId = explode('-', $request->id)[0];
            $size = $cart[$request->id]['size'] ?? null;
            $color = $cart[$request->id]['color'] ?? null;
            
            $inventory = Inventory::where('product_id', $productId);
            
            if ($size) {
                $inventory->where('size', $size);
            }
            
            if ($color) {
                $inventory->where('color', $color);
            }
            
            $inventory = $inventory->first();
                
            if (!$inventory || $inventory->quantity < $request->quantity) {
                return back()->with('error', 'Số lượng sản phẩm không đủ.');
            }
            
            $cart[$request->id]['quantity'] = $request->quantity;
            Session::put('cart', $cart);
            
            return back()->with('success', 'Giỏ hàng đã được cập nhật.');
        }
        
        return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            Session::put('cart', $cart);
            
            return back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }
        
        return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
    
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Giỏ hàng đã được xóa.');
    }
    
    public function getCount()
    {
        $cart = Session::get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'count' => $count,
        ]);
    }
}
