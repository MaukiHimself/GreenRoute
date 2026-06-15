<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeocodeCache extends Model
{
    protected $fillable = [
        'address_hash',
        'address',
        'latitude',
        'longitude',
        'geocoded_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'geocoded_at' => 'datetime',
    ];
}
