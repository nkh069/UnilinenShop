<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'category_id',
        'sku',
        'status',
        'featured',
        'sizes',
        'colors',
        'color_codes',
        'brand',
        'weight',
        'material',
        'discount_percent',
        'track_inventory',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'featured' => 'boolean',
        'sizes' => 'array',
        'colors' => 'array',
        'color_codes' => 'array',
        'weight' => 'decimal:2',
        'discount_percent' => 'integer',
        'track_inventory' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function getFinalPrice()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isInStock(): bool
    {
        return $this->status !== 'out_of_stock' && $this->inventories()->sum('quantity') > 0;
    }

    /**
     * Định nghĩa mối quan hệ với bảng product_images
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Định nghĩa mối quan hệ với bảng product_colors
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(ProductColor::class, 'product_color_product')
            ->withPivot('color_code')
            ->withTimestamps();
    }

    /**
     * Định nghĩa mối quan hệ với bảng product_sizes
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(ProductSize::class, 'product_size_product')
            ->withTimestamps();
    }
}
