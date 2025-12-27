<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInterest extends Model
{
    protected $fillable = [
        'vehicle_id',
        'availability_id',
        'interest_type',
        'name',
        'email',
        'phone',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
        'delivery_option',
        'delivery_address',
        'delivery_city',
        'delivery_postal_code',
        'delivery_cost'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function availability()
    {
        return $this->belongsTo(VisitAvailability::class);
    }
}
