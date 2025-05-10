<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Hiển thị form đánh giá sản phẩm từ đơn hàng.
     */
    public function orderReviewForm(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->with(['items.product.category', 'items.product.brand'])
            ->firstOrFail();
        
        // Lấy danh sách sản phẩm chưa được đánh giá từ đơn hàng này
        $unreviewed = [];
        
        foreach ($order->items as $item) {
            $reviewed = Review::where('user_id', auth()->id())
                ->where('product_id', $item->product_id)
                ->where('order_id', $order->id)
                ->exists();
            
            if (!$reviewed) {
                $unreviewed[] = $item;
            }
        }
        
        // Nếu tất cả sản phẩm đã được đánh giá, chuyển hướng về trang chi tiết đơn hàng
        if (empty($unreviewed)) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('info', 'Bạn đã đánh giá tất cả sản phẩm từ đơn hàng này.');
        }
        
        return view('shop.reviews.order_review', compact('order', 'unreviewed'));
    }
    
    /**
     * Lưu đánh giá mới từ đơn hàng.
     */
    public function addOrderReview(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->firstOrFail();
        
        // Validate dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'rating' => 'required|array',
            'rating.*' => 'required|integer|min:1|max:5',
            'comment' => 'required|array',
            'comment.*' => 'required|string|min:10',
            'pros' => 'nullable|array',
            'pros.*' => 'nullable|string',
            'cons' => 'nullable|array',
            'cons.*' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            $pointsEarned = 0;
            
            foreach ($request->product_id as $index => $productId) {
                // Kiểm tra xem sản phẩm này có trong đơn hàng không
                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$orderItem) {
                    continue;
                }
                
                // Kiểm tra xem đã đánh giá sản phẩm này từ đơn hàng này chưa
                $existingReview = Review::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->where('order_id', $order->id)
                    ->first();
                
                if ($existingReview) {
                    continue;
                }
                
                // Tạo đánh giá mới
                $review = new Review();
                $review->user_id = $user->id;
                $review->product_id = $productId;
                $review->order_id = $order->id;
                $review->rating = $request->rating[$index];
                $review->comment = $request->comment[$index];
                $review->pros = $request->pros[$index] ?? null;
                $review->cons = $request->cons[$index] ?? null;
                $review->status = 'approved'; // Có thể thay đổi thành 'pending' nếu muốn kiểm duyệt trước
                $review->save();
                
                // Xử lý hình ảnh (nếu có)
                if ($request->hasFile("images.$index")) {
                    foreach ($request->file("images.$index") as $image) {
                        $path = $image->store('reviews', 'public');
                        
                        $reviewImage = new ReviewImage();
                        $reviewImage->review_id = $review->id;
                        $reviewImage->image = $path;
                        $reviewImage->save();
                    }
                }
                
                // Cộng điểm thưởng cho người dùng
                if ($user->points === null) {
                    $user->points = 0;
                }
                $user->points += 100;
                $pointsEarned += 100;
                
                // Ghi lại lịch sử điểm thưởng
                $pointHistory = new PointHistory();
                $pointHistory->user_id = $user->id;
                $pointHistory->points = 100;
                $pointHistory->type = 'earned';
                $pointHistory->description = 'Đánh giá sản phẩm #' . $productId;
                $pointHistory->save();
                
                // Cập nhật xếp hạng trung bình của sản phẩm
                $this->updateProductRating($productId);
            }
            
            // Lưu điểm thưởng đã cập nhật
            $user->save();
            
            DB::commit();
            
            return redirect()->route('orders.show', $orderNumber)
                ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! Bạn đã nhận được ' . $pointsEarned . ' điểm thưởng.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi lưu đánh giá: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Cập nhật xếp hạng trung bình của sản phẩm.
     */
    protected function updateProductRating($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Tính xếp hạng trung bình từ các đánh giá đã được phê duyệt
        $avgRating = Review::where('product_id', $productId)
            ->where('status', 'approved')
            ->avg('rating');
        
        // Đếm tổng số đánh giá
        $reviewCount = Review::where('product_id', $productId)
            ->where('status', 'approved')
            ->count();
        
        // Cập nhật sản phẩm
        $product->avg_rating = $avgRating;
        $product->review_count = $reviewCount;
        $product->save();
        
        return $product;
    }
} 