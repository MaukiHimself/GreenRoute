<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Contractor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InvoiceApiController extends Controller
{
    /**
     * Create a new invoice (Contractor creates for their assigned client)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contractor_registration_number' => 'required|string|exists:contractors,registration_number',
            'client_registration_number' => 'nullable|string|exists:clients,registration_number',
            'schedule_id' => 'nullable|exists:schedules,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get contractor
            $contractor = Contractor::where('registration_number', $validated['contractor_registration_number'])
                ->firstOrFail();

            // If no client_registration_number provided, use contractor's assigned client
            $clientRegNumber = $validated['client_registration_number'] ?? $contractor->client_registration_number;

            if (!$clientRegNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'No client specified and contractor is not assigned to any client'
                ], 400);
            }

            // Get client by registration number
            $client = Client::where('registration_number', $clientRegNumber)->firstOrFail();

            // Get contractor_id and client_id for legacy relationships
            $contractorId = $contractor->user_id;
            $clientId = $client->id;

            // Create invoice
            $invoice = new Invoice($validated);
            $invoice->contractor_id = $contractorId;
            $invoice->client_id = $clientId;
            $invoice->contractor_registration_number = $contractor->registration_number;
            $invoice->client_registration_number = $clientRegNumber;
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->status = 'draft';
            
            // Calculate totals
            $invoice->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => [
                    'invoice' => $invoice->load(['client', 'contractor'])
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all invoices for a specific client (by registration number)
     * 
     * @param string $clientRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientInvoices($clientRegistrationNumber)
    {
        try {
            $client = Client::where('registration_number', $clientRegistrationNumber)->firstOrFail();

            $invoices = Invoice::forClient($clientRegistrationNumber)
                ->with(['contractor', 'schedule'])
                ->orderBy('invoice_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Client invoices retrieved successfully',
                'data' => [
                    'client' => [
                        'registration_number' => $client->registration_number,
                        'name' => $client->name
                    ],
                    'invoices' => $invoices,
                    'count' => $invoices->count(),
                    'total_amount' => $invoices->sum('total_amount'),
                    'total_paid' => $invoices->where('status', 'paid')->sum('total_amount'),
                    'total_outstanding' => $invoices->whereIn('status', ['draft', 'sent', 'overdue'])->sum('total_amount')
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client invoices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all invoices created by a specific contractor (by registration number)
     * 
     * @param string $contractorRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractorInvoices($contractorRegistrationNumber)
    {
        try {
            $contractor = Contractor::where('registration_number', $contractorRegistrationNumber)->firstOrFail();

            $invoices = Invoice::byContractorRegNumber($contractorRegistrationNumber)
                ->with(['client', 'schedule'])
                ->orderBy('invoice_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Contractor invoices retrieved successfully',
                'data' => [
                    'contractor' => [
                        'registration_number' => $contractor->registration_number,
                        'company_name' => $contractor->company_name
                    ],
                    'invoices' => $invoices,
                    'count' => $invoices->count(),
                    'total_amount' => $invoices->sum('total_amount'),
                    'total_paid' => $invoices->where('status', 'paid')->sum('total_amount'),
                    'total_outstanding' => $invoices->whereIn('status', ['draft', 'sent', 'overdue'])->sum('total_amount')
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contractor invoices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific invoice by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::with(['client', 'contractor', 'schedule'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Invoice retrieved successfully',
                'data' => [
                    'invoice' => $invoice
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update an invoice
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_date' => 'sometimes|date',
            'due_date' => 'sometimes|date',
            'service_type' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'subtotal' => 'sometimes|numeric|min:0',
            'tax_rate' => 'sometimes|numeric|min:0|max:100',
            'status' => 'sometimes|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->update($validated);

            if (isset($validated['subtotal']) || isset($validated['tax_rate'])) {
                $invoice->calculateTotals();
            }

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => [
                    'invoice' => $invoice->fresh()->load(['client', 'contractor'])
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark invoice as paid
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsPaid(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:255'
        ]);

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->markAsPaid($validated['payment_method'] ?? null);

            return response()->json([
                'success' => true,
                'message' => 'Invoice marked as paid successfully',
                'data' => [
                    'invoice' => $invoice->fresh()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark invoice as paid',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an invoice
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
