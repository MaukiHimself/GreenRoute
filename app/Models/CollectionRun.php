<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'contractor_id',
        'route_id',
        'route_name',
        'started_at',
        'completed_at',
        'total_stops',
        'collected_count',
        'skipped_count',
        'blocked_count',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(ContractorRoute::class, 'route_id');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(CollectionRunStop::class);
    }
}
