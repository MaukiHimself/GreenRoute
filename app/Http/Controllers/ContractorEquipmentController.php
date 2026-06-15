<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Schedule;
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
            'is_available' => 'sometimes|boolean',
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
            'is_available' => 'sometimes|boolean',
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
}
