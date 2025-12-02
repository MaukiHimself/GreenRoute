<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\PaymentTransaction;
use App\Services\AzamPayService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $azamPay;

    public function __construct(AzamPayService $azamPay)
    {
        $this->azamPay = $azamPay;
    }

    /**
     * Show Payment Options for Invoice
     */
    public function checkout(Invoice $invoice)
    {
        // Security check: Ensure user can view this invoice
        // (Assuming middleware or policy handles this, but simple check here)
        if (auth()->check() && auth()->user()->isClient()) {
             // Check invoice ownership logic here
        }

        return view('payments.checkout', compact('invoice'));
    }

    /**
     * Process Mobile Money Payment
     */
    public function payMobile(Request $request, Invoice $invoice)
    {
        $request->validate([
            'provider' => 'required|in:Airtel,Tigo,Halotel,AzamPesa',
            'phone_number' => 'required|string', // Format 255...
        ]);

        // Sanitize phone number: Remove '+' and ensure it starts with 255
        $accountNumber = preg_replace('/[^0-9]/', '', $request->phone_number);
        if (str_starts_with($accountNumber, '0')) {
            $accountNumber = '255' . substr($accountNumber, 1);
        }

        try {
            $reference = 'TXN-' . Str::random(12);
            
            // Create Transaction Record
            $transaction = PaymentTransaction::create([
                'invoice_id' => $invoice->id,
                'transaction_reference' => $reference,
                'amount' => $invoice->balance_due, // or request amount
                'currency' => 'TZS',
                'provider' => $request->provider,
                'account_number' => $accountNumber,
                'status' => 'pending',
            ]);

            // Initiate Payment
            $response = $this->azamPay->mobileCheckout(
                $accountNumber,
                $transaction->amount,
                $reference,
                $request->provider
            );

            if ($response['success'] ?? false) {
                $transaction->update(['external_reference' => $response['transactionId'] ?? null]);
                return response()->json([
                    'success' => true,
                    'message' => 'Payment request sent to your phone. Please enter PIN to confirm.',
                    'reference' => $reference
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed', 
                    'payment_details' => $response
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => $response['message'] ?? 'Payment failed'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            // Return actual error for debugging
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process Bank Payment
     */
    public function payBank(Request $request, Invoice $invoice)
    {
        try {
            $reference = 'TXN-' . Str::random(12);
            
            // Create Transaction Record
            $transaction = PaymentTransaction::create([
                'invoice_id' => $invoice->id,
                'transaction_reference' => $reference,
                'amount' => $invoice->balance_due,
                'currency' => 'TZS',
                'provider' => 'Bank',
                'status' => 'pending',
            ]);

            // This is the URL user comes back to after bank payment
            $redirectUrl = route('client.invoices'); 

            // Initiate Payment
            $response = $this->azamPay->bankCheckout(
                $transaction->amount,
                $reference,
                $redirectUrl
            );

            if ($response['success'] ?? false) {
                $transaction->update(['external_reference' => $response['transactionId'] ?? null]);
                return response()->json([
                    'success' => true,
                    'redirect_url' => $response['redirectUrl']
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed', 
                    'payment_details' => $response
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => $response['message'] ?? 'Bank payment initialization failed'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Bank Payment Error: ' . $e->getMessage());
             // Return actual error for debugging
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Callback from AzamPay
     */
    public function callback(Request $request)
    {
        // Verify signature if applicable, AzamPay usually sends JSON body
        $data = $request->all();
        Log::info('AzamPay Callback:', $data);

        $externalId = $data['externalId'] ?? null; // Matches our transaction_reference
        $status = $data['status'] ?? null; // success, failed

        if ($externalId) {
            $transaction = PaymentTransaction::where('transaction_reference', $externalId)->first();

            if ($transaction) {
                if ($status === 'success' || $data['success'] === true) {
                    $transaction->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'payment_details' => $data
                    ]);

                    // Mark Invoice as Paid
                    $invoice = $transaction->invoice;
                    $invoice->markAsPaid('AzamPay - ' . $transaction->provider);
                } else {
                    $transaction->update([
                        'status' => 'failed',
                        'payment_details' => $data
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
