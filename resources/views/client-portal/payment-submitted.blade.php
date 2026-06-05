<x-dashboard-layout title="Payment Submitted">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.request.service') }}"><i class="bi bi-plus-circle me-2"></i>Request Service</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.equipment') }}"><i class="bi bi-tools me-2"></i>Equipment</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.contractor.info') }}"><i class="bi bi-building me-2"></i>Contractor Info</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.payments') }}"><i class="bi bi-credit-card me-2"></i>Payments</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.feedback') }}"><i class="bi bi-chat-dots me-2"></i>Feedback</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Payment Submitted</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-success mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success rounded-circle p-2 text-white">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Malipo Yako Yanahakikiwa!</h5>
                        <p class="mb-0">Tafadhali subiri kwa dakika chache wakati Mkandarasi anakagua muamala wako ili kukutumia risiti.</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Submission Summary</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Invoice Number</p>
                            <p class="fw-semibold mb-0">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Payment Method</p>
                            <p class="fw-semibold mb-0">{{ $paymentMethodName ?? ucfirst(str_replace('_', ' ', $submission->payment_method)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Payer Name</p>
                            <p class="fw-semibold mb-0">{{ $submission->payer_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Submission Time</p>
                            <p class="fw-semibold mb-0">{{ $submission->submitted_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <p class="text-muted small mb-1">Amount Submitted</p>
                                <p class="h4 fw-bold mb-0">TZS {{ number_format($submission->amount_submitted, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Invoice Status After This Payment</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Original Amount</p>
                            <p class="fw-semibold mb-0">TZS {{ number_format($invoice->total_amount, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Previously Paid</p>
                            <p class="fw-semibold text-success mb-0">TZS {{ number_format($invoice->amount_paid, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Expected Remaining Balance</p>
                            <p class="fw-semibold text-danger mb-0">TZS {{ number_format(max(0, $invoice->total_amount - $invoice->amount_paid - $submission->amount_submitted), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">What Happens Next</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>1.</strong> Contractor reviews the payment details.</li>
                        <li class="mb-2"><strong>2.</strong> The payment is approved or the contractor asks for correction.</li>
                        <li class="mb-2"><strong>3.</strong> A receipt is issued and your invoice status is updated.</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('client.invoices') }}" class="btn btn-secondary flex-fill">Back to Invoices</a>
                <a href="{{ route('client.dashboard') }}" class="btn btn-primary flex-fill">Go to Dashboard</a>
            </div>

            <div class="card mt-4">
                <div class="card-body text-center small text-muted">
                    <p class="mb-1">Submission Reference: <strong class="fw-bold">{{ $submission->id }}</strong></p>
                    <p class="mb-0">Submitted at: <strong>{{ $submission->submitted_at->format('Y-m-d H:i:s') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
