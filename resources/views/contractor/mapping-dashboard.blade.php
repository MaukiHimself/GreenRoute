@extends('layouts.contractor-sidebar')

@section('title', 'Contractor Dashboard')

@section('styles')
<style>
    :root {
        --primary-teal: #055c5c;
        --primary-green: #2e7d32;
        --primary-red: #c0392b;
        --light-teal: #e6f2f2;
        --light-green: #e8f5e9;
        --light-red: #f9eaea;
    }

    .stat-card {
        text-align: center;
        padding: 20px 15px;
        border-radius: 10px;
        color: white;
    }

    .stat-card {
        box-shadow: 0 6px 18px rgba(2, 53, 53, 0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(2, 53, 53, 0.18);
    }

    .stat-card.clients {
        background: linear-gradient(135deg, var(--primary-teal), #088484);
    }

    .stat-card.invoices {
        background: linear-gradient(135deg, #0a7c7c, var(--primary-green));
    }

    .stat-card.payments {
        background: linear-gradient(135deg, var(--primary-green), #1b5e20);
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

    .quick-action {
        text-align: center;
        padding: 20px 15px;
        border-radius: 10px;
        background-color: white;
        transition: all 0.3s;
        border: 1px solid #eee;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .quick-action:hover {
        background-color: var(--light-teal);
        transform: translateY(-5px);
        color: inherit;
    }

    .quick-action i {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: var(--primary-teal);
    }

    .table th {
        background-color: var(--light-teal);
        color: var(--primary-teal);
        font-weight: 600;
        border-top: none;
    }

    .map-container {
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
    }

    .tab-content iframe {
        border-radius: 0 0 10px 10px;
    }
</style>
@section('content')
<div class="container-fluid">
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
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('billing.create') }}" class="quick-action d-block">
                                    <i class="bi bi-receipt"></i>
                                    <h6>Create Invoice</h6>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('schedules.create') }}" class="quick-action d-block">
                                    <i class="bi bi-calendar-plus"></i>
                                    <h6>Schedule Collection</h6>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('reports.index') }}" class="quick-action d-block">
                                    <i class="bi bi-graph-up"></i>
                                    <h6>View Reports</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payment Submissions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Payment Submissions</h5>
                        <a href="{{ route('contractor.pending-payments') }}" class="btn btn-sm btn-outline-teal">View All</a>
                    </div>
                    <div class="card-body">
                        <div id="recentPaymentSubmissions">
                            <p class="text-muted">Loading recent payment submissions...</p>
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
                        <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-teal">View All</a>
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
                        <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-teal">View All</a>
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
            <div class="card-header">
                <h5 class="mb-0">Client Database</h5>
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
        <iframe src="{{ route('invoices.index') }}" width="100%" height="600" frameborder="0"></iframe>
    </div>

    <div id="collection-tab" class="tab-content" style="display: none;">
        <iframe src="{{ route('schedules.index') }}" width="100%" height="600" frameborder="0"></iframe>
    </div>

    <div id="disposal-tab" class="tab-content" style="display: none;">
        <iframe id="disposal-iframe" src="{{ route('disposal.index') }}" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
    </div>

    <div id="chats-tab" class="tab-content" style="display: none;">
        <iframe id="chats-iframe" src="{{ route('sms.inbox') }}" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
    </div>

    <div id="route-management-tab" class="tab-content" style="display: none;">
        <iframe id="route-management-iframe" src="{{ route('route-management.index') }}" width="100%" height="800" frameborder="0" style="border: none;"></iframe>
    </div>

    <div id="route-optimization-tab" class="tab-content" style="display: none;">
        <iframe src="{{ route('routes.index') }}" width="100%" height="600" frameborder="0"></iframe>
    </div>

    <div id="gps-tab" class="tab-content" style="display: none;">
        <iframe src="{{ route('trucks.index') }}" width="100%" height="600" frameborder="0"></iframe>
    </div>

    <div id="reports-tab" class="tab-content" style="display: none;">
        <iframe src="{{ route('reports.index') }}" width="100%" height="600" frameborder="0"></iframe>
    </div>

@include('components.leaflet-assets')

@endsection

