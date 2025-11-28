<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Rates Management - AFIA ORBIT Admin</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .btn-primary {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary-teal);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: #044a4a;
            color: white;
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
        
        .price-display {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-teal);
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
            <div>
                <h1 class="page-title">Billing Rates Management</h1>
                <p class="page-description">Manage collection fees by category and location</p>
            </div>
            <div>
                <a href="{{ route('admin.billing.rates.create') }}" class="btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add New Rate
                </a>
            </div>
        </div>

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
                <div class="stat-label">Total Rates</div>
                <div class="stat-value">{{ $totalRates }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Active Rates</div>
                <div class="stat-value">{{ $activeRates }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Residential Rates</div>
                <div class="stat-value">{{ $residentialRates }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Commercial Rates</div>
                <div class="stat-value">{{ $commercialRates }}</div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search by category, location, or frequency..." 
                   onkeyup="filterTable()">
        </div>

        <!-- Billing Rates Table -->
        @if($rates->count() > 0)
            <div class="table-container">
                <table id="ratesTable">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Frequency</th>
                            <th>Collection Fee</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rates as $rate)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $rate->category }}">
                                        {{ ucfirst($rate->category) }}
                                    </span>
                                </td>
                                <td><strong>{{ $rate->location }}</strong></td>
                                <td>{{ $rate->frequency ? ucfirst(str_replace('-', ' ', $rate->frequency)) : 'Any' }}</td>
                                <td>
                                    <span class="price-display">TZS {{ number_format($rate->collection_fee, 2) }}</span>
                                </td>
                                <td>{{ $rate->description ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $rate->is_active ? 'active' : 'inactive' }}">
                                        {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.billing.rates.edit', $rate) }}" class="action-btn" title="Edit Rate">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.billing.rates.delete', $rate) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this billing rate?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn" style="background: #ef4444;" title="Delete Rate">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-currency-dollar"></i>
                <h3>No Billing Rates Found</h3>
                <p>Click "Add New Rate" to create your first billing rate.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('ratesTable');
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
