<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\UserTypeController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ContractorFeedbackController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Test route for dashboard component
Route::get('/test-dashboard', function () {
    // Create a mock user for testing
    $user = new \App\Models\User();
    $user->id = 1;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->user_type = 'contractor';
    
    // Mock authentication
    auth()->login($user);
    
    return view('test-dashboard');
});

// Test route for subscription system
Route::get('/test-subscription', function () {
    return view('test-subscription');
});

// Subscription routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/profile', [SubscriptionController::class, 'profile'])->name('subscription.profile');
    Route::post('/subscription/store', [SubscriptionController::class, 'store'])->name('subscription.store');
});

// Main dashboard route that redirects to the appropriate dashboard based on user type
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Type-specific dashboard routes
Route::get('/dashboard/client', [DashboardController::class, 'clientDashboard'])->name('dashboard.client');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/contractor', [DashboardController::class, 'contractorDashboard'])->name('dashboard.contractor');
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');

    // Client portal (client views)
    Route::prefix('dashboard/client')->group(function () {
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('client.dashboard');
        Route::get('schedules', [ClientPortalController::class, 'schedules'])->name('client.schedules');
        Route::get('invoices', [ClientPortalController::class, 'invoices'])->name('client.invoices');
        Route::view('support', 'client_portal.support')->name('client.support');
        Route::post('feedback', [ClientPortalController::class, 'storeFeedback'])->name('client.feedback.store');
    });
    
    // Contractor routes
    Route::prefix('dashboard/contractor')->group(function () {
        Route::resource('clients', ClientController::class)->names([
            'index' => 'contractor.clients.index',
            'create' => 'contractor.clients.create',
            'store' => 'contractor.clients.store',
            'show' => 'contractor.clients.show',
            'edit' => 'contractor.clients.edit',
            'update' => 'contractor.clients.update',
            'destroy' => 'contractor.clients.destroy'
        ]);
        Route::get('feedback', [ContractorFeedbackController::class, 'index'])->name('contractor.feedback.index');
    });
    
    // Schedule management for contractors
    Route::resource('schedules', ScheduleController::class);
    
    // Invoice management for contractors
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::patch('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
});

/*Route::middleware('auth')->group(function () {*/
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{product}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    /* Route::get('/product/{product}/choice', [ProductController::class, 'choice'])->name('product.choice');*/

    
    
    
    
    // Registration routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Client registration routes
Route::get('/register/client', [UserTypeController::class, 'createClient'])->name('register.client');
Route::post('/register/client', [UserTypeController::class, 'storeClient'])->name('register.client.store');

// Contractor registration routes
Route::get('/register/contractor', [UserTypeController::class, 'createContractor'])->name('register.contractor');
Route::post('/register/contractor', [UserTypeController::class, 'storeContractor'])->name('register.contractor.store');

// Login routes for different user types
Route::get('/login/admin', [UserTypeController::class, 'loginAdmin'])->name('login.admin');
Route::post('/login/admin', [UserTypeController::class, 'authenticateAdmin'])->name('login.admin.authenticate');

Route::get('/login/client', [UserTypeController::class, 'loginClient'])->name('login.client');
Route::post('/login/client', [UserTypeController::class, 'authenticateClient'])->name('login.client.authenticate');

Route::get('/login/contractor', [UserTypeController::class, 'loginContractor'])->name('login.contractor');
Route::post('/login/contractor', [UserTypeController::class, 'authenticateContractor'])->name('login.contractor.authenticate');

// Admin registration routes
Route::get('/register/admin', [UserTypeController::class, 'createAdmin'])->name('register.admin');
Route::post('/register/admin', [UserTypeController::class, 'storeAdmin'])->name('register.admin.store');

// Public location validation for registration
Route::post('/location/validate-public', [App\Http\Controllers\LocationController::class, 'validateLocationAccuracy'])->name('location.validate.public');
Route::post('/location/reverse-geocode', [App\Http\Controllers\LocationController::class, 'reverseGeocode'])->name('location.reverse.geocode');

// Location and mapping routes
Route::middleware(['auth'])->group(function () {
    Route::post('/location/update', [App\Http\Controllers\LocationController::class, 'updateContractorLocation'])->name('location.update');
    Route::get('/location/contractors', [App\Http\Controllers\LocationController::class, 'getContractorLocations'])->name('location.contractors');
    Route::get('/location/clients', [App\Http\Controllers\LocationController::class, 'getClientLocations'])->name('location.clients');
    Route::post('/location/geocode', [App\Http\Controllers\LocationController::class, 'geocodeAddress'])->name('location.geocode');
    Route::post('/location/validate', [App\Http\Controllers\LocationController::class, 'validateLocationAccuracy'])->name('location.validate');
    
    // Admin routes
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/contractors/locations', [App\Http\Controllers\AdminController::class, 'getContractorLocations'])->name('admin.contractors.locations');
    });
    
    // Contractor routes
    Route::middleware(['auth'])->prefix('contractor')->group(function () {
        Route::get('/clients/locations', [App\Http\Controllers\ContractorController::class, 'getAssignedClients'])->name('contractor.clients.locations');
    });
});

require __DIR__.'/auth.php';
