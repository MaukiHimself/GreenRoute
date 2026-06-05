<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'contractor_id',
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'region',
        'district',
        'ward',
        'street',
        'latitude',
        'longitude',
        'city',
        'state',
        'zip_code',
        'notes',
        'status',
        'registration_number',
        'contact_name',
        'category',
        'phone_2',
        'phone_3',
        'email_2',
        'email_3',
        'route',
        'route_sequence',
        'self_registered',
        'verified_at',
    ];

    protected $casts = [
        'status' => 'string',
        'self_registered' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Query Scopes for Location Filtering
     */
    public function scopeByLocation($query, $region = null, $district = null, $ward = null, $street = null)
    {
        if ($region) {
            $query->where('region', $region);
        }
        if ($district) {
            $query->where('district', $district);
        }
        if ($ward) {
            $query->where('ward', $ward);
        }
        if ($street) {
            $query->where('street', $street);
        }
        return $query;
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeByWard($query, $ward)
    {
        return $query->where('ward', $ward);
    }

    public function scopeForContractor($query, $contractorId)
    {
        return $query->where('contractor_id', $contractorId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get full site location address
     */
    public function getSiteLocationAttribute()
    {
        $parts = array_filter([$this->street, $this->ward, $this->district, $this->region]);
        return !empty($parts) ? implode(', ', $parts) : null;
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($client) {
            if (empty($client->registration_number)) {
                $client->registration_number = 'CL' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                
                // Ensure uniqueness
                while (static::where('registration_number', $client->registration_number)->exists()) {
                    $client->registration_number = 'CL' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                }
            }
        });
    }
}
