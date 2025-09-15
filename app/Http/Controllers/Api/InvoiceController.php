<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoices = Invoice::where('contractor_id', $user->id)
                ->with(['client', 'schedule'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $invoices,
                'message' => 'Invoices retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'schedule_id' => 'nullable|exists:schedules,id',
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'service_type' => 'required|in:Waste Collection,Recycling,Hazardous Waste,Bulk Pickup,Other',
                'subtotal' => 'required|numeric|min:0',
                'tax_rate' => 'required|numeric|min:0|max:100',
                'description' => 'nullable|string|max:1000',
                'notes' => 'nullable|string|max:1000',
                'status' => 'in:draft,sent,paid,overdue,cancelled'
            ]);

            // Verify client belongs to contractor
            $client = Client::where('id', $validated['client_id'])
                ->where('contractor_id', $user->id)
                ->firstOrFail();

            // Verify schedule belongs to contractor if provided
            if ($validated['schedule_id']) {
                Schedule::where('id', $validated['schedule_id'])
                    ->where('contractor_id', $user->id)
                    ->firstOrFail();
            }

            // Generate invoice number
            $lastInvoice = Invoice::where('contractor_id', $user->id)
                ->orderBy('id', 'desc')
                ->first();
            
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

            // Calculate amounts
            $taxAmount = $validated['subtotal'] * ($validated['tax_rate'] / 100);
            $totalAmount = $validated['subtotal'] + $taxAmount;

            $invoice = Invoice::create([
                'contractor_id' => $user->id,
                'client_id' => $validated['client_id'],
                'schedule_id' => $validated['schedule_id'] ?? null,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'service_type' => $validated['service_type'],
                'subtotal' => $validated['subtotal'],
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'status' => $validated['status'] ?? 'draft',
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $invoice->load(['client', 'schedule']),
                'message' => 'Invoice created successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoice = Invoice::where('contractor_id', $user->id)
                ->with(['client', 'schedule'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $invoice,
                'message' => 'Invoice retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoice = Invoice::where('contractor_id', $user->id)->findOrFail($id);

            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'schedule_id' => 'nullable|exists:schedules,id',
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'service_type' => 'required|in:Waste Collection,Recycling,Hazardous Waste,Bulk Pickup,Other',
                'subtotal' => 'required|numeric|min:0',
                'tax_rate' => 'required|numeric|min:0|max:100',
                'description' => 'nullable|string|max:1000',
                'notes' => 'nullable|string|max:1000',
                'status' => 'in:draft,sent,paid,overdue,cancelled'
            ]);

            // Verify client belongs to contractor
            $client = Client::where('id', $validated['client_id'])
                ->where('contractor_id', $user->id)
                ->firstOrFail();

            // Verify schedule belongs to contractor if provided
            if ($validated['schedule_id']) {
                Schedule::where('id', $validated['schedule_id'])
                    ->where('contractor_id', $user->id)
                    ->firstOrFail();
            }

            // Recalculate amounts
            $taxAmount = $validated['subtotal'] * ($validated['tax_rate'] / 100);
            $totalAmount = $validated['subtotal'] + $taxAmount;

            $invoice->update([
                'client_id' => $validated['client_id'],
                'schedule_id' => $validated['schedule_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'service_type' => $validated['service_type'],
                'subtotal' => $validated['subtotal'],
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => $validated['status'] ?? $invoice->status,
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $invoice->load(['client', 'schedule']),
                'message' => 'Invoice updated successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoice = Invoice::where('contractor_id', $user->id)->findOrFail($id);
            
            // Prevent deletion if invoice is paid
            if ($invoice->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete paid invoice'
                ], 409);
            }

            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function pdf(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoice = Invoice::where('contractor_id', $user->id)
                ->with(['client', 'schedule'])
                ->findOrFail($id);

            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
            $pdfContent = base64_encode($pdf->output());

            return response()->json([
                'success' => true,
                'data' => [
                    'pdf_content' => $pdfContent,
                    'filename' => 'invoice-' . $invoice->invoice_number . '.pdf',
                    'mime_type' => 'application/pdf'
                ],
                'message' => 'PDF generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->type !== 'contractor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $invoice = Invoice::where('contractor_id', $user->id)->findOrFail($id);
            
            $invoice->update([
                'status' => 'paid',
                'amount_paid' => $invoice->total_amount
            ]);

            return response()->json([
                'success' => true,
                'data' => $invoice->load(['client', 'schedule']),
                'message' => 'Invoice marked as paid successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark invoice as paid',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
