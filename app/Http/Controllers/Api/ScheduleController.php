<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
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

            $schedules = Schedule::where('contractor_id', $user->id)
                ->with(['client'])
                ->orderBy('pickup_date', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $schedules,
                'message' => 'Schedules retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve schedules',
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
                'client_id' => 'required|exists:clients,id',
                'pickup_date' => 'required|date|after_or_equal:today',
                'pickup_time' => 'required|date_format:H:i',
                'pickup_location' => 'required|string|max:500',
                'service_type' => 'required|in:Waste Collection,Recycling,Hazardous Waste,Bulk Pickup,Other',
                'status' => 'in:scheduled,in_progress,completed,cancelled',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Verify client belongs to contractor
            $client = Client::where('id', $validated['client_id'])
                ->where('contractor_id', $user->id)
                ->firstOrFail();

            $schedule = Schedule::create([
                'contractor_id' => $user->id,
                'client_id' => $validated['client_id'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'pickup_location' => $validated['pickup_location'],
                'service_type' => $validated['service_type'],
                'status' => $validated['status'] ?? 'scheduled',
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $schedule->load('client'),
                'message' => 'Schedule created successfully'
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
                'message' => 'Failed to create schedule',
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

            $schedule = Schedule::where('contractor_id', $user->id)
                ->with('client')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $schedule,
                'message' => 'Schedule retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
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

            $schedule = Schedule::where('contractor_id', $user->id)->findOrFail($id);

            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'pickup_date' => 'required|date',
                'pickup_time' => 'required|date_format:H:i',
                'pickup_location' => 'required|string|max:500',
                'service_type' => 'required|in:Waste Collection,Recycling,Hazardous Waste,Bulk Pickup,Other',
                'status' => 'in:scheduled,in_progress,completed,cancelled',
                'notes' => 'nullable|string|max:1000'
            ]);

            // Verify client belongs to contractor
            $client = Client::where('id', $validated['client_id'])
                ->where('contractor_id', $user->id)
                ->firstOrFail();

            $schedule->update($validated);

            return response()->json([
                'success' => true,
                'data' => $schedule->load('client'),
                'message' => 'Schedule updated successfully'
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
                'message' => 'Failed to update schedule',
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

            $schedule = Schedule::where('contractor_id', $user->id)->findOrFail($id);
            
            // Prevent deletion if schedule is completed
            if ($schedule->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete completed schedule'
                ], 409);
            }

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
