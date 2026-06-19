<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Schedules Management - AFIA ORBIT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 1600px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            margin-bottom: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-teal);
        }
        
        .stat-card.green { border-left-color: #10b981; }
        .stat-card.orange { border-left-color: #f59e0b; }
        .stat-card.blue { border-left-color: #3b82f6; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.emerald { border-left-color: #059669; }
        
        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }
        
        /* Filters */
        .filters-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.65rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .btn-filter {
            background: var(--primary-teal);
            color: white;
            padding: 0.65rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        
        .btn-filter:hover {
            background: #044a4a;
        }
        
        .btn-reset {
            background: #6b7280;
            color: white;
            padding: 0.65rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-reset:hover {
            background: #4b5563;
            color: white;
        }
        
        /* Table */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: var(--primary-teal);
            color: white;
        }
        
        th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 0.35rem 0.65rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-scheduled { background: #dbeafe; color: #1e40af; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-in_progress { background: #e0e7ff; color: #4338ca; }
        
        .organic-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .action-btn {
            background: var(--primary-teal);
            color: white;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .action-btn:hover {
            background: #044a4a;
            color: white;
        }
        
        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('dashboard.admin') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Admin Dashboard
            </a>
        </div>
        
        <h1 class="page-title">Collection Schedules Management</h1>
        <p class="page-description">View and manage waste collection schedules with organic waste integration</p>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Schedules</div>
                <div class="stat-value">{{ $totalSchedules }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Upcoming</div>
                <div class="stat-value">{{ $upcomingSchedules }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completedSchedules }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $pendingSchedules }}</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">Today</div>
                <div class="stat-value">{{ $todaySchedules }}</div>
            </div>
            <div class="stat-card emerald">
                <div class="stat-label">Organic Waste</div>
                <div class="stat-value">{{ $organicWasteSchedules }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="{{ route('admin.schedules') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Contractor</label>
                        <select name="contractor_id" class="form-control">
                            <option value="">All Contractors</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ isset($filters['contractor_id']) && $filters['contractor_id'] == $contractor->id ? 'selected' : '' }}>
                                    {{ $contractor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ isset($filters['status']) && $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="scheduled" {{ isset($filters['status']) && $filters['status'] == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ isset($filters['status']) && $filters['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ isset($filters['status']) && $filters['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ isset($filters['status']) && $filters['status'] == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Frequency</label>
                        <select name="frequency" class="form-control">
                            <option value="">All Frequencies</option>
                            <option value="daily" {{ isset($filters['frequency']) && $filters['frequency'] == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ isset($filters['frequency']) && $filters['frequency'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="bi-weekly" {{ isset($filters['frequency']) && $filters['frequency'] == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                            <option value="monthly" {{ isset($filters['frequency']) && $filters['frequency'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Organic Waste</label>
                        <select name="organic_waste" class="form-control">
                            <option value="">All Collections</option>
                            <option value="yes" {{ isset($filters['organic_waste']) && $filters['organic_waste'] == 'yes' ? 'selected' : '' }}>Includes Organic</option>
                            <option value="no" {{ isset($filters['organic_waste']) && $filters['organic_waste'] == 'no' ? 'selected' : '' }}>Regular Only</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                    </div>

                    <div class="filter-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.schedules') }}" class="btn-reset">
                        <i class="bi bi-x-circle me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Schedules Table -->
        @if($schedules->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date & Time</th>
                            <th>Contractor</th>
                            <th>Client</th>
                            <th>Location</th>
                            <th>Service Type</th>
                            <th>Price</th>
                            <th>Billing Rate</th>
                            <th>Adjusted</th>
                            <th>Frequency</th>
                            <th>Organic</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td><strong>#{{ $schedule->id }}</strong></td>
                                <td>
                                    <div><strong>{{ $schedule->scheduled_date ? $schedule->scheduled_date->format('M d, Y') : ($schedule->pickup_date ? $schedule->pickup_date->format('M d, Y') : 'N/A') }}</strong></div>
                                    <div style="font-size: 0.85rem; color: #666;">
                                        {{ $schedule->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('h:i A') : 'No time set' }}
                                    </div>
                                </td>
                                <td>{{ $schedule->contractor->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->client->name ?? 'N/A' }}</td>
                                <td>
                                    <div>{{ $schedule->pickup_address ?? $schedule->city }}</div>
                                    <div style="font-size: 0.85rem; color: #666;">{{ $schedule->city }}, {{ $schedule->state }}</div>
                                </td>
                                <td>{{ $schedule->service_type ?? 'Standard' }}</td>
                                <td>
                                    @if($schedule->displayed_price !== null)
                                        <strong>TZS {{ number_format($schedule->displayed_price, 2) }}</strong>
                                    @else
                                        <span style="font-size: 0.85rem; color: #999;">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem;">{{ $schedule->billingRateLabel ?? '-' }}</div>
                                    @if($schedule->billing_rate_change_reason)
                                        <div style="font-size: 0.75rem; color: #666;">{{ \Illuminate\Support\Str::limit($schedule->billing_rate_change_reason, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->has_billing_adjustment)
                                        <span class="organic-badge">Yes</span>
                                    @else
                                        <span style="font-size: 0.85rem; color: #999;">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->includes_organic_waste)
                                        <span class="organic-badge">
                                            <i class="bi bi-recycle"></i>Yes
                                        </span>
                                    @else
                                        <span style="font-size: 0.85rem; color: #999;">No</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $schedule->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="action-btn" title="Edit Schedule">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem;">
                {{ $schedules->appends($filters)->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-calendar3"></i>
                <h3>No Schedules Found</h3>
                <p>No schedules match your current filters. Try adjusting the filters above.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
