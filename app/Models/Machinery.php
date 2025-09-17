<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machinery extends Model
{
    use HasFactory;

    protected $table = 'machinery';

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'condition',
        'availability_type',
        'is_available',
        'brand',
        'model',
        'year',
        'fuel_type',
        'specifications',
        'images',
        'location',
        'latitude',
        'longitude',
        'view_count',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'is_available' => 'boolean',
        'specifications' => 'array',
        'images' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'view_count' => 'integer',
    ];

    /**
     * The seller (user) who owns this machinery
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The category this machinery belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Orders for this machinery
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Rentals for this machinery
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Reviews for this machinery
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Cart items for this machinery
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Messages related to this machinery
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Check if machinery is available for sale
     */
    public function isAvailableForSale(): bool
    {
        return in_array($this->availability_type, ['sale', 'both']) && 
               $this->is_available && 
               $this->status === 'active';
    }

    /**
     * Check if machinery is available for rent
     */
    public function isAvailableForRent(): bool
    {
        return in_array($this->availability_type, ['rent', 'both']) && 
               $this->is_available && 
               $this->status === 'active';
    }

    /**
     * Get average rating
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Get review count
     */
    public function reviewCount()
    {
        return $this->reviews()->count();
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for active machinery
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for available machinery
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for machinery available for sale
     */
    public function scopeForSale($query)
    {
        return $query->whereIn('availability_type', ['sale', 'both']);
    }

    /**
     * Scope for machinery available for rent
     */
    public function scopeForRent($query)
    {
        return $query->whereIn('availability_type', ['rent', 'both']);
    }
}