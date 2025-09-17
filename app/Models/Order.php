<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'machinery_id',
        'type',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'shipping_address',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * The buyer (user) who placed this order
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * The seller (user) who received this order
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The machinery being ordered
     */
    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    /**
     * Rental details if this is a rental order
     */
    public function rental()
    {
        return $this->hasOne(Rental::class);
    }

    /**
     * Reviews for this order
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if order is for purchase
     */
    public function isPurchase(): bool
    {
        return $this->type === 'purchase';
    }

    /**
     * Check if order is for rental
     */
    public function isRental(): bool
    {
        return $this->type === 'rental';
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order is delivered
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'EQZ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }

    /**
     * Scope for purchase orders
     */
    public function scopePurchase($query)
    {
        return $query->where('type', 'purchase');
    }

    /**
     * Scope for rental orders
     */
    public function scopeRental($query)
    {
        return $query->where('type', 'rental');
    }

    /**
     * Scope for paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
}