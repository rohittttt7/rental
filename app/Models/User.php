<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'company_name', 
        'company_address',
        'kyc_status',
        'kyc_documents',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kyc_documents' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is a seller
     */
    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Machinery listed by this user (if seller)
     */
    public function machinery()
    {
        return $this->hasMany(Machinery::class, 'seller_id');
    }

    /**
     * Orders made by this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Orders received by this user (as seller)
     */
    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Rentals made by this user
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'renter_id');
    }

    /**
     * Reviews written by this user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Cart items for this user
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
