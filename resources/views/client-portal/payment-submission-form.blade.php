<x-dashboard-layout title="Submit Payment">
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
        <li class="breadcrumb-item"><a href="{{ route('client.invoices') }}">Invoices</a></li>
        <li class="breadcrumb-item active">Submit Payment</li>
    </x-slot>

    @php
        $logos = [
            'vodacom_mpesa' => 'mpesa.png',
            'airtel_money' => 'airtel_money.png',
            'halopesa' => 'halopesa.png',
            'mixx_by_yas' => 'mixx_by_yas.png',
            'crdb_bank' => 'crdb.png',
            'nmb_bank' => 'nmb.png',
            'nbc_bank' => 'nbc.png',
        ];
        $logo = $logos[$paymentMethod] ?? 'mpesa.png';
    @endphp

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <p class="text-uppercase text-success fw-semibold small mb-2">Manual payment confirmation</p>
                            <h1 class="h3 mb-2">{{ $paymentMethodName }}</h1>
                            <p class="text-muted mb-0">Copy the Lipa No, complete the transfer outside GreenRoute, then submit the confirmation details below.</p>
                        </div>
                        <a href="{{ route('client.payment-methods', $invoice) }}" class="btn btn-outline-secondary align-self-md-start">
                            <i class="bi bi-arrow-left me-1"></i> Back to Methods
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body p-4">
                    <img src="{{ asset('assets/images/payments/' . $logo) }}" alt="{{ $paymentMethodName }} logo" class="mb-4" style="width: 150px; height: 82px; object-fit: contain;">

                    <div class="bg-light rounded-3 p-3 mb-3">
                        <p class="text-uppercase text-muted small mb-1">Lipa No ya Mkandarasi</p>
                        <p class="h3 fw-bold mb-0">{{ $lipaNo }}</p>
                    </div>

                    <p class="mb-1 text-muted small">Registered Contractor Account Name</p>
                    <p class="fw-semibold mb-3">{{ $contractor->name ?? $contractor->company_name }}</p>

                    <div class="alert alert-info mb-0">
                        Open your phone USSD menu or mobile banking app, send the money to this Lipa No, then confirm the payer name and amount sent.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <form action="{{ route('client.payment-submission.store', $invoice) }}" method="POST" class="card">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">

                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Invoice Total</p>
                            <p class="fw-bold mb-0">TZS {{ number_format($invoice->total_amount, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Already Paid</p>
                            <p class="fw-bold text-success mb-0">TZS {{ number_format($invoice->amount_paid, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Balance Due</p>
                            <p class="fw-bold text-danger mb-0">TZS {{ number_format($balanceDue, 2) }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="payer_name" class="form-label">Jina la Aliyetuma Pesa</label>
                        <input type="text" id="payer_name" name="payer_name" value="{{ old('payer_name', $clientName) }}" class="form-control @error('payer_name') is-invalid @enderror" required>
                        @error('payer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount_submitted" class="form-label">Kiasi Kilichotumwa</label>
                        <div class="input-group">
                            <span class="input-group-text">TZS</span>
                            <input type="number" id="amount_submitted" name="amount_submitted" value="{{ old('amount_submitted') }}" step="0.01" min="0.01" max="{{ $balanceDue }}" class="form-control @error('amount_submitted') is-invalid @enderror" required>
                            @error('amount_submitted')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Maximum amount: TZS {{ number_format($balanceDue, 2) }}. Partial payments are accepted.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
