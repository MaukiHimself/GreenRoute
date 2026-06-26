<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #047857;
            --primary-red: #c0392b;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 1400px;
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
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        
        .stat-card.green {
            border-left-color: #10b981;
        }
        
        .stat-card.orange {
            border-left-color: #f59e0b;
        }
        
        .stat-card.blue {
            border-left-color: #22c55e;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e293b;
        }
        
        /* Search */
        .search-box {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .search-input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            max-width: 500px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-teal);
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
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-scheduled {
            background: #dcfce7;
            color: #15803d;
        }
        
        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-in_progress {
            background: #e0e7ff;
            color: #4338ca;
        }
        
        .action-btn {
            background: var(--primary-teal);
            color: white;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-right: 0.25rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .action-btn:hover {
            background: #065f46;
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
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('dashboard.admin') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Admin Dashboard
            </a>
        </div>
        
        <div class="page-header">
            <h1 class="page-title">Schedules Management</h1>
            <p class="page-description">System-wide schedule overview and management</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Schedules</div>
                <div class="stat-value">{{ $totalSchedules }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completedSchedules }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $pendingSchedules }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Today</div>
                <div class="stat-value">{{ $todaySchedules }}</div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search by client, contractor, or service..." 
                   onkeyup="filterTable()">
        </div>

        <!-- Schedules Table -->
        @if($schedules->count() > 0)
            <div class="table-container">
                <table id="schedulesTable">
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Contractor</th>
                            <th>Service Type</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td><strong>#{{ $schedule->id }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y') }}</td>
                                <td>{{ $schedule->client->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->contractor->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->service_type ?? 'Standard Service' }}</td>
                                <td>
                                    @if($schedule->start_time)
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                    @else
                                        Not set
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $schedule->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn" onclick="viewSchedule({{ $schedule->id }})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($schedule->client && $schedule->client->latitude && $schedule->client->longitude)
                                        <a href="https://www.openstreetmap.org/?mlat={{ $schedule->client->latitude }}&mlon={{ $schedule->client->longitude }}#map=16/{{ $schedule->client->latitude }}/{{ $schedule->client->longitude }}" 
                                           target="_blank" class="action-btn" title="View Location">
                                            <i class="bi bi-geo-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-calendar3"></i>
                <h3>No Schedules Found</h3>
                <p>There are no schedules in the system yet.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('schedulesTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
        
        function viewSchedule(id) {
            alert('Schedule details view coming soon. Schedule ID: ' + id);
        }
    </script>
</body>
</html>
