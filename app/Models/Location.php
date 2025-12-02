<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region',
        'district',
        'ward',
        'street',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to get distinct regions
     */
    public function scopeDistinctRegions($query)
    {
        return $query->select('region')->distinct()->orderBy('region');
    }

    /**
     * Scope to filter by region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope to filter by district
     */
    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    /**
     * Scope to filter by ward
     */
    public function scopeByWard($query, $ward)
    {
        return $query->where('ward', $ward);
    }

    /**
     * Search locations by keyword
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('region', 'LIKE', "%{$keyword}%")
              ->orWhere('district', 'LIKE', "%{$keyword}%")
              ->orWhere('ward', 'LIKE', "%{$keyword}%")
              ->orWhere('street', 'LIKE', "%{$keyword}%");
        });
    }
}
