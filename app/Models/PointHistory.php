<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'points',
        'type',
        'description',
        'reference_id',
        'reference_type',
    ];
    
    /**
     * Get the user that owns the point history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 