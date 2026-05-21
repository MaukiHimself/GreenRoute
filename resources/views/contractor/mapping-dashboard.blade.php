<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Dashboard | AFIA ORBIT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --light-teal: #e6f2f2;
            --light-red: #f9eaea;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            width: 250px;
            z-index: 100;
        }

        .sidebar .brand {
            background-color: white;
            color: var(--primary-teal);
            padding: 20px 15px;
            font-weight: 700;
            font-size: 1.3rem;
            text-align: center;
            border-bottom: 2px solid var(--primary-teal);
        }

        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background-color: var(--light-teal);
            color: var(--primary-teal);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-teal);
            color: white;
            border-left: 4px solid var(--primary-red);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        /* Header Styling */
        .header {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .breadcrumb {
            margin-bottom: 0;
        }

        .breadcrumb-item.active {
            color: var(--primary-teal);
            font-weight: 600;
        }

        .user-badge {
            background-color: var(--primary-teal);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
            color: var(--primary-teal);
        }

        /* Stat Cards */
        .stat-card {
            text-align: center;
            padding: 20px 15px;
            border-radius: 10px;
            color: white;
        }

        .stat-card.clients {
            background: linear-gradient(135deg, var(--primary-teal), #088484);
        }

        .stat-card.invoices {
            background: linear-gradient(135deg, #0a7c7c, #0b9b9b);
        }

        .stat-card.payments {
            background: linear-gradient(135deg, var(--primary-red), #8a1a1a);
        }

        .stat-card.routes {
            background: linear-gradient(135deg, #0a7c7c, var(--primary-teal));
        }

        .stat-card h3 {
            font-size: 2.2rem;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .stat-card p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        /* Button Styling */
        .btn-teal {
            background-color: var(--primary-teal);
            color: white;
            border: none;
        }

        .btn-teal:hover {
            background-color: #044a4a;
            color: white;
        }

        .btn-outline-teal {
            border: 1px solid var(--primary-teal);
            color: var(--primary-teal);
        }

        .btn-outline-teal:hover {
            background-color: var(--primary-teal);
            color: white;
        }

        .btn-red {
            background-color: var(--primary-red);
            color: white;
            border: none;
        }

        .btn-red:hover {
            background-color: #530303;
            color: white;
        }

        /* Badge Styling */
        .badge-teal {
            background-color: var(--light-teal);
            color: var(--primary-teal);
        }

        .badge-red {
            background-color: var(--light-red);
            color: var(--primary-red);
        }

        /* Quick Actions */
        .quick-action {
            text-align: center;
            padding: 20px 15px;
            border-radius: 10px;
            background-color: white;
            transition: all 0.3s;
            border: 1px solid #eee;
        }

        .quick-action:hover {
            background-color: var(--light-teal);
            transform: translateY(-5px);
        }

        .quick-action i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary-teal);
        }

        /* Table Styling */
        .table th {
            background-color: var(--light-teal);
            color: var(--primary-teal);
            font-weight: 600;
            border-top: none;
        }

        /* Map Container */
        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }

            .sidebar .brand span {
                display: none;
            }

            .sidebar .nav-link span {
                display: none;
            }

            .sidebar .nav-link i {
                margin-right: 0;
            }

            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar .nav-link span {
                display: inline;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="brand">
                    <img src="{{ asset('result.png') }}" alt="Logo" style="max-height: 90px; width: 90%; background-color: white; object-fit: contain;">
                </div>
                <nav class="nav flex-column mt-3">
                    <a class="nav-link active" href="#" data-tab="dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="clients">
                        <i class="bi bi-people"></i>
                        <span>Client Database</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="billing">
                        <i class="bi bi-credit-card"></i>
                        <span>Billing & Payments</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="collection">
                        <i class="bi bi-calendar3"></i>
                        <span>Collection Schedules</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="disposal">
                        <i class="bi bi-trash"></i>
                        <span>Disposal Schedules</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="chats">
                        <i class="bi bi-chat-dots"></i>
                        <span>Chats</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="route-management">
                        <i class="bi bi-signpost-split"></i>
                        <span>Routes Management</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="route-optimization">
                        <i class="bi bi-geo-alt"></i>
                        <span>Route Optimization</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="gps">
                        <i class="bi bi-pin-map"></i>
                        <span>GPS Tracker</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="reports">
                        <i class="bi bi-graph-up"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 main-content">
                <!-- Header -->
                <div class="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2" style="border-color: #e0e0e0; background: #f8f9fa;">
                                <i class="bi bi-house-door-fill text-teal" style="color: var(--primary-teal);"></i> <span style="color: #333;">Home</span>
                            </a>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Waste Contractor</li>
                                <li class="breadcrumb-item active">Dashboard</li>
                                <li class="breadcrumb-item active">{{ auth()->user()->name }}</li>
                            </ol>
                        </nav>
                    </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="user-badge">
                                <i class="bi bi-bell me-1"></i>Notifications: 2
                            </span>
                            <div class="dropdown">
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; cursor: pointer;" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-fill text-white"></i>
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
                </div>

                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card clients">
                                <h3 id="totalClients">0</h3>
                                <p>Total Clients</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card invoices">
                                <h3 id="totalInvoices">0</h3>
                                <p>Total Invoices</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card payments">
                                <h3 id="pendingPayments">TZS 0</h3>
                                <p>Pending Payments</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card routes">
                                <h3 id="activeRoutes">0</h3>
                                <p>Active Routes</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <a href="/dashboard/contractor/clients/create" class="quick-action d-block">
                                                <i class="bi bi-person-plus"></i>
                                                <h6>Add New Client</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/billing/create" class="quick-action d-block">
                                                <i class="bi bi-receipt"></i>
                                                <h6>Create Invoice</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/schedules/create" class="quick-action d-block">
                                                <i class="bi bi-calendar-plus"></i>
                                                <h6>Schedule Collection</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/reports" class="quick-action d-block">
                                                <i class="bi bi-graph-up"></i>
                                                <h6>View Reports</h6>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Invoices & Upcoming Collections -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent Invoices</h5>
                                    <a href="/billing" class="btn btn-sm btn-outline-teal">View All</a>
                                </div>
                                <div class="card-body">
                                    <div id="recentInvoices">
                                        <p class="text-muted">Loading recent invoices...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Upcoming Collections</h5>
                                    <a href="/schedules" class="btn btn-sm btn-outline-teal">View All</a>
                                </div>
                                <div class="card-body">
                                    <div id="upcomingSchedules">
                                        <p class="text-muted">Loading upcoming schedules...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">GPS Tracker & Route Map</h5>
                        </div>
                        <div class="card-body">
                            <div id="dashboardMap" class="map-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Client Database Tab -->
                <div id="clients-tab" class="tab-content" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Search Client Database</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="searchName" placeholder="Search by name...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="searchCategory">
                                        <option value="">All Categories</option>
                                        <option value="residential">Residential</option>
                                        <option value="commercial">Commercial</option>
                                        <option value="industrial">Industrial</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="searchLocation" placeholder="Search by location...">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="searchRegNumber" placeholder="Registration number...">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="btn btn-teal" onclick="searchClients()">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="clearSearch()">
                                        <i class="bi bi-x-circle me-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Client Database</h5>
                            <a href="/dashboard/contractor/clients/create" class="btn btn-teal btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add New Client
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Reg. Number</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Category</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="clientsTable">
                                        <tr><td colspan="8" class="text-center">Loading clients...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other tabs (simplified) -->
                <div id="billing-tab" class="tab-content" style="display: none;">
                    <iframe src="/billing" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="collection-tab" class="tab-content" style="display: none;">
                    <iframe src="/schedules" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="disposal-tab" class="tab-content" style="display: none;">
                    <iframe id="disposal-iframe" src="/disposal" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
                </div>

                <div id="chats-tab" class="tab-content" style="display: none;">
                    <iframe id="chats-iframe" src="/sms/inbox" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
                </div>

                <div id="route-management-tab" class="tab-content" style="display: none;">
                    <iframe id="route-management-iframe" src="/route-management" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
                </div>

                <div id="route-optimization-tab" class="tab-content" style="display: none;">
                    <iframe src="/routes" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="gps-tab" class="tab-content" style="display: none;">
                    <iframe src="/trucks" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="reports-tab" class="tab-content" style="display: none;">
                    <iframe src="/reports" width="100%" height="600" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab switching logic
        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all tabs
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                // Hide all tab content
                document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');

                // Show selected tab content
                const tabId = this.getAttribute('data-tab') + '-tab';
                const selectedTab = this.getAttribute('data-tab');
                document.getElementById(tabId).style.display = 'block';

                // Refresh disposal iframe when tab is clicked to show latest completed schedules
                if (selectedTab === 'disposal') {
                    const disposalIframe = document.getElementById('disposal-iframe');
                    if (disposalIframe) {
                        disposalIframe.src = disposalIframe.src; // Force refresh
                    }
                }

                // Load specific content for tabs
                if (selectedTab === 'clients') {
                    loadClientsTable();
                } else if (selectedTab === 'gps') {
                    initGPSMap();
                }
            });
        });

        // Initialize dashboard data
        function loadDashboardData() {
            // Load dashboard statistics
            fetch('/contractor/dashboard-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalClients').textContent = data.total_clients || 0;
                    document.getElementById('totalInvoices').textContent = data.total_invoices || 0;
                    document.getElementById('pendingPayments').textContent = 'TZS ' + (data.pending_payments || 0);
                    document.getElementById('activeRoutes').textContent = data.active_routes || 0;
                })
                .catch(() => {
                    console.log('Dashboard stats not available');
                });

            // Load recent invoices
            fetch('/contractor/recent-invoices')
                .then(response => response.json())
                .then(invoices => {
                    const container = document.getElementById('recentInvoices');
                    if (invoices.length === 0) {
                        container.innerHTML = '<p class="text-muted">No recent invoices</p>';
                        return;
                    }
                    container.innerHTML = '';
                    invoices.forEach(invoice => {
                        container.innerHTML += `
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-start border-3 border-primary">
                                <div>
                                    <strong>Invoice #${invoice.id}</strong><br>
                                    <small class="text-muted">${invoice.client_name}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-${invoice.status === 'paid' ? 'success' : 'warning'}">TZS ${invoice.total_amount}</span><br>
                                    <small class="badge bg-${invoice.status === 'paid' ? 'success' : 'warning'}">${invoice.status}</small>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(() => {
                    document.getElementById('recentInvoices').innerHTML = '<p class="text-muted">Unable to load invoices</p>';
                });

            // Load upcoming schedules
            fetch('/contractor/upcoming-schedules')
                .then(response => response.json())
                .then(schedules => {
                    const container = document.getElementById('upcomingSchedules');
                    if (schedules.length === 0) {
                        container.innerHTML = '<p class="text-muted">No upcoming schedules</p>';
                        return;
                    }
                    container.innerHTML = '';
                    schedules.forEach(schedule => {
                        container.innerHTML += `
                            <div class="border-start border-success border-4 ps-3 mb-3 bg-light p-2 rounded">
                                <strong>${schedule.pickup_location}</strong><br>
                                <small class="text-muted">${schedule.client_name}</small><br>
                                <small class="text-info">${schedule.pickup_date} at ${schedule.pickup_time}</small>
                            </div>
                        `;
                    });
                })
                .catch(() => {
                    document.getElementById('upcomingSchedules').innerHTML = '<p class="text-muted">Unable to load schedules</p>';
                });
        }

        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });

        // Client search functions
        function searchClients() {
            const name = document.getElementById('searchName').value;
            const category = document.getElementById('searchCategory').value;
            const location = document.getElementById('searchLocation').value;
            const regNumber = document.getElementById('searchRegNumber').value;

            const params = new URLSearchParams();
            if (name) params.append('name', name);
            if (category) params.append('category', category);
            if (location) params.append('location', location);
            if (regNumber) params.append('registration_number', regNumber);

            fetch(`/contractor/clients/locations?${params.toString()}`)
                .then(response => response.json())
                .then(clients => {
                    const tbody = document.getElementById('clientsTable');
                    tbody.innerHTML = '';
                    if (clients.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center">No clients found matching your search criteria</td></tr>';
                        return;
                    }
                    clients.forEach(client => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${client.registration_number || 'N/A'}</td>
                                <td>${client.name}</td>
                                <td>${client.contact_name || 'N/A'}</td>
                                <td><span class="badge badge-teal">${client.category || 'N/A'}</span></td>
                                <td>${client.phone}<br><small>${client.phone_2 || ''}<br>${client.phone_3 || ''}</small></td>
                                <td>${client.email}<br><small>${client.email_2 || ''}<br>${client.email_3 || ''}</small></td>
                                <td>${client.address}</td>
                                <td>
                                    <a href="/dashboard/contractor/clients/${client.id}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="/dashboard/contractor/clients/${client.id}/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(() => {
                    document.getElementById('clientsTable').innerHTML = '<tr><td colspan="8" class="text-center">Error searching clients</td></tr>';
                });
        }

        function clearSearch() {
            document.getElementById('searchName').value = '';
            document.getElementById('searchCategory').value = '';
            document.getElementById('searchLocation').value = '';
            document.getElementById('searchRegNumber').value = '';
            loadClientsTable();
        }

        // Load clients table
        function loadClientsTable() {
            fetch('/contractor/clients/locations')
                .then(response => response.json())
                .then(clients => {
                    const tbody = document.getElementById('clientsTable');
                    tbody.innerHTML = '';
                    clients.forEach(client => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${client.registration_number || 'N/A'}</td>
                                <td>${client.name}</td>
                                <td>${client.contact_name || 'N/A'}</td>
                                <td><span class="badge badge-teal">${client.category || 'N/A'}</span></td>
                                <td>${client.phone}<br><small>${client.phone_2 || ''}<br>${client.phone_3 || ''}</small></td>
                                <td>${client.email}<br><small>${client.email_2 || ''}<br>${client.email_3 || ''}</small></td>
                                <td>${client.address}</td>
                                <td>
                                    <a href="/dashboard/contractor/clients/${client.id}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="/dashboard/contractor/clients/${client.id}/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(() => {
                    document.getElementById('clientsTable').innerHTML = '<tr><td colspan="8" class="text-center">No clients found</td></tr>';
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });
    </script>
    @include('components.leaflet-assets')
    <script>
        GreenRouteMap.whenReady(function () {
            const ctx = GreenRouteMap.createMap('dashboardMap', { lat: -6.7924, lng: 39.2083, zoom: 12 });
            if (ctx) {
                GreenRouteMap.addMarker(ctx, -6.7924, 39.2083, {
                    title: 'Dar es Salaam',
                    popup: '<strong>GPS Tracker</strong><br>Map ready — assign client coordinates to show stops.',
                });
            }
        });
    </script>
</body>
</html>
