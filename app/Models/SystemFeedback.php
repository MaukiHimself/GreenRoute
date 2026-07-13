<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Feedback submitted by a client or contractor about the GreenRoute system,
 * addressed to admins. Distinct from the client↔contractor Feedback model.
 */
class SystemFeedback extends Model
{
    use HasFactory;

    protected $table = 'system_feedback';

    protected $fillable = [
        'user_id',
        'role',
        'category',
        'subject',
        'message',
        'status',
        'admin_response',
        'responded_at',
        'responded_by',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /** The client or contractor who submitted the feedback. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The admin who responded. */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
