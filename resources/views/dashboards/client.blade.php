<x-dashboard-layout title="Client Dashboard">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.profile') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.location') }}">
                    <i class="bi bi-geo-alt me-2"></i>My Location
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.chats') }}">
                    <i class="bi bi-chat-dots me-2"></i>Chats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.support') }}">
                    <i class="bi bi-headset me-2"></i>Support/Help
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ $portalHomeUrl }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Client</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <x-slot name="notificationCount">{{ $client->unread_notifications_count ?? 1 }}</x-slot>

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

        /* Welcome Section - Enhanced from contractor */
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

        /* Statistics Grid - Direct from contractor */
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
            display: flex;
            align-items: center;
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

        /* Tabs from contractor */
        .tab-buttons {
            display: flex;
            gap: 0.5rem;
            background: var(--light-bg);
            padding: 0.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
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

        /* Table Styling from contractor */
        .table-container {
            background: transparent;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
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

        .badge.primary { background: var(--primary-color); color: white; }
        .badge.success { background: var(--primary-color); color: white; }
        .badge.warning { background: #d97706; color: white; }

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

        /* Buttons from contractor */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
            color: white !important;
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
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white !important;
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            gap: 0.75rem;
        }

        .quick-actions-panel {
            background: var(--white-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 100%;
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

        /* Activity Feed */
        .activity-feed {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .dashboard-top-row {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            margin-bottom: 2rem;
            align-items: stretch;
        }

        @media (max-width: 992px) {
            .dashboard-top-row {
                grid-template-columns: 1fr;
            }
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

        /* Responsive from contractor */
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
        <!-- Welcome Section -->
        <div class="welcome-section">
                <h1 class="welcome-title">Welcome back, {{ $client->name ?? $authUser->name ?? 'Client' }}!</h1>
                <p class="welcome-subtitle">Track your pickups, invoices, and payments in one place.</p>
                <div class="date-display" style="justify-content: flex-end">
                    <div class="date-item">
                        <div class="date-value">{{ date('d') }}</div>
                        <div class="date-label">{{ date('M Y') }}</div>
                    </div>
                    <div class="date-item">
                        <div class="date-value">{{ date('l') }}</div>
                        <div class="date-label">{{ date('H:i A') }}</div>
                    </div>
                </div>
        </div>

        <!-- Stats Grid from contractor, adapted for client data -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon primary">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $completedPickups ?? 0 }}</div>
                    <div class="stat-label">Completed Pickups</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $upcomingSchedulesCount ?? 0 }}</div>
                    <div class="stat-label">Upcoming Schedules</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon warning">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">TZS {{ number_format($totalPaid ?? 0, 0) }}</div>
                    <div class="stat-label">Total Paid</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon info">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">TZS {{ number_format($totalPending ?? 0, 0) }}</div>
                    <div class="stat-label">Pending Amount</div>
                </div>
            </div>
        </div>

        <div class="quick-actions-panel mb-4">
            <h6 class="fw-bold text-uppercase text-muted small mb-3"><i class="bi bi-lightning-charge me-1"></i> Quick Actions</h6>
            <div class="quick-actions-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                <a href="{{ route('client.request.service') }}" class="action-btn primary">
                    <div class="d-flex align-items-center"><div class="action-icon"><i class="bi bi-plus-circle"></i></div><span>Request Pickup</span></div>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <a href="{{ route('client.invoices') }}" class="action-btn">
                    <div class="d-flex align-items-center"><div class="action-icon"><i class="bi bi-receipt"></i></div><span>View Invoices</span></div>
                    <i class="bi bi-arrow-right text-primary"></i>
                </a>
                <a href="{{ route('client.chats') }}" class="action-btn">
                    <div class="d-flex align-items-center"><div class="action-icon"><i class="bi bi-chat-dots"></i></div><span>Message Contractor</span></div>
                    <i class="bi bi-arrow-right text-primary"></i>
                </a>
                <a href="{{ route('client.profile') }}" class="action-btn">
                    <div class="d-flex align-items-center"><div class="action-icon"><i class="bi bi-person"></i></div><span>Update Profile</span></div>
                    <i class="bi bi-arrow-right text-primary"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
        <div class="col-lg-8">
        <!-- Tabs -->
        <div class="tab-buttons">
            <a href="{{ route('client.dashboard') }}" class="tab-btn active">
                <i class="bi bi-speedometer2"></i>Overview
            </a>
            <a href="{{ route('client.schedules') }}" class="tab-btn">
                <i class="bi bi-calendar3"></i>Schedules
            </a>
            <a href="{{ route('client.invoices') }}" class="tab-btn">
                <i class="bi bi-receipt"></i>Invoices
            </a>
            <a href="{{ route('client.chats') }}" class="tab-btn">
                <i class="bi bi-chat-dots"></i>Messages
            </a>
        </div>

        <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-list-ul"></i>Your Services Overview
                        </h2>
                        <div>
                            <a href="{{ route('client.request.service') }}" class="btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Request Service
                            </a>
                        </div>
                    </div>

                    <!-- Client Schedules Table from contractor style -->
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingSchedules as $schedule)
                                    @php
                                        $st = $schedule->status;
                                        $progress = match($st) {
                                            'completed' => 100,
                                            'in_progress' => 60,
                                            'scheduled' => 30,
                                            default => 15,
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-truck text-primary fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $schedule->service_type ?? 'collection')) }}</div>
                                                    <small class="text-muted">{{ $schedule->pickup_location ?? 'Your location' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $schedule->pickup_date?->format('D, M j') ?? '—' }}</td>
                                        <td><span class="badge {{ $st === 'completed' ? 'success' : ($st === 'cancelled' ? 'bg-secondary' : 'warning') }}">{{ ucfirst(str_replace('_', ' ', $st)) }}</span></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar success" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $progress }}%</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('client.schedules') }}" class="btn-outline-primary btn-sm" title="View schedules">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No upcoming schedules. <a href="{{ route('client.request.service') }}">Request a pickup</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recent Activity from contractor -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-clock-history"></i>Recent Activity
                        </h2>
                    </div>

                    <div class="activity-feed">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['color'] }}">
                                    <i class="bi bi-{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $activity['title'] }}</div>
                                    <small class="text-muted">{{ $activity['description'] }}</small>
                                </div>
                                <div>
                                    <small class="text-muted">{{ $activity['time']->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0 py-3">No recent activity yet. Your pickups and invoices will appear here.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


    <script>
        // Animations from contractor
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat items on hover
            const statItems = document.querySelectorAll('.stat-item');
            statItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Animate table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</x-dashboard-layout>