@section('scripts')
@verbatim
<script>
    function showTab(tabName) {
        document.querySelectorAll('#portal-sidebar .portal-sidebar__link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-tab') === tabName) {
                link.classList.add('active');
            }
        });

        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });

        const tabId = tabName + '-tab';
        const selectedTabEl = document.getElementById(tabId);
        if (selectedTabEl) {
            selectedTabEl.style.display = 'block';
        }

        if (tabName === 'disposal') {
            const disposalIframe = document.getElementById('disposal-iframe');
            if (disposalIframe) {
                disposalIframe.src = disposalIframe.src;
            }
        }

        if (tabName === 'clients') {
            loadClientsTable();
        } else if (tabName === 'gps') {
            initGPSMap();
        }
    }

    document.querySelectorAll('#portal-sidebar .portal-sidebar__link[data-tab]').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabName = this.getAttribute('data-tab');
            const path = '/dashboard/contractor' + (tabName === 'dashboard' ? '' : '/' + tabName);
            history.pushState({tab: tabName}, '', path);
            showTab(tabName);
        });
    });

    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.tab) {
            showTab(e.state.tab);
        } else {
            const path = window.location.pathname;
            const segments = path.split('/').filter(Boolean);
            const tabName = segments[segments.length - 1] || 'dashboard';
            showTab(tabName);
        }
    });

    (function() {
        const path = window.location.pathname;
        const segments = path.split('/').filter(Boolean);
        const lastSegment = segments[segments.length - 1] || 'dashboard';
        const validTabs = ['dashboard', 'clients', 'billing', 'collection', 'disposal', 'gps', 'schedules', 'routes', 'reports', 'zones', 'pending-payment-approvals'];
        const tabName = validTabs.includes(lastSegment) ? lastSegment : 'dashboard';
        showTab(tabName);
    })();

    function loadDashboardData() {
        fetch('{{ route("contractor.dashboard-stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalClients').textContent = data.total_clients || 0;
                document.getElementById('totalInvoices').textContent = data.total_invoices || 0;
                document.getElementById('pendingPayments').textContent = 'TZS ' + (data.pending_payments || 0);
                document.getElementById('activeRoutes').textContent = data.active_routes || 0;
                const badge = document.getElementById('paymentNotificationCount');
                if (badge) {
                    badge.textContent = data.new_payment_notifications || 0;
                }
            })
            .catch(() => {
                console.log('Dashboard stats not available');
            });

        fetch('{{ route("contractor.recent-invoices") }}')
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

        fetch('{{ route("contractor.upcoming-schedules") }}')
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
                             ${schedule.schedule_price ? `<br><small class="text-success fw-bold">TZS ${schedule.schedule_price}</small>` : ''}
                        </div>
                    `;
                });
            })
            .catch(() => {
                document.getElementById('upcomingSchedules').innerHTML = '<p class="text-muted">Unable to load schedules</p>';
            });

        fetch('{{ route("contractor.recent-pending-payments") }}')
            .then(response => response.json())
            .then(payments => {
                const container = document.getElementById('recentPaymentSubmissions');
                if (payments.length === 0) {
                    container.innerHTML = '<p class="text-muted">No recent payment submissions</p>';
                    return;
                }
                container.innerHTML = '';
                payments.forEach(payment => {
                    container.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-start border-3 border-warning">
                            <div>
                                <strong>Payment #${payment.id}</strong><br>
                                <small class="text-muted">${payment.client_name}</small><br>
                                <small class="text-muted">Invoice #${payment.invoice_number}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-warning">TZS ${payment.amount_submitted}</span><br>
                                <small class="badge bg-warning text-dark">${payment.status === 'pending_approval' ? 'Pending Approval' : payment.status}</small>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(() => {
                document.getElementById('recentPaymentSubmissions').innerHTML = '<p class="text-muted">Unable to load payment submissions</p>';
            });
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

        fetch(`{{ route('contractor.clients.locations') }}?${params.toString()}`)
            .then(response => response.json())
            .then(clients => {
                const tbody = document.getElementById('clientsTable');
                tbody.innerHTML = '';
                if (clients.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">No clients found matching your search criteria</td></tr>';
                    return;
                }
                clients.forEach(client => {
                    const isPending = client.status === 'pending';
                    const rowBg = isPending ? 'background:#fffbeb;' : '';
                    const statusBadge = isPending
                        ? '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>'
                        : '<span class="badge bg-success">Active</span>';
                    const selfRegBadge = client.self_registered
                        ? '<br><small class="text-muted"><i class="bi bi-person-up"></i> Self-registered</small>'
                        : '';
                    const actionButtons = isPending
                        ? `<form action="/contractor/pending-clients/${client.id}/approve" method="POST" class="d-inline" onsubmit="return confirm('Approve ${client.name}?')">
                               @csrf
                               <button type="submit" class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg me-1"></i>Approve</button>
                           </form>
                           <form action="/contractor/pending-clients/${client.id}/reject" method="POST" class="d-inline" onsubmit="return confirm('Reject ${client.name}?')">
                               @csrf
                               <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                           </form>`
                        : `<a href="{{ url('dashboard/contractor/clients/' + client.id) }}" class="btn btn-sm btn-outline-primary">View</a>
                           <a href="{{ url('dashboard/contractor/clients/' + client.id + '/edit') }}" class="btn btn-sm btn-outline-warning">Edit</a>`;
                    tbody.innerHTML += `
                        <tr style="${rowBg}">
                            <td>${client.registration_number || 'N/A'}</td>
                            <td><strong>${client.name}</strong>${selfRegBadge}</td>
                            <td>${client.contact_name || 'N/A'}</td>
                            <td><span class="badge bg-info">${client.category || 'N/A'}</span></td>
                            <td>${client.phone || 'N/A'}<br><small>${client.phone_2 || ''} ${client.phone_3 ? '<br>' + client.phone_3 : ''}</small></td>
                            <td>${client.email || 'N/A'}</td>
                            <td>${client.address || 'N/A'}${client.route ? '<br><small class="text-muted">Route: ' + client.route + ' (' + client.region + ')</small>' : ''}</td>
                            <td>${statusBadge}</td>
                            <td><div class="d-flex gap-1">${actionButtons}</div></td>
                        </tr>
                    `;
                });
            })
            .catch(() => {
                document.getElementById('clientsTable').innerHTML = '<tr><td colspan="9" class="text-center">Error searching clients</td></tr>';
            });
    }

    function clearSearch() {
        document.getElementById('searchName').value = '';
        document.getElementById('searchCategory').value = '';
        document.getElementById('searchLocation').value = '';
        document.getElementById('searchRegNumber').value = '';
        loadClientsTable();
    }

    function loadClientsTable() {
        fetch('{{ route('contractor.clients.locations') }}')
            .then(response => response.json())
            .then(clients => {
                const tbody = document.getElementById('clientsTable');
                tbody.innerHTML = '';
                if (clients.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">No clients found</td></tr>';
                    return;
                }
                clients.forEach(client => {
                    const isPending = client.status === 'pending';
                    const rowBg = isPending ? 'background:#fffbeb;' : '';
                    const statusBadge = isPending
                        ? '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>'
                        : '<span class="badge bg-success">Active</span>';
                    const selfRegBadge = client.self_registered
                        ? '<br><small class="text-muted"><i class="bi bi-person-up"></i> Self-registered</small>'
                        : '';
                    const actionButtons = isPending
                        ? `<form action="/contractor/pending-clients/${client.id}/approve" method="POST" class="d-inline" onsubmit="return confirm('Approve ${client.name}? They will receive login credentials by email.')">
                               @csrf
                               <button type="submit" class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg me-1"></i>Approve</button>
                           </form>
                           <form action="/contractor/pending-clients/${client.id}/reject" method="POST" class="d-inline" onsubmit="return confirm('Reject ${client.name}?')">
                               @csrf
                               <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                           </form>`
                        : `<a href="{{ url('dashboard/contractor/clients/' + client.id) }}" class="btn btn-sm btn-outline-primary">View</a>
                           <a href="{{ url('dashboard/contractor/clients/' + client.id + '/edit') }}" class="btn btn-sm btn-outline-warning">Edit</a>`;
                    tbody.innerHTML += `
                        <tr style="${rowBg}">
                            <td>${client.registration_number || 'N/A'}</td>
                            <td><strong>${client.name}</strong>${selfRegBadge}</td>
                            <td>${client.contact_name || 'N/A'}</td>
                            <td><span class="badge bg-info">${client.category || 'N/A'}</span></td>
                            <td>${client.phone || 'N/A'}<br><small>${client.phone_2 || ''} ${client.phone_3 ? '<br>' + client.phone_3 : ''}</small></td>
                            <td>${client.email || 'N/A'}</td>
                            <td>${client.address || 'N/A'}${client.route ? '<br><small class="text-muted">Route: ' + client.route + ' (' + client.region + ')</small>' : ''}</td>
                            <td>${statusBadge}</td>
                            <td><div class="d-flex gap-1">${actionButtons}</div></td>
                        </tr>
                    `;
                });
            })
            .catch(() => {
                document.getElementById('clientsTable').innerHTML = '<tr><td colspan="9" class="text-center">Error loading clients</td></tr>';
            });
    }

    function initGPSMap() {
        GreenRouteMap.whenReady(function () {
            const ctx = GreenRouteMap.createMap('dashboardMap', { lat: -6.7924, lng: 39.2083, zoom: 12 });
            if (ctx) {
                GreenRouteMap.addMarker(ctx, -6.7924, 39.2083, {
                    title: 'Dar es Salaam',
                    popup: '<strong>GPS Tracker</strong><br>Map ready — assign client coordinates to show stops.',
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initGPSMap();
        loadDashboardData();
    });
</script>
@endverbatim
@endsection
