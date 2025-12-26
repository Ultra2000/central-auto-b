<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlateOrder extends Model
{
    protected $fillable = [
        'plate_type',
        'plate_number',
        'department',
        'custom_text',
        'material',
        'quantity',
        'unit_price',
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];
}
