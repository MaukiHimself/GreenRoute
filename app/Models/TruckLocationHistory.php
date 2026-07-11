<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckLocationHistory extends Model
{
    use HasFactory;

    protected $table = 'truck_locations_history';

    protected $fillable = [
        'truck_id',
        'latitude',
        'longitude',
        'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    /**
     * Get the truck that owns the location history.
     */
    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }
}
