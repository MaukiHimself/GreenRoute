<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Client;
use App\Services\ClientInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ContractorLinkingController extends Controller
{
    /**
     * Assign a contractor to a client using registration numbers
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignContractorToClient(Request $request)
    {
        $validated = $request->validate([
            'contractor_registration_number' => 'required|string|exists:contractors,registration_number',
            'client_registration_number' => 'required|string|exists:clients,registration_number'
        ]);

        try {
            $contractor = Contractor::where('registration_number', $validated['contractor_registration_number'])
                ->firstOrFail();
            
            $client = Client::where('registration_number', $validated['client_registration_number'])
                ->firstOrFail();

            // Link contractor to client
            $contractor->client_registration_number = $validated['client_registration_number'];
            $contractor->save();

            return response()->json([
                'success' => true,
                'message' => 'Contractor successfully linked to client',
                'data' => [
                    'contractor' => [
                        'id' => $contractor->id,
                        'registration_number' => $contractor->registration_number,
                        'company_name' => $contractor->company_name,
                        'assigned_to' => $contractor->client_registration_number
                    ],
                    'client' => [
                        'id' => $client->id,
                        'registration_number' => $client->registration_number,
                        'name' => $client->name
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to link contractor to client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unlink a contractor from their assigned client
     * 
     * @param string $contractorRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkContractor($contractorRegistrationNumber)
    {
        try {
            $contractor = Contractor::where('registration_number', $contractorRegistrationNumber)
                ->firstOrFail();

            $contractor->client_registration_number = null;
            $contractor->save();

            return response()->json([
                'success' => true,
                'message' => 'Contractor successfully unlinked from client',
                'data' => [
                    'contractor_registration_number' => $contractor->registration_number
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unlink contractor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contractor's assigned client information
     * 
     * @param string $contractorRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractorAssignment($contractorRegistrationNumber)
    {
        try {
            $contractor = Contractor::where('registration_number', $contractorRegistrationNumber)
                ->with('assignedClient')
                ->firstOrFail();

            if (!$contractor->client_registration_number) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contractor is not assigned to any client',
                    'data' => [
                        'contractor_registration_number' => $contractor->registration_number,
                        'assigned_client' => null
                    ]
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contractor assignment retrieved successfully',
                'data' => [
                    'contractor' => [
                        'registration_number' => $contractor->registration_number,
                        'company_name' => $contractor->company_name
                    ],
                    'assigned_client' => [
                        'registration_number' => $contractor->assignedClient->registration_number,
                        'name' => $contractor->assignedClient->name,
                        'email' => $contractor->assignedClient->email,
                        'phone' => $contractor->assignedClient->phone,
                        'address' => $contractor->assignedClient->address
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contractor assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all contractors linked to a specific client
     * 
     * @param string $clientRegistrationNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientContractors($clientRegistrationNumber)
    {
        try {
            $client = Client::where('registration_number', $clientRegistrationNumber)
                ->firstOrFail();

            $contractors = Contractor::where('client_registration_number', $clientRegistrationNumber)
                ->get(['id', 'registration_number', 'company_name', 'email', 'phone']);

            return response()->json([
                'success' => true,
                'message' => 'Client contractors retrieved successfully',
                'data' => [
                    'client' => [
                        'registration_number' => $client->registration_number,
                        'name' => $client->name
                    ],
                    'contractors' => $contractors,
                    'count' => $contractors->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client contractors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new client and send invitation email
     * 
     * @param Request $request
     * @param ClientInvitationService $invitationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClientWithInvitation(Request $request, ClientInvitationService $invitationService)
    {
        $validated = $request->validate([
            'contractor_registration_number' => 'required|string|exists:contractors,registration_number',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'status' => 'nullable|string',
            'send_invitation' => 'nullable|boolean',
            'create_user_account' => 'nullable|boolean',
        ]);

        try {
            $contractor = Contractor::where('registration_number', $validated['contractor_registration_number'])
                ->firstOrFail();

            // Remove contractor registration number from client data
            $clientData = $validated;
            unset($clientData['contractor_registration_number']);
            unset($clientData['send_invitation']);
            unset($clientData['create_user_account']);

            $sendInvitation = $validated['send_invitation'] ?? true;
            $createUserAccount = $validated['create_user_account'] ?? true;

            if ($sendInvitation) {
                // Use invitation service
                $result = $invitationService->createClientWithInvitation(
                    $clientData,
                    $contractor,
                    $createUserAccount
                );

                $client = $result['client'];
                $user = $result['user'];
                $temporaryPassword = $result['password'];
            } else {
                // Create client without invitation
                $client = Client::create($clientData);
                $user = null;
                $temporaryPassword = null;
            }

            // Link contractor to client
            $contractor->client_registration_number = $client->registration_number;
            $contractor->save();

            return response()->json([
                'success' => true,
                'message' => $sendInvitation 
                    ? 'Client created and invitation email sent successfully'
                    : 'Client created successfully (no invitation sent)',
                'data' => [
                    'client' => [
                        'id' => $client->id,
                        'registration_number' => $client->registration_number,
                        'name' => $client->name,
                        'email' => $client->email
                    ],
                    'contractor' => [
                        'registration_number' => $contractor->registration_number,
                        'company_name' => $contractor->company_name
                    ],
                    'user_account_created' => $user ? true : false,
                    'temporary_password' => $temporaryPassword, // Include for contractor's reference
                    'invitation_sent' => $sendInvitation
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend invitation email to an existing client
     * 
     * @param string $clientRegistrationNumber
     * @param ClientInvitationService $invitationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendInvitation($clientRegistrationNumber, ClientInvitationService $invitationService)
    {
        try {
            $client = Client::where('registration_number', $clientRegistrationNumber)
                ->firstOrFail();

            // Find contractor linked to this client
            $contractor = Contractor::where('client_registration_number', $clientRegistrationNumber)
                ->firstOrFail();

            $sent = $invitationService->resendInvitation($client, $contractor);

            if ($sent) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invitation email resent successfully',
                    'data' => [
                        'client_registration_number' => $client->registration_number,
                        'email' => $client->email
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation email. Client may not have an email address.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend invitation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
