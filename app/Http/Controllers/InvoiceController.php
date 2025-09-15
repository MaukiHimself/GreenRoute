<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $clients = Client::where('contractor_id', Auth::id())->get();
        $schedules = Schedule::where('contractor_id', Auth::id())
            ->where('status', 'completed')
            ->whereDoesntHave('invoices')
            ->with('client')
            ->get();

        return view('invoices.create', compact('clients', 'schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        // Verify client belongs to contractor
        $client = Client::where('id', $validated['client_id'])
            ->where('contractor_id', Auth::id())
            ->firstOrFail();

        $invoice = new Invoice($validated);
        $invoice->contractor_id = Auth::id();
        $invoice->invoice_number = $invoice->generateInvoiceNumber();
        // Calculate totals and persist in one go to satisfy NOT NULL constraints
        $invoice->calculateTotals();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
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
        Gate::authorize('view', $invoice);
        
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
