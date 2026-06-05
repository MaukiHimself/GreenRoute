<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Location; // Added
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema; // Added
use Illuminate\Support\Facades\DB; // Added
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['client', 'schedule'])
            ->forContractor(Auth::id())
            ->orderBy('invoice_date', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contractorId = Auth::id();
        $clients = Client::where('contractor_id', $contractorId)
            ->select('id', 'name', 'registration_number', 'email', 'phone', 'address', 'city', 'region', 'district', 'ward', 'street', 'route')
            ->orderBy('name')
            ->get();
        $schedules = Schedule::where('contractor_id', $contractorId)
            ->where('status', 'completed')
            ->whereDoesntHave('invoices')
            ->with('client')
            ->get();
            
        // Get regions for group selection
        $regions = [];
        if (Schema::hasTable('tbl_locations')) {
            try {
                $regions = Location::select('region')
                    ->distinct()
                    ->orderBy('region')
                    ->pluck('region');
            } catch (\Exception $e) {
                $regions = [];
            }
        }

        return view('invoices.create', compact('clients', 'schedules', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'nullable|string|in:single,group',
            'client_id' => 'required_if:mode,single|nullable|exists:clients,id',
            'client_ids' => 'required_if:mode,group|nullable|array',
            'client_ids.*' => 'exists:clients,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        $contractorId = Auth::id();

        DB::transaction(function () use ($validated, $contractorId) {
            $clientIds = [];
            
            if (($validated['mode'] ?? 'single') === 'group' && !empty($validated['client_ids'])) {
                $clientIds = $validated['client_ids'];
            } elseif (!empty($validated['client_id'])) {
                $clientIds = [$validated['client_id']];
            }
            
            foreach ($clientIds as $clientId) {
                // Verify client belongs to contractor
                $client = Client::where('id', $clientId)
                    ->where('contractor_id', $contractorId)
                    ->first();
                
                if (!$client) continue;

                $invoice = new Invoice();
                $invoice->contractor_id = $contractorId;
                $invoice->client_id = $clientId;
                $invoice->schedule_id = $validated['schedule_id'] ?? null;
                $invoice->invoice_date = $validated['invoice_date'];
                $invoice->due_date = $validated['due_date'];
                $invoice->service_type = $validated['service_type'];
                $invoice->description = $validated['description'];
                $invoice->subtotal = $validated['subtotal'];
                $invoice->tax_rate = $validated['tax_rate'];
                $invoice->notes = $validated['notes'];
                
                $invoice->invoice_number = $invoice->generateInvoiceNumber();
                $invoice->calculateTotals();
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoices created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);
        
        $invoice->load(['client', 'schedule', 'contractor']);
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        Gate::authorize('update', $invoice);
        
        $clients = Client::where('contractor_id', Auth::id())->get();
        $schedules = Schedule::where('contractor_id', Auth::id())
            ->where('status', 'completed')
            ->with('client')
            ->get();

        return view('invoices.edit', compact('invoice', 'clients', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Verify client belongs to contractor
        $client = Client::where('id', $validated['client_id'])
            ->where('contractor_id', Auth::id())
            ->firstOrFail();

        $invoice->update($validated);
        $invoice->calculateTotals();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        Gate::authorize('delete', $invoice);
        
        $invoice->delete();
        
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Generate PDF for the invoice.
     */
    public function pdf(Invoice $invoice)
    {
        // Allow invoice PDF download for both contractors and their clients.
        // Prevent 403 by using explicit checks instead of the InvoicePolicy which
        // currently only allows contractors.
        $user = Auth::user();

        $canView = false;

        if ($user && $user->isContractor()) {
            $canView = (int) $user->id === (int) $invoice->contractor_id;
        }

        if ($user && $user->user_type === 'client') {
            // Client users can view/download invoices belonging to their client record.

            $client = \App\Models\Client::where('user_id', $user->id)->first();
            if ($client) {
                $canView = (int) $client->id === (int) $invoice->client_id;
            } else {
                // Fallback by email (mirrors ClientPortalController logic)
                $email = strtolower($user->email);
                $client = \App\Models\Client::whereRaw('LOWER(email) = ?', [$email])->first();
                if ($client) {
                    $canView = (int) $client->id === (int) $invoice->client_id;
                }
            }
        }

        if (!$canView) {
            abort(403, 'Unauthorized');
        }

        $invoice->load(['client', 'schedule', 'contractor']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }


    /**
     * Mark invoice as paid.
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);
        
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:255'
        ]);
        
        $invoice->markAsPaid($validated['payment_method'] ?? null);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice marked as paid successfully.');
    }
}
