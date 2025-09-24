<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #f8f9fa; }
        .nav-link.active { background: #198754; color: white !important; }
        .nav-link { color: #495057; padding: 12px 20px; margin: 2px 0; border-radius: 8px; }
        .nav-link:hover { background: #e9ecef; }
        .content-area { min-height: 100vh; }
        .notification-badge { background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="mb-4">
                    <h4 class="text-success">🌱 AFIA ORBIT</h4>
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            ☰ Menu
                        </button>
                    </div>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" data-tab="dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Client Dashboard
                    </a>
                    <a class="nav-link" href="#" data-tab="profile">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                    <a class="nav-link" href="#" data-tab="schedules">
                        <i class="bi bi-calendar3 me-2"></i>Schedules
                    </a>
                    <a class="nav-link" href="#" data-tab="invoices">
                        <i class="bi bi-receipt me-2"></i>Invoices
                    </a>
                    <a class="nav-link" href="#" data-tab="support">
                        <i class="bi bi-question-circle me-2"></i>Support/Help
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-area p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item">Client</li>
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active">Client ID: {{ auth()->user()->id }}, {{ auth()->user()->name }}</li>
                        </ol>
                    </nav>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-danger">Notification [1]</span>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <span class="ms-2">User Profile</span>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Tab -->
                <div id="dashboard" class="tab-content active">
                    <h2 class="mb-4">WELCOME, {{ auth()->user()->name }}</h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">My Schedule</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($upcomingSchedules as $schedule)
                                        <div class="mb-3">
                                            <strong>{{ ucfirst($schedule->service_type) }}:</strong> {{ $schedule->pickup_date->format('F j, Y') }}, {{ $schedule->pickup_time->format('g:i A') }}
                                            <br><small class="text-muted">Status: {{ ucfirst($schedule->status) }}</small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No upcoming schedules</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">My Invoice</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($recentInvoices as $invoice)
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $invoice->invoice_number }}: ${{ number_format($invoice->total_amount, 2) }}</span>
                                                <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($invoice->status) }}</span>
                                            </div>
                                            <small class="text-muted">{{ $invoice->invoice_date->format('M j, Y') }}</small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No invoices found</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Feedback Form</h5>
                                </div>
                                <div class="card-body">
                                    <form id="feedbackForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Subject</label>
                                            <input type="text" class="form-control" name="subject" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Message</label>
                                            <textarea class="form-control" name="message" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Submit Feedback</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Help Center</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-2"><a href="#">How to schedule a pickup</a></p>
                                            <p class="mb-2"><a href="#">Payment methods</a></p>
                                            <p class="mb-2"><a href="#">Contact support</a></p>
                                        </div>
                                        <button class="btn btn-outline-primary">Policy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Tab -->
                <div id="profile" class="tab-content">
                    <h2 class="mb-4">Profile Settings</h2>
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ auth()->user()->email }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Schedules Tab -->
                <div id="schedules" class="tab-content">
                    <h2 class="mb-4">My Schedules</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Service Type</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $allSchedules = \App\Models\Schedule::where('client_id', $client->id ?? 0)->with('contractor')->orderBy('pickup_date', 'desc')->get();
                                        @endphp
                                        @forelse($allSchedules as $schedule)
                                            <tr>
                                                <td>{{ ucfirst($schedule->service_type) }}</td>
                                                <td>{{ $schedule->pickup_date->format('F j, Y') }}</td>
                                                <td>{{ $schedule->pickup_time->format('g:i A') }}</td>
                                                <td><span class="badge bg-{{ $schedule->status === 'completed' ? 'success' : ($schedule->status === 'scheduled' ? 'primary' : 'warning') }}">{{ ucfirst($schedule->status) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No schedules found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div id="invoices" class="tab-content">
                    <h2 class="mb-4">My Invoices</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $allInvoices = \App\Models\Invoice::where('client_id', $client->id ?? 0)->with('contractor')->orderBy('invoice_date', 'desc')->get();
                                        @endphp
                                        @forelse($allInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>{{ $invoice->invoice_date->format('F j, Y') }}</td>
                                                <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                                <td><span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($invoice->status) }}</span></td>
                                                <td>
                                                    @if($invoice->status === 'paid')
                                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-primary">Pay Now</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Tab -->
                <div id="support" class="tab-content">
                    <h2 class="mb-4">Support & Help</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Contact Support</h5>
                                </div>
                                <div class="card-body">
                                    <p><i class="bi bi-telephone"></i> Phone: +255 123 456 789</p>
                                    <p><i class="bi bi-envelope"></i> Email: support@afiaorbit.com</p>
                                    <p><i class="bi bi-clock"></i> Hours: Mon-Fri 8AM-6PM</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Help</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><a href="#">How to schedule pickup</a></li>
                                        <li><a href="#">Payment guide</a></li>
                                        <li><a href="#">Service areas</a></li>
                                        <li><a href="#">FAQ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab switching functionality
        document.querySelectorAll('[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all nav links
                document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
                // Add active class to clicked nav
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                // Show selected tab content
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });

        // Feedback form submission
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("client.feedback.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Feedback submitted successfully!');
                    this.reset();
                } else {
                    alert('Error submitting feedback. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting feedback. Please try again.');
            });
        });
    </script>
</body>
</html>