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
        'route',
        'route_group_id',
        'billing_rate_id',
        'billing_rate_category',
        'billing_rate_location',
        'billing_rate_frequency',
        'base_collection_fee',
        'contractor_adjusted_fee',
        'schedule_price',
        'billing_rate_change_reason',
        'billing_rate_modified_at',
        'pickup_date',
        'pickup_time',
        'scheduled_date',
        'scheduled_time',
        'pickup_location',
        'pickup_address',
        'city',
        'state',
        'zip_code',
        'service_type',
        'frequency',
        'includes_organic_waste',
        'organic_waste_notes',
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
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'estimated_duration' => 'decimal:2',
        'base_collection_fee' => 'decimal:2',
        'contractor_adjusted_fee' => 'decimal:2',
        'schedule_price' => 'decimal:2',
        'billing_rate_modified_at' => 'datetime',
        'service_type' => 'string',
        'frequency' => 'string',
        'includes_organic_waste' => 'boolean',
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

    public function billingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class);
    }

    public function billingRateChanges(): HasMany
    {
        return $this->hasMany(ContractorBillingRateChange::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getBillingRateLabelAttribute(): ?string
    {
        if ($this->billingRate) {
            return $this->billingRate->label;
        }

        return collect([
            $this->billing_rate_category,
            $this->billing_rate_location,
            $this->billing_rate_frequency ? ucfirst(str_replace('-', ' ', $this->billing_rate_frequency)) : null,
        ])->filter()->implode(' - ');
    }

    public function getDisplayedPriceAttribute(): ?float
    {
        return $this->schedule_price ?? $this->contractor_adjusted_fee ?? $this->base_collection_fee;
    }

    public function getHasBillingAdjustmentAttribute(): bool
    {
        if ($this->contractor_adjusted_fee === null) {
            return false;
        }

        if ($this->base_collection_fee === null) {
            return true;
        }

        return (float) $this->contractor_adjusted_fee !== (float) $this->base_collection_fee;
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
