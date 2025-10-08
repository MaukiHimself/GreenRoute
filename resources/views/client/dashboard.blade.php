<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #055c5c;
            --secondary-color: #640404;
            --white-color: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .main-content {
            padding: 2rem 0;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Dashboard Section */
        .dashboard-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Navigation */
        .nav-tabs {
            border: none;
            margin-bottom: 2rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            margin-right: 0.5rem;
            color: var(--text-muted);
            font-weight: 600;
            transition: all 0.3s ease;
            background: var(--light-bg);
        }
        
        .nav-tabs .nav-link:hover {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-color);
        }
        
        .nav-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
            color: white;
        }
        
        .btn-success {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Stats Cards */
        .stat-card {
            background: var(--white-color);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .stat-description {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }
        
        /* Tables */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--light-bg);
            border: none;
            font-weight: 600;
            color: var(--text-dark);
            padding: 1rem;
        }
        
        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .bg-success {
            background-color: var(--primary-color) !important;
        }
        
        .bg-warning {
            background-color: #d97706 !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        /* Forms */
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .notification-badge {
            background: var(--secondary-color);
            color: white;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        /* Tab Content */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 0.5rem;
            }
            
            .dashboard-section {
                padding: 1.5rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .nav-tabs .nav-link {
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-container">
            <!-- Client Dashboard -->
            <div class="dashboard-section">
                <!-- Header -->
                <div class="section-header">
                    <h2 class="section-title">🌱 AFIA ORBIT - Client Dashboard</h2>
                    <div class="d-flex align-items-center gap-3">
                        <span class="notification-badge">Notification [1]</span>
                        <div class="user-profile">
                            <div class="user-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="fw-bold" id="clientName">Client</div>
                                <small class="text-muted" id="clientId">Registration: N/A</small>
                            </div>
                        </div>
                        <button onclick="logout()" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </div>
                </div>
                
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="bi bi-person me-2"></i>Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="schedules-tab" data-bs-toggle="tab" data-bs-target="#schedules" type="button" role="tab">
                            <i class="bi bi-calendar3 me-2"></i>Schedules
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">
                            <i class="bi bi-receipt me-2"></i>Invoices
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="support-tab" data-bs-toggle="tab" data-bs-target="#support" type="button" role="tab">
                            <i class="bi bi-question-circle me-2"></i>Support
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="clientTabContent">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                        <div class="mb-4">
                            <h3 class="text-primary mb-3" id="welcomeMessage">WELCOME, CLIENT</h3>
                    
                            @php
                                $client = auth()->user() && auth()->user()->isClient() ? \App\Models\Client::where('user_id', auth()->id())->first() : null;
                                $upcomingSchedules = $client ? \App\Models\Schedule::where('client_id', $client->id)->where('pickup_date', '>=', now())->get() : collect();
                                $allSchedules = $client ? \App\Models\Schedule::where('client_id', $client->id)->get() : collect();
                                $missedPickups = $allSchedules->where('status', 'missed')->count();
                                $allInvoices = $client ? \App\Models\Invoice::where('client_id', $client->id)->get() : collect();
                                $pendingInvoices = $allInvoices->where('status', 'pending');
                                $paidInvoices = $allInvoices->where('status', 'paid');
                                $monthlyPayments = $paidInvoices->groupBy(function($invoice) { return $invoice->created_at ? $invoice->created_at->format('Y-m') : 'unknown'; });
                            @endphp
                            
                            <!-- Stats Grid -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-title">
                                            <i class="bi bi-calendar-check"></i> Upcoming Pickups
                                        </div>
                                        <div class="stat-value">{{ $upcomingSchedules->count() }}</div>
                                        <p class="stat-description">Scheduled this month</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-title">
                                            <i class="bi bi-exclamation-triangle"></i> Missed Pickups
                                        </div>
                                        <div class="stat-value text-warning">{{ $missedPickups }}</div>
                                        <p class="stat-description">Total missed collections</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-title">
                                            <i class="bi bi-receipt"></i> Pending Invoices
                                        </div>
                                        <div class="stat-value text-danger">{{ $pendingInvoices->count() }}</div>
                                        <p class="stat-description">${{ number_format($pendingInvoices->sum('total_amount'), 2) }} due</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-title">
                                            <i class="bi bi-check-circle"></i> Paid This Month
                                        </div>
                                        <div class="stat-value text-success">${{ number_format($paidInvoices->where('created_at', '>=', now()->startOfMonth())->sum('total_amount'), 2) }}</div>
                                        <p class="stat-description">Monthly payments</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>My Schedule</h5>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $client = auth()->user() && auth()->user()->isClient() ? \App\Models\Client::where('user_id', auth()->id())->first() : null;
                                                $upcomingSchedules = $client ? \App\Models\Schedule::where('client_id', $client->id)->where('pickup_date', '>=', now())->orderBy('pickup_date')->limit(3)->get() : collect();
                                            @endphp
                                            @forelse($upcomingSchedules as $schedule)
                                                <div class="mb-3 p-2 border-start border-primary border-3">
                                                    <strong>{{ ucfirst($schedule->service_type ?? 'Pickup') }}:</strong> {{ $schedule->pickup_date ? $schedule->pickup_date->format('F j, Y') : 'TBD' }}
                                                    @if($schedule->pickup_time)
                                                        , {{ $schedule->pickup_time->format('g:i A') }}
                                                    @endif
                                                    <br><small class="text-muted">Status: <span class="badge bg-primary">{{ ucfirst($schedule->status ?? 'scheduled') }}</span></small>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-3">
                                                    <i class="bi bi-calendar-x display-4 mb-2"></i>
                                                    <p class="mb-0">No upcoming schedules</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Recent Invoices</h5>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $recentInvoices = $client ? \App\Models\Invoice::where('client_id', $client->id)->orderBy('invoice_date', 'desc')->limit(3)->get() : collect();
                                            @endphp
                                            @forelse($recentInvoices as $invoice)
                                                <div class="mb-3 p-2 border-start border-primary border-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $invoice->invoice_number }}</strong>
                                                            <br><small class="text-muted">{{ $invoice->invoice_date ? $invoice->invoice_date->format('M j, Y') : 'N/A' }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="fw-bold">${{ number_format($invoice->total_amount ?? 0, 2) }}</div>
                                                            <span class="badge {{ ($invoice->status ?? 'pending') === 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($invoice->status ?? 'pending') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-3">
                                                    <i class="bi bi-receipt display-4 mb-2"></i>
                                                    <p class="mb-0">No invoices found</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Request Services</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary text-start" data-bs-toggle="modal" data-bs-target="#customServiceModal">
                                                    <i class="bi bi-calendar-plus me-2"></i>Custom Waste Collection
                                                </button>
                                                <button class="btn btn-outline-primary text-start" data-bs-toggle="modal" data-bs-target="#equipmentModal">
                                                    <i class="bi bi-box me-2"></i>Storage Equipment
                                                </button>
                                                <button class="btn btn-outline-primary text-start" data-bs-toggle="modal" data-bs-target="#routeModal">
                                                    <i class="bi bi-map me-2"></i>Collection Route Info
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Your Waste Contractor</h5>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $contractor = $client && $client->contractor ? $client->contractor : null;
                                                $contractorData = $contractor ? \App\Models\Contractor::where('user_id', $contractor->id)->first() : null;
                                            @endphp
                                            @if($contractor)
                                                <div class="text-center mb-3">
                                                    <div class="user-avatar mx-auto mb-2">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                    <h6>{{ $contractorData->company_name ?? $contractor->name }}</h6>
                                                    <small class="text-muted">{{ $contractor->email }}</small>
                                                    @if($contractorData)
                                                        <div class="mt-2">
                                                            <small class="badge bg-primary">License: {{ $contractorData->license_number }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Contact Info:</strong>
                                                    <div class="small text-muted">{{ $contractorData->phone ?? 'N/A' }}</div>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Service Areas:</strong>
                                                    <div class="small text-muted">{{ $contractorData->site_locations ?? 'Not specified' }}</div>
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#contractorModal">
                                                        <i class="bi bi-info-circle me-1"></i>Full Details
                                                    </button>
                                                </div>
                                            @else
                                                <p class="text-muted text-center">No contractor assigned</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Quick Feedback</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="quickFeedbackForm">
                                                @csrf
                                                <div class="mb-3">
                                                    <textarea class="form-control" name="message" rows="3" required placeholder="Share your feedback..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-send me-2"></i>Submit
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Account Profile</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($client)
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Contact Name</label>
                                                <input type="text" class="form-control" value="{{ $client->name }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" value="{{ $client->phone }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Registration Number</label>
                                                <input type="text" class="form-control" value="{{ $client->registration_number }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status</label>
                                                <input type="text" class="form-control" value="{{ ucfirst($client->status) }}" readonly>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Geopolitical Address</label>
                                                <textarea class="form-control" rows="2" readonly>{{ $client->address ?: 'Not provided' }}</textarea>
                                                <div class="form-text">Registered collection address tied to client number: {{ $client->registration_number }}</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control" value="{{ $client->city ?: 'N/A' }}" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" class="form-control" value="{{ $client->state ?: 'N/A' }}" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" value="{{ $client->zip_code ?: 'N/A' }}" readonly>
                                            </div>
                                        </div>
                                        @else
                                        <p class="text-muted">No profile data available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Account Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Total Collections:</strong>
                                            <div class="text-primary fs-4">{{ $allSchedules->where('status', 'completed')->count() }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Total Payments:</strong>
                                            <div class="text-success fs-4">${{ number_format($paidInvoices->sum('total_amount'), 2) }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Outstanding Balance:</strong>
                                            <div class="text-danger fs-4">${{ number_format($pendingInvoices->sum('total_amount'), 2) }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Member Since:</strong>
                                            <div class="text-muted">{{ auth()->user() && auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedules Tab -->
                    <div class="tab-pane fade" id="schedules" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-check-circle"></i> Completed</div>
                                    <div class="stat-value text-success">{{ $allSchedules->where('status', 'completed')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-clock"></i> Scheduled</div>
                                    <div class="stat-value text-primary">{{ $allSchedules->where('status', 'scheduled')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-x-circle"></i> Missed</div>
                                    <div class="stat-value text-danger">{{ $allSchedules->where('status', 'missed')->count() }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Collection Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Service Type</th>
                                                <th>Scheduled Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($allSchedules->sortByDesc('pickup_date') as $schedule)
                                                <tr class="{{ ($schedule->status ?? 'scheduled') === 'missed' ? 'table-warning' : '' }}">
                                                    <td>{{ ucfirst($schedule->service_type ?? 'Waste Pickup') }}</td>
                                                    <td>{{ $schedule->pickup_date ? $schedule->pickup_date->format('M j, Y') : 'TBD' }}</td>
                                                    <td>{{ $schedule->pickup_time ? $schedule->pickup_time->format('g:i A') : 'TBD' }}</td>
                                                    <td>
                                                        @php
                                                            $status = $schedule->status ?? 'scheduled';
                                                            $badgeClass = $status === 'completed' ? 'success' : ($status === 'missed' ? 'danger' : 'primary');
                                                        @endphp
                                                        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                    </td>
                                                    <td>{{ $schedule->notes ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        <i class="bi bi-calendar-x display-4 mb-2 d-block"></i>
                                                        No collection records found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Tab -->
                    <div class="tab-pane fade" id="invoices" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-check-circle"></i> Paid Invoices</div>
                                    <div class="stat-value text-success">${{ number_format($paidInvoices->sum('total_amount'), 2) }}</div>
                                    <p class="stat-description">{{ $paidInvoices->count() }} invoices paid</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-exclamation-circle"></i> Pending</div>
                                    <div class="stat-value text-danger">${{ number_format($pendingInvoices->sum('total_amount'), 2) }}</div>
                                    <p class="stat-description">{{ $pendingInvoices->count() }} unpaid invoices</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-title"><i class="bi bi-calendar-month"></i> This Month</div>
                                    <div class="stat-value text-primary">${{ number_format($paidInvoices->where('created_at', '>=', now()->startOfMonth())->sum('total_amount'), 2) }}</div>
                                    <p class="stat-description">Monthly payment</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($pendingInvoices->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Pending Unpaid Invoices</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingInvoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('M j, Y') : 'N/A' }}</td>
                                                    <td class="fw-bold text-danger">${{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('M j, Y') : 'N/A' }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary">
                                                            <i class="bi bi-credit-card me-1"></i>Pay Now
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Monthly Payment History</h5>
                            </div>
                            <div class="card-body">
                                @if($monthlyPayments->count() > 0)
                                    <div class="row">
                                        @foreach($monthlyPayments->sortKeysDesc()->take(6) as $month => $payments)
                                            <div class="col-md-4 mb-3">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-primary">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h6>
                                                    <div class="fs-5 fw-bold">${{ number_format($payments->sum('total_amount'), 2) }}</div>
                                                    <small class="text-muted">{{ $payments->count() }} payment(s)</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">No payment history available</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>All Invoices</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Payment Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($allInvoices->sortByDesc('invoice_date') as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('M j, Y') : 'N/A' }}</td>
                                                    <td>${{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                                    <td><span class="badge bg-{{ ($invoice->status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($invoice->status ?? 'pending') }}</span></td>
                                                    <td>{{ ($invoice->status ?? 'pending') === 'paid' ? ($invoice->updated_at ? $invoice->updated_at->format('M j, Y') : 'N/A') : '-' }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye me-1"></i>View
                                                            </button>
                                                            @if(($invoice->status ?? 'pending') === 'paid')
                                                                <button class="btn btn-sm btn-success" onclick="downloadReceipt('{{ $invoice->invoice_number }}')">
                                                                    <i class="bi bi-download me-1"></i>Receipt
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        <i class="bi bi-receipt display-4 mb-2 d-block"></i>
                                                        No invoices found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Tab -->
                    <div class="tab-pane fade" id="support" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Submit Enquiry</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="enquiryForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Subject</label>
                                                <select class="form-control" name="subject" required>
                                                    <option value="">Select enquiry type</option>
                                                    <option value="billing">Billing Question</option>
                                                    <option value="schedule">Schedule Change</option>
                                                    <option value="service">Service Issue</option>
                                                    <option value="complaint">Complaint</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Message</label>
                                                <textarea class="form-control" name="message" rows="4" required placeholder="Describe your enquiry..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-send me-2"></i>Submit Enquiry
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-star me-2"></i>Leave Feedback</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="feedbackForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Rating</label>
                                                <div class="d-flex gap-1 mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star rating-star" data-rating="{{ $i }}" style="cursor: pointer; font-size: 1.5rem; color: #ddd;"></i>
                                                    @endfor
                                                </div>
                                                <input type="hidden" name="rating" id="rating" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Feedback</label>
                                                <textarea class="form-control" name="feedback" rows="3" required placeholder="Share your experience..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-heart me-2"></i>Submit Feedback
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $customerFeedback = $client ? \App\Models\Feedback::where('client_id', $client->id)->orderBy('created_at', 'desc')->get() : collect();
                        @endphp
                        
                        @if($customerFeedback->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>My Enquiries & Feedback History</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customerFeedback as $feedback)
                                                <tr>
                                                    <td>{{ $feedback->created_at ? $feedback->created_at->format('M j, Y') : 'N/A' }}</td>
                                                    <td><span class="badge bg-info">{{ ucfirst($feedback->type ?? 'Feedback') }}</span></td>
                                                    <td>{{ $feedback->subject ?? 'General Feedback' }}</td>
                                                    <td><span class="badge bg-{{ ($feedback->status ?? 'pending') === 'resolved' ? 'success' : 'warning' }}">{{ ucfirst($feedback->status ?? 'pending') }}</span></td>
                                                    <td>{{ $feedback->response ? 'Responded' : 'Pending' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-headset me-2"></i>Contact Support</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <i class="bi bi-telephone-fill text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <strong>Phone Support</strong>
                                                <br><span class="text-muted">+255 123 456 789</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <i class="bi bi-envelope-fill text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <strong>Email Support</strong>
                                                <br><span class="text-muted">support@afiaorbit.com</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="bi bi-clock-fill text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <strong>Business Hours</strong>
                                                <br><span class="text-muted">Mon-Fri 8AM-6PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Help Resources</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="#" class="btn btn-outline-primary text-start">
                                                <i class="bi bi-calendar-plus me-2"></i>How to schedule pickup
                                            </a>
                                            <a href="#" class="btn btn-outline-primary text-start">
                                                <i class="bi bi-credit-card me-2"></i>Payment guide
                                            </a>
                                            <a href="#" class="btn btn-outline-primary text-start">
                                                <i class="bi bi-geo-alt me-2"></i>Service areas
                                            </a>
                                            <a href="#" class="btn btn-outline-primary text-start">
                                                <i class="bi bi-question-circle me-2"></i>FAQ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Request Modals -->
    <div class="modal fade" id="customServiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar-plus me-2"></i>Request Custom Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="customServiceForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Service Type</label>
                            <select class="form-control" name="service_type" required>
                                <option value="">Select service type</option>
                                <option value="bulk_pickup">Bulk Waste Pickup</option>
                                <option value="hazardous">Hazardous Waste</option>
                                <option value="electronic">Electronic Waste</option>
                                <option value="garden">Garden Waste</option>
                                <option value="construction">Construction Debris</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" class="form-control" name="preferred_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the waste collection needed..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="equipmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box me-2"></i>Storage Equipment Enquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="equipmentForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Equipment Type</label>
                            <select class="form-control" name="equipment_type" required>
                                <option value="">Select equipment</option>
                                <option value="bins">Waste Bins</option>
                                <option value="containers">Large Containers</option>
                                <option value="compactors">Compactors</option>
                                <option value="recycling">Recycling Bins</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity Needed</label>
                            <input type="number" class="form-control" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Requirements</label>
                            <textarea class="form-control" name="requirements" rows="3" placeholder="Any specific requirements..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Enquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="routeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-map me-2"></i>Collection Route Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Your Collection Schedule</h6>
                            <ul class="list-unstyled">
                                <li><strong>Regular Pickup:</strong> Every Tuesday & Friday</li>
                                <li><strong>Time Window:</strong> 8:00 AM - 12:00 PM</li>
                                <li><strong>Route Number:</strong> R-{{ $client ? $client->id : 'N/A' }}</li>
                                <li><strong>Collection Area:</strong> {{ $client ? ($client->city ?: 'Not specified') : 'N/A' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Important Notes</h6>
                            <ul class="list-unstyled">
                                <li>• Place bins at collection point by 7:00 AM</li>
                                <li>• Ensure bins are accessible</li>
                                <li>• No hazardous materials in regular bins</li>
                                <li>• Contact us for schedule changes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contractorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-building me-2"></i>Contractor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($contractor && $contractorData)
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Company Details</h6>
                            <ul class="list-unstyled">
                                <li><strong>Company:</strong> {{ $contractorData->company_name }}</li>
                                <li><strong>Contact Person:</strong> {{ $contractorData->name }}</li>
                                <li><strong>Email:</strong> {{ $contractorData->email }}</li>
                                <li><strong>Phone:</strong> {{ $contractorData->phone }}</li>
                                <li><strong>License:</strong> {{ $contractorData->license_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Service Information</h6>
                            <ul class="list-unstyled">
                                <li><strong>Service Areas:</strong></li>
                                <li class="text-muted">{{ $contractorData->site_locations }}</li>
                                <li class="mt-2"><strong>Address:</strong></li>
                                <li class="text-muted">{{ $contractorData->address }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">
                                    <i class="bi bi-telephone me-1"></i>Call Contractor
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-1"></i>Send Email
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-chat-dots me-1"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No contractor information available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check if user is logged in
        document.addEventListener('DOMContentLoaded', function() {
            const clientData = sessionStorage.getItem('clientLoggedIn');
            if (!clientData) {
                window.location.href = '/client/login';
                return;
            }
            
            const data = JSON.parse(clientData);
            document.getElementById('welcomeMessage').textContent = `WELCOME, ${data.contact_name.toUpperCase()}`;
            document.getElementById('clientName').textContent = data.contact_name;
            document.getElementById('clientId').textContent = `Registration: ${data.registration_number}`;
        });
        
        function logout() {
            sessionStorage.removeItem('clientLoggedIn');
            localStorage.removeItem('completedRegistration');
            window.location.href = '/client/login';
        }
        
        // Rating stars functionality
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('rating').value = rating;
                
                // Update star display
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#ffc107';
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                    } else {
                        s.style.color = '#ddd';
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    }
                });
            });
        });
        
        // Feedback form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!document.getElementById('rating').value) {
                showNotification('Please select a rating', 'error');
                return;
            }
            
            const formData = new FormData(this);
            
            // Mock feedback submission
            setTimeout(() => {
                showNotification('Feedback submitted successfully!', 'success');
                this.reset();
                // Reset stars
                document.querySelectorAll('.rating-star').forEach(s => {
                    s.style.color = '#ddd';
                    s.classList.remove('bi-star-fill');
                    s.classList.add('bi-star');
                });
                document.getElementById('rating').value = '';
            }, 1000);
        });
        
        // Enquiry form submission
        document.getElementById('enquiryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Mock enquiry submission
            setTimeout(() => {
                showNotification('Enquiry submitted successfully! We will respond within 24 hours.', 'success');
                this.reset();
            }, 1000);
        });
        
        // Service request forms
        document.getElementById('customServiceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Custom collection request submitted!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('customServiceModal')).hide();
            this.reset();
        });
        
        document.getElementById('equipmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Equipment enquiry submitted!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('equipmentModal')).hide();
            this.reset();
        });
        
        document.getElementById('quickFeedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Feedback submitted!', 'success');
            this.reset();
        });
        
        // Receipt download function
        window.downloadReceipt = function(invoiceNumber) {
            // Mock receipt download
            showNotification('Downloading receipt for ' + invoiceNumber, 'success');
            
            // In production, this would generate and download actual receipt
            const link = document.createElement('a');
            link.href = '#'; // Replace with actual receipt URL
            link.download = 'receipt_' + invoiceNumber + '.pdf';
            link.click();
        };
        
        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                animation: slideIn 0.3s ease;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            
            if (type === 'success') {
                notification.style.background = 'var(--primary-color)';
            } else if (type === 'error') {
                notification.style.background = 'var(--secondary-color)';
            } else {
                notification.style.background = 'var(--text-muted)';
            }
            
            notification.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="bi ${
                        type === 'success' ? 'bi-check-circle' : 
                        type === 'error' ? 'bi-exclamation-circle' : 'bi-info-circle'
                    }"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>