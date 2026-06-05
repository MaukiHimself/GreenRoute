<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentSubmission extends Model
{
    protected $fillable = [
        'invoice_id',
        'client_id',
        'contractor_id',
        'payer_name',
        'amount_submitted',
        'payment_method',
        'status',
        'submitted_at',
        'verified_at',
        'rejected_at',
        'rejection_reason',
        'receipt_number',
        'receipt_path',
        'receipt_issued_at',
    ];

    protected $casts = [
        'amount_submitted' => 'decimal:2',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'receipt_issued_at' => 'datetime',
    ];

    /**
     * Get the invoice associated with this payment submission
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the client who made the payment
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the contractor who will verify the payment
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'pending_approval']);
    }

    /**
     * Scope for approved payments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get submissions for a contractor
     */
    public function scopeForContractor($query, $contractorId)
    {
        return $query->where('contractor_id', $contractorId);
    }

    /**
     * Mark payment as approved
     */
    public function approve(): void
    {
        $invoice = $this->invoice;
        $newAmountPaid = min($invoice->total_amount, $invoice->amount_paid + $this->amount_submitted);

        $this->update([
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        $invoice->amount_paid = $newAmountPaid;
        $invoice->remaining_balance = max(0, $invoice->total_amount - $newAmountPaid);

        if ($newAmountPaid >= $invoice->total_amount) {
            $invoice->status = 'paid';
            $invoice->paid_at = now();
        } else {
            $invoice->status = 'partially_paid';
        }

        $invoice->save();
    }

    /**
     * Mark payment as rejected
     */
    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Generate a receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$timestamp}{$random}";
    }
}
