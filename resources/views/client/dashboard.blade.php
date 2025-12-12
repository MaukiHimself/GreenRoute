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
        }
        
        body {
            background: var(--white-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--white-color);
            border-right: 1px solid #e0e0e0;
            padding: 0;
        }
        
        .logo-section {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            background: white;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
        }
        
        .menu-section {
            padding: 1rem 0;
        }
        
        .menu-item {
            display: block;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }
        
        .menu-item.active {
            background: var(--primary-color);
            color: white;
            border-left: 4px solid var(--secondary-color);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            background: #f8f9fa;
        }
        
        /* Header */
        .header {
            background: var(--white-color);
            padding: 1rem 2rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification {
            background: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Content Area */
        .content-area {
            padding: 2rem;
        }
        
        .welcome-section {
            margin-bottom: 2rem;
        }
        
        .welcome-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }
        
        /* Cards */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            background: var(--white-color);
        }
        
        .card-header {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1rem;
            border-bottom: none;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Schedule Section */
        .schedule-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        /* Invoice Section */
        .invoice-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-option input[type="radio"] {
            margin: 0;
        }
        
        /* Feedback Section */
        .feedback-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }
        
        .feedback-form {
            flex: 1;
        }
        
        .help-center {
            background: var(--white-color);
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            width: 200px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: #044a4a;
            border-color: #044a4a;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
        
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <img src="/your-logo2.png" alt="Logo" style="max-height: 60px; width: 90%; object-fit: contain;">
            </div>
            <div class="menu-section">
                <a href="#" class="menu-item active" onclick="showSection('dashboard')">
                    Client Dashboard
                </a>
                <a href="#" class="menu-item" onclick="showSection('profile')">
                    Profile
                </a>
                <a href="#" class="menu-item" onclick="showSection('schedules')">
                    Schedules
                </a>
                <a href="#" class="menu-item" onclick="showSection('invoices')">
                    Invoices
                </a>
                <a href="#" class="menu-item" onclick="showSection('chats')">
                    <i class="bi bi-chat-dots me-2"></i>Chats
                </a>
                <a href="#" class="menu-item" onclick="showSection('support')">
                    Support / Help
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('client.dashboard') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2" style="border-color: #e0e0e0; background: #f8f9fa;">
                        <i class="bi bi-house-door-fill" style="color: var(--primary-color);"></i> <span style="color: #333;">Home</span>
                    </a>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Client</li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active" id="clientInfo">Client ID, Name</li>
                    </ol>
                </nav>
                <div class="user-info">
                    <div class="notification">Notification [1]</div>
                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown" style="cursor: pointer;">
                            <div class="user-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Dashboard Section -->
                <div id="dashboard" class="content-section active">
                    <div class="welcome-section">
                        <h2 class="welcome-title" id="welcomeMessage">WELCOME, {{ strtoupper($client->contact_name ?? $client->name ?? 'CLIENT') }}</h2>
                    </div>

                    <!-- Account Profile Section -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">Account Profile</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Client Name:</strong><br>{{ $client->name ?? 'N/A' }}</p>
                                            <p><strong>Contact Name:</strong><br>{{ $client->contact_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Registration Number:</strong><br>{{ $client->registration_number ?? 'N/A' }}</p>
                                            <p><strong>Phone Number:</strong><br>{{ $client->phone ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Physical Address:</strong><br>{{ $client->address ?? 'N/A' }}<br>
                                            {{ $client->city ?? '' }}{{ $client->city && $client->state ? ', ' : '' }}{{ $client->state ?? '' }} {{ $client->zip_code ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Assigned Contractor</div>
                                <div class="card-body">
                                    @if(isset($client->contractor_id) && $client->contractor_id)
                                        @php
                                            $contractor = \App\Models\User::find($client->contractor_id);
                                        @endphp
                                        @if($contractor)
                                            <p><strong>{{ $contractor->name }}</strong></p>
                                            <p><small class="text-muted">{{ $contractor->email }}</small></p>
                                            <p><small>All data shown is from this contractor</small></p>
                                        @else
                                            <p class="text-muted">Contractor not found</p>
                                        @endif
                                    @else
                                        <p class="text-muted">No contractor assigned</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-primary">{{ $completedPickups ?? 0 }}</h4>
                                    <p class="mb-0">Completed Pickups</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-danger">{{ $missedPickups ?? 0 }}</h4>
                                    <p class="mb-0">Missed Pickups</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-success">TZS {{ number_format($totalPaid ?? 0, 2) }}</h4>
                                    <p class="mb-0">Total Paid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-warning">TZS {{ number_format($totalPending ?? 0, 2) }}</h4>
                                    <p class="mb-0">Pending Amount</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Collection Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Collection Schedule</div>
                                <div class="card-body">
                                    @forelse($upcomingSchedules->take(3) as $schedule)
                                        <div class="mb-2 p-2 border-bottom">
                                            <strong>{{ ucfirst($schedule->service_type ?? 'Pickup') }}</strong><br>
                                            <small class="text-muted">
                                                {{ $schedule->pickup_date ? $schedule->pickup_date->format('M j, Y') : 'TBD' }}
                                                @if($schedule->pickup_time)
                                                    at {{ $schedule->pickup_time }}
                                                @endif
                                            </small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No upcoming schedules</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Pending Invoices -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Pending Unpaid Invoices</div>
                                <div class="card-body">
                                    @forelse($pendingInvoices->take(3) as $invoice)
                                        <div class="mb-2 p-2 border-bottom">
                                            <strong>{{ $invoice->invoice_number }}</strong><br>
                                            <small class="text-muted">
                                                TZS {{ number_format($invoice->total_amount ?? 0, 2) }} - 
                                                {{ $invoice->invoice_date ? $invoice->invoice_date->format('M j, Y') : 'N/A' }}
                                            </small>
                                        </div>
                                    @empty
                                        <p class="text-success">No pending invoices</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Payments -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Monthly Payments (Last 6 Months)</div>
                                <div class="card-body">
                                    @forelse($monthlyPayments->take(6) as $payment)
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span>{{ date('M Y', mktime(0, 0, 0, $payment->month, 1, $payment->year)) }}</span>
                                            <strong>TZS {{ number_format($payment->total, 2) }}</strong>
                                        </div>
                                    @empty
                                        <p class="text-muted">No payment history</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Customer Enquiries -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recent Enquiries & Feedback</div>
                                <div class="card-body">
                                    @forelse($recentFeedback->take(3) as $feedback)
                                        <div class="mb-2 p-2 border-bottom">
                                            <strong>{{ $feedback->subject }}</strong><br>
                                            <small class="text-muted">
                                                {{ $feedback->created_at->format('M j, Y') }} - 
                                                <span class="badge bg-{{ $feedback->status === 'resolved' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($feedback->status) }}
                                                </span>
                                            </small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No enquiries submitted</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="feedback-section">
                        <div class="feedback-form">
                            <h5>Feedback form</h5>
                            <form id="feedbackForm">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="feedback" rows="4" placeholder="Enter your feedback here..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                            </form>
                        </div>
                        <div class="help-center">
                            <h6>Help center</h6>
                            <button class="btn btn-outline-primary btn-sm">Policy</button>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="profile" class="content-section">
                    <h3>Complete Profile Information</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Basic Information</h5>
                                    <p><strong>Client Name:</strong> {{ $client->name ?? 'N/A' }}</p>
                                    <p><strong>Contact Name:</strong> {{ $client->contact_name ?? 'N/A' }}</p>
                                    <p><strong>Registration Number:</strong> {{ $client->registration_number ?? 'N/A' }}</p>
                                    <p><strong>Category:</strong> {{ ucfirst($client->category ?? 'N/A') }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($client->status ?? 'N/A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Contact Information</h5>
                                    <p><strong>Primary Phone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                                    <p><strong>Secondary Phone:</strong> {{ $client->phone_2 ?? 'N/A' }}</p>
                                    <p><strong>Third Phone:</strong> {{ $client->phone_3 ?? 'N/A' }}</p>
                                    <p><strong>Primary Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                                    <p><strong>Secondary Email:</strong> {{ $client->email_2 ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Address Information</h5>
                                    <p><strong>Physical Address:</strong><br>
                                    {{ $client->address ?? 'N/A' }}<br>
                                    {{ $client->city ?? '' }}{{ $client->city && $client->state ? ', ' : '' }}{{ $client->state ?? '' }} {{ $client->zip_code ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedules Section -->
                <div id="schedules" class="content-section">
                    <h3>Collection Schedules</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">All Schedules</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Service Type</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($allSchedules as $schedule)
                                                    <tr>
                                                        <td>{{ ucfirst($schedule->service_type ?? 'collection') }}</td>
                                                        <td>{{ $schedule->pickup_date ? $schedule->pickup_date->format('M j, Y') : 'TBD' }}</td>
                                                        <td>{{ $schedule->pickup_time ?? 'TBD' }}</td>
                                                        <td>{{ $schedule->pickup_location ?? 'Client Location' }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $schedule->status === 'completed' ? 'success' : ($schedule->status === 'cancelled' ? 'danger' : 'primary') }}">
                                                                {{ ucfirst($schedule->status ?? 'scheduled') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No schedules found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Collection Summary</div>
                                <div class="card-body">
                                    <p><strong>Total Schedules:</strong> {{ $allSchedules->count() }}</p>
                                    <p><strong>Completed:</strong> <span class="text-success">{{ $completedPickups }}</span></p>
                                    <p><strong>Missed/Cancelled:</strong> <span class="text-danger">{{ $missedPickups }}</span></p>
                                    <p><strong>Upcoming:</strong> {{ $upcomingSchedules->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoices Section -->
                <div id="invoices" class="content-section">
                    <h3>Payment Details</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">All Invoices</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Invoice #</th>
                                                    <th>Date</th>
                                                    <th>Service</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Paid Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentInvoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('M j, Y') : 'N/A' }}</td>
                                                        <td>{{ $invoice->service_type ?? 'Waste Collection' }}</td>
                                                        <td>TZS {{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                                                        <td>
                                                            <span class="badge {{ ($invoice->status ?? 'sent') === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                                {{ ucfirst($invoice->status ?? 'sent') }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $invoice->paid_at ? $invoice->paid_at->format('M j, Y') : 'N/A' }}</td>
                                                        <td>
                                                            @if(($invoice->status ?? '') !== 'paid')
                                                                <a href="{{ route('client.payments.checkout', $invoice->id) }}" class="btn btn-sm btn-primary">
                                                                    Pay Now
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No invoices found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Payment Summary</div>
                                <div class="card-body">
                                    <p><strong>Total Invoices:</strong> {{ $recentInvoices->count() }}</p>
                                    <p><strong>Total Paid:</strong> <span class="text-success">TZS {{ number_format($totalPaid, 2) }}</span></p>
                                    <p><strong>Pending:</strong> <span class="text-warning">TZS {{ number_format($totalPending, 2) }}</span></p>
                                    <p><strong>Paid Invoices:</strong> {{ $paidInvoices->count() }}</p>
                                    <p><strong>Pending Invoices:</strong> {{ $pendingInvoices->count() }}</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Monthly Payments</div>
                                <div class="card-body">
                                    @forelse($monthlyPayments as $payment)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ date('M Y', mktime(0, 0, 0, $payment->month, 1, $payment->year)) }}</span>
                                            <strong>TZS {{ number_format($payment->total, 2) }}</strong>
                                        </div>
                                    @empty
                                        <p class="text-muted">No payment history</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chats Section -->
                <div id="chats" class="content-section">
                    <h3><i class="bi bi-chat-dots me-2"></i>Chat with Contractor</h3>
                    <iframe src="{{ route('client.chats') }}" width="100%" height="700" frameborder="0" style="border: none; border-radius: 12px;"></iframe>
                </div>

                <!-- Support Section -->
                <div id="support" class="content-section">
                    <h3>Support & Help</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Submit Enquiry</div>
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
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Message</label>
                                            <textarea class="form-control" name="message" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Enquiry</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Contact Information</div>
                                <div class="card-body">
                                    <p><strong>Phone:</strong> +255 123 456 789</p>
                                    <p><strong>Email:</strong> support@afiaorbit.com</p>
                                    <p><strong>Hours:</strong> Mon-Fri 8AM-6PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Add active class to clicked menu item
            event.target.classList.add('active');
        }

        // Initialize client data from server-side (Laravel Auth)
        document.addEventListener('DOMContentLoaded', function() {
            // Client data is already passed from controller via Blade
            // No need for sessionStorage check - Laravel middleware handles auth
            const clientName = '{{ $client->contact_name ?? $client->name }}';
            const clientReg = '{{ $client->registration_number }}';
            
            if (clientName && document.getElementById('welcomeMessage')) {
                document.getElementById('welcomeMessage').textContent = `WELCOME, ${clientName.toUpperCase()}`;
            }
            if (clientReg && document.getElementById('clientInfo')) {
                document.getElementById('clientInfo').textContent = `Client ${clientReg}, ${clientName}`;
            }
        });

        // Form submissions
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Feedback submitted successfully!');
            this.reset();
        });

        document.getElementById('enquiryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Enquiry submitted successfully!');
            this.reset();
        });
    </script>
</body>
</html>