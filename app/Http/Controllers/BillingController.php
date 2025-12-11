<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    private const CATEGORY_PRICES = [
        'Residential (Unplanned)' => 10000,
        'Residential (Planned/Modern)' => 20000,
        'Commercial Residential (Apartment)' => 30000,
        'Commercial Residential Storey' => 80000,
        'Commercial Residential above 2 storey' => 100000,
        'Commercial Industrial & Institutions' => 150000,
        'Tea Room' => 10000,
        'Café' => 10000,
        'Ice Par Lour' => 10000,
        'Restaurant' => 15000,
        'Guest House' => 10000,
        'Dispensary (domestic waste)' => 15000,
        'Health Centre (Domestic waste)' => 20000,
        'Hospital (Domestic waste)' => 35000,
        'Sawing mills' => 35000,
        'Furniture making' => 22000,
        'Metal workshops' => 22000,
        'Industries (Light waste)' => 35000,
        'Industries (Heavy Industries)' => 40000,
        'Wholesale shops (general)' => 15000,
        'Retail shops (food and other items)' => 10000,
        'Retail shops (other commodities)' => 10000,
        'Private Day Primary School' => 10000,
        'Private Boarding Secondary schools' => 15000,
        'Private Day Secondary schools' => 10000,
        'Private Boarding Secondary schools (Large)' => 25000,
        'Institution per month' => 25000,
        'Groceries' => 10000,
        'Bar' => 15000,
        'Butcher' => 10000,
        'Pharmacy' => 15000,
        'Markets' => 50000,
        'Street Market (Magenge) per table' => 2000,
        'Food vendors (Mama ntilie)' => 5000,
        'Bus stations (per bus per day)' => 5000,
        'Mosque/ church' => 20000,
        'Informal dry cleaners, tailors' => 10000,
        'Informal Carpenter' => 10000,
        'Shoe makers' => 5000,
        'Electronic gadgets repair' => 10000,
        'Street Barbers' => 10000,
        'Female Saloons' => 15000,
        'Petrol Stations' => 30000,
        'Warehouses' => 30000,
        'Hotels' => 150000,
        'Offices' => 100000,
        'Construction waste per trip' => 25000,
        'Garage' => 10000,
    ];

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
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ];

        if ($request->input('mode') === 'single') {
            $rules['client_id'] = 'required|exists:clients,id';
            $rules['subtotal'] = 'required|numeric|min:0';
        } else {
            $rules['client_ids'] = 'required|array|min:1';
            $rules['client_ids.*'] = 'exists:clients,id';
            $rules['subtotal'] = 'nullable|numeric|min:0'; // Optional for group mode
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
            
            // Determine subtotal based on mode
            if ($request->input('mode') === 'group') {
                $client = Client::find($clientId);
                // Automated pricing based on category
                if ($client && isset(self::CATEGORY_PRICES[$client->category])) {
                    $invoice->subtotal = self::CATEGORY_PRICES[$client->category];
                } else {
                    // Fallback to manual subtotal if category not found or price not set
                    $invoice->subtotal = $validated['subtotal'] ?? 0;
                }
            } else {
                // Single mode uses manual subtotal
                $invoice->subtotal = $validated['subtotal'];
            }

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