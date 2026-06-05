<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Truck extends Model
{
    protected $fillable = [
        'contractor_id',
        'plate_number',
        'driver_name',
        'driver_phone',
        'truck_type',
        'status',
        'current_latitude',
        'current_longitude',
        'previous_latitude',
        'previous_longitude',
        'daily_distance',
        'last_updated',
        'tracking_token'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'daily_distance' => 'decimal:2'
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
}
