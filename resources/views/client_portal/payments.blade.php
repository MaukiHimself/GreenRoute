<x-dashboard-layout title="Payment History">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
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
                <a class="nav-link" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.equipment') }}">
                    <i class="bi bi-tools me-2"></i>Equipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.contractor.info') }}">
                    <i class="bi bi-building me-2"></i>Contractor Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.payments') }}">
                    <i class="bi bi-credit-card me-2"></i>Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.feedback') }}">
                    <i class="bi bi-chat-dots me-2"></i>Feedback
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Payments</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment History & Receipts</h5>
                    <div>
                        <span class="badge bg-success">{{ $payments->total() }} Total Payments</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Service Period</th>
                                        <th>Payment Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <strong>{{ $payment->invoice_number }}</strong>
                                            </td>
                                            <td>
                                                {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">
                                                    TZS {{ number_format($payment->total_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $payment->service_period ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $payment->payment_method ?? 'Not specified' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('invoices.pdf', $payment) }}" class="btn btn-outline-primary" target="_blank">
                                                        <i class="bi bi-download"></i> Receipt
                                                    </a>
                                                    <button class="btn btn-outline-secondary" onclick="viewPaymentDetails({{ $payment->id }})">
                                                        <i class="bi bi-eye"></i> Details
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-credit-card display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Payment History</h5>
                            <p class="text-muted">You haven't made any payments yet.</p>
                            <a href="{{ route('client.invoices') }}" class="btn btn-primary">
                                <i class="bi bi-receipt me-2"></i>View Invoices
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($payments->count() > 0)
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Paid</h6>
                                <h4 class="mb-0 text-success">TZS {{ number_format($payments->sum('total_amount'), 2) }}</h4>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-cash-stack fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Last Payment</h6>
                                <h4 class="mb-0">
                                    @if($payments->first() && $payments->first()->paid_at)
                                        {{ $payments->first()->paid_at->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </h4>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-calendar-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Payment Count</h6>
                                <h4 class="mb-0">{{ $payments->total() }}</h4>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-receipt fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="paymentDetailsContent">
                    <!-- Payment details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewPaymentDetails(paymentId) {
            // In a real application, you would fetch payment details via AJAX
            const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading payment details...</p>
                </div>
            `;
            modal.show();
            
            // Simulate loading
            setTimeout(() => {
                document.getElementById('paymentDetailsContent').innerHTML = `
                    <div class="row">
                        <div class="col-6"><strong>Payment ID:</strong></div>
                        <div class="col-6">${paymentId}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>Transaction ID:</strong></div>
                        <div class="col-6">TXN-${Math.random().toString(36).substr(2, 9).toUpperCase()}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>Status:</strong></div>
                        <div class="col-6"><span class="badge bg-success">Completed</span></div>
                    </div>
                `;
            }, 1000);
        }
    </script>
</x-dashboard-layout>