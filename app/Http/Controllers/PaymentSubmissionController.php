<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentSubmission;
use App\Models\Contractor;
use App\Models\Client;
use App\Notifications\GenericNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PaymentSubmissionController extends Controller
{
    /**
     * Show the payment methods selection page for a client
     */
    public function showPaymentMethods(Invoice $invoice)
    {
        // Verify the invoice is visible to the current user
        $client = Auth::user()->client;
        if (!$client || $invoice->client_id !== $client->id) {
            abort(403, 'Unauthorized');
        }

        // Get the contractor associated with this invoice
        $contractor = $invoice->contractor?->contractor;

        if (!$contractor) {
            return view('client-portal.payment-methods', [
                'invoice' => $invoice,
                'contractor' => null,
                'paymentMethods' => [],
                'balanceDue' => $invoice->total_amount - $invoice->amount_paid,
                'error' => 'Your contractor has not configured payment details yet. Please contact them for payment instructions.'
            ]);
        }

        $paymentMethods = $this->getAvailablePaymentMethods($contractor);

        return view('client-portal.payment-methods', [
            'invoice' => $invoice,
            'contractor' => $contractor,
            'paymentMethods' => $paymentMethods,
            'balanceDue' => $invoice->total_amount - $invoice->amount_paid,
            'error' => null
        ]);
    }

    /**
     * Show the payment submission form for a specific payment method
     */
    public function showSubmissionForm(Invoice $invoice, $paymentMethod)
    {
        // Verify the invoice is visible to the current user
        $client = Auth::user()->client;
        if (!$client || $invoice->client_id !== $client->id) {
            abort(403, 'Unauthorized');
        }

        // Get the contractor
        $contractor = $invoice->contractor?->contractor;

        if (!$contractor) {
            return redirect()->route('client.payment-methods', $invoice)
                ->with('error', 'Your contractor is not configured for payments. Please contact them.');
        }

        // Validate payment method
        $lipaNo = $contractor->getLipaNo($paymentMethod);
        if (!$lipaNo) {
            return redirect()->route('client.payment-methods', $invoice)
                ->with('error', 'Invalid payment method. Please select a valid method.');
        }

        $paymentMethodName = $this->getPaymentMethodName($paymentMethod);
        $balanceDue = $invoice->total_amount - $invoice->amount_paid;

        return view('client-portal.payment-submission-form', [
            'invoice' => $invoice,
            'contractor' => $contractor,
            'paymentMethod' => $paymentMethod,
            'paymentMethodName' => $paymentMethodName,
            'lipaNo' => $lipaNo,
            'balanceDue' => $balanceDue,
            'clientName' => $client->name
        ]);
    }

    /**
     * Store a payment submission
     */
    public function store(Request $request, Invoice $invoice)
    {
        // Verify the invoice is visible to the current user
        $client = Auth::user()->client;
        if (!$client || $invoice->client_id !== $client->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'payer_name' => 'required|string|max:255',
            'amount_submitted' => 'required|numeric|min:0.01|max:99999999.99',
            'payment_method' => 'required|string|in:vodacom_mpesa,airtel_money,halopesa,mixx_by_yas,crdb_bank,nmb_bank,nbc_bank',
        ]);

        $balanceDue = $invoice->total_amount - $invoice->amount_paid;

        if ($validated['amount_submitted'] > $balanceDue) {
            return back()->with('error', 'Payment amount cannot exceed the balance due.')
                ->withInput();
        }

        // Get the contractor
        $contractor = $invoice->contractor?->contractor;

        if (!$contractor) {
            return redirect()->route('client.payment-methods', $invoice)
                ->with('error', 'Your contractor is not configured for payments. Please contact them.');
        }

        // Verify payment method is available
        $lipaNo = $contractor->getLipaNo($validated['payment_method']);
        if (!$lipaNo) {
            return redirect()->route('client.payment-methods', $invoice)
                ->with('error', 'Invalid payment method. Please select a valid method.');
        }

        // Create the payment submission
        $submission = PaymentSubmission::create([
            'invoice_id' => $invoice->id,
            'client_id' => $client->id,
            'contractor_id' => $contractor->id,
            'payer_name' => $validated['payer_name'],
            'amount_submitted' => $validated['amount_submitted'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending_approval',
            'submitted_at' => now(),
        ]);

        // Notify the contractor (bell) that a client submitted a payment for approval
        $contractorUser = User::find($invoice->contractor_id);
        if ($contractorUser) {
            $contractorUser->notify(new GenericNotification(
                title: 'Payment submitted for approval',
                message: ($client->name ?? 'A client') . ' submitted TZS ' . number_format($validated['amount_submitted'], 0) . ' for invoice ' . $invoice->invoice_number,
                url: route('contractor.pending-payments'),
                icon: 'bi-cash-coin',
            ));
        }

        return redirect()->route('client.payment-submitted', $invoice)
            ->with('success', 'Payment submission received. Please wait while the contractor verifies your transaction.');
    }

    /**
     * Show payment submission confirmation
     */
    public function showSubmissionConfirmation(Invoice $invoice)
    {
        $client = Auth::user()->client;
        if (!$client || $invoice->client_id !== $client->id) {
            abort(403, 'Unauthorized');
        }

        // Get the latest pending or recent submission
        $submission = PaymentSubmission::where('invoice_id', $invoice->id)
            ->orderBy('submitted_at', 'desc')
            ->first();

        if (!$submission) {
            return redirect()->route('client.invoices')
                ->with('error', 'Payment submission not found.');
        }

        return view('client-portal.payment-submitted', [
            'invoice' => $invoice,
            'submission' => $submission,
            'paymentMethodName' => $this->getPaymentMethodName($submission->payment_method),
        ]);
    }

    /**
     * Get payment methods for a contractor
     */
    private function getAvailablePaymentMethods(Contractor $contractor): array
    {
        $available = [];
        foreach ($this->paymentMethodCatalog() as $key => $method) {
            $lipaNo = $contractor->getLipaNo($key);
            $available[$key] = [
                'name' => $method['name'],
                'logo' => $method['logo'],
                'lipa_no' => $lipaNo,
                'configured' => filled($lipaNo),
                'account_name' => $contractor->name ?? $contractor->company_name,
            ];
        }

        return $available;
    }

    /**
     * Get payment method display name
     */
    private function getPaymentMethodName(string $method): string
    {
        return $this->paymentMethodCatalog()[$method]['name'] ?? 'Unknown';
    }

    private function paymentMethodCatalog(): array
    {
        return [
            'vodacom_mpesa' => [
                'name' => 'Vodacom M-Pesa',
                'logo' => 'mpesa.png',
            ],
            'airtel_money' => [
                'name' => 'Airtel Money',
                'logo' => 'airtel_money.png',
            ],
            'halopesa' => [
                'name' => 'Halopesa',
                'logo' => 'halopesa.png',
            ],
            'mixx_by_yas' => [
                'name' => 'Mixx by Yas',
                'logo' => 'mixx_by_yas.png',
            ],
            'crdb_bank' => [
                'name' => 'CRDB Bank',
                'logo' => 'crdb.png',
            ],
            'nmb_bank' => [
                'name' => 'NMB Bank',
                'logo' => 'nmb.png',
            ],
            'nbc_bank' => [
                'name' => 'NBC Bank',
                'logo' => 'nbc.png',
            ],
        ];
    }
}
