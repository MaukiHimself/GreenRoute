<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>
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
            background: #044a4a;
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
</head>
<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Reports & Analytics</h1>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('dashboard.contractor') }}" class="btn-primary" style="background: #6c757d;">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('reports.export') }}" class="btn-primary" target="_blank">
                    <i class="bi bi-download"></i> Export Report
                </a>
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
                    <h5 class="mb-3" style="color: var(--primary-color);">Clients by Location</h5>
                    <div class="category-list">
                        @foreach($data['clientStats']['by_location'] as $location)
                        <div class="category-item">
                            <span class="category-name">{{ $location->address }}</span>
                            <span class="category-count">{{ $location->count }}</span>
                        </div>
                        @endforeach
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
                    <div class="stat-value success">${{ number_format($data['billingStats']['paid_revenue'], 2) }}</div>
                    <div class="stat-label">Total Revenue Paid</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value warning">${{ number_format($data['billingStats']['pending_payments'], 2) }}</div>
                    <div class="stat-label">Pending Payments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value info">${{ number_format($data['billingStats']['weekly_revenue'], 2) }}</div>
                    <div class="stat-label">Weekly Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value info">${{ number_format($data['billingStats']['monthly_revenue'], 2) }}</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value info">${{ number_format($data['billingStats']['yearly_revenue'], 2) }}</div>
                    <div class="stat-label">Yearly Revenue</div>
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

        <!-- Collection & Disposal Statistics -->
        <div class="row">
            <div class="col-lg-6">
                <div class="content-section h-100">
                    <div class="section-header">
                        <h2 class="section-title">Collection Summary</h2>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value primary">{{ $data['collectionStats']['total_routes'] }}</div>
                            <div class="stat-label">Total Routes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value success">{{ $data['collectionStats']['completed_collections'] }}</div>
                            <div class="stat-label">Completed Collections</div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <h5 class="mb-3" style="color: var(--primary-color);">Volumes by Route</h5>
                    <div class="category-list">
                        @foreach($data['collectionStats']['volumes_by_route'] as $route)
                        <div class="category-item">
                            <span class="category-name">{{ $route->pickup_location }}</span>
                            <span class="category-count">{{ number_format($route->total_volume, 2) }} m³</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="content-section h-100">
                    <div class="section-header">
                        <h2 class="section-title">Disposal Summary</h2>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value primary">{{ number_format($data['disposalStats']['total_volume_collected'], 2) }} m³</div>
                            <div class="stat-label">Total Volume Collected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value success">{{ number_format($data['disposalStats']['recycled_volume'], 2) }} m³</div>
                            <div class="stat-label">Volume Recycled</div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <h5 class="mb-3" style="color: var(--primary-color);">Volumes by Disposal Type</h5>
                    <div class="category-list">
                        @foreach($data['disposalStats']['volumes_by_disposal_type'] as $disposal)
                        <div class="category-item">
                            <span class="category-name">{{ ucfirst(str_replace('_', ' ', $disposal->disposal_type)) }}</span>
                            <span class="category-count">{{ number_format($disposal->total_volume, 2) }} m³</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mt-4">
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
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Weekly', 'Monthly', 'Yearly'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [
                        {{ $data['billingStats']['weekly_revenue'] }},
                        {{ $data['billingStats']['monthly_revenue'] }},
                        {{ $data['billingStats']['yearly_revenue'] }}
                    ],
                    backgroundColor: ['#055c5c', '#087272', '#0a8989']
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
                    backgroundColor: ['#055c5c', '#087272', '#0a8989', '#0ba0a0', '#0cb7b7']
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
</body>
</html>
