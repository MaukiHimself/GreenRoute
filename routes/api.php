<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractorLinkingController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\ScheduleApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return response()->json(['message' => 'API endpoint']);
});

// ===================================
// Contractor-Client Linking API
// ===================================

// Assign contractor to client
Route::post('/contractors/assign', [ContractorLinkingController::class, 'assignContractorToClient']);

// Unlink contractor from client
Route::delete('/contractors/{contractorRegistrationNumber}/unlink', [ContractorLinkingController::class, 'unlinkContractor']);

// Get contractor's assigned client
Route::get('/contractors/{contractorRegistrationNumber}/assignment', [ContractorLinkingController::class, 'getContractorAssignment']);

// Get all contractors linked to a client
Route::get('/clients/{clientRegistrationNumber}/contractors', [ContractorLinkingController::class, 'getClientContractors']);

// Create client with invitation email
Route::post('/clients/create-with-invitation', [ContractorLinkingController::class, 'createClientWithInvitation']);

// Resend invitation email to client
Route::post('/clients/{clientRegistrationNumber}/resend-invitation', [ContractorLinkingController::class, 'resendInvitation']);

// ===================================
// Invoice API Routes
// ===================================

// Create invoice
Route::post('/invoices', [InvoiceApiController::class, 'store']);

// Get invoices for a client (by registration number)
Route::get('/clients/{clientRegistrationNumber}/invoices', [InvoiceApiController::class, 'getClientInvoices']);

// Get invoices created by a contractor (by registration number)
Route::get('/contractors/{contractorRegistrationNumber}/invoices', [InvoiceApiController::class, 'getContractorInvoices']);

// Get, update, delete specific invoice
Route::get('/invoices/{id}', [InvoiceApiController::class, 'show']);
Route::put('/invoices/{id}', [InvoiceApiController::class, 'update']);
Route::patch('/invoices/{id}', [InvoiceApiController::class, 'update']);
Route::delete('/invoices/{id}', [InvoiceApiController::class, 'destroy']);

// Mark invoice as paid
Route::post('/invoices/{id}/mark-paid', [InvoiceApiController::class, 'markAsPaid']);

// ===================================
// Schedule API Routes
// ===================================

// Create schedule
Route::post('/schedules', [ScheduleApiController::class, 'store']);

// Get schedules for a client (by registration number)
Route::get('/clients/{clientRegistrationNumber}/schedules', [ScheduleApiController::class, 'getClientSchedules']);

// Get schedules created by a contractor (by registration number)
Route::get('/contractors/{contractorRegistrationNumber}/schedules', [ScheduleApiController::class, 'getContractorSchedules']);

// Get, update, delete specific schedule
Route::get('/schedules/{id}', [ScheduleApiController::class, 'show']);
Route::put('/schedules/{id}', [ScheduleApiController::class, 'update']);
Route::patch('/schedules/{id}', [ScheduleApiController::class, 'update']);
Route::delete('/schedules/{id}', [ScheduleApiController::class, 'destroy']);

// Update schedule status
Route::patch('/schedules/{id}/status', [ScheduleApiController::class, 'updateStatus']);