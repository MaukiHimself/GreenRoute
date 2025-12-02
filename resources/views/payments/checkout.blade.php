<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #055c5c;
            --secondary-color: #640404;
            --white-color: #ffffff;
            --light-bg: #f8f9fa;
        }
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 40px;
        }
        .payment-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        @media(min-width: 768px) {
            .payment-container {
                flex-direction: row;
                min-height: 500px;
            }
        }
        .invoice-summary {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .payment-form-section {
            flex: 1.5;
            padding: 2rem;
        }
        .provider-option {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            margin-bottom: 10px;
        }
        .provider-option:hover {
            border-color: var(--primary-color);
            background: #f9f9f9;
        }
        .provider-option.selected {
            border-color: var(--primary-color);
            background: rgba(5, 92, 92, 0.05);
            font-weight: bold;
            color: var(--primary-color);
        }
        .btn-pay {
            background: var(--primary-color);
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 20px;
        }
        .btn-pay:hover {
            background: #044a4a;
        }
        .loader {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="payment-container">
        <!-- Left Side: Invoice Summary -->
        <div class="invoice-summary">
            <h4 class="mb-4"><i class="bi bi-receipt me-2"></i>Payment Details</h4>
            
            <div class="mb-3">
                <small class="text-white-50">INVOICE NUMBER</small>
                <div class="fs-5 fw-bold">{{ $invoice->invoice_number }}</div>
            </div>

            <div class="mb-3">
                <small class="text-white-50">SERVICE</small>
                <div class="fs-5">{{ ucfirst($invoice->service_type) }}</div>
            </div>
            
            <div class="mb-3">
                <small class="text-white-50">DUE DATE</small>
                <div class="fs-5">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.2);">

            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="fs-5">Total Amount</span>
                <span class="fs-3 fw-bold">TZS {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            
            <a href="{{ route('client.invoices') }}" class="text-white-50 mt-auto text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Cancel & Return
            </a>
        </div>

        <!-- Right Side: Payment Form -->
        <div class="payment-form-section">
            <h3 class="mb-4 text-dark">Select Payment Method</h3>

            @if($invoice->status === 'paid')
                <div class="alert alert-success text-center py-4">
                    <i class="bi bi-check-circle-fill fs-1 d-block mb-2"></i>
                    <h4>This invoice is already paid!</h4>
                    <p>Paid on {{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : '' }}</p>
                    <a href="{{ route('client.invoices') }}" class="btn btn-outline-success">Return to Invoices</a>
                </div>
            @else
                <ul class="nav nav-tabs mb-4" id="paymentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mobile-tab" data-bs-toggle="tab" data-bs-target="#mobile" type="button" role="tab">Mobile Money</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">Bank Card</button>
                    </li>
                </ul>

                <div class="tab-content" id="paymentTabsContent">
                    <!-- Mobile Money -->
                    <div class="tab-pane fade show active" id="mobile" role="tabpanel">
                        <form id="mobilePaymentForm">
                            @csrf
                            <label class="form-label fw-bold">Select Network Provider</label>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="provider-option" onclick="selectProvider('Airtel')">
                                        Airtel Money
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="provider-option" onclick="selectProvider('Tigo')">
                                        Tigo Pesa
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="provider-option" onclick="selectProvider('Halopesa')">
                                        Halo Pesa
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="provider-option" onclick="selectProvider('Azampesa')">
                                        AzamPesa
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="provider" id="selectedProvider">

                            <div class="mb-3">
                                <label for="phone_number" class="form-label fw-bold">Phone Number</label>
                                <input type="text" class="form-control p-3" id="phone_number" name="phone_number" placeholder="e.g. 0712345678" required>
                                <div class="form-text">Format: 07... or 06... (System automatically formats to 255)</div>
                            </div>

                            <button type="submit" class="btn-pay" id="payBtn">
                                Pay TZS {{ number_format($invoice->total_amount, 2) }}
                            </button>
                        </form>
                    </div>

                    <!-- Bank Card -->
                    <div class="tab-pane fade" id="bank" role="tabpanel">
                        <div class="text-center py-4">
                            <i class="bi bi-bank fs-1 text-primary mb-3"></i>
                            <h5 class="mb-3">Pay with Local Bank Card</h5>
                            <p class="text-muted mb-4">You will be redirected to AzamPay's secure gateway to complete your payment using Visa, Mastercard, or UnionPay.</p>
                            
                            <form id="bankPaymentForm">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                    Proceed to Secure Checkout <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="loader" id="loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Processing payment request...<br>Please do not close this page.</p>
                </div>

                <div id="responseMessage" class="mt-3"></div>
            @endif
        </div>
    </div>
    
    <div class="text-center mt-4 text-muted small">
        <i class="bi bi-shield-lock-fill me-1"></i> Secured by AzamPay
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectProvider(provider) {
        document.getElementById('selectedProvider').value = provider;
        document.querySelectorAll('.provider-option').forEach(el => el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    }

    document.getElementById('mobilePaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const provider = document.getElementById('selectedProvider').value;
        if (!provider) {
            alert('Please select a network provider');
            return;
        }

        const btn = document.getElementById('payBtn');
        const loader = document.getElementById('loader');
        const msgDiv = document.getElementById('responseMessage');

        btn.style.display = 'none';
        loader.style.display = 'block';
        msgDiv.innerHTML = '';

        const formData = new FormData(this);

        fetch("{{ route('client.payments.mobile', $invoice->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';
            if (data.success) {
                msgDiv.innerHTML = `<div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>${data.message}
                    <br><strong>Transaction Ref: ${data.reference}</strong>
                </div>`;
            } else {
                btn.style.display = 'block';
                msgDiv.innerHTML = `<div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>${data.message}
                </div>`;
            }
        })
        .catch(error => {
            loader.style.display = 'none';
            btn.style.display = 'block';
            msgDiv.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
            console.error(error);
        });
    });

    // Bank Payment Handler
    if(document.getElementById('bankPaymentForm')) {
        document.getElementById('bankPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const loader = document.getElementById('loader');
            const msgDiv = document.getElementById('responseMessage');
            
            this.style.display = 'none';
            loader.style.display = 'block';
            msgDiv.innerHTML = '';

            fetch("{{ route('client.payments.bank', $invoice->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    loader.style.display = 'none';
                    this.style.display = 'block';
                    msgDiv.innerHTML = `<div class="alert alert-danger"><strong>Payment Failed:</strong><br>${data.message || 'Unable to process bank payment'}</div>`;
                }
            })
            .catch(error => {
                loader.style.display = 'none';
                this.style.display = 'block';
                const errorMsg = error.message || 'Network error. Please check your connection and try again.';
                msgDiv.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong><br>${errorMsg}</div>`;
                console.error('Bank payment error:', error);
            });
        });
    }
</script>

</body>
</html>
