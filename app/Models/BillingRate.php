<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'location',
        'collection_fee',
        'frequency',
        'description',
        'is_active'
    ];

    protected $casts = [
        'collection_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function getLabelAttribute(): string
    {
        $frequency = $this->frequency ? ucfirst(str_replace('-', ' ', $this->frequency)) : 'Any';
        return "{$this->category} - {$this->location} - {$frequency}";
    }

    public function getFormattedCollectionFeeAttribute(): string
    {
        return number_format($this->collection_fee, 2);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(ContractorBillingRateChange::class);
    }

    /**
     * Get billing rate by category and location
     */
    public static function getRateByLocation($category, $location, $frequency = null)
    {
        $query = self::where('category', $category)
            ->where('location', $location)
            ->where('is_active', true);
        
        if ($frequency) {
            $query->where('frequency', $frequency);
        }
        
        return $query->first();
    }

    /**
     * Get all active rates grouped by category
     */
    public static function getActiveRatesGrouped()
    {
        return self::where('is_active', true)
            ->orderBy('category')
            ->orderBy('location')
            ->get()
            ->groupBy('category');
    }
}
