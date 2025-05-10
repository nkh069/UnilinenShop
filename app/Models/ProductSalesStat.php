<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSalesStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'revenue_report_id',
        'product_id',
        'product_name',
        'quantity_sold',
        'revenue',
    ];

    protected $casts = [
        'quantity_sold' => 'integer',
        'revenue' => 'decimal:2',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(RevenueReport::class, 'revenue_report_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getAveragePrice(): float
    {
        return $this->quantity_sold > 0 ? $this->revenue / $this->quantity_sold : 0;
    }
}
