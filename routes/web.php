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
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PaymentSubmissionController;
use App\Http\Controllers\ContractorPaymentApprovalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing');
});

// Legal pages
Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

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

// Test route for Location API
Route::get('/test-locations', function () {
    return view('location-test');
});

// Test route for OpenStreetMap / Leaflet
Route::get('/test-maps', function () {
    return view('test-maps');
});

// Debug route for API testing
Route::get('/debug-api', function () {
    return view('debug-api');
});

// Main dashboard route that redirects to the appropriate dashboard based on user type
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Client portal routes (auth only, no verification required for invited clients)
Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard/client')->group(function () {
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('client.dashboard');
        Route::get('profile', [ClientPortalController::class, 'profile'])->name('client.profile');
        Route::put('profile', [ClientPortalController::class, 'updateProfile'])->name('client.profile.update');
        Route::get('schedules', [ClientPortalController::class, 'schedules'])->name('client.schedules');
        Route::get('request-service', [ClientPortalController::class, 'requestService'])->name('client.request.service');
        Route::post('request-service', [ClientPortalController::class, 'storeServiceRequest'])->name('client.request.service.store');
        Route::get('equipment', [ClientPortalController::class, 'equipment'])->name('client.equipment');
        Route::get('contractor-info', [ClientPortalController::class, 'contractorInfo'])->name('client.contractor.info');
        Route::get('invoices', [ClientPortalController::class, 'invoices'])->name('client.invoices');
        Route::get('payments', [ClientPortalController::class, 'payments'])->name('client.payments');
        Route::get('chats', [ClientPortalController::class, 'chats'])->name('client.chats');
        Route::get('support', [ClientPortalController::class, 'support'])->name('client.support');
        Route::post('support', [ClientPortalController::class, 'storeSupport'])->name('client.support.submit');
        Route::get('feedback', [ClientPortalController::class, 'feedback'])->name('client.feedback');
        Route::post('feedback', [ClientPortalController::class, 'storeFeedback'])->name('client.feedback.store');

        // Payment Routes
        Route::get('payments/{invoice}/checkout', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('client.payments.checkout');
        Route::post('payments/{invoice}/mobile', [App\Http\Controllers\PaymentController::class, 'payMobile'])->name('client.payments.mobile');
        Route::post('payments/{invoice}/bank', [App\Http\Controllers\PaymentController::class, 'payBank'])->name('client.payments.bank');

        // Payment Submission Routes
        Route::get('invoices/{invoice}/payment-methods', [PaymentSubmissionController::class, 'showPaymentMethods'])->name('client.payment-methods');
        Route::get('invoices/{invoice}/payment-submission/{paymentMethod}', [PaymentSubmissionController::class, 'showSubmissionForm'])->name('client.payment-submission-form');
        Route::post('invoices/{invoice}/payment-submission', [PaymentSubmissionController::class, 'store'])->name('client.payment-submission.store');
        Route::get('invoices/{invoice}/payment-submitted', [PaymentSubmissionController::class, 'showSubmissionConfirmation'])->name('client.payment-submitted');
    });
});

// Contractor routes (require admin verification)
Route::middleware(['auth', 'verified.contractor'])->group(function () {
    Route::get('/dashboard/contractor', [DashboardController::class, 'contractorDashboard'])->name('dashboard.contractor');

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

        // Client invitation management
        Route::post('clients/{client}/resend-invitation', [ClientController::class, 'resendInvitation'])
            ->name('contractor.clients.resend-invitation');
        Route::post('clients/{client}/reset-password', [ClientController::class, 'resetPassword'])
            ->name('contractor.clients.reset-password');

        Route::get('feedback', [ContractorFeedbackController::class, 'index'])->name('contractor.feedback.index');
    });

    // Invoice management for contractors
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::patch('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');

    // Payment Approval Routes for Contractors
    Route::prefix('dashboard/contractor')->group(function () {
        Route::get('pending-payment-approvals', [ContractorPaymentApprovalController::class, 'showPendingApprovals'])->name('contractor.pending-payments');
        Route::get('payment-approvals/stats', [ContractorPaymentApprovalController::class, 'getStats'])->name('contractor.payment-stats');
    });

    Route::post('payment-submissions/{submission}/approve', [ContractorPaymentApprovalController::class, 'approve'])->name('payment-submissions.approve');
    Route::post('payment-submissions/{submission}/reject', [ContractorPaymentApprovalController::class, 'reject'])->name('payment-submissions.reject');
    Route::get('payment-submissions/{submission}/receipt/download', [ContractorPaymentApprovalController::class, 'downloadReceipt'])->name('payment-submissions.receipt.download');
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

