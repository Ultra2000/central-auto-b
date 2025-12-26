<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'message', 'type', 'vehicle_id', 'status'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
