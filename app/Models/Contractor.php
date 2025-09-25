<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contractor extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'name',
        'email',
        'phone',
        'address',
        'site_locations',
        'license_number',
        'certificate_path',
        'vehicle_type',
        'license_plate'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ContractorLocation::class, 'contractor_id', 'user_id');
    }

    public function latestLocation()
    {
        return $this->locations()->latest()->first();
    }
}