<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractorBillingRateChange extends Model
{
    protected $fillable = [
        'contractor_id',
        'schedule_id',
        'client_id',
        'billing_rate_id',
        'old_billing_rate_id',
        'new_billing_rate_id',
        'old_fee',
        'new_fee',
        'old_rate_label',
        'new_rate_label',
        'action',
        'reason',
    ];

    protected $casts = [
        'old_fee' => 'decimal:2',
        'new_fee' => 'decimal:2',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function billingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class);
    }

    public function oldBillingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class, 'old_billing_rate_id');
    }

    public function newBillingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class, 'new_billing_rate_id');
    }
}
