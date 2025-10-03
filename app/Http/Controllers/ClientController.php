<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

class ClientController extends Controller
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
        $clients = Client::where('contractor_id', Auth::id())->paginate(10);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'category' => 'required|in:residential,commercial,industrial',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->where(fn ($q) => $q->where('contractor_id', Auth::id())),
            ],
            'email_2' => 'nullable|email|max:255',
            'email_3' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'required|string|max:20',
            'phone_3' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $validated['contractor_id'] = Auth::id();
        
        // Auto-link to existing user account (client role) by email
        $maybeUser = User::whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
        if ($maybeUser && $maybeUser->isClient()) {
            $validated['user_id'] = $maybeUser->id;
        }
        
        Client::create($validated);

        return redirect()->route('contractor.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'category' => 'required|in:residential,commercial,industrial',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($client->id)->where(fn ($q) => $q->where('contractor_id', Auth::id())),
            ],
            'email_2' => 'nullable|email|max:255',
            'email_3' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'required|string|max:20',
            'phone_3' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        // Refresh link if email changed
        $maybeUser = User::whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
        $validated['user_id'] = ($maybeUser && $maybeUser->isClient()) ? $maybeUser->id : null;

        $client->update($validated);

        return redirect()->route('contractor.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->contractor_id !== Auth::id()) {
            abort(404);
        }
        
        $client->delete();

        return redirect()->route('contractor.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
