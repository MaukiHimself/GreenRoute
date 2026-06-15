<?php

namespace App\Http\Controllers;

use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractorServicePricingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified.contractor']);
    }

    public function index()
    {
        $prices = ServicePrice::where('contractor_id', Auth::id())
            ->orderBy('service_type')
            ->orderBy('volume_tier')
            ->paginate(30);

        return view('contractor.pricing.index', compact('prices'));
    }

    public function create()
    {
        return view('contractor.pricing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string',
            'waste_type' => 'nullable|string',
            'volume_tier' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'includes' => 'nullable|string|max:2000',
        ]);

        $validated['contractor_id'] = Auth::id();

        ServicePrice::create($validated);

        return redirect()->route('contractor.pricing.index')
            ->with('success', 'Service price added successfully.');
    }

    public function edit(ServicePrice $price)
    {
        if ((int) $price->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('contractor.pricing.edit', compact('price'));
    }

    public function update(Request $request, ServicePrice $price)
    {
        if ((int) $price->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'service_type' => 'required|string',
            'waste_type' => 'nullable|string',
            'volume_tier' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'includes' => 'nullable|string|max:2000',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $price->update($validated);

        return redirect()->route('contractor.pricing.index')
            ->with('success', 'Service price updated successfully.');
    }

    public function destroy(ServicePrice $price)
    {
        if ((int) $price->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $price->delete();

        return redirect()->route('contractor.pricing.index')
            ->with('success', 'Service price removed successfully.');
    }

    public function toggleActive(ServicePrice $price)
    {
        if ((int) $price->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $price->update(['is_active' => !$price->is_active]);

        return back()->with('success', $price->is_active
            ? 'Price is now active and visible to clients.'
            : 'Price is now inactive and hidden from clients.');
    }

    public function clientView()
    {
        $client = auth()->user();

        $contractorId = null;
        if ($client && $client->user_type === 'client') {
            $clientRecord = \App\Models\Client::where('user_id', $client->id)->first();
            if ($clientRecord) {
                $contractorId = $clientRecord->contractor_id;
            }
        }

        $prices = collect();
        if ($contractorId) {
            $prices = ServicePrice::where('contractor_id', $contractorId)
                ->where('is_active', true)
                ->orderBy('service_type')
                ->orderBy('volume_tier')
                ->get();
        }

        return view('client_portal.pricing', compact('prices'));
    }
}
