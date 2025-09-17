<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_number',
        'renter_id',
        'machinery_id',
        'order_id',
        'start_date',
        'end_date',
        'rental_days',
        'daily_rate',
        'total_amount',
        'security_deposit',
        'status',
        'pickup_address',
        'delivery_address',
        'pickup_scheduled_at',
        'delivery_scheduled_at',
        'picked_up_at',
        'returned_at',
        'pickup_notes',
        'return_notes',
        'is_extended',
        'extension_details',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'pickup_scheduled_at' => 'datetime',
        'delivery_scheduled_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
        'is_extended' => 'boolean',
        'extension_details' => 'array',
    ];

    /**
     * The renter (user) who rented this machinery
     */
    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /**
     * The machinery being rented
     */
    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    /**
     * The order associated with this rental
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if rental is currently active/ongoing
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing' && 
               Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Check if rental is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'ongoing' && 
               Carbon::now()->greaterThan($this->end_date);
    }

    /**
     * Calculate days remaining
     */
    public function daysRemaining(): int
    {
        if ($this->status !== 'ongoing') {
            return 0;
        }
        
        return max(0, Carbon::now()->diffInDays($this->end_date, false));
    }

    /**
     * Calculate overdue days
     */
    public function overdueDays(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->end_date);
    }

    /**
     * Calculate total rental cost
     */
    public function calculateTotalCost(): float
    {
        return $this->rental_days * $this->daily_rate;
    }

    /**
     * Generate unique rental number
     */
    public static function generateRentalNumber(): string
    {
        do {
            $rentalNumber = 'EQR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('rental_number', $rentalNumber)->exists());
        
        return $rentalNumber;
    }

    /**
     * Extend the rental period
     */
    public function extend(Carbon $newEndDate, float $additionalRate = null): bool
    {
        if ($this->status !== 'ongoing') {
            return false;
        }

        $additionalDays = $this->end_date->diffInDays($newEndDate);
        $rate = $additionalRate ?: $this->daily_rate;
        $additionalCost = $additionalDays * $rate;

        $extensionDetails = $this->extension_details ?: [];
        $extensionDetails[] = [
            'extended_at' => now(),
            'original_end_date' => $this->end_date,
            'new_end_date' => $newEndDate,
            'additional_days' => $additionalDays,
            'additional_cost' => $additionalCost,
        ];

        $this->update([
            'end_date' => $newEndDate,
            'rental_days' => $this->rental_days + $additionalDays,
            'total_amount' => $this->total_amount + $additionalCost,
            'is_extended' => true,
            'extension_details' => $extensionDetails,
        ]);

        return true;
    }

    /**
     * Scope for active rentals
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['booked', 'ongoing']);
    }

    /**
     * Scope for ongoing rentals
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope for overdue rentals
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'ongoing')
                    ->where('end_date', '<', Carbon::now());
    }
}