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
            --dark-teal: #044a4a;
            --dark-red: #530303;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            width: 250px;
            z-index: 100;
            transition: all 0.3s;
        }
        
        .sidebar .brand {
            background-color: var(--primary-teal);
            color: white;
            padding: 20px 15px;
            font-weight: 700;
            font-size: 1.3rem;
            text-align: center;
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
            transition: all 0.3s;
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
            height: 100%;
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
            background-color: var(--dark-teal);
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
            background-color: var(--dark-red);
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
            height: 100%;
        }
        
        .quick-action:hover {
            background-color: var(--light-teal);
            transform: translateY(-5px);
            text-decoration: none;
        }
        
        .quick-action i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary-teal);
        }
        
        .quick-action h6 {
            color: var(--primary-teal);
            font-weight: 600;
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
        
        /* Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 101;
            background: var(--primary-teal);
            color: white;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        @media (max-width: 992px) {
            .sidebar-toggle {
                display: flex;
            }
        }
        
        /* Tab Content */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Demo Map */
        .demo-map {
            background: linear-gradient(135deg, #e6f2f2, #cce6e6);
            border-radius: 10px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        
        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar" id="sidebar">
                <div class="brand">
                    <span>AFIA ORBIT</span>
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
                    <a class="nav-link" href="#" data-tab="sms">
                        <i class="bi bi-chat-dots"></i>
                        <span>SMS Manager</span>
                    </a>
                    <a class="nav-link" href="#" data-tab="routes">
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
            <div class="col-lg-10 main-content" id="mainContent">
                <!-- Header -->
                <div class="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Waste Contractor</li>
                                <li class="breadcrumb-item active">Dashboard</li>
                                <li class="breadcrumb-item active" id="userName">Demo User</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3">
                            <span class="user-badge">
                                <i class="bi bi-bell me-1"></i>Notifications: 2
                            </span>
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content active">
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
                                <h3 id="pendingPayments">$0</h3>
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
                                            <a href="#" class="quick-action d-block">
                                                <i class="bi bi-person-plus"></i>
                                                <h6>Add New Client</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="#" class="quick-action d-block">
                                                <i class="bi bi-receipt"></i>
                                                <h6>Create Invoice</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="#" class="quick-action d-block">
                                                <i class="bi bi-calendar-plus"></i>
                                                <h6>Schedule Collection</h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="#" class="quick-action d-block">
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
                                    <a href="#" class="btn btn-sm btn-outline-teal">View All</a>
                                </div>
                                <div class="card-body">
                                    <div id="recentInvoices">
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-start border-3 border-primary">
                                            <div>
                                                <strong>Invoice #1001</strong><br>
                                                <small class="text-muted">ABC Manufacturing</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="fw-bold text-warning">$450.00</span><br>
                                                <small class="badge bg-warning">Pending</small>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-start border-3 border-primary">
                                            <div>
                                                <strong>Invoice #1002</strong><br>
                                                <small class="text-muted">XYZ Retail</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="fw-bold text-success">$320.50</span><br>
                                                <small class="badge bg-success">Paid</small>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-start border-3 border-primary">
                                            <div>
                                                <strong>Invoice #1003</strong><br>
                                                <small class="text-muted">Global Foods Inc</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="fw-bold text-warning">$680.75</span><br>
                                                <small class="badge bg-warning">Pending</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Upcoming Collections</h5>
                                    <a href="#" class="btn btn-sm btn-outline-teal">View All</a>
                                </div>
                                <div class="card-body">
                                    <div id="upcomingSchedules">
                                        <div class="border-start border-success border-4 ps-3 mb-3 bg-light p-2 rounded">
                                            <strong>Downtown Business District</strong><br>
                                            <small class="text-muted">15 Commercial Clients</small><br>
                                            <small class="text-info">Tomorrow at 8:00 AM</small>
                                        </div>
                                        <div class="border-start border-success border-4 ps-3 mb-3 bg-light p-2 rounded">
                                            <strong>Northside Residential Area</strong><br>
                                            <small class="text-muted">45 Residential Clients</small><br>
                                            <small class="text-info">Friday at 9:30 AM</small>
                                        </div>
                                        <div class="border-start border-success border-4 ps-3 mb-3 bg-light p-2 rounded">
                                            <strong>Industrial Park Zone</strong><br>
                                            <small class="text-muted">8 Industrial Clients</small><br>
                                            <small class="text-info">Next Monday at 7:00 AM</small>
                                        </div>
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
                        <div class="card-body p-0">
                            <div class="demo-map">
                                <i class="bi bi-geo-alt-fill display-1 text-teal"></i>
                                <h4 class="mt-3 text-teal">Live GPS Tracking</h4>
                                <p class="text-muted">Active routes and collection points</p>
                                <button class="btn btn-teal mt-2" id="refreshMap">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh Map
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client Database Tab -->
                <div id="clients-tab" class="tab-content">
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
                            <a href="#" class="btn btn-teal btn-sm">
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
                                        <tr>
                                            <td>WC-1001</td>
                                            <td>ABC Manufacturing</td>
                                            <td>John Smith</td>
                                            <td><span class="badge badge-teal">Industrial</span></td>
                                            <td>(555) 123-4567</td>
                                            <td>john@abcmfg.com</td>
                                            <td>123 Industrial Way</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                                <button class="btn btn-sm btn-outline-warning">Edit</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>WC-1002</td>
                                            <td>XYZ Retail</td>
                                            <td>Sarah Johnson</td>
                                            <td><span class="badge badge-teal">Commercial</span></td>
                                            <td>(555) 987-6543</td>
                                            <td>sarah@xyzretail.com</td>
                                            <td>456 Commerce St</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                                <button class="btn btn-sm btn-outline-warning">Edit</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>WC-1003</td>
                                            <td>Global Foods Inc</td>
                                            <td>Michael Brown</td>
                                            <td><span class="badge badge-teal">Industrial</span></td>
                                            <td>(555) 456-7890</td>
                                            <td>michael@globalfoods.com</td>
                                            <td>789 Food Ave</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                                <button class="btn btn-sm btn-outline-warning">Edit</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other tabs (simplified) -->
                <div id="billing-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Billing & Payments</h5>
                        </div>
                        <div class="card-body text-center py-5">
                            <i class="bi bi-credit-card display-1 text-teal"></i>
                            <h3 class="mt-3 text-teal">Billing & Payments Module</h3>
                            <p class="text-muted">This section would contain billing information and payment tracking</p>
                            <button class="btn btn-teal mt-3">View Billing Dashboard</button>
                        </div>
                    </div>
                </div>

                <div id="collection-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Collection Schedules</h5>
                        </div>
                        <div class="card-body text-center py-5">
                            <i class="bi bi-calendar3 display-1 text-teal"></i>
                            <h3 class="mt-3 text-teal">Collection Schedules</h3>
                            <p class="text-muted">Manage and view waste collection schedules</p>
                            <button class="btn btn-teal mt-3">View Schedule Calendar</button>
                        </div>
                    </div>
                </div>

                <!-- Additional tabs would follow the same pattern -->
                <div id="disposal-tab" class="tab-content">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-teal">Disposal Schedules</h3>
                            <p class="text-muted">Content for disposal schedules would appear here</p>
                        </div>
                    </div>
                </div>

                <div id="sms-tab" class="tab-content">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-teal">SMS Manager</h3>
                            <p class="text-muted">Content for SMS management would appear here</p>
                        </div>
                    </div>
                </div>

                <div id="routes-tab" class="tab-content">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-teal">Route Optimization</h3>
                            <p class="text-muted">Content for route optimization would appear here</p>
                        </div>
                    </div>
                </div>

                <div id="gps-tab" class="tab-content">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-teal">GPS Tracker</h3>
                            <p class="text-muted">Content for GPS tracking would appear here</p>
                        </div>
                    </div>
                </div>

                <div id="reports-tab" class="tab-content">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-teal">Reports & Analytics</h3>
                            <p class="text-muted">Content for reports and analytics would appear here</p>
                        </div>
                    </div>
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
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Show selected tab content
                const tabId = this.getAttribute('data-tab') + '-tab';
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar.style.width === '250px' || sidebar.style.width === '') {
                sidebar.style.width = '0';
                mainContent.style.marginLeft = '0';
            } else {
                sidebar.style.width = '250px';
                mainContent.style.marginLeft = '250px';
            }
        });
        
        // Initialize dashboard data
        function loadDashboardData() {
            // Simulate API call delay
            setTimeout(() => {
                document.getElementById('totalClients').textContent = '142';
                document.getElementById('totalInvoices').textContent = '89';
                document.getElementById('pendingPayments').textContent = '$2,450';
                document.getElementById('activeRoutes').textContent = '6';
            }, 1000);
        }
        
        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            
            // Set user name from auth data (simulated)
            document.getElementById('userName').textContent = 'Demo Contractor';
        });
        
        // Client search functions
        function searchClients() {
            const name = document.getElementById('searchName').value;
            const category = document.getElementById('searchCategory').value;
            const location = document.getElementById('searchLocation').value;
            const regNumber = document.getElementById('searchRegNumber').value;
            
            // In a real application, this would make an API call
            alert(`Searching for clients with:\nName: ${name}\nCategory: ${category}\nLocation: ${location}\nReg Number: ${regNumber}`);
        }
        
        function clearSearch() {
            document.getElementById('searchName').value = '';
            document.getElementById('searchCategory').value = '';
            document.getElementById('searchLocation').value = '';
            document.getElementById('searchRegNumber').value = '';
        }
        
        // Refresh map button
        document.getElementById('refreshMap').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="loading-spinner"></span> Refreshing...';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                alert('Map data refreshed successfully!');
            }, 1500);
        });
    </script>
</body>
</html>