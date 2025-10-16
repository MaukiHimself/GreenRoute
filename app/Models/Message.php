<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'client_id',
        'sender_type',
        'message',
        'message_type',
        'status',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the contractor that this message belongs to
     */
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    /**
     * Get the client that this message belongs to
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', '!=', 'read');
    }

    /**
     * Scope for messages between contractor and client
     */
    public function scopeConversation($query, $contractorId, $clientId)
    {
        return $query->where('contractor_id', $contractorId)
                    ->where('client_id', $clientId)
                    ->orderBy('created_at', 'asc');
    }
}
