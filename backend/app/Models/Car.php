<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'segment',
        'transmission',
        'fuel',
        'description',
        'image_url',
        'latitude',
        'longitude',
        'rate_minute',
        'rate_hour',
        'rate_day',
        'range_km',
        'is_active',
    ];
}