// Client Authentication Routes
Route::prefix('client')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\ClientAuthController::class, 'showLogin'])->name('client.login');
    Route::post('/login', [App\Http\Controllers\Auth\ClientAuthController::class, 'login'])->name('client.login.submit');

    // Self-registration (public — no auth required)
    Route::get('/signup',         [App\Http\Controllers\ClientSelfRegistrationController::class, 'showForm'])->name('client.self-register');
    Route::post('/signup',        [App\Http\Controllers\ClientSelfRegistrationController::class, 'store'])->name('client.self-register.store');
    Route::get('/signup/pending', [App\Http\Controllers\ClientSelfRegistrationController::class, 'pendingPage'])->name('client.registration.pending');

    // AJAX: contractor routes for a region
    Route::get('/registration/routes', [App\Http\Controllers\ClientSelfRegistrationController::class, 'getContractorRoutes'])->name('client.registration.routes');

    // Legacy activation (contractor-created clients)
    Route::get('/register', [App\Http\Controllers\Auth\ClientAuthController::class, 'showRegister'])->name('client.register');
    Route::post('/register', [App\Http\Controllers\Auth\ClientAuthController::class, 'register'])->name('client.register.submit');

    Route::get('/verify-phone', [App\Http\Controllers\Auth\ClientAuthController::class, 'showVerifyPhone'])->name('client.verify-phone');
    Route::post('/verify-phone', [App\Http\Controllers\Auth\ClientAuthController::class, 'verifyPhone'])->name('client.verify-phone.submit');

    Route::get('/set-password', [App\Http\Controllers\Auth\ClientAuthController::class, 'showSetPassword'])->name('client.set-password');
    Route::post('/set-password', [App\Http\Controllers\Auth\ClientAuthController::class, 'setPassword'])->name('client.set-password.submit');
});

// Contractor: approve / reject self-registered clients
Route::middleware(['auth', 'verified.contractor'])->group(function () {
    Route::get('/contractor/pending-clients',
        [App\Http\Controllers\ClientSelfRegistrationController::class, 'pendingApprovals'])
        ->name('contractor.clients.pending');

    Route::post('/contractor/pending-clients/{client}/approve',
        [App\Http\Controllers\ClientSelfRegistrationController::class, 'approve'])
        ->name('contractor.clients.approve');

    Route::post('/contractor/pending-clients/{client}/reject',
        [App\Http\Controllers\ClientSelfRegistrationController::class, 'reject'])
        ->name('contractor.clients.reject');
});

Route::get('/register/contractor', [UserTypeController::class, 'createContractor'])->name('register.contractor');
Route::post('/register/contractor', [UserTypeController::class, 'storeContractor'])->name('register.contractor.store');

// Contractor pending approval page
Route::get('/contractor/pending', function () {
    return view('contractor.pending');
})->name('contractor.pending');

// Login routes for different user types
Route::get('/login/client', [UserTypeController::class, 'loginClient'])->name('login.client');
Route::post('/login/client', [UserTypeController::class, 'authenticateClient'])->name('login.client.authenticate');

Route::get('/login/contractor', [UserTypeController::class, 'loginContractor'])->name('login.contractor');
Route::post('/login/contractor', [UserTypeController::class, 'authenticateContractor'])->name('login.contractor.authenticate');

// Admin registration routes
Route::get('/register/admin', [UserTypeController::class, 'createAdmin'])->name('register.admin');
Route::post('/register/admin', [UserTypeController::class, 'storeAdmin'])->name('register.admin.store');

// Admin login routes (separate from main login)
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'admin') {
                return redirect()->route('dashboard.admin');
            }

            Auth::logout();
        }

        // Clear any stale session before rendering the login page
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return response(view('auth.admin-login'))
            ->header('Cache-Control', 'no-store, no-cache, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    })->name('admin.login');


    Route::post('/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'store'])
        ->name('admin.login.submit');


    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    })->name('admin.logout')->middleware('auth');
});

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

    // Location Hierarchy API (for dependent dropdowns)
    Route::get('/location/regions', [App\Http\Controllers\LocationController::class, 'getRegions'])->name('location.regions');
    Route::get('/location/districts', [App\Http\Controllers\LocationController::class, 'getDistricts'])->name('location.districts');
    Route::get('/location/wards', [App\Http\Controllers\LocationController::class, 'getWards'])->name('location.wards');
    Route::get('/location/streets', [App\Http\Controllers\LocationController::class, 'getStreets'])->name('location.streets');
});

