<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Client;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScheduleApiController extends Controller
{
    /**
     * Create a new schedule (Contractor creates for their assigned client)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contractor_registration_number' => 'required|string|exists:contractors,registration_number',
            'client_registration_number' => 'nullable|string|exists:clients,registration_number',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'pickup_address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'service_type' => 'required|in:collection,disposal,both',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'estimated_duration' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'disposal_site' => 'nullable|string',
            'disposal_type' => 'nullable|string',
            'disposal_notes' => 'nullable|string'
        ]);

        try {
            // Get contractor
            $contractor = Contractor::where('registration_number', $validated['contractor_registration_number'])
                ->firstOrFail();

            // If no client_registration_number provided, use contractor's assigned client
            $clientRegNumber = $validated['client_registration_number'] ?? $contractor->client_registration_number;

            if (!$clientRegNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'No client specified and contractor is not assigned to any client'
                ], 400);
            }

            // Get client by registration number
            $client = Client::where('registration_number', $clientRegNumber)->firstOrFail();

            // Get contractor_id and client_id for legacy relationships
            $contractorId = $contractor->user_id;
            $clientId = $client->id;

            // Create schedule
            $schedule = new Schedule($validated);
            $schedule->contractor_id = $contractorId;
            $schedule->client_id = $clientId;
            $schedule->contractor_registration_number = $contractor->registration_number;
            $schedule->client_registration_number = $clientRegNumber;
            $schedule->status = $validated['status'] ?? 'scheduled';
            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully',
                'data' => [
                    'schedule' => $schedule->load(['client', 'contractor'])
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all schedules for a specific client (by registration number)
     * 
     * @param string $clientRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientSchedules($clientRegistrationNumber)
    {
        try {
            $client = Client::where('registration_number', $clientRegistrationNumber)->firstOrFail();

            $schedules = Schedule::forClient($clientRegistrationNumber)
                ->with(['contractor'])
                ->orderBy('pickup_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Client schedules retrieved successfully',
                'data' => [
                    'client' => [
                        'registration_number' => $client->registration_number,
                        'name' => $client->name
                    ],
                    'schedules' => $schedules,
                    'count' => $schedules->count(),
                    'upcoming' => $schedules->where('pickup_date', '>=', now()->toDateString())->count(),
                    'completed' => $schedules->where('status', 'completed')->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client schedules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all schedules created by a specific contractor (by registration number)
     * 
     * @param string $contractorRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractorSchedules($contractorRegistrationNumber)
    {
        try {
            $contractor = Contractor::where('registration_number', $contractorRegistrationNumber)->firstOrFail();

            $schedules = Schedule::byContractorRegNumber($contractorRegistrationNumber)
                ->with(['client'])
                ->orderBy('pickup_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Contractor schedules retrieved successfully',
                'data' => [
                    'contractor' => [
                        'registration_number' => $contractor->registration_number,
                        'company_name' => $contractor->company_name
                    ],
                    'schedules' => $schedules,
                    'count' => $schedules->count(),
                    'upcoming' => $schedules->where('pickup_date', '>=', now()->toDateString())->count(),
                    'completed' => $schedules->where('status', 'completed')->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contractor schedules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific schedule by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $schedule = Schedule::with(['client', 'contractor', 'invoices'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Schedule retrieved successfully',
                'data' => [
                    'schedule' => $schedule
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a schedule
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pickup_date' => 'sometimes|date',
            'pickup_time' => 'sometimes|date_format:H:i',
            'pickup_location' => 'sometimes|string|max:255',
            'pickup_address' => 'sometimes|string',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'zip_code' => 'sometimes|string|max:10',
            'service_type' => 'sometimes|in:collection,disposal,both',
            'status' => 'sometimes|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'estimated_duration' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'disposal_site' => 'nullable|string',
            'disposal_type' => 'nullable|string',
            'disposal_notes' => 'nullable|string'
        ]);

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'data' => [
                    'schedule' => $schedule->fresh()->load(['client', 'contractor'])
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule status
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled'
        ]);

        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule status updated successfully',
                'data' => [
                    'schedule' => $schedule->fresh()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a schedule
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
