<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Information - AFIA ORBIT Admin</title>
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
        
        .stat-card.blue {
            border-left-color: #3b82f6;
        }
        
        .stat-card.orange {
            border-left-color: #f59e0b;
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
        
        .badge-residential {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-commercial {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
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
            <h1 class="page-title">Clients Information</h1>
            <p class="page-description">Manage all clients across the system</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Clients</div>
                <div class="stat-value">{{ $totalClients }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Residential</div>
                <div class="stat-value">{{ $residentialCount }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Commercial</div>
                <div class="stat-value">{{ $commercialCount }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Active</div>
                <div class="stat-value">{{ $activeCount }}</div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search by name, email, phone, or address..." 
                   onkeyup="filterTable()">
        </div>

        <!-- Clients Table -->
        @if($clients->count() > 0)
            <div class="table-container">
                <table id="clientsTable">
                    <thead>
                        <tr>
                            <th>Reg #</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Contractor</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td><strong>{{ $client->registration_number }}</strong></td>
                                <td>{{ $client->name }}</td>
                                <td>
                                    <div><i class="bi bi-telephone me-1"></i>{{ $client->phone }}</div>
                                    @if($client->email)
                                        <div class="small text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $client->address }}</div>
                                    <div class="small text-muted">
                                        {{ $client->city }}, {{ $client->state }} {{ $client->zip_code }}
                                    </div>
                                </td>
                                <td>{{ $client->contractor->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge badge-{{ $client->category }}">
                                        {{ ucfirst($client->category) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $client->status }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}" class="action-btn" title="Email">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                    @endif
                                    <a href="tel:{{ $client->phone }}" class="action-btn" title="Call">
                                        <i class="bi bi-telephone"></i>
                                    </a>
                                    @if($client->latitude && $client->longitude)
                                        <a href="https://www.google.com/maps?q={{ $client->latitude }},{{ $client->longitude }}" 
                                           target="_blank" class="action-btn" title="View on Map">
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
                {{ $clients->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h3>No Clients Found</h3>
                <p>There are no clients in the system yet.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('clientsTable');
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
    </script>
</body>
</html>
