<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Truck extends Model
{
    protected $fillable = [
        'contractor_id',
        'plate_number',
        'driver_name',
        'driver_phone',
        'truck_type',
        'tare_weight_kg',
        'status',
        'base_latitude',
        'base_longitude',
        'assigned_route_id',
        'current_latitude',
        'current_longitude',
        'previous_latitude',
        'previous_longitude',
        'daily_distance',
        'last_updated',
        'tracking_token',
        'stop_statuses'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'daily_distance' => 'decimal:2',
        'stop_statuses' => 'array'
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function assignedRoute(): BelongsTo
    {
        return $this->belongsTo(ContractorRoute::class, 'assigned_route_id');
    }

    public function locationHistories()
    {
        return $this->hasMany(TruckLocationHistory::class);
    }

    public function collectionRuns(): HasMany
    {
        return $this->hasMany(CollectionRun::class);
    }

    /**
     * The currently active (in-progress) collection run for this truck, if any.
     */
    public function activeRun(): ?CollectionRun
    {
        return $this->collectionRuns()
            ->where('status', 'in_progress')
            ->latest('id')
            ->first();
    }
}
