<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'contractor_id',
        'client_id',
        'contractor_registration_number',
        'client_registration_number',
        'schedule_id',
        'invoice_date',
        'due_date',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'service_type',
        'description',
        'notes',
        'amount_paid',
        'remaining_balance',
        'paid_at',
        'payment_method'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2'
    ];

    // Relationships
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function paymentSubmissions()
    {
        return $this->hasMany(PaymentSubmission::class);
    }

    public function pendingPaymentSubmissions()
    {
        return $this->paymentSubmissions()->whereIn('status', ['pending', 'pending_approval']);
    }

    public function approvedPaymentSubmissions()
    {
        return $this->paymentSubmissions()->where('status', 'approved');
    }

    // Accessors
    public function getBalanceDueAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date->isPast();
    }

    public function getIsPaidAttribute()
    {
        return $this->status === 'paid' || $this->amount_paid >= $this->total_amount;
    }

    // Scopes
    public function scopeForContractor($query, $contractorId)
    {
        return $query->where('contractor_id', $contractorId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
                    ->where('due_date', '<', now());
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid')
                    ->whereColumn('total_amount', '>', 'amount_paid');
    }

    public function scopeForClient($query, $clientRegistrationNumber)
    {
        return $query->where('client_registration_number', $clientRegistrationNumber);
    }

    public function scopeByContractorRegNumber($query, $contractorRegNumber)
    {
        return $query->where('contractor_registration_number', $contractorRegNumber);
    }

    // Methods
    public function generateInvoiceNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $lastInvoice = static::where('invoice_number', 'like', "INV-{$year}{$month}-%")
                           ->orderBy('invoice_number', 'desc')
                           ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "INV-{$year}{$month}-{$newNumber}";
    }

    public function markAsPaid($paymentMethod = null)
    {
        $this->update([
            'status' => 'paid',
            'amount_paid' => $this->total_amount,
            'paid_at' => now(),
            'payment_method' => $paymentMethod
        ]);
    }

    public function calculateTotals()
    {
        $this->tax_amount = 0;
        $this->total_amount = $this->subtotal;
        $this->remaining_balance = $this->total_amount - ($this->amount_paid ?? 0);
        $this->save();
    }
}
