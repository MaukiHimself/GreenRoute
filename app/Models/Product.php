<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable=[
        'name',
        'price',
        'description',
        'specifications',
        'unit',
        'category',
        'image',
        'is_available',
        'contractor_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'TZS ' . number_format($this->price, 2) . ($this->unit ? ' / ' . $this->unit : '');
    }
}
