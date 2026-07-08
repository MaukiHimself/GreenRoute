<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Schedule;
use App\Models\EquipmentRequest;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContractorEquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified.contractor']);
    }

    public function index()
    {
        $equipments = Product::where('contractor_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('contractor.equipment.index', compact('equipments'));
    }

    public function create()
    {
        return view('contractor.equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'unit' => 'nullable|string|max:100',
            'specifications' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'nullable',
        ]);

        $validated['contractor_id'] = Auth::id();
        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment-images', 'public');
        }

        Product::create($validated);

        return redirect()->route('contractor.equipment.index')
            ->with('success', 'Equipment added successfully.');
    }

    public function edit(Product $equipment)
    {
        $this->authorizeContractor($equipment);
        return view('contractor.equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Product $equipment)
    {
        $this->authorizeContractor($equipment);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'unit' => 'nullable|string|max:100',
            'specifications' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'nullable',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }
            $validated['image'] = $request->file('image')->store('equipment-images', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('contractor.equipment.index')
            ->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Product $equipment)
    {
        $this->authorizeContractor($equipment);

        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('contractor.equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }

    public function toggleAvailability(Product $equipment)
    {
        $this->authorizeContractor($equipment);

        $equipment->update([
            'is_available' => !$equipment->is_available,
        ]);

        return back()->with('success', $equipment->is_available
            ? 'Equipment is now available.'
            : 'Equipment is now unavailable.');
    }

    private function authorizeContractor(Product $equipment): void
    {
        if ((int) $equipment->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }

    // ── Equipment Requests ────────────────────────────────────────────────

    public function requests()
    {
        $requests = EquipmentRequest::with(['product', 'client'])
            ->where('contractor_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('contractor.equipment.requests', compact('requests'));
    }

    public function respondRequest(Request $request, EquipmentRequest $equipmentRequest)
    {
        abort_unless((int) $equipmentRequest->contractor_id === (int) Auth::id(), 403);

        $data = $request->validate([
            'status'              => 'required|in:approved,rejected,fulfilled',
            'contractor_response' => 'nullable|string|max:1000',
        ]);

        $equipmentRequest->update([
            'status'              => $data['status'],
            'contractor_response' => $data['contractor_response'] ?? null,
            'responded_at'        => now(),
        ]);

        // Notify the client (bell) about the equipment request response
        $client = $equipmentRequest->client;
        if ($client && $client->user) {
            $productName = $equipmentRequest->product->name ?? 'equipment';
            $statusMessages = [
                'approved'  => ['title' => 'Equipment request approved', 'message' => 'Your request for ' . $productName . ' has been approved.', 'icon' => 'bi-box-seam'],
                'rejected'  => ['title' => 'Equipment request rejected', 'message' => 'Your request for ' . $productName . ' was not approved.' . ($data['contractor_response'] ? ' Note: ' . \Illuminate\Support\Str::limit($data['contractor_response'], 60) : ''), 'icon' => 'bi-x-circle'],
                'fulfilled' => ['title' => 'Equipment delivered', 'message' => 'Your requested ' . $productName . ' has been delivered.', 'icon' => 'bi-truck'],
            ];
            if (isset($statusMessages[$data['status']])) {
                $n = $statusMessages[$data['status']];
                $client->user->notify(new GenericNotification(
                    title: $n['title'],
                    message: $n['message'],
                    url: route('client.equipment'),
                    icon: $n['icon'],
                ));
            }
        }

        return back()->with('success', 'Request updated successfully.');
    }
}
