<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRequest extends Model
{
    protected $fillable = [
        'type',
        'category',
        'budget',
        'brand',
        'model',
        'transmission',
        'name',
        'email',
        'phone',
        'message',
        'status'
    ];
}
