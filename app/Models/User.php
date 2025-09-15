<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'username_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }
    
    /**
     * Check if the user is a client.
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }
    
    /**
     * Check if the user is a contractor.
     *
     * @return bool
     */
    public function isContractor(): bool
    {
        return $this->user_type === 'contractor';
    }
    
    /**
     * Get the clients for the contractor.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'contractor_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'contractor_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'contractor_id');
    }
}
