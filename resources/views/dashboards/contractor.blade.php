<x-dashboard-layout title="Contractor Dashboard">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard.contractor') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('clients.index') }}">
                    <i class="bi bi-people me-2"></i>Client Database
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('schedules.index') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedule Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('invoices.index') }}">
                    <i class="bi bi-receipt me-2"></i>Invoice Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-credit-card me-2"></i>Billing & Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-chat-dots me-2"></i>SMS Manager
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-geo-alt me-2"></i>Route Optimization
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-gps me-2"></i>GPS Tracker
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-graph-up me-2"></i>Reports & Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('contractor.feedback.index') }}">
                    <i class="bi bi-chat-dots me-2"></i>Client Feedback
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <x-slot name="notificationCount">2</x-slot>

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-dark">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-muted mb-0">Here's what's happening with your waste management business today.</p>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people-fill text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">24</div>
                            <div class="text-muted small">Active Clients</div>
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
                                <i class="bi bi-truck text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">12</div>
                            <div class="text-muted small">Total Routes</div>
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
                                <i class="bi bi-check-circle text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">36</div>
                            <div class="text-muted small">Completed Jobs</div>
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
                            <div class="h4 mb-0 text-dark">$2,450</div>
                            <div class="text-muted small">Monthly Revenue</div>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Dashboard Tabs -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark">
                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Overview
                        </h5>
                        <div class="btn-group" role="group" aria-label="Dashboard tabs">
                            <button type="button" class="btn btn-primary active" id="routes-tab" data-bs-toggle="tab" data-bs-target="#routes" role="tab">
                                <i class="bi bi-route me-1"></i>Routes
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-primary" id="clients-tab-link" role="tab">
                                <i class="bi bi-people me-1"></i>Clients
                            </a>
                            <button type="button" class="btn btn-outline-primary" id="schedules-tab" data-bs-toggle="tab" data-bs-target="#schedules" role="tab">
                                <i class="bi bi-calendar3 me-1"></i>Schedules
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dashboardTabContent">
                        <!-- Routes Tab -->
                        <div class="tab-pane fade show active" id="routes" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-muted">Active Routes</h6>
                                <a href="{{ route('schedules.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Add Route
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Route</th>
                                            <th>Clients</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                            <th>Action</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                        <i class="bi bi-geo-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Route A</div>
                                                        <small class="text-muted">Downtown Area</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary">15 Clients</span></td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                                </div>
                                                <small class="text-muted">75% Complete</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" title="Edit Route">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </div>
                                        </td>
                                    </tr>
                                    <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                        <i class="bi bi-geo-alt text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Route B</div>
                                                        <small class="text-muted">Suburban Area</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning">9 Clients</span></td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" style="width: 30%"></div>
                                                </div>
                                                <small class="text-muted">30% Complete</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-warning" title="Start Route">
                                                        <i class="bi bi-play-circle"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <!-- Clients Tab -->
                        <!-- Removed: Clients content now lives at /dashboard/contractor/clients -->
                        
                        <!-- Schedules Tab -->
                        <div class="tab-pane fade" id="schedules" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-muted">Upcoming Schedules</h6>
                                <a href="{{ route('schedules.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-calendar-plus me-1"></i>New Schedule
                                </a>
                            </div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-calendar-check text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Route A - Downtown</h6>
                                            <small class="text-muted">Tomorrow, 8:00 AM - 12:00 PM</small>
                                        </div>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-success">Start</button>
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    </div>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-calendar text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Route B - Suburban</h6>
                                            <small class="text-muted">Friday, 2:00 PM - 6:00 PM</small>
                                        </div>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-warning">Prepare</button>
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Performance -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
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
                            <a href="{{ route('clients.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-1 me-2">
                                        <i class="bi bi-person-plus text-white"></i>
                                    </div>
                                    <span>Add New Client</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('schedules.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-calendar-plus text-primary"></i>
                                    </div>
                                    <span>Schedule Pickup</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ route('invoices.create') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-receipt text-success"></i>
                                    </div>
                                    <span>Create Invoice</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="#" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-graph-up text-info"></i>
                                    </div>
                                    <span>View Reports</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3 text-muted">Today's Summary</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="bg-primary bg-opacity-10 rounded p-2">
                                    <div class="h5 mb-0 text-primary">8</div>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-warning bg-opacity-10 rounded p-2">
                                    <div class="h5 mb-0 text-warning">3</div>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="bi bi-bar-chart me-2 text-info"></i>Weekly Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between" style="height: 200px;">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 60px;"></div>
                            <small class="text-muted mt-2">Mon</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 80px;"></div>
                            <small class="text-muted mt-2">Tue</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 120px;"></div>
                            <small class="text-muted mt-2">Wed</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 90px;"></div>
                            <small class="text-muted mt-2">Thu</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 140px;"></div>
                            <small class="text-muted mt-2">Fri</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 100px;"></div>
                            <small class="text-muted mt-2">Sat</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary rounded-top" style="width: 20px; height: 70px;"></div>
                            <small class="text-muted mt-2">Sun</small>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="bi bi-clock-history me-2 text-secondary"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Pickup Completed</div>
                                    <small class="text-muted">Route A - 15 clients served</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-person-plus text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">New Client Added</div>
                                    <small class="text-muted">John Smith - Downtown Area</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">4 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-receipt text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Invoice Generated</div>
                                    <small class="text-muted">INV-001 - $250.00</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">6 hours ago</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-calendar text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Schedule Updated</div>
                                    <small class="text-muted">Route B - 3 new pickups</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>