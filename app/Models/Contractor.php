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
        'license_plate',
        'registration_number',
        'client_registration_number'
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

    /**
     * Get the client this contractor is assigned to
     */
    public function assignedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_registration_number', 'registration_number');
    }

    /**
     * Get all clients managed by this contractor (legacy relationship via user_id)
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'contractor_id', 'user_id');
    }

    /**
     * Get all invoices created by this contractor
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'contractor_registration_number', 'registration_number');
    }

    /**
     * Get all schedules created by this contractor
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'contractor_registration_number', 'registration_number');
    }

    /**
     * Boot method to auto-generate registration number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($contractor) {
            if (empty($contractor->registration_number)) {
                $contractor->registration_number = 'CT' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                
                // Ensure uniqueness
                while (static::where('registration_number', $contractor->registration_number)->exists()) {
                    $contractor->registration_number = 'CT' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                }
            }
        });
    }
}