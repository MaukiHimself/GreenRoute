<x-dashboard-layout title="Client Dashboard">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard.client') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.chats') }}">
                    <i class="bi bi-chat-dots me-2"></i>Chats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.support') }}">
                    <i class="bi bi-headset me-2"></i>Support/Help
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Client</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <x-slot name="notificationCount">1</x-slot>

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-dark">Welcome, {{ Auth::user()->name }}!</h1>
                            <p class="text-muted mb-0">Manage your waste collection services and stay updated with your account.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end">
                                <div class="text-center me-4">
                                    <div class="h4 mb-0 text-primary">{{ date('d') }}</div>
                                    <small class="text-muted">{{ date('M Y') }}</small>
                                </div>
                                <div class="text-center">
                                    <div class="h4 mb-0 text-success">{{ date('l') }}</div>
                                    <small class="text-muted">{{ date('H:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <!-- Service Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-trash text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">Monthly</div>
                            <div class="text-muted small">Collection Frequency</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-check text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">{{ date('M d', strtotime('+15 days')) }}</div>
                            <div class="text-muted small">Next Collection</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">8:00 AM</div>
                            <div class="text-muted small">Collection Time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">$250</div>
                            <div class="text-muted small">Monthly Cost</div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Dashboard Tabs (now links) -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark">
                            <i class="bi bi-house me-2 text-primary"></i>My Services
                        </h5>
                        <div class="btn-group" role="group" aria-label="Client dashboard tabs">
                            <a href="{{ route('client.schedules') }}" class="btn btn-primary">
                                <i class="bi bi-calendar3 me-1"></i>Schedules
                            </a>
                            <a href="{{ route('client.invoices') }}" class="btn btn-outline-primary">
                                <i class="bi bi-receipt me-1"></i>Invoices
                            </a>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-clock-history me-1"></i>History
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">Use the buttons above to view your detailed schedules and invoices.</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark">
                            <i class="bi bi-lightning me-2 text-warning"></i>Quick Actions
                        </h5>
                        <span class="badge bg-warning text-dark">4 Actions</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <a href="{{ route('client.schedules') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-1 me-2">
                                        <i class="bi bi-calendar-plus text-white"></i>
                                    </div>
                                    <span>View Schedules</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('client.invoices') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-receipt text-primary"></i>
                                    </div>
                                    <span>View Invoices</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-success w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-credit-card text-success"></i>
                                    </div>
                                    <span>Make Payment</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-info w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-headset text-info"></i>
                                    </div>
                                    <span>Contact Support</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Service Status -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3 text-muted">Service Status</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">Active Service</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">Monthly Collection</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">Next: {{ date('M d', strtotime('+15 days')) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                            </div>

    <!-- Invoices Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark">
                            <i class="bi bi-receipt me-2 text-success"></i>My Invoices
                        </h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('client.invoices') }}" class="btn btn-outline-success btn-sm active">Open Invoices</a>
                            <a href="{{ route('client.invoices') }}" class="btn btn-outline-warning btn-sm">All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">See your full invoices list on the Invoices page.</p>
                </div>
            </div>
        </div>
    </div>

                <!-- Feedback Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="bi bi-chat-dots me-2 text-info"></i>Feedback Form
                    </h5>
                </div>
                <div class="card-body">
                            <form>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Your Feedback</label>
                                <textarea class="form-control" rows="4" placeholder="Please share your experience with our waste collection service..."></textarea>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Rating</label>
                                <div class="d-flex gap-1 mb-3">
                                    <button type="button" class="btn btn-outline-warning btn-sm p-2">
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm p-2">
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm p-2">
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm p-2">
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm p-2">
                                        <i class="bi bi-star"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-1"></i>Submit Feedback
                                </button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
                                    </div>
                                </div>

    <!-- Help Center -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="bi bi-question-circle me-2 text-secondary"></i>Help Center
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Quick Help</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-calendar-plus text-primary me-2"></i>How to schedule a pickup?
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-credit-card text-primary me-2"></i>Payment methods
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>Service areas
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-headset text-primary me-2"></i>Contact support
                                </a>
                                    </div>
                                </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Policy</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-file-text text-primary me-2"></i>Terms of Service
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-shield-check text-primary me-2"></i>Privacy Policy
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-x-circle text-primary me-2"></i>Cancellation Policy
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                                    <i class="bi bi-arrow-clockwise text-primary me-2"></i>Refund Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>