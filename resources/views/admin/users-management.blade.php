<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
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
        
        .stat-card.orange { border-left-color: #f59e0b; }
        .stat-card.blue { border-left-color: #3b82f6; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.green { border-left-color: #10b981; }
        .stat-card.yellow { border-left-color: #eab308; }
        
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        
        .badge-admin { background: #fef3c7; color: #92400e; }
        .badge-contractor { background: #dbeafe; color: #1e40af; }
        .badge-client { background: #e0e7ff; color: #4338ca; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #f3f4f6; color: #4b5563; }
        
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
            margin-right: 0.25rem;
        }
        
        .action-btn:hover {
            background: #044a4a;
            color: white;
        }
        
        .action-btn.delete {
            background: #ef4444;
        }
        
        .action-btn.delete:hover {
            background: #dc2626;
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
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
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
        
        <h1 class="page-title">Users Management</h1>
        <p class="page-description">Manage all system users - edit and delete user accounts</p>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Administrators</div>
                <div class="stat-value">{{ $adminCount }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Contractors</div>
                <div class="stat-value">{{ $contractorCount }}</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">Clients</div>
                <div class="stat-value">{{ $clientCount }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Approved</div>
                <div class="stat-value">{{ $approvedCount }}</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $pendingCount }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="{{ route('admin.users') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, email, or username" value="{{ $filters['search'] ?? '' }}">
                    </div>

                    <div class="filter-group">
                        <label>User Type</label>
                        <select name="user_type" class="form-control">
                            <option value="">All Types</option>
                            <option value="admin" {{ isset($filters['user_type']) && $filters['user_type'] == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="contractor" {{ isset($filters['user_type']) && $filters['user_type'] == 'contractor' ? 'selected' : '' }}>Contractor</option>
                            <option value="client" {{ isset($filters['user_type']) && $filters['user_type'] == 'client' ? 'selected' : '' }}>Client</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="approved" {{ isset($filters['status']) && $filters['status'] == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ isset($filters['status']) && $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ isset($filters['status']) && $filters['status'] == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn-reset">
                        <i class="bi bi-x-circle me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        @if($users->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><strong>#{{ $user->id }}</strong></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->user_type }}">
                                        {{ ucfirst($user->user_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->status)
                                        <span class="badge badge-{{ $user->status }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->subscription_status)
                                        <span class="badge badge-{{ $user->subscription_status }}">
                                            {{ ucfirst($user->subscription_status) }}
                                        </span>
                                    @else
                                        <span style="font-size: 0.85rem; color: #999;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 0.85rem; color: #666;">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-btn" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete User">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem;">
                {{ $users->appends($filters)->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h3>No Users Found</h3>
                <p>No users match your current filters. Try adjusting the filters above.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
