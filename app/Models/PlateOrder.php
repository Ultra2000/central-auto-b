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
        'payment_status',
        'payment_method',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'paid_at',
        'delivery_option',
        'delivery_address',
        'delivery_city',
        'delivery_postal_code',
        'delivery_cost',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'quantity' => 'integer',
        'paid_at' => 'datetime',
    ];
}
