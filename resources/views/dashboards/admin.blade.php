<x-dashboard-layout title="Administrator Dashboard">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard.admin') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-shield-check me-2"></i>Verification
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people me-2"></i>Client Information
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-credit-card me-2"></i>Billing & Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-person-gear me-2"></i>Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-graph-up me-2"></i>Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-gear me-2"></i>Settings
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Administrator</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <x-slot name="notificationCount">3</x-slot>

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary" style="background: linear-gradient(135deg, #047857 0%, #087272 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-white">Welcome, {{ Auth::user()->name }}!</h1>
                            <p class="text-white-50 mb-0">Monitor and manage the entire GreenRoute waste management system.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end">
                                <div class="text-center me-4">
                                    <div class="h4 mb-0 text-white">{{ date('d') }}</div>
                                    <small class="text-white-50">{{ date('M Y') }}</small>
                                </div>
                                <div class="text-center">
                                    <div class="h4 mb-0 text-white">{{ date('l') }}</div>
                                    <small class="text-white-50">{{ date('H:i A') }}</small>
                                </div>
                            </div>
        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <!-- System Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-left: 4px solid #047857;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-truck text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">{{ $contractorsCount ?? 12 }}</div>
                            <div class="text-muted small">Contractors</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">{{ $clientsCount ?? 48 }}</div>
                            <div class="text-muted small">Clients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-route text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">{{ $activeRoutesCount ?? 24 }}</div>
                            <div class="text-muted small">Active Routes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-left: 4px solid #06b6d4;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h4 mb-0 text-dark">{{ $pendingVerifications ?? 3 }}</div>
                            <div class="text-muted small">Pending Verifications</div>
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
                            <i class="bi bi-shield-check me-2 text-primary"></i>System Management
                        </h5>
                        <div class="btn-group" role="group" aria-label="Admin dashboard tabs">
                            <a href="{{ route('admin.verification') }}" class="btn btn-primary">
                                <i class="bi bi-shield-check me-1"></i>Verification
                            </a>
                            <a href="{{ route('admin.clients') }}" class="btn btn-outline-primary">
                                <i class="bi bi-people me-1"></i>Clients
                            </a>
                            <a href="{{ route('admin.schedules') }}" class="btn btn-outline-primary">
                                <i class="bi bi-calendar3 me-1"></i>Schedules
                            </a>
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-gear me-1"></i>Users
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($pendingTasks) && count($pendingTasks) > 0)
                        <h6 class="mb-3 text-muted">Pending Tasks</h6>
                        <div class="row">
                            @foreach($pendingTasks as $task)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 border-start border-warning border-3 h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-{{ $task['icon'] ?? 'exclamation-circle' }} text-warning"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $task['title'] }}</h6>
                                                    <small class="text-muted">{{ $task['count'] }} pending</small>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-3">{{ $task['description'] }}</p>
                                            <a href="{{ $task['link'] }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <h6 class="mt-3 text-muted">All caught up!</h6>
                            <p class="text-muted small">No pending tasks at the moment.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Quick Actions & Alerts -->
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
                            <button class="btn btn-warning w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-1 me-2">
                                        <i class="bi bi-shield-check text-white"></i>
                                    </div>
                                    <span>Verify Contractors</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-people text-primary"></i>
                                    </div>
                                    <span>Manage Users</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-success w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-graph-up text-success"></i>
                                    </div>
                                    <span>View Analytics</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-info w-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="bi bi-gear text-info"></i>
                                    </div>
                                    <span>System Settings</span>
                                </div>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- System Status -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3 text-muted">System Status</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">All Systems Operational</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">3 Pending Verifications</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">156 Active Users</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Alerts -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>System Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>3 contractors</strong> pending verification
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>System maintenance</strong> scheduled for tonight
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>All systems</strong> running normally
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                    <i class="bi bi-person-plus text-success"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">New Contractor Registration</div>
                                    <small class="text-muted">John Smith - Downtown Area</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-route text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Route Updated</div>
                                    <small class="text-muted">Zone A - 5 new pickups added</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">4 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-credit-card text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Payment Processed</div>
                                    <small class="text-muted">Invoice #INV-001 - $250.00</small>
                                </div>
                                <div class="ms-auto">
                                    <small class="text-muted">6 hours ago</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-gear text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">System Configuration</div>
                                    <small class="text-muted">Updated notification settings</small>
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