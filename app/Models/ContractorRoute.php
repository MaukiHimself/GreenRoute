<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'route_name',
        'region',
        'district',
        'ward',
        'street',
        'dumping_site',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the contractor that owns the route
     */
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    /**
     * Get all clients assigned to this route
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'route', 'route_name');
    }
}