// Admin routes (protected with admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard.admin');
        // Contractor Verification & Management
        Route::get('/verification', [App\Http\Controllers\AdminController::class, 'verification'])->name('admin.verification');
        Route::get('/contractors/{user}', [App\Http\Controllers\AdminController::class, 'showContractor'])->name('admin.contractors.show');
        Route::post('/contractors/{user}/approve', [App\Http\Controllers\AdminController::class, 'approveContractor'])->name('admin.contractors.approve');
        Route::post('/contractors/{user}/reject', [App\Http\Controllers\AdminController::class, 'rejectContractor'])->name('admin.contractors.reject');
        Route::post('/contractors/{user}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleContractorStatus'])->name('admin.contractors.toggle');
        Route::get('/clients', [App\Http\Controllers\AdminController::class, 'clients'])->name('admin.clients');
        Route::get('/clients/create', [App\Http\Controllers\AdminController::class, 'createClient'])->name('admin.clients.create');
        Route::post('/clients', [App\Http\Controllers\AdminController::class, 'storeClient'])->name('admin.clients.store');
        Route::get('/clients/{client}/edit', [App\Http\Controllers\AdminController::class, 'editClient'])->name('admin.clients.edit');
        Route::put('/clients/{client}', [App\Http\Controllers\AdminController::class, 'updateClient'])->name('admin.clients.update');
        Route::delete('/clients/{client}', [App\Http\Controllers\AdminController::class, 'deleteClient'])->name('admin.clients.delete');
        Route::get('/billing', [App\Http\Controllers\AdminController::class, 'billing'])->name('admin.billing');
        Route::get('/schedules', [App\Http\Controllers\AdminController::class, 'schedules'])->name('admin.schedules');
        Route::get('/schedules/{schedule}/edit', [App\Http\Controllers\AdminController::class, 'editSchedule'])->name('admin.schedules.edit');
        Route::put('/schedules/{schedule}', [App\Http\Controllers\AdminController::class, 'updateSchedule'])->name('admin.schedules.update');
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/contractors/locations', [App\Http\Controllers\AdminController::class, 'getContractorLocations'])->name('admin.contractors.locations');

        // SMS Campaign routes
        Route::get('/sms-campaign', [App\Http\Controllers\AdminController::class, 'smsCampaign'])->name('admin.sms.campaign');
        Route::post('/sms-campaign/send', [App\Http\Controllers\AdminController::class, 'sendSmsCampaign'])->name('admin.sms.send');

        // Billing Rates Management routes
        Route::get('/billing-rates', [App\Http\Controllers\AdminController::class, 'billingRates'])->name('admin.billing.rates');
        Route::get('/billing-rates/create', [App\Http\Controllers\AdminController::class, 'createBillingRate'])->name('admin.billing.rates.create');
        Route::post('/billing-rates', [App\Http\Controllers\AdminController::class, 'storeBillingRate'])->name('admin.billing.rates.store');
        Route::get('/billing-rates/{rate}/edit', [App\Http\Controllers\AdminController::class, 'editBillingRate'])->name('admin.billing.rates.edit');
        Route::put('/billing-rates/{rate}', [App\Http\Controllers\AdminController::class, 'updateBillingRate'])->name('admin.billing.rates.update');
        Route::delete('/billing-rates/{rate}', [App\Http\Controllers\AdminController::class, 'deleteBillingRate'])->name('admin.billing.rates.delete');

        // CSV Management routes
        Route::get('/csv-management', function() {
            return view('admin.csv-management');
        })->name('admin.csv.management');
    });

    // Contractor routes
