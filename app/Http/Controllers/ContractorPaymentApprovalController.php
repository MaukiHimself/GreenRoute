<?php

namespace App\Http\Controllers;

use App\Models\PaymentSubmission;
use App\Models\Invoice;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractorPaymentApprovalController extends Controller
{
    /**
     * Get pending payment submissions for the contractor's dashboard
     */
    public function getPendingSubmissions()
    {
        $contractor = Auth::user()->contractor;

        if (!$contractor) {
            abort(403, 'Only contractors can view payments');
        }

        $submissions = PaymentSubmission::where('contractor_id', $contractor->id)
            ->with(['invoice', 'client'])
            ->whereIn('status', ['pending', 'pending_approval'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(15);

        return $submissions;
    }

    /**
     * Show the pending approvals section data
     */
    public function showPendingApprovals()
    {
        $contractor = Auth::user()->contractor;

        if (!$contractor) {
            abort(403, 'Only contractors can view payments');
        }

        $submissions = PaymentSubmission::where('contractor_id', $contractor->id)
            ->whereIn('status', ['pending', 'pending_approval'])
            ->with(['invoice', 'client'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        // History so the contractor can review already-processed transactions.
        $approved = PaymentSubmission::where('contractor_id', $contractor->id)
            ->where('status', 'approved')
            ->with(['invoice', 'client'])
            ->orderByDesc('verified_at')
            ->get();

        $rejected = PaymentSubmission::where('contractor_id', $contractor->id)
            ->where('status', 'rejected')
            ->with(['invoice', 'client'])
            ->orderByDesc('rejected_at')
            ->get();

        return view('contractor.pending-payment-approvals', [
            'submissions' => $submissions,
            'pendingCount' => $submissions->count(),
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }

    /**
     * Approve a payment submission and generate receipt
     */
    public function approve(PaymentSubmission $submission, Request $request)
    {
        // Verify the contractor owns this submission
        $contractor = Auth::user()->contractor;

        if (!$contractor || $submission->contractor_id !== $contractor->id) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($submission->status, ['pending', 'pending_approval'], true)) {
            return response()->json([
                'error' => 'This payment has already been processed.'
            ], 400);
        }

        try {
            // Approve the payment
            $submission->approve();

            // Generate receipt
            $receiptNumber = PaymentSubmission::generateReceiptNumber();
            $receipt = $this->generateReceipt($submission, $receiptNumber);

            // Save receipt path
            $submission->update([
                'receipt_number' => $receiptNumber,
                'receipt_path' => $receipt['path'],
                'receipt_issued_at' => now(),
            ]);

            // Notify the client (bell) that their payment was approved
            if ($submission->client && $submission->client->user) {
                $submission->client->user->notify(new GenericNotification(
                    title: 'Payment approved',
                    message: 'Your payment of TZS ' . number_format($submission->amount_submitted, 0) . ' has been approved. Receipt #' . $receiptNumber,
                    url: route('client.payments'),
                    icon: 'bi-check-circle',
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment approved and receipt generated successfully.',
                'receipt_url' => $receipt['url'],
                'receipt_number' => $submission->receipt_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to approve payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a payment submission
     */
    public function reject(PaymentSubmission $submission, Request $request)
    {
        // Verify the contractor owns this submission
        $contractor = Auth::user()->contractor;

        if (!$contractor || $submission->contractor_id !== $contractor->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if (!in_array($submission->status, ['pending', 'pending_approval'], true)) {
            return response()->json([
                'error' => 'This payment has already been processed.'
            ], 400);
        }

        $submission->reject($validated['reason']);

        // Notify the client (bell) that their payment was rejected
        if ($submission->client && $submission->client->user) {
            $submission->client->user->notify(new GenericNotification(
                title: 'Payment rejected',
                message: 'Your payment of TZS ' . number_format($submission->amount_submitted, 0) . ' was rejected. Reason: ' . $validated['reason'],
                url: route('client.invoices'),
                icon: 'bi-x-circle',
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment submission rejected.'
        ]);
    }

    /**
     * Download receipt
     */
    public function downloadReceipt(PaymentSubmission $submission)
    {
        // Verify the user has access to this receipt
        $contractor = Auth::user()->contractor;
        $client = Auth::user()->client;

        $hasAccess = false;

        if ($contractor && $submission->contractor_id === $contractor->id) {
            $hasAccess = true;
        } elseif ($client && $submission->client_id === $client->id) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        if (!$submission->receipt_path || !Storage::exists($submission->receipt_path)) {
            abort(404, 'Receipt not found');
        }

        return Storage::download($submission->receipt_path);
    }

    /**
     * Generate a receipt for the payment
     */
    private function generateReceipt(PaymentSubmission $submission, string $receiptNumber): array
    {
        $invoice = $submission->invoice;
        $contractor = $submission->contractor;
        $client = $submission->client;

        $pdf = Pdf::loadView('receipts.payment-receipt', [
            'submission' => $submission,
            'invoice' => $invoice,
            'contractor' => $contractor,
            'client' => $client,
            'receiptNumber' => $receiptNumber,
            'generatedAt' => now(),
        ]);

        // Save the PDF
        $fileName = "receipt_{$submission->id}_{$receiptNumber}.pdf";
        $path = "receipts/{$receiptNumber}";

        Storage::put("{$path}.pdf", $pdf->output());

        return [
            'path' => "{$path}.pdf",
            'url' => route('payment-submissions.receipt.download', $submission),
            'fileName' => $fileName
        ];
    }

    /**
     * Get statistics for pending approvals
     */
    public function getStats()
    {
        $contractor = Auth::user()->contractor;

        if (!$contractor) {
            abort(403, 'Only contractors can view payments');
        }

        $stats = [
            'pending_count' => PaymentSubmission::where('contractor_id', $contractor->id)
                ->whereIn('status', ['pending', 'pending_approval'])
                ->count(),
            'approved_today' => PaymentSubmission::where('contractor_id', $contractor->id)
                ->where('status', 'approved')
                ->whereDate('verified_at', today())
                ->count(),
            'total_pending_amount' => PaymentSubmission::where('contractor_id', $contractor->id)
                ->whereIn('status', ['pending', 'pending_approval'])
                ->sum('amount_submitted'),
        ];

        return response()->json($stats);
    }
}
