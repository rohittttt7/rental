<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Machinery in this category
     */
    public function machinery()
    {
        return $this->hasMany(Machinery::class);
    }

    /**
     * Get active machinery in this category
     */
    public function activeMachinery()
    {
        return $this->hasMany(Machinery::class)->where('status', 'active');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}