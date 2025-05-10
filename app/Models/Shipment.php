<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipper_id',
        'tracking_number',
        'carrier',
        'status',
        'shipped_at',
        'delivered_at',
        'shipping_method',
        'shipping_cost',
        'tracking_url',
        'tracking_history',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
        'tracking_history' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    public function shipper(): BelongsTo
    {
        return $this->belongsTo(Shipper::class);
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped' || $this->status === 'delivered';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }
    
    public function hasShipper(): bool
    {
        return !is_null($this->shipper_id);
    }
}
