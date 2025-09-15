<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
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

            $clients = Client::where('contractor_id', $user->id)
                ->with(['schedules', 'invoices'])
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $clients,
                'message' => 'Clients retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clients',
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'company' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000'
            ]);

            $client = Client::create([
                'contractor_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'company' => $validated['company'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $client->load(['schedules', 'invoices']),
                'message' => 'Client created successfully'
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
                'message' => 'Failed to create client',
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

            $client = Client::where('contractor_id', $user->id)
                ->with(['schedules', 'invoices'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $client,
                'message' => 'Client retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
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

            $client = Client::where('contractor_id', $user->id)->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email,' . $client->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'company' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000'
            ]);

            $client->update($validated);

            return response()->json([
                'success' => true,
                'data' => $client->load(['schedules', 'invoices']),
                'message' => 'Client updated successfully'
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
                'message' => 'Failed to update client',
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

            $client = Client::where('contractor_id', $user->id)->findOrFail($id);
            
            // Check if client has associated schedules or invoices
            if ($client->schedules()->count() > 0 || $client->invoices()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete client with existing schedules or invoices'
                ], 409);
            }

            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
