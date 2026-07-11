<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRunStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_run_id',
        'client_id',
        'client_name',
        'status',
        'actioned_at',
    ];

    protected $casts = [
        'actioned_at' => 'datetime',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(CollectionRun::class, 'collection_run_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
