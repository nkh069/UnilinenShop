<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'id_card',
        'company',
        'address',
        'city',
        'province',
        'postal_code',
        'status',
        'avatar',
        'rating',
        'notes',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'status' => 'boolean',
    ];
    
    /**
     * Lấy danh sách các đơn vận chuyển được giao cho shipper
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
    
    /**
     * Kiểm tra shipper có đang hoạt động
     */
    public function isActive(): bool
    {
        return $this->status == true;
    }
} 