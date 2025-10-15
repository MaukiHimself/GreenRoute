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
        'route_sequence'
    ];

    protected $casts = [
        'status' => 'string'
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
