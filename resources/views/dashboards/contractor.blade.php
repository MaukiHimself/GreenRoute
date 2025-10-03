<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Dashboard</title>
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
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Header Section */
        .page-header {
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
        
        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), #087272);
            color: var(--white-color);
            padding: 3rem 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(5, 92, 92, 0.2);
        }
        
        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .date-display {
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
        }
        
        .date-item {
            text-align: center;
        }
        
        .date-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .date-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }
        
        .stat-icon.primary { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        .stat-icon.success { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        .stat-icon.warning { background: rgba(217, 119, 6, 0.1); color: #d97706; }
        .stat-icon.info { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }
        
        .stat-label {
            color: var(--text-muted);
            font-weight: 500;
        }
        
        /* Content Sections */
        .content-section {
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
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Tabs */
        .tab-buttons {
            display: flex;
            gap: 0.5rem;
            background: var(--light-bg);
            padding: 0.5rem;
            border-radius: 12px;
        }
        
        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tab-btn.active {
            background: var(--primary-color);
            color: white;
        }
        
        .tab-btn:not(.active):hover {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-color);
        }
        
        /* Table Styling */
        .table-container {
            background: transparent;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: var(--white-color);
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge.primary { background: var(--primary-color); }
        .badge.success { background: var(--primary-color); }
        .badge.warning { background: #d97706; }
        
        /* Progress Bars */
        .progress {
            height: 8px;
            background: var(--light-bg);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            border-radius: 10px;
        }
        
        .progress-bar.success { background: var(--primary-color); }
        .progress-bar.warning { background: #d97706; }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
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
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            background: var(--white-color);
            border: 2px solid transparent;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .action-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .action-btn.primary {
            background: var(--primary-color);
            color: white;
        }
        
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .action-btn.primary .action-icon {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .action-btn:not(.primary) .action-icon {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-color);
        }
        
        /* Performance Chart */
        .performance-section {
            background: linear-gradient(135deg, var(--primary-color), #087272);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2rem;
        }
        
        .chart-container {
            height: 200px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 1rem 0;
        }
        
        .chart-bar {
            flex: 1;
            margin: 0 0.25rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
            min-height: 20px;
        }
        
        .chart-bar:hover {
            background: white;
            transform: scaleY(1.05);
        }
        
        /* Activity Feed */
        .activity-feed {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: var(--white-color);
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }
        
        .activity-icon.success { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        .activity-icon.primary { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        .activity-icon.warning { background: rgba(217, 119, 6, 0.1); color: #d97706; }
        .activity-icon.info { background: rgba(5, 92, 92, 0.1); color: var(--primary-color); }
        
        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--light-bg);
        }
        
        .quick-stat {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        
        .quick-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .quick-stat-value.primary { color: var(--primary-color); }
        .quick-stat-value.warning { color: #d97706; }
        
        .quick-stat-label {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .activity-feed {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 992px) {
            .dashboard-container {
                padding: 1.5rem;
            }
            
            .welcome-section {
                padding: 2rem 1.5rem;
            }
            
            .date-display {
                justify-content: flex-start;
                margin-top: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
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
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .tab-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="welcome-title">Welcome back, John!</h1>
                    <p class="welcome-subtitle">Here's what's happening with your waste management business today.</p>
                </div>
                <div class="col-lg-4">
                    <div class="date-display">
                        <div class="date-item">
                            <div class="date-value">24</div>
                            <div class="date-label">May 2023</div>
                        </div>
                        <div class="date-item">
                            <div class="date-value">Wednesday</div>
                            <div class="date-label">10:30 AM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-item d-flex align-items-center">
                <div class="stat-icon primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Active Clients</div>
                </div>
            </div>
            <div class="stat-item d-flex align-items-center">
                <div class="stat-icon success">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Total Routes</div>
                </div>
            </div>
            <div class="stat-item d-flex align-items-center">
                <div class="stat-icon warning">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">36</div>
                    <div class="stat-label">Completed Jobs</div>
                </div>
            </div>
            <div class="stat-item d-flex align-items-center">
                <div class="stat-icon info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">$2,450</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Dashboard Content -->
            <div class="col-lg-8">
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-speedometer2"></i>Dashboard Overview
                        </h2>
                        <div class="tab-buttons">
                            <button class="tab-btn active">
                                <i class="bi bi-route"></i>Routes
                            </button>
                            <a href="{{ route('contractor.clients.index') }}" class="tab-btn">
                                <i class="bi bi-people"></i>Clients
                            </a>
                            <button class="tab-btn">
                                <i class="bi bi-calendar3"></i>Schedules
                            </button>
                        </div>
                    </div>
                    
                    <!-- Routes Table -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0" style="color: var(--text-muted);">Active Routes</h5>
                            <a href="{{ route('schedules.index') }}" class="btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i>Add Route
                            </a>
                        </div>
                        
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Route</th>
                                        <th>Clients</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-geo-alt text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">Route A</div>
                                                    <small class="text-muted">Downtown Area</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge primary">15 Clients</span></td>
                                        <td><span class="badge success">Active</span></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar success" style="width: 75%"></div>
                                            </div>
                                            <small class="text-muted">75% Complete</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn-outline-primary btn-sm" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn-outline-primary btn-sm" title="Edit Route">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-geo-alt text-warning"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">Route B</div>
                                                    <small class="text-muted">Suburban Area</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge warning">9 Clients</span></td>
                                        <td><span class="badge warning">Pending</span></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar warning" style="width: 30%"></div>
                                            </div>
                                            <small class="text-muted">30% Complete</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn-outline-primary btn-sm" title="Start Route">
                                                    <i class="bi bi-play-circle"></i>
                                                </button>
                                                <button class="btn-outline-primary btn-sm" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Quick Actions & Performance -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-lightning"></i>Quick Actions
                        </h2>
                        <span class="badge primary">4 Actions</span>
                    </div>
                    
                    <div class="quick-actions-grid">
                        <a href="{{ route('clients.create') }}" class="action-btn primary">
                            <div class="d-flex align-items-center">
                                <div class="action-icon">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <span>Add New Client</span>
                            </div>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('schedules.create') }}" class="action-btn">
                            <div class="d-flex align-items-center">
                                <div class="action-icon">
                                    <i class="bi bi-calendar-plus"></i>
                                </div>
                                <span>Schedule Pickup</span>
                            </div>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </a>
                        <a href="{{ route('invoices.create') }}" class="action-btn">
                            <div class="d-flex align-items-center">
                                <div class="action-icon">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <span>Create Invoice</span>
                            </div>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </a>
                        <a href="#" class="action-btn">
                            <div class="d-flex align-items-center">
                                <div class="action-icon">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <span>View Reports</span>
                            </div>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </a>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="quick-stats">
                        <div class="quick-stat">
                            <div class="quick-stat-value primary">8</div>
                            <div class="quick-stat-label">Completed</div>
                        </div>
                        <div class="quick-stat">
                            <div class="quick-stat-value warning">3</div>
                            <div class="quick-stat-label">Pending</div>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Chart -->
                <div class="performance-section">
                    <h5 class="mb-4 text-white">
                        <i class="bi bi-bar-chart me-2"></i>Weekly Performance
                    </h5>
                    <div class="chart-container">
                        <div class="d-flex flex-column align-items-center">
                            <div class="chart-bar" style="height: 60px;"></div>
                            <small class="text-white mt-2">Mon</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="chart-bar" style="height: 80px;"></div>
                            <small class="text-white mt-2">Tue</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="chart-bar" style="height: 120px;"></div>
                            <small class="text-white mt-2">Wed</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="chart-bar" style="height: 90px;"></div>
                            <small class="text-white mt-2">Thu</small>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="chart-bar" style="height: 140px;"></div>
                            <small class="text-white mt-2">Fri</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="content-section mt-4">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-clock-history"></i>Recent Activity
                </h2>
            </div>
            
            <div class="activity-feed">
                <div class="activity-column">
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Pickup Completed</div>
                            <small class="text-muted">Route A - 15 clients served</small>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon primary">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">New Client Added</div>
                            <small class="text-muted">John Smith - Downtown Area</small>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">4 hours ago</small>
                        </div>
                    </div>
                </div>
                <div class="activity-column">
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Invoice Generated</div>
                            <small class="text-muted">INV-001 - $250.00</small>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">6 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Schedule Updated</div>
                            <small class="text-muted">Route B - 3 new pickups</small>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple animation for chart bars
        document.addEventListener('DOMContentLoaded', function() {
            const chartBars = document.querySelectorAll('.chart-bar');
            chartBars.forEach(bar => {
                const originalHeight = bar.style.height;
                bar.style.height = '0';
                setTimeout(() => {
                    bar.style.height = originalHeight;
                }, 300);
            });
            
            // Add hover effects to stat items
            const statItems = document.querySelectorAll('.stat-item');
            statItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>