<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'machinery_id',
        'type',
        'quantity',
        'rental_start_date',
        'rental_end_date',
        'rental_days',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * The user who owns this cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The machinery in this cart item
     */
    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    /**
     * Check if this is a purchase cart item
     */
    public function isPurchase(): bool
    {
        return $this->type === 'purchase';
    }

    /**
     * Check if this is a rental cart item
     */
    public function isRental(): bool
    {
        return $this->type === 'rental';
    }

    /**
     * Calculate rental days
     */
    public function calculateRentalDays(): int
    {
        if (!$this->isRental() || !$this->rental_start_date || !$this->rental_end_date) {
            return 0;
        }
        
        return $this->rental_start_date->diffInDays($this->rental_end_date) + 1;
    }

    /**
     * Update total price based on type and dates
     */
    public function updateTotalPrice()
    {
        if ($this->isPurchase()) {
            $this->total_price = $this->machinery->price * $this->quantity;
        } elseif ($this->isRental()) {
            $days = $this->calculateRentalDays();
            $this->rental_days = $days;
            $this->total_price = $this->machinery->daily_rate * $days;
        }
        
        $this->save();
    }

    /**
     * Scope for purchase items
     */
    public function scopePurchase($query)
    {
        return $query->where('type', 'purchase');
    }

    /**
     * Scope for rental items
     */
    public function scopeRental($query)
    {
        return $query->where('type', 'rental');
    }
}