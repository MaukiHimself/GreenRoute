<x-dashboard-layout title="Pay Invoice">
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
        <li class="breadcrumb-item active">Pay Invoice</li>
    </x-slot>

    @php
        $methodsJson = json_encode($paymentMethods);
        $oldMethod = old('payment_method');
    @endphp

    <style>
        .payment-card {
            min-height: 156px;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .payment-card:hover,
        .payment-card.active {
            border-color: #047857;
            box-shadow: 0 10px 24px rgba(5, 92, 92, .12);
            transform: translateY(-2px);
        }

        .payment-logo {
            width: 100%;
            height: 58px;
            object-fit: contain;
        }
    </style>

    <div class="row g-4">
        <div class="col-12">
            @if(session('error') || !empty($error))
                <div class="alert alert-warning">{{ session('error') ?? $error }}</div>
            @endif

            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div>
                            <p class="text-uppercase text-success fw-semibold small mb-2">Invoice checkout</p>
                            <h1 class="h3 mb-2">Chagua Njia ya Malipo</h1>
                            <p class="text-muted mb-0">
                                Tafadhali chagua mtandao au benki yako hapo chini ili kuona Lipa Namba ya Mkandarasi na kamilisha malipo yako salama.
                            </p>
                        </div>
                        <a href="{{ route('client.invoices') }}" class="btn btn-outline-secondary align-self-lg-start">
                            <i class="bi bi-arrow-left me-1"></i> Back to Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-uppercase text-muted small mb-1">Total Amount</p>
                            <p class="h4 fw-bold mb-0">TZS {{ number_format($invoice->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-uppercase text-muted small mb-1">Already Paid</p>
                            <p class="h4 fw-bold text-success mb-0">TZS {{ number_format($invoice->amount_paid, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-uppercase text-muted small mb-1">Balance Due</p>
                            <p class="h4 fw-bold text-danger mb-0">TZS {{ number_format($balanceDue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-4">
                        <div>
                            <h2 class="h5 mb-1">Payment methods</h2>
                            <p class="text-muted mb-0">Invoice <strong>{{ $invoice->invoice_number }}</strong> · Contractor: <strong>{{ $contractor->name ?? $contractor->company_name ?? 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <div class="row g-3">
                        @forelse($paymentMethods as $method => $details)
                            <div class="col-sm-6 col-lg-4">
                                <button
                                    type="button"
                                    class="payment-card w-100 p-3 text-start"
                                    data-payment-card="{{ $method }}"
                                    onclick="selectPaymentMethod('{{ $method }}')">
                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                        <img src="{{ asset('assets/images/payments/' . $details['logo']) }}" alt="{{ $details['name'] }} logo" class="payment-logo">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div>
                                            <h3 class="h6 mb-1">{{ $details['name'] }}</h3>
                                            <p class="text-muted small mb-0">
                                                {{ $details['configured'] ? 'Click to view Lipa No' : 'Waiting for contractor setup' }}
                                            </p>
                                        </div>
                                        @if($details['configured'])
                                            <i class="bi bi-chevron-down text-success"></i>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Not set</span>
                                        @endif
                                    </div>
                                </button>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">
                                    No payment methods are currently configured for this contractor. Please contact them for payment instructions.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div id="paymentDetails" class="card d-none">
                    <div class="card-body p-4">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-5">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <img id="selectedLogo" src="" alt="" style="width: 86px; height: 56px; object-fit: contain;">
                                        <div>
                                            <p class="text-uppercase text-muted small mb-1">Selected method</p>
                                            <h2 id="selectedName" class="h5 mb-0"></h2>
                                        </div>
                                    </div>

                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <p class="text-uppercase text-muted small mb-1">Lipa No ya Mkandarasi</p>
                                        <p id="selectedLipaNo" class="h3 fw-bold mb-0"></p>
                                    </div>

                                    <p class="mb-1 text-muted small">Registered Contractor Account Name</p>
                                    <p id="selectedAccountName" class="fw-semibold mb-3"></p>

                                    <div id="paymentInstruction" class="alert alert-info mb-0">
                                        Copy the Lipa No, open your phone USSD menu or mobile banking app, and transfer the funds independently outside the system. Then confirm the payment details here.
                                    </div>
                                </div>
                            </div>

                            <div id="submissionPanel" class="col-lg-7">
                                <form action="{{ route('client.payment-submission.store', $invoice) }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="paymentMethodInput" name="payment_method" value="{{ old('payment_method') }}">

                                    <div class="mb-3">
                                        <label for="payer_name" class="form-label">Jina la Aliyetuma Pesa</label>
                                        <input type="text" id="payer_name" name="payer_name" value="{{ old('payer_name', Auth::user()->client?->name) }}" class="form-control @error('payer_name') is-invalid @enderror" placeholder="Jina la Aliyetuma Pesa" required>
                                        @error('payer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="amount_submitted" class="form-label">Kiasi Kilichotumwa</label>
                                        <div class="input-group">
                                            <span class="input-group-text">TZS</span>
                                            <input type="number" id="amount_submitted" name="amount_submitted" value="{{ old('amount_submitted') }}" step="0.01" min="0.01" max="{{ $balanceDue }}" class="form-control @error('amount_submitted') is-invalid @enderror" placeholder="0.00" required>
                                            @error('amount_submitted')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Maximum amount: TZS {{ number_format($balanceDue, 2) }}. Partial payments are accepted.</div>
                                    </div>

                                    <div class="bg-light rounded-3 p-3 mb-4">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div>
                                                <p class="text-muted small mb-1">Amount Submitted</p>
                                                <p id="preview_amount" class="h5 fw-bold mb-0">TZS 0.00</p>
                                            </div>
                                            <div class="text-md-end">
                                                <p class="text-muted small mb-1">Remaining After Payment</p>
                                                <p id="preview_remaining" class="h5 fw-bold mb-0">TZS {{ number_format($balanceDue, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-check2-circle me-1"></i> Confirm Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const paymentMethods = {!! $methodsJson ?: '{}' !!};
        const oldMethod = @json($oldMethod);
        const balanceDue = Number({{ $balanceDue }});
        const paymentDetails = document.getElementById('paymentDetails');
        const amountInput = document.getElementById('amount_submitted');
        const previewAmount = document.getElementById('preview_amount');
        const previewRemaining = document.getElementById('preview_remaining');

        function formatTzs(amount) {
            return 'TZS ' + Number(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function selectPaymentMethod(method) {
            const details = paymentMethods[method];
            if (!details) {
                return;
            }

            document.querySelectorAll('[data-payment-card]').forEach(card => {
                card.classList.toggle('active', card.dataset.paymentCard === method);
            });

            document.getElementById('selectedLogo').src = '{{ asset('assets/images/payments') }}/' + details.logo;
            document.getElementById('selectedLogo').alt = details.name + ' logo';
            document.getElementById('selectedName').textContent = details.name;
            document.getElementById('selectedLipaNo').textContent = details.lipa_no || 'Not configured yet';
            document.getElementById('selectedAccountName').textContent = details.account_name || 'Contractor account';
            document.getElementById('paymentMethodInput').value = method;
            document.getElementById('submissionPanel').classList.toggle('d-none', !details.configured);
            document.getElementById('paymentInstruction').className = details.configured ? 'alert alert-info mb-0' : 'alert alert-warning mb-0';
            document.getElementById('paymentInstruction').textContent = details.configured
                ? 'Copy the Lipa No, open your phone USSD menu or mobile banking app, and transfer the funds independently outside the system. Then confirm the payment details here.'
                : 'This payment method is visible, but the contractor has not added its Lipa No yet. Please choose a configured method or ask the contractor to update settings.';

            paymentDetails.classList.remove('d-none');
            paymentDetails.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function updatePreview() {
            if (!amountInput) {
                return;
            }

            const amount = Number(amountInput.value || 0);
            previewAmount.textContent = formatTzs(amount);
            previewRemaining.textContent = formatTzs(Math.max(0, balanceDue - amount));
        }

        if (amountInput) {
            amountInput.addEventListener('input', updatePreview);
            updatePreview();
        }

        if (oldMethod && paymentMethods[oldMethod]) {
            selectPaymentMethod(oldMethod);
        }
    </script>
</x-dashboard-layout>
