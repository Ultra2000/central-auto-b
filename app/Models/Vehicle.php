<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'make', 'model', 'year', 'type', 'price', 'rent_price', 
        'fuel', 'transmission', 'mileage', 'seats', 'images', 'main_image',
        'badge_type', 'badge_value', 'is_available', 'is_featured',
        'status', 'sold_at'
    ];

    protected $casts = [
        'images' => 'array',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'sold_at' => 'datetime',
    ];
}
