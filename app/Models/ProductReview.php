<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review',
        'comment',
        'pros',
        'cons',
        'is_verified_purchase',
        'is_approved',
        'images',
    ];

    /**
     * Các thuộc tính được ép kiểu.
     */
    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Sản phẩm liên quan đến đánh giá.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Người dùng đã viết đánh giá.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Lấy nội dung đánh giá (comment hoặc review)
     */
    public function getReviewTextAttribute()
    {
        return $this->comment ?? $this->review ?? '';
    }
}
