<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePrice extends Model
{
    protected $fillable = [
        'contractor_id',
        'service_type',
        'waste_type',
        'category',
        'volume_tier',
        'price',
        'currency',
        'description',
        'includes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public static function getLabel($type)
    {
        return match ($type) {
            'regular_pickup' => 'Regular Pickup',
            'bulk_collection' => 'Bulk Collection',
            'hazardous_waste' => 'Hazardous Waste',
            'recycling' => 'Recycling',
            'organic_waste' => 'Organic Waste',
            'construction_debris' => 'Construction Debris',
            default => ucwords(str_replace('_', ' ', $type)),
        };
    }

    public static function getVolumeLabel($tier)
    {
        return match ($tier) {
            'small' => 'Small (1-5 bags)',
            'medium' => 'Medium (6-15 bags)',
            'large' => 'Large (16-30 bags)',
            'extra_large' => 'Extra Large (30+ bags)',
            'container' => 'Full Container',
            default => $tier ?: 'Standard',
        };
    }
}
