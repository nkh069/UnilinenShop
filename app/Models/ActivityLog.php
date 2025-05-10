<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];
    
    /**
     * Các thuộc tính sẽ được ép kiểu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
    ];
    
    /**
     * Lấy người dùng thực hiện hành động.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Lấy đối tượng liên quan (polymorphic).
     */
    public function subject()
    {
        return $this->morphTo('model');
    }
}
