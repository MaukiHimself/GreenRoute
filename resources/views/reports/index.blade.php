@extends('layouts.contractor-sidebar')

@section('title', 'Reports & Analytics')

@section('styles')
<style>
    :root {
        --primary-color: #047857;
        --secondary-color: #c0392b;
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
        padding: 0;
        margin: 0;
    }

    .container-fluid {
        padding: 2rem;
        max-width: 1400px;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary-color);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: #065f46;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        color: white;
    }

    /* Content Sections */
    .content-section {
        background: var(--white-color);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-bg);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border-left: 4px solid var(--primary-color);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-value.primary { color: var(--primary-color); }
    .stat-value.success { color: var(--primary-color); }
    .stat-value.warning { color: #d97706; }
    .stat-value.info { color: var(--primary-color); }
    .stat-value.danger { color: var(--secondary-color); }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Category Lists */
    .category-list {
        background: var(--light-bg);
        border-radius: 10px;
        padding: 1.5rem;
        max-height: 200px;
        overflow-y: auto;
    }

    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .category-item:last-child {
        border-bottom: none;
    }

    .category-name {
        color: var(--text-dark);
        font-weight: 500;
    }

    .category-count {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Charts Container */
    .chart-container {
        background: var(--white-color);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-bg);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    canvas {
        border-radius: 8px;
    }

    /* Divider */
    .section-divider {
        height: 2px;
        background: var(--light-bg);
        margin: 2rem 0;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .container-fluid {
            padding: 1.5rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .content-section {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 1.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Reports & Analytics</h1>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('dashboard.contractor') }}" class="btn-primary" style="background: #6c757d;">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="{{ route('reports.export') }}" class="btn-primary" target="_parent">
                <i class="bi bi-download"></i> Export Report
            </a>
        </div>
    </div>

    <!-- Business Overview -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Business Overview</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value primary">{{ $data['overview']['total_clients'] }}</div>
                <div class="stat-label">Clients</div>
            </div>
            <div class="stat-card">
                <div class="stat-value primary">{{ $data['overview']['total_routes'] }}</div>
                <div class="stat-label">Routes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value primary">{{ $data['overview']['total_trucks'] }}</div>
                <div class="stat-label">Trucks</div>
            </div>
            <div class="stat-card">
                <div class="stat-value success">{{ $data['overview']['runs_completed'] }}</div>
                <div class="stat-label">Completed Collection Runs</div>
            </div>
        </div>
    </div>

    <!-- Field Operations (from collection runs + weighbridge) -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Field Operations & Waste Collected</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value success">{{ number_format($data['operationsStats']['total_waste_kg'], 1) }} kg</div>
                <div class="stat-label">Waste Weighed at Dumping Site (all trips)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value info">{{ number_format($data['operationsStats']['month_waste_kg'], 1) }} kg</div>
                <div class="stat-label">Weighed This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-value primary">{{ $data['operationsStats']['trips_weighed'] }}</div>
                <div class="stat-label">Trips Weighed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value {{ ($data['operationsStats']['success_rate'] ?? 0) >= 80 ? 'success' : 'warning' }}">
                    {{ $data['operationsStats']['success_rate'] !== null ? $data['operationsStats']['success_rate'] . '%' : '—' }}
                </div>
                <div class="stat-label">Collection Success Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-value success">{{ $data['operationsStats']['stops_collected'] }}</div>
                <div class="stat-label">Stops Collected</div>
            </div>
            <div class="stat-card">
                <div class="stat-value warning">{{ $data['operationsStats']['stops_skipped'] }}</div>
                <div class="stat-label">Stops Skipped</div>
            </div>
            <div class="stat-card">
                <div class="stat-value danger">{{ $data['operationsStats']['stops_blocked'] }}</div>
                <div class="stat-label">Stops Blocked</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Waste by Route (weighbridge)</h5>
                <div class="category-list">
                    @forelse($data['operationsStats']['waste_by_route'] as $row)
                    <div class="category-item">
                        <span class="category-name">{{ $row->route_name ?? 'Unnamed route' }} <small class="text-muted">({{ $row->trips }} trip{{ $row->trips == 1 ? '' : 's' }})</small></span>
                        <span class="category-count">{{ number_format($row->total_kg, 1) }} kg</span>
                    </div>
                    @empty
                    <div class="text-muted small py-2">No weighed trips yet — record a weighbridge reading from the driver terminal after a route.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Top Clients by Waste (estimated share)</h5>
                <div class="category-list">
                    @forelse($data['operationsStats']['top_clients_by_waste'] as $row)
                    <div class="category-item">
                        <span class="category-name">{{ $row->client_name ?? 'Client' }} <small class="text-muted">({{ $row->pickups }} pickup{{ $row->pickups == 1 ? '' : 's' }})</small></span>
                        <span class="category-count">~{{ number_format($row->total_kg, 1) }} kg</span>
                    </div>
                    @empty
                    <div class="text-muted small py-2">Per-client estimates appear once trips are weighed.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Disposal Records (from completed schedules) -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Disposal Records</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value primary">{{ number_format($data['disposalStats']['recorded_weight_kg'], 1) }} kg</div>
                <div class="stat-label">Recorded on Schedules</div>
            </div>
            <div class="stat-card">
                <div class="stat-value success">{{ number_format($data['disposalStats']['recycled_kg'], 1) }} kg</div>
                <div class="stat-label">To Sorting Facility (recycling)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value warning">{{ number_format($data['disposalStats']['landfill_kg'], 1) }} kg</div>
                <div class="stat-label">To Landfill</div>
            </div>
            <div class="stat-card">
                <div class="stat-value danger">{{ $data['disposalStats']['pending_records'] }}</div>
                <div class="stat-label">Completed Collections Awaiting Record</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Waste by Category</h5>
                <div class="category-list">
                    @forelse($data['disposalStats']['by_category'] as $row)
                    <div class="category-item">
                        <span class="category-name">{{ ucfirst($row->waste_category ?? 'Uncategorised') }}</span>
                        <span class="category-count">{{ number_format($row->total_kg, 1) }} kg</span>
                    </div>
                    @empty
                    <div class="text-muted small py-2">No disposal records yet — use "Record Data" on a completed collection.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Waste by Disposal Site</h5>
                <div class="category-list">
                    @forelse($data['disposalStats']['by_site'] as $row)
                    <div class="category-item">
                        <span class="category-name">{{ $row->disposal_site }}</span>
                        <span class="category-count">{{ number_format($row->total_kg, 1) }} kg</span>
                    </div>
                    @empty
                    <div class="text-muted small py-2">No disposal records yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Billing Statistics -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Billing & Revenue Summary</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value success">TZS {{ number_format($data['billingStats']['paid_revenue'], 2) }}</div>
                <div class="stat-label">Total Revenue Paid</div>
            </div>
            <div class="stat-card">
                <div class="stat-value warning">TZS {{ number_format($data['billingStats']['pending_payments'], 2) }}</div>
                <div class="stat-label">Pending Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value info">TZS {{ number_format($data['billingStats']['monthly_revenue'], 2) }}</div>
                <div class="stat-label">This Month's Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-value success">{{ $data['billingStats']['paid_customers'] }}</div>
                <div class="stat-label">Customers Paid</div>
            </div>
            <div class="stat-card">
                <div class="stat-value danger">{{ $data['billingStats']['overdue_customers'] }}</div>
                <div class="stat-label">Overdue Customers</div>
            </div>
        </div>
    </div>

    <!-- Client Statistics -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Client Database Summary</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value primary">{{ $data['clientStats']['total_clients'] }}</div>
                <div class="stat-label">Total Clients</div>
            </div>
            <div class="stat-card">
                <div class="stat-value success">{{ $data['clientStats']['new_this_month'] }}</div>
                <div class="stat-label">New This Month</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Clients by Category</h5>
                <div class="category-list">
                    @foreach($data['clientStats']['by_category'] as $category)
                    <div class="category-item">
                        <span class="category-name">{{ ucfirst($category->category ?? 'Unknown') }}</span>
                        <span class="category-count">{{ $category->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3" style="color: var(--primary-color);">Clients by Route</h5>
                <div class="category-list">
                    @forelse($data['clientStats']['by_route'] as $route)
                    <div class="category-item">
                        <span class="category-name">{{ $route->route }}</span>
                        <span class="category-count">{{ $route->count }}</span>
                    </div>
                    @empty
                    <div class="text-muted small py-2">No clients assigned to routes yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-lg-12 mb-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Waste Collected per Month (kg)</h3>
                </div>
                <canvas id="wasteChart" height="90"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Revenue Trend</h3>
                </div>
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Client Distribution</h3>
                </div>
                <canvas id="clientChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly waste chart: weighbridge trips vs schedule disposal records
    const wasteCtx = document.getElementById('wasteChart').getContext('2d');
    new Chart(wasteCtx, {
        type: 'bar',
        data: {
            labels: @json($data['monthlyWaste']['labels']),
            datasets: [
                {
                    label: 'Weighbridge (trips, kg)',
                    data: @json($data['monthlyWaste']['trip_kg']),
                    backgroundColor: '#047857'
                },
                {
                    label: 'Schedule records (kg)',
                    data: @json($data['monthlyWaste']['schedule_kg']),
                    backgroundColor: '#0a8989'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Weekly', 'Monthly', 'Yearly'],
            datasets: [{
                label: 'Revenue (TZS)',
                data: [
                    {{ $data['billingStats']['weekly_revenue'] }},
                    {{ $data['billingStats']['monthly_revenue'] }},
                    {{ $data['billingStats']['yearly_revenue'] }}
                ],
                backgroundColor: ['#047857', '#087272', '#0a8989']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Client Distribution Chart
    const clientCtx = document.getElementById('clientChart').getContext('2d');
    new Chart(clientCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($data['clientStats']['by_category'] as $category)
                    '{{ ucfirst($category->category ?? "Unknown") }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($data['clientStats']['by_category'] as $category)
                        {{ $category->count }},
                    @endforeach
                ],
                backgroundColor: ['#047857', '#087272', '#0a8989', '#0ba0a0', '#0cb7b7']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
