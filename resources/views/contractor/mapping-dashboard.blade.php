<!DOCTYPE html>
<html>
<head>
    <title>Contractor Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #fff; border-right: 1px solid #dee2e6; }
        .sidebar .nav-link { color: #495057; padding: 15px 20px; border-bottom: 1px solid #f8f9fa; }
        .sidebar .nav-link:hover { background: #e3f2fd; }
        .sidebar .nav-link.active { background: #198754; color: white; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .chart-container { height: 250px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3 border-bottom">
                    <h4 class="text-success fw-bold">AFIA ORBIT</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" data-tab="dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#" data-tab="clients">
                        <i class="bi bi-people me-2"></i>Client Database
                    </a>
                    <a class="nav-link" href="#" data-tab="billing">
                        <i class="bi bi-credit-card me-2"></i>Billing & Payments
                    </a>
                    <a class="nav-link" href="#" data-tab="collection">
                        <i class="bi bi-calendar3 me-2"></i>Collection Schedules
                    </a>
                    <a class="nav-link" href="#" data-tab="disposal">
                        <i class="bi bi-trash me-2"></i>Disposal Schedules
                    </a>
                    <a class="nav-link" href="#" data-tab="sms">
                        <i class="bi bi-chat-dots me-2"></i>SMS Manager
                    </a>
                    <a class="nav-link" href="#" data-tab="routes">
                        <i class="bi bi-geo-alt me-2"></i>Route Optimization
                    </a>
                    <a class="nav-link" href="#" data-tab="gps">
                        <i class="bi bi-pin-map me-2"></i>GPS Tracker
                    </a>
                    <a class="nav-link" href="#" data-tab="reports">
                        <i class="bi bi-graph-up me-2"></i>Reports & Analytics
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="bg-white p-3 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Waste Contractor</li>
                                <li class="breadcrumb-item active">Dashboard</li>
                                <li class="breadcrumb-item active">{{ auth()->user()->name }}</li>
                            </ol>
                        </nav>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success">Notifications: 2</span>
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="dashboard-tab" class="tab-content">
                    <!-- Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <h3 class="text-success" id="totalClients">0</h3>
                                    <p class="text-muted mb-0">Total Clients</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <h3 class="text-primary" id="totalInvoices">0</h3>
                                    <p class="text-muted mb-0">Total Invoices</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <h3 class="text-warning" id="pendingPayments">$0</h3>
                                    <p class="text-muted mb-0">Pending Payments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <h3 class="text-info" id="activeRoutes">0</h3>
                                    <p class="text-muted mb-0">Active Routes</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-success mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <a href="/dashboard/contractor/clients/create" class="btn btn-outline-success w-100">
                                                <i class="bi bi-person-plus me-2"></i>Add New Client
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="/billing/create" class="btn btn-outline-primary w-100">
                                                <i class="bi bi-receipt me-2"></i>Create Invoice
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="/schedules/create" class="btn btn-outline-info w-100">
                                                <i class="bi bi-calendar-plus me-2"></i>Schedule Collection
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="/reports" class="btn btn-outline-warning w-100">
                                                <i class="bi bi-graph-up me-2"></i>View Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between">
                                    <h5 class="text-success mb-0">Recent Invoices</h5>
                                    <a href="/billing" class="btn btn-sm btn-outline-success">View All</a>
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
                                <div class="card-header d-flex justify-content-between">
                                    <h5 class="text-success mb-0">Upcoming Collections</h5>
                                    <a href="/schedules" class="btn btn-sm btn-outline-success">View All</a>
                                </div>
                                <div class="card-body">
                                    <div id="upcomingSchedules">
                                        <p class="text-muted">Loading upcoming schedules...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Map -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-success mb-0">GPS Tracker & Route Map</h5>
                        </div>
                        <div class="card-body">
                            <div id="map" style="height: 400px; border-radius: 8px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Client Database Tab -->
                <div id="clients-tab" class="tab-content" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="text-success mb-0">Search Client Database</h5>
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
                                    <button class="btn btn-success" onclick="searchClients()"><i class="bi bi-search me-1"></i>Search</button>
                                    <button class="btn btn-outline-secondary" onclick="clearSearch()"><i class="bi bi-x-circle me-1"></i>Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="text-success mb-0">Client Database</h5>
                            <a href="/dashboard/contractor/clients/create" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Add New Client
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
                                        <tr><td colspan="5" class="text-center">Loading clients...</td></tr>
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
                    <iframe src="/disposal" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="sms-tab" class="tab-content" style="display: none;">
                    <iframe src="/sms" width="100%" height="600" frameborder="0"></iframe>
                </div>

                <div id="routes-tab" class="tab-content" style="display: none;">
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
        let map, markers = [], currentLocation;
        
        // Tab switching
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
                document.getElementById(tabId).style.display = 'block';
                
                // Load specific content for tabs
                if (this.getAttribute('data-tab') === 'clients') {
                    loadClientsTable();
                } else if (this.getAttribute('data-tab') === 'gps') {
                    initGPSMap();
                }
            });
        });
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 40.7128, lng: -74.0060 }
            });
            
            loadClientLocations();
            getCurrentLocation();
            
            document.getElementById('updateLocation').onclick = updateMyLocation;
            document.getElementById('optimizeRoute').onclick = optimizeRoute;
        }
        
        function initGPSMap() {
            if (document.getElementById('gpsMap')) {
                const gpsMap = new google.maps.Map(document.getElementById('gpsMap'), {
                    zoom: 12,
                    center: { lat: 40.7128, lng: -74.0060 }
                });
            }
        }
        
        function loadClientLocations() {
            fetch('/contractor/clients/locations')
                .then(response => response.json())
                .then(clients => {
                    clients.forEach(client => {
                        const marker = new google.maps.Marker({
                            position: { lat: parseFloat(client.latitude), lng: parseFloat(client.longitude) },
                            map: map,
                            title: client.name
                        });
                        markers.push(marker);
                    });
                    document.getElementById('totalClients').textContent = clients.length;
                })
                .catch(() => document.getElementById('totalClients').textContent = '0');
        }
        
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
                                <td><span class="badge bg-info">${client.category || 'N/A'}</span></td>
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
        
        function loadDashboardData() {
            // Load dashboard statistics
            fetch('/contractor/dashboard-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalClients').textContent = data.total_clients || 0;
                    document.getElementById('totalInvoices').textContent = data.total_invoices || 0;
                    document.getElementById('pendingPayments').textContent = '$' + (data.pending_payments || 0);
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
                                    <span class="fw-bold text-${invoice.status === 'paid' ? 'success' : 'warning'}">$${invoice.total_amount}</span><br>
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
        
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    new google.maps.Marker({
                        position: currentLocation,
                        map: map,
                        title: 'My Location',
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    });
                    map.setCenter(currentLocation);
                });
            }
        }
        
        function updateMyLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    fetch('/location/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const toast = new bootstrap.Toast(document.createElement('div'));
                            alert('Location updated successfully!');
                        }
                    });
                });
            }
        }
        
        function optimizeRoute() {
            alert('Route optimization activated');
        }
        
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
                                <td><span class="badge bg-info">${client.category || 'N/A'}</span></td>
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


    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcwt701YioUFnzbJp9Bktla31qjKwM304&callback=initMap"></script>
</body>
</html>