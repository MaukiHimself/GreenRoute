<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'contractor_id',
        'client_id',
        'contractor_registration_number',
        'client_registration_number',
        'pickup_date',
        'pickup_time',
        'pickup_location',
        'pickup_address',
        'city',
        'state',
        'zip_code',
        'service_type',
        'status',
        'notes',
        'estimated_duration',
        'total_volume',
        'disposal_site',
        'disposal_type',
        'disposal_notes'
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'pickup_time' => 'datetime:H:i',
        'estimated_duration' => 'decimal:2',
        'service_type' => 'string',
        'status' => 'string'
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->pickup_address}, {$this->city}, {$this->state} {$this->zip_code}";
    }

    public function scopeForContractor($query, $contractorId)
    {
        return $query->where('contractor_id', $contractorId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('pickup_date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled');
    }

    public function scopeForClient($query, $clientRegistrationNumber)
    {
        return $query->where('client_registration_number', $clientRegistrationNumber);
    }

    public function scopeByContractorRegNumber($query, $contractorRegNumber)
    {
        return $query->where('contractor_registration_number', $contractorRegNumber);
    }
}
