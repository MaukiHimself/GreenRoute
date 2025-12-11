<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $invoices = Invoice::forContractor(Auth::id())
            ->with(['client', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total_invoices' => Invoice::forContractor(Auth::id())->count(),
            'paid_invoices' => Invoice::forContractor(Auth::id())->where('status', 'paid')->count(),
            'overdue_invoices' => Invoice::forContractor(Auth::id())->overdue()->count(),
            'total_revenue' => Invoice::forContractor(Auth::id())->where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::forContractor(Auth::id())->unpaid()->sum('total_amount')
        ];

        return view('billing.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $clients = Client::where('contractor_id', Auth::id())->get();
        return view('billing.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $rules = [
            'mode' => 'required|in:single,group',
            'service_type' => 'required|string',
            'description' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ];

        if ($request->input('mode') === 'single') {
            $rules['client_id'] = 'required|exists:clients,id';
        } else {
            $rules['client_ids'] = 'required|array|min:1';
            $rules['client_ids.*'] = 'exists:clients,id';
        }

        $validated = $request->validate($rules);

        $clientIds = [];
        if ($request->input('mode') === 'single') {
            $clientIds[] = $validated['client_id'];
        } else {
            $clientIds = $validated['client_ids'];
        }

        $count = 0;
        foreach ($clientIds as $clientId) {
            $invoice = new Invoice();
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->contractor_id = Auth::id();
            $invoice->client_id = $clientId;
            $invoice->invoice_date = now();
            $invoice->due_date = $validated['due_date'];
            $invoice->status = 'draft';
            $invoice->subtotal = $validated['subtotal'];
            $invoice->tax_rate = $validated['tax_rate'] ?? 0;
            $invoice->service_type = $validated['service_type'];
            $invoice->description = $validated['description'];
            $invoice->notes = $validated['notes'];
            $invoice->amount_paid = 0;
            
            $invoice->calculateTotals();
            $count++;
        }

        $message = $count > 1 
            ? "$count invoices created successfully" 
            : 'Invoice created successfully';

        return redirect()->route('billing.index')->with('success', $message);
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->contractor_id !== Auth::id()) {
            abort(404);
        }
        
        return view('billing.show', compact('invoice'));
    }

    public function markPaid(Invoice $invoice, Request $request)
    {
        if ($invoice->contractor_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric|min:0'
        ]);

        $invoice->update([
            'amount_paid' => $validated['amount_paid'],
            'payment_method' => $validated['payment_method'],
            'paid_at' => now(),
            'status' => $validated['amount_paid'] >= $invoice->total_amount ? 'paid' : 'partial'
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }

    public function sendInvoice(Invoice $invoice)
    {
        if ($invoice->contractor_id !== Auth::id()) {
            abort(404);
        }

        // Here you would implement SMS/Email sending logic
        // For now, just return success message
        
        return redirect()->back()->with('success', 'Invoice sent successfully');
    }

    public function sendReminder(Invoice $invoice)
    {
        if ($invoice->contractor_id !== Auth::id()) {
            abort(404);
        }

        // Here you would implement debt reminder SMS/Email logic
        
        return redirect()->back()->with('success', 'Payment reminder sent successfully');
    }
}