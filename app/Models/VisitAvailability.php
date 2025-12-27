<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitAvailability extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'max_bookings',
        'current_bookings',
        'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    public function isAvailable()
    {
        return $this->is_active && $this->current_bookings < $this->max_bookings;
    }

    public function incrementBookings()
    {
        $this->increment('current_bookings');
    }

    public function decrementBookings()
    {
        if ($this->current_bookings > 0) {
            $this->decrement('current_bookings');
        }
    }
}
