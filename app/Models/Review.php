<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'machinery_id',
        'order_id',
        'rating',
        'review',
        'images',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * The user who wrote this review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The machinery being reviewed
     */
    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    /**
     * The order associated with this review
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for reviews with rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}