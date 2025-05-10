<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            // Ghi log dữ liệu đầu vào để debug
            \Illuminate\Support\Facades\Log::debug('Dữ liệu đánh giá nhận được: ' . json_encode($request->all()));

            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|min:10|max:1000',
                'pros' => 'nullable|string|max:500',
                'cons' => 'nullable|string|max:500',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $product = Product::findOrFail($id);
            \Illuminate\Support\Facades\Log::debug('Tìm thấy sản phẩm: ' . $product->id . ' - ' . $product->name);
            
            // Check if user has already reviewed this product
            $existingReview = ProductReview::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();
            
            if ($existingReview) {
                return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
            }
            
            // Tạm thời bỏ qua kiểm tra đã mua hàng (đặt true để luôn cho phép đánh giá)
            $skipPurchaseCheck = true;
            
            // Check if user has purchased this product (optional for verified purchase)
            try {
                $hasPurchased = $skipPurchaseCheck ? true : OrderItem::whereHas('order', function($query) {
                        $query->where('user_id', Auth::id())
                            ->whereIn('status', ['delivered', 'completed']);
                    })
                    ->where('product_id', $product->id)
                    ->exists();
            } catch (\Exception $e) {
                // Log lỗi
                \Illuminate\Support\Facades\Log::error('Lỗi kiểm tra mua hàng trong đánh giá: ' . $e->getMessage());
                // Cho phép đánh giá nếu có lỗi
                $hasPurchased = true;
            }
            
            $review = new ProductReview();
            $review->product_id = $product->id;
            $review->user_id = Auth::id();
            $review->rating = $request->rating;
            $review->comment = $request->comment; // Lưu vào trường comment
            $review->review = $request->comment; // Lưu cả vào trường review để tương thích với cả hai trường
            $review->pros = $request->pros;
            $review->cons = $request->cons;
            $review->is_verified_purchase = $hasPurchased;
            $review->is_approved = true; // Auto-approve for now
            
            \Illuminate\Support\Facades\Log::debug('Đã tạo đối tượng đánh giá, chuẩn bị lưu hình ảnh');
            
            // Lưu hình ảnh nếu có
            $imagesPaths = [];
            try {
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('reviews/' . $product->id, 'public');
                        \Illuminate\Support\Facades\Log::debug('Đã lưu hình ảnh: ' . $path);
                        $imagesPaths[] = $path;
                    }
                    $review->images = $imagesPaths;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi khi lưu hình ảnh: ' . $e->getMessage());
                // Tiếp tục mà không có hình ảnh
            }
            
            // Lưu đánh giá vào cơ sở dữ liệu
            \Illuminate\Support\Facades\Log::debug('Chuẩn bị lưu đánh giá vào cơ sở dữ liệu');
            $review->save();
            \Illuminate\Support\Facades\Log::debug('Đã lưu đánh giá thành công');
            
            // Cập nhật điểm thưởng cho người dùng nếu là đơn hàng đã xác nhận
            if ($hasPurchased && !$skipPurchaseCheck) {
                try {
                    $user = Auth::user();
                    $user->points += 100; // Thưởng 100 điểm cho mỗi đánh giá
                    $user->save();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Lỗi cập nhật điểm thưởng: ' . $e->getMessage());
                }
            }
            
            \Illuminate\Support\Facades\Log::debug('Đánh giá sản phẩm thành công');
            return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!')->withFragment('reviews');
        } catch (\Exception $e) {
            // Ghi chi tiết lỗi hơn
            \Illuminate\Support\Facades\Log::error('Lỗi khi lưu đánh giá: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Tại file: ' . $e->getFile() . ' dòng ' . $e->getLine());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Đã xảy ra lỗi khi gửi đánh giá. Vui lòng thử lại sau.')->withInput();
        }
    }
    
    public function edit($id)
    {
        $review = ProductReview::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $product = $review->product;
        
        return view('shop.reviews.edit', compact('review', 'product'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
            'pros' => 'nullable|string|max:500',
            'cons' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
        ]);
        
        $review = ProductReview::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $imagesPaths = $review->images ? $review->images : [];
        
        // Remove images if requested
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $index) {
                if (isset($imagesPaths[$index]) && $imagesPaths[$index]) {
                    Storage::disk('public')->delete($imagesPaths[$index]);
                    unset($imagesPaths[$index]);
                }
            }
            $imagesPaths = array_values($imagesPaths); // Re-index array
        }
        
        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews/' . $review->product_id, 'public');
                $imagesPaths[] = $path;
            }
        }
        
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->pros = $request->pros;
        $review->cons = $request->cons;
        $review->images = !empty($imagesPaths) ? $imagesPaths : null;
        // Reset approval if reviews are moderated
        $review->is_approved = true; // Auto-approve for now
        $review->save();
        
        return redirect()->route('products.show', $review->product->slug)
            ->with('success', 'Đánh giá của bạn đã được cập nhật.');
    }
    
    public function destroy($id)
    {
        $review = ProductReview::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Delete images
        if ($review->images) {
            foreach ($review->images as $image) {
                if ($image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
        
        $review->delete();
        
        return back()->with('success', 'Đánh giá đã được xóa.');
    }
    
    public function userReviews()
    {
        $reviews = ProductReview::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);
            
        return view('shop.reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị form đánh giá sản phẩm từ đơn hàng đã giao
     */
    public function orderReviewForm($orderNumber)
    {
        // Kiểm tra đơn hàng có thuộc về người dùng hiện tại không
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product'])
            ->firstOrFail();
        
        // Kiểm tra đơn hàng đã giao hoặc hoàn thành chưa
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã giao thành công.');
        }
        
        // Lấy danh sách sản phẩm chưa được đánh giá
        $productIds = $order->items->pluck('product_id')->toArray();
        $reviewedProductIds = ProductReview::where('user_id', Auth::id())
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->toArray();
        
        $unreviewed = $order->items->filter(function($item) use ($reviewedProductIds) {
            return !in_array($item->product_id, $reviewedProductIds);
        });
        
        $reviewed = $order->items->filter(function($item) use ($reviewedProductIds) {
            return in_array($item->product_id, $reviewedProductIds);
        });
        
        // Nếu tất cả sản phẩm đã được đánh giá
        if ($unreviewed->isEmpty()) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('info', 'Bạn đã đánh giá tất cả sản phẩm trong đơn hàng này.');
        }
        
        return view('shop.reviews.order_review', compact('order', 'unreviewed', 'reviewed'));
    }
    
    /**
     * Lưu đánh giá sản phẩm từ đơn hàng
     */
    public function addOrderReview(Request $request, $orderNumber)
    {
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'rating' => 'required|array',
            'rating.*' => 'required|integer|min:1|max:5',
            'comment' => 'required|array',
            'comment.*' => 'required|string|min:10|max:1000',
            'pros' => 'nullable|array',
            'pros.*' => 'nullable|string|max:500',
            'cons' => 'nullable|array',
            'cons.*' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'nullable|array',
            'images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Kiểm tra đơn hàng
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items'])
            ->firstOrFail();
        
        // Kiểm tra đơn hàng đã giao hoặc hoàn thành chưa
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã giao thành công.');
        }
        
        $orderItems = $order->items->pluck('product_id')->toArray();
        $successCount = 0;
        
        foreach ($request->product_id as $index => $productId) {
            // Kiểm tra sản phẩm có trong đơn hàng không
            if (!in_array($productId, $orderItems)) {
                continue;
            }
            
            // Kiểm tra sản phẩm đã được đánh giá chưa
            $existingReview = ProductReview::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($existingReview) {
                continue;
            }
            
            $imagesPaths = [];
            
            // Xử lý ảnh nếu có
            if ($request->hasFile("images.$index")) {
                foreach ($request->file("images.$index") as $image) {
                    $path = $image->store("reviews/$productId", 'public');
                    $imagesPaths[] = $path;
                }
            }
            
            // Tạo đánh giá mới
            $review = new ProductReview();
            $review->product_id = $productId;
            $review->user_id = Auth::id();
            $review->rating = $request->rating[$index];
            $review->review = $request->comment[$index];
            $review->pros = isset($request->pros[$index]) ? $request->pros[$index] : null;
            $review->cons = isset($request->cons[$index]) ? $request->cons[$index] : null;
            $review->images = !empty($imagesPaths) ? $imagesPaths : null;
            $review->is_verified_purchase = true; // Đã mua sản phẩm
            $review->is_approved = true; // Auto-approve for now
            $review->save();
            
            $successCount++;
        }
        
        if ($successCount > 0) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('success', "Đã đánh giá $successCount sản phẩm thành công! Cảm ơn bạn đã đánh giá.");
        } else {
            return redirect()->route('orders.show', $orderNumber)
                ->with('info', 'Không có sản phẩm nào được đánh giá. Có thể bạn đã đánh giá tất cả sản phẩm trước đó.');
        }
    }
}
