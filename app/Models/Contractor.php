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
        'region',
        'district',
        'ward',
        'street',
        'license_number',
        'certificate_path',
        'vehicle_type',
        'license_plate',
        'registration_number',
        'client_registration_number',
        'vodacom_mpesa_lipa_no',
        'airtel_money_lipa_no',
        'halopesa_lipa_no',
        'mixx_by_yas_lipa_no',
        'crdb_bank_lipa_no',
        'nmb_bank_lipa_no',
        'nbc_bank_lipa_no'
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

    /**
     * Get all payment submissions for this contractor
     */
    public function paymentSubmissions(): HasMany
    {
        return $this->hasMany(PaymentSubmission::class, 'contractor_id');
    }

    /**
     * Get pending payment submissions for approval
     */
    public function pendingPaymentSubmissions(): HasMany
    {
        return $this->paymentSubmissions()->whereIn('status', ['pending', 'pending_approval']);
    }

    /**
     * Get a payment method's Lipa No by key
     */
    public function getLipaNo(string $paymentMethod): ?string
    {
        $lipaNoColumn = match($paymentMethod) {
            'vodacom_mpesa' => 'vodacom_mpesa_lipa_no',
            'airtel_money' => 'airtel_money_lipa_no',
            'halopesa' => 'halopesa_lipa_no',
            'mixx_by_yas' => 'mixx_by_yas_lipa_no',
            'crdb_bank' => 'crdb_bank_lipa_no',
            'nmb_bank' => 'nmb_bank_lipa_no',
            'nbc_bank' => 'nbc_bank_lipa_no',
            default => null,
        };

        return $lipaNoColumn ? $this->$lipaNoColumn : null;
    }

    /**
     * Get all payment methods with their Lipa Nos
     */
    public function getPaymentMethods(): array
    {
        return [
            'vodacom_mpesa' => [
                'name' => 'Vodacom M-Pesa',
                'lipa_no' => $this->vodacom_mpesa_lipa_no,
            ],
            'airtel_money' => [
                'name' => 'Airtel Money',
                'lipa_no' => $this->airtel_money_lipa_no,
            ],
            'halopesa' => [
                'name' => 'Halopesa',
                'lipa_no' => $this->halopesa_lipa_no,
            ],
            'mixx_by_yas' => [
                'name' => 'Mixx by Yas (Tigo Pesa)',
                'lipa_no' => $this->mixx_by_yas_lipa_no,
            ],
            'crdb_bank' => [
                'name' => 'CRDB Bank',
                'lipa_no' => $this->crdb_bank_lipa_no,
            ],
            'nmb_bank' => [
                'name' => 'NMB Bank',
                'lipa_no' => $this->nmb_bank_lipa_no,
            ],
            'nbc_bank' => [
                'name' => 'NBC Bank',
                'lipa_no' => $this->nbc_bank_lipa_no,
            ],
        ];
    }

    /**
     * Get full site location address
     */
    public function getSiteLocationAttribute()
    {
        $parts = array_filter([$this->street, $this->ward, $this->district, $this->region]);
        return !empty($parts) ? implode(', ', $parts) : $this->site_locations;
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
