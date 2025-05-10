<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevenueReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'period_type',
        'start_date',
        'end_date',
        'total_revenue',
        'cost_of_goods',
        'gross_profit',
        'tax_collected',
        'shipping_fees',
        'refunds',
        'discounts',
        'orders_count',
        'products_sold',
        'notes',
        'generated_by',
    ];

    protected $casts = [
        'report_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_revenue' => 'decimal:2',
        'cost_of_goods' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'tax_collected' => 'decimal:2',
        'shipping_fees' => 'decimal:2',
        'refunds' => 'decimal:2',
        'discounts' => 'decimal:2',
        'orders_count' => 'integer',
        'products_sold' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function productStats(): HasMany
    {
        return $this->hasMany(ProductSalesStat::class);
    }

    public function getNetRevenue(): float
    {
        return $this->total_revenue - $this->refunds - $this->discounts;
    }

    public function getNetProfit(): float
    {
        return $this->getNetRevenue() - $this->cost_of_goods;
    }

    public function getProfitMargin(): float
    {
        $netRevenue = $this->getNetRevenue();
        if ($netRevenue <= 0) {
            return 0;
        }
        return ($this->getNetProfit() / $netRevenue) * 100;
    }
}