Route::middleware(['auth'])->prefix('contractor')->group(function () {
     Route::get('/clients/locations', [App\Http\Controllers\ContractorController::class, 'getAssignedClients'])->name('contractor.clients.locations');
     Route::get('/dashboard-stats', [App\Http\Controllers\ContractorController::class, 'getDashboardStats']);
     Route::get('/recent-invoices', [App\Http\Controllers\ContractorController::class, 'getRecentInvoices']);
     Route::get('/recent-payments', [App\Http\Controllers\ContractorController::class, 'getRecentPayments'])->name('contractor.recent-payments');
     Route::get('/upcoming-schedules', [App\Http\Controllers\ContractorController::class, 'getUpcomingSchedules']);
     Route::get('/recent-pending-payments', [App\Http\Controllers\ContractorController::class, 'getRecentPendingPayments'])->name('contractor.recent-pending-payments');
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit']);
});

    // Billing routes
    Route::middleware(['auth'])->prefix('billing')->group(function () {
        Route::get('/', [App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
        Route::get('/create', [App\Http\Controllers\BillingController::class, 'create'])->name('billing.create');
        Route::post('/', [App\Http\Controllers\BillingController::class, 'store'])->name('billing.store');
        Route::get('/{invoice}', [App\Http\Controllers\BillingController::class, 'show'])->name('billing.show');
        Route::post('/{invoice}/mark-paid', [App\Http\Controllers\BillingController::class, 'markPaid'])->name('billing.mark-paid');
        Route::post('/{invoice}/send', [App\Http\Controllers\BillingController::class, 'sendInvoice'])->name('billing.send');
        Route::post('/{invoice}/remind', [App\Http\Controllers\BillingController::class, 'sendReminder'])->name('billing.remind');
    });

    // Schedule routes
    Route::middleware(['auth'])->prefix('schedules')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::get('/{schedule}/print', [ScheduleController::class, 'print'])->name('schedules.print');
        Route::post('/{schedule}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.status');
    });

    // Disposal routes
    Route::middleware(['auth'])->prefix('disposal')->group(function () {
        Route::get('/', [App\Http\Controllers\DisposalController::class, 'index'])->name('disposal.index');
        Route::get('/{schedule}', [App\Http\Controllers\DisposalController::class, 'show'])->name('disposal.show');
        Route::get('/{schedule}/edit', [App\Http\Controllers\DisposalController::class, 'edit'])->name('disposal.edit');
        Route::put('/{schedule}', [App\Http\Controllers\DisposalController::class, 'update'])->name('disposal.update');
    });

    // SMS routes
    Route::middleware(['auth'])->prefix('sms')->group(function () {
        Route::get('/', [App\Http\Controllers\SmsController::class, 'index'])->name('sms.index');
        Route::post('/send', [App\Http\Controllers\SmsController::class, 'send'])->name('sms.send');
        Route::get('/inbox', [App\Http\Controllers\SmsController::class, 'inbox'])->name('sms.inbox');
        Route::get('/conversation/{client}', [App\Http\Controllers\SmsController::class, 'conversation'])->name('sms.conversation');
        Route::post('/conversation/{client}', [App\Http\Controllers\SmsController::class, 'sendMessage'])->name('sms.sendMessage');
        Route::get('/template', [App\Http\Controllers\SmsController::class, 'getTemplate'])->name('sms.template');
    });

    // Client SMS API (for clients to send messages to contractors)
    Route::post('/api/sms/client-send', [App\Http\Controllers\SmsController::class, 'clientSend'])->name('api.sms.clientSend');

    // Route Optimization routes
    Route::middleware(['auth'])->prefix('routes')->group(function () {
        Route::get('/', [App\Http\Controllers\RouteOptimizationController::class, 'index'])->name('routes.index');
        Route::post('/optimize', [App\Http\Controllers\RouteOptimizationController::class, 'optimize'])->name('routes.optimize');
    });

    // Reports routes
    Route::middleware(['auth'])->prefix('reports')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/export', [App\Http\Controllers\ReportsController::class, 'export'])->name('reports.export');
    });

    // GPS Tracker routes
    Route::middleware(['auth'])->prefix('trucks')->group(function () {
        Route::get('/', [App\Http\Controllers\TruckController::class, 'index'])->name('trucks.index');
        Route::post('/', [App\Http\Controllers\TruckController::class, 'store'])->name('trucks.store');
        Route::post('/{truck}/location', [App\Http\Controllers\TruckController::class, 'updateLocation'])->name('trucks.location');
        Route::get('/locations', [App\Http\Controllers\TruckController::class, 'getLocations'])->name('trucks.locations');
        Route::delete('/{truck}', [App\Http\Controllers\TruckController::class, 'destroy'])->name('trucks.destroy');
    });

    // Route Management routes (for creating and managing collection routes)
    Route::middleware(['auth'])->prefix('route-management')->group(function () {
        Route::get('/', [App\Http\Controllers\RouteManagementController::class, 'index'])->name('route-management.index');
        Route::get('/create', [App\Http\Controllers\RouteManagementController::class, 'create'])->name('route-management.create');
        Route::post('/', [App\Http\Controllers\RouteManagementController::class, 'store'])->name('route-management.store');
        Route::get('/{contractorRoute}', [App\Http\Controllers\RouteManagementController::class, 'show'])->name('route-management.show');
        Route::get('/{contractorRoute}/edit', [App\Http\Controllers\RouteManagementController::class, 'edit'])->name('route-management.edit');
        Route::put('/{contractorRoute}', [App\Http\Controllers\RouteManagementController::class, 'update'])->name('route-management.update');
        Route::delete('/{contractorRoute}', [App\Http\Controllers\RouteManagementController::class, 'destroy'])->name('route-management.destroy');
    });

    // Location autocomplete for schedule creation (searches tbl_locations)
    Route::middleware(['auth'])->get('/location/autocomplete', [App\Http\Controllers\LocationController::class, 'autocomplete'])->name('location.autocomplete');

// Driver Public Tracking Routes (No login required)
Route::get('/driver/track/{token}', [App\Http\Controllers\TruckController::class, 'driverTrack'])->name('driver.track');
Route::post('/driver/location/{token}', [App\Http\Controllers\TruckController::class, 'updateLocationByToken'])->name('driver.location');

require __DIR__.'/auth.php';
