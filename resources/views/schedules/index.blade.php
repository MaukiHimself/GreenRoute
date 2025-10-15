<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Collection Schedules</title>
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
        
        .container {
            max-width: 1400px;
            padding: 2rem;
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
        
        .btn-success {
            background: var(--primary-color) !important;
            border: none !important;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
            transition: all 0.3s ease;
            color: white !important;
        }
        
        .btn-success:hover {
            background: #044a4a !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.4);
        }
        
        /* Table Section - No Cards */
        .table-section {
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table thead th:first-child {
            border-radius: 12px 0 0 0;
        }
        
        .table thead th:last-child {
            border-radius: 0 12px 0 0;
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
        
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        .btn-group .btn {
            border-radius: 8px;
            margin: 0 2px;
            font-weight: 500;
        }
        
        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }
        
        .btn-outline-secondary {
            color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }
        
        .btn-outline-secondary:hover {
            background: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            color: white !important;
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color) !important;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .pagination .page-link:hover {
            background: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }
        
        /* Route Badge Styling */
        .badge.bg-primary {
            background: var(--primary-color) !important;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .table-section {
                padding: 1.5rem;
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn-group .btn {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Collection Schedules</h1>
            <a href="{{ route('schedules.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Schedule
            </a>
        </div>

        <!-- Schedules Table - No Cards -->
        <div class="table-section">
            <div class="section-header">
                <h2 class="section-title">Scheduled Collections</h2>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Route Name</th>
                            <th>Client</th>
                            <th>Pickup Location</th>
                            <th>Address</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $schedule->route ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $schedule->client->name }}</td>
                            <td>{{ $schedule->pickup_location }}</td>
                            <td>{{ $schedule->pickup_address }}</td>
                            <td>{{ $schedule->pickup_date->format('M d, Y') }}</td>
                            <td>
                                <select class="form-select form-select-sm" onchange="updateStatus({{ $schedule->id }}, this.value)">
                                    <option value="scheduled" {{ $schedule->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ $schedule->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-primary">View</a>
                                    <a href="{{ route('schedules.print', $schedule) }}" class="btn btn-outline-secondary" target="_blank">Print</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No schedules found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function updateStatus(scheduleId, status) {
            fetch(`/schedules/${scheduleId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show message if schedule was completed
                    if (data.redirectToDisposal) {
                        if (confirm(data.message + '\n\nGo to Disposal page now?')) {
                            window.location.href = '/disposal';
                        } else {
                            location.reload();
                        }
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        }
    </script>
</body>
</html>