<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Payments - AFIA ORBIT Admin</title>
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
        
        .stat-card.orange {
            border-left-color: #f59e0b;
        }
        
        .stat-card.red {
            border-left-color: #ef4444;
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
        
        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-overdue {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-cancelled {
            background: #f3f4f6;
            color: #4b5563;
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
            <h1 class="page-title">Billing & Payments</h1>
            <p class="page-description">System-wide billing and payment management</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Pending</div>
                <div class="stat-value">${{ number_format($pendingAmount, 2) }}</div>
            </div>
            <div class="stat-card red">
                <div class="stat-label">Overdue</div>
                <div class="stat-value">${{ number_format($overdueAmount, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Invoices</div>
                <div class="stat-value">{{ $totalInvoices }}</div>
            </div>
        </div>

        <!-- Search -->
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search by invoice number, client, contractor..." 
                   onkeyup="filterTable()">
        </div>

        <!-- Invoices Table -->
        @if($invoices->count() > 0)
            <div class="table-container">
                <table id="invoicesTable">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Contractor</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}</strong></td>
                                <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                <td>{{ $invoice->client->name ?? 'N/A' }}</td>
                                <td>{{ $invoice->contractor->name ?? 'N/A' }}</td>
                                <td><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                                <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $invoice->status }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn" onclick="viewInvoice({{ $invoice->id }})" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="action-btn" onclick="downloadInvoice({{ $invoice->id }})" title="Download">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-credit-card"></i>
                <h3>No Invoices Found</h3>
                <p>There are no invoices in the system yet.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('invoicesTable');
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
        
        function viewInvoice(id) {
            alert('Invoice details view coming soon. Invoice ID: ' + id);
        }
        
        function downloadInvoice(id) {
            alert('Invoice download coming soon. Invoice ID: ' + id);
        }
    </script>
</body>
</html>
