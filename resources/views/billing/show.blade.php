<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .primary-dark { color: #055c5c; }
        .primary-light { 
            background-color: rgba(5, 92, 92, 0.08);
            border-left: 4px solid #055c5c;
        }
        .accent-color { color: #640404; }
        .btn-primary-custom {
            background-color: #055c5c;
            border-color: #055c5c;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #044a4a;
            border-color: #044a4a;
        }
        .btn-accent {
            background-color: #640404;
            border-color: #640404;
            color: white;
        }
        .btn-accent:hover {
            background-color: #530303;
            border-color: #530303;
        }
        .btn-outline-custom {
            border-color: #055c5c;
            color: #055c5c;
        }
        .btn-outline-custom:hover {
            background-color: #055c5c;
            color: white;
        }
        .section-header {
            border-bottom: 2px solid #055c5c;
            padding-bottom: 8px;
            margin-bottom: 15px;
            color: #055c5c;
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid rgba(5, 92, 92, 0.1);
        }
        .badge-overdue { background-color: #640404; }
        .badge-pending { background-color: #055c5c; }
        .badge-paid { background-color: #28a745; }
    </style>
</head>
<body class="bg-light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 primary-light rounded">
            <h4 class="primary-dark mb-0">Invoice Details</h4>
            <div>
                @if($invoice->status !== 'paid')
                    <button class="btn btn-primary-custom btn-sm" onclick="markPaid({{ $invoice->id }})">Mark Paid</button>
                    <button class="btn btn-outline-custom btn-sm" onclick="sendInvoice({{ $invoice->id }})">Send Invoice</button>
                    @if($invoice->is_overdue)
                        <button class="btn btn-accent btn-sm" onclick="sendReminder({{ $invoice->id }})">Send Reminder</button>
                    @endif
                @endif
                <a href="{{ route('billing.index') }}" class="btn btn-outline-custom btn-sm">Back</a>
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="info-card">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="section-header">Invoice Information</h5>
                    <div class="mb-2">
                        <strong class="primary-dark">Invoice Number:</strong> 
                        <span class="ms-2">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="mb-2">
                        <strong class="primary-dark">Client:</strong> 
                        <span class="ms-2">{{ $invoice->client->name }}</span>
                    </div>
                    <div class="mb-2">
                        <strong class="primary-dark">Service Type:</strong> 
                        <span class="ms-2">{{ ucfirst(str_replace('_', ' ', $invoice->service_type)) }}</span>
                    </div>
                    <div class="mb-2">
                        <strong class="primary-dark">Status:</strong> 
                        <span class="ms-2">
                            @if($invoice->status === 'paid')
                                <span class="badge badge-paid">Paid</span>
                            @elseif($invoice->is_overdue)
                                <span class="badge badge-overdue">Overdue</span>
                            @else
                                <span class="badge badge-pending">{{ ucfirst($invoice->status) }}</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="section-header">Dates & Payment</h5>
                    <div class="mb-2">
                        <strong class="primary-dark">Invoice Date:</strong> 
                        <span class="ms-2">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-2">
                        <strong class="primary-dark">Due Date:</strong> 
                        <span class="ms-2">{{ $invoice->due_date->format('M d, Y') }}</span>
                    </div>
                    @if($invoice->paid_at)
                        <div class="mb-2">
                            <strong class="primary-dark">Paid Date:</strong> 
                            <span class="ms-2">{{ $invoice->paid_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <strong class="primary-dark">Payment Method:</strong> 
                            <span class="ms-2">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="section-header">Description</h5>
                    <p>{{ $invoice->description }}</p>
                    @if($invoice->notes)
                        <h5 class="section-header mt-4">Notes</h5>
                        <p>{{ $invoice->notes }}</p>
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong class="primary-dark">Subtotal:</strong></td>
                            <td class="text-end">${{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong class="primary-dark">Tax ({{ $invoice->tax_rate }}%):</strong></td>
                            <td class="text-end">${{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong class="primary-dark">Total Amount:</strong></td>
                            <td class="text-end"><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong class="primary-dark">Amount Paid:</strong></td>
                            <td class="text-end">${{ number_format($invoice->amount_paid, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong class="primary-dark">Balance Due:</strong></td>
                            <td class="text-end"><strong>${{ number_format($invoice->balance_due, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark Paid Modal -->
    <div class="modal fade" id="markPaidModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title primary-dark">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label primary-dark">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label primary-dark">Amount Paid</label>
                            <input type="number" name="amount_paid" class="form-control" step="0.01" value="{{ $invoice->balance_due }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function markPaid(invoiceId) {
            const form = document.getElementById('paymentForm');
            form.action = `/billing/${invoiceId}/mark-paid`;
            const modal = document.getElementById('markPaidModal');
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(modal).show();
            } else {
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        }

        function sendInvoice(invoiceId) {
            if (confirm('Send invoice to client?')) {
                fetch(`/billing/${invoiceId}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).then(() => location.reload());
            }
        }
    </script>
</body>
</html>