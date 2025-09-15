<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\InvoiceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for Contractors
Route::middleware(['auth:sanctum'])->group(function () {
    // Client Management API
    Route::apiResource('clients', ClientController::class);
    
    // Schedule Management API
    Route::apiResource('schedules', ScheduleController::class);
    
    // Invoice Management API
    Route::apiResource('invoices', InvoiceController::class);
    Route::get('invoices/{id}/pdf', [InvoiceController::class, 'pdf']);
    Route::patch('invoices/{id}/mark-paid', [InvoiceController::class, 'markPaid']);
});