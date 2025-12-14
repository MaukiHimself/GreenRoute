<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Payments</title>
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
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.4);
        }
        
        /* Stats Section - No Cards */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-item {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.8);
            text-align: center;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1;
        }
        
        .stat-value.total { color: var(--primary-color); }
        .stat-value.paid { color: var(--primary-color); }
        .stat-value.overdue { color: var(--secondary-color); }
        .stat-value.revenue { color: var(--primary-color); }
        
        .stat-label {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
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
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge.bg-success { background: var(--primary-color) !important; }
        .badge.bg-danger { background: var(--secondary-color) !important; }
        .badge.bg-warning { background: #ffc107 !important; color: #000 !important; }
        
        .btn-group .btn {
            border-radius: 8px;
            margin: 0 2px;
            font-weight: 500;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-info {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-info:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-danger {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-danger:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal-content {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: modalAppear 0.3s ease;
        }
        
        @keyframes modalAppear {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close-btn:hover {
            color: var(--secondary-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-secondary {
            background: var(--text-muted);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
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
            
            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
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
        <!-- Header Section -->
        <div class="page-header">
            <h1 class="page-title">Billing & Payments</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-dark d-flex align-items-center gap-2" style="border-color: #cbd5e1;" target="_parent">
                    <i class="bi bi-house-door-fill" style="color: var(--primary-color);"></i> Home
                </a>
                <button onclick="window.history.back()" class="btn btn-outline-dark d-flex align-items-center gap-2" style="border-color: #cbd5e1;">
                    Back
                </button>
                <a href="{{ route('billing.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Create Invoice
                </a>
            </div>
        </div>

        <!-- Stats Section - No Cards -->
        <div class="stats-section">
            <div class="stat-item">
                <div class="stat-value total">{{ $stats['total_invoices'] }}</div>
                <div class="stat-label">Total Invoices</div>
            </div>
            <div class="stat-item">
                <div class="stat-value paid">{{ $stats['paid_invoices'] }}</div>
                <div class="stat-label">Paid Invoices</div>
            </div>
            <div class="stat-item">
                <div class="stat-value overdue">{{ $stats['overdue_invoices'] }}</div>
                <div class="stat-label">Overdue</div>
            </div>
            <div class="stat-item">
                <div class="stat-value revenue">TZS {{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>

        <!-- Invoices Table - No Cards -->
        <div class="table-section">
            <div class="section-header">
                <h2 class="section-title">Invoices</h2>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->client ? $invoice->client->name : 'Unknown Client' }}</td>
                            <td>{{ $invoice->service_type }}</td>
                            <td>TZS {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->is_overdue)
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('billing.show', $invoice) }}" class="btn btn-outline-primary">View</a>
                                    @if($invoice->status !== 'paid')
                                        <button class="btn btn-outline-success" onclick="markPaid({{ $invoice->id }}, {{ $invoice->balance_due }})">Mark Paid</button>
                                        @if($invoice->client)
                                            <button class="btn btn-outline-info" onclick="sendInvoice({{ $invoice->id }})">Send</button>
                                            @if($invoice->is_overdue)
                                                <button class="btn btn-outline-danger" onclick="sendReminder({{ $invoice->id }})">Remind</button>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mark Paid Modal -->
    <div class="modal-overlay" id="markPaidModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Record Payment</h3>
                <button type="button" class="close-btn" onclick="closeModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="check">Check</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount Paid</label>
                    <input type="number" id="paymentAmountInput" name="amount_paid" class="form-control" step="0.01" required oninput="calculateBalance()">
                </div>
                
                <!-- Dynamic Calculation Display -->
                <div class="alert alert-light border d-flex justify-content-between align-items-center p-3 mb-3 rounded" id="calculationResult" style="background-color: #f8f9fa;">
                    <span>Remaining Balance:</span>
                    <strong id="balanceDisplay">TZS 0.00</strong>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-success">Record Payment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let currentBalanceDue = 0;

        function calculateBalance() {
            const inputAmount = parseFloat(document.getElementById('paymentAmountInput').value) || 0;
            const difference = currentBalanceDue - inputAmount;
            
            const displayElement = document.getElementById('balanceDisplay');
            const resultContainer = document.getElementById('calculationResult');
            const labelElement = resultContainer.querySelector('span');

            if (difference < 0) {
                // Surplus
                labelElement.textContent = "Credit Surplus:";
                displayElement.textContent = "TZS " + Math.abs(difference).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                displayElement.className = "text-success fw-bold";
                displayElement.style.color = "#198754"; // Bootstrap success color
            } else {
                // Remaining Balance
                labelElement.textContent = "Remaining Balance:";
                displayElement.textContent = "TZS " + difference.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                displayElement.className = "text-danger fw-bold";
                displayElement.style.color = "#dc3545"; // Bootstrap danger color
            }
        }

        function markPaid(invoiceId, balanceDue) {
            currentBalanceDue = parseFloat(balanceDue);
            const form = document.getElementById('paymentForm');
            form.action = `/billing/${invoiceId}/mark-paid`;
            
            // Set initial value and calculate
            document.getElementById('paymentAmountInput').value = currentBalanceDue;
            calculateBalance();

            const modal = document.getElementById('markPaidModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            const modal = document.getElementById('markPaidModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking backdrop
        document.getElementById('markPaidModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function sendInvoice(invoiceId) {
            if (confirm('Send invoice to client?')) {
                fetch(`/billing/${invoiceId}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }

        function sendReminder(invoiceId) {
            if (confirm('Send payment reminder to client?')) {
                fetch(`/billing/${invoiceId}/remind`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }
    </script>
</body>
</html>