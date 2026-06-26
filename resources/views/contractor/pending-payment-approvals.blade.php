@extends('layouts.app')

@section('title', 'Pending Payment Approvals')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 rounded-2xl shadow-lg overflow-hidden"
             style="background: linear-gradient(135deg, #047857 0%, #059669 100%);">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-6 sm:p-8">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Pending Payment Approvals</h1>
                    </div>
                    <p class="text-white/80 mt-2">Review and verify client payment submissions</p>
                </div>
                <div class="text-center bg-white/15 rounded-xl px-6 py-3 backdrop-blur">
                    <div class="text-4xl font-bold text-white leading-none">{{ $pendingCount }}</div>
                    <p class="text-white/80 text-xs mt-1 uppercase tracking-wide">Pending</p>
                </div>
            </div>
        </div>

        @if ($submissions->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-md p-16 text-center border border-gray-100">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
                 style="background:#d1fae5;">
                <svg class="w-10 h-10" style="color:#047857" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">All Caught Up!</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no pending payment submissions to review right now. New submissions from clients will appear here.</p>
            <a href="{{ route('dashboard.contractor') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-white font-semibold shadow-sm transition"
               style="background:#047857;"
               onmouseover="this.style.background='#064e3b'" onmouseout="this.style.background='#047857'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
        @else

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4" style="border-left:4px solid #047857">
                <p class="text-sm text-gray-600">Total Pending</p>
                <p class="text-3xl font-bold mt-1" style="color:#047857">{{ $pendingCount }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4" style="border-left:4px solid #d97706">
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">
                    TZS {{ number_format($submissions->sum('amount_submitted'), 2) }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4" style="border-left:4px solid #2e7d32">
                <p class="text-sm text-gray-600">Avg. Submission</p>
                <p class="text-3xl font-bold mt-1" style="color:#2e7d32">
                    TZS {{ number_format($submissions->avg('amount_submitted'), 2) }}
                </p>
            </div>
        </div>

        <!-- Filter/Search Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
                <input type="text"
                       placeholder="Search by client name, payer name..."
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                       style="--tw-ring-color:#047857"
                       id="searchInput">
                <button type="button"
                        class="px-6 py-2 text-white rounded-lg transition"
                        style="background-color:#047857"
                        onmouseover="this.style.backgroundColor='#064e3b'" onmouseout="this.style.backgroundColor='#047857'"
                        onclick="filterSubmissions()">
                    Search
                </button>
            </div>
        </div>

        <!-- Submissions List -->
        <div class="space-y-4">
            @foreach ($submissions as $submission)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden submission-card"
                 data-client="{{ $submission->client->name ?? '' }}"
                 data-payer="{{ $submission->payer_name }}">

                <div class="p-6">
                    <!-- Header Row -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $submission->client->name ?? 'Unknown Client' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Invoice: <strong>{{ $submission->invoice->invoice_number }}</strong>
                            </p>
                        </div>
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-semibold text-sm">
                            Pending Approval
                        </span>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-xs text-gray-600 uppercase">Payer Name</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $submission->payer_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase">Payment Method</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">
                                @switch($submission->payment_method)
                                    @case('vodacom_mpesa')
                                        Vodacom M-Pesa
                                    @break
                                    @case('airtel_money')
                                        Airtel Money
                                    @break
                                    @case('halopesa')
                                        Halopesa
                                    @break
                                    @case('mixx_by_yas')
                                        Mixx by Yas
                                    @break
                                    @case('crdb_bank')
                                        CRDB Bank
                                    @break
                                    @case('nmb_bank')
                                        NMB Bank
                                    @break
                                    @case('nbc_bank')
                                        NBC Bank
                                    @break
                                    @default
                                        {{ ucfirst(str_replace('_', ' ', $submission->payment_method)) }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase">Amount Submitted</p>
                            <p class="text-xl font-bold mt-1" style="color:#047857">
                                TZS {{ number_format($submission->amount_submitted, 2) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase">Submitted</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">
                                {{ $submission->submitted_at->format('M d, H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Invoice Balance Info -->
                    <div class="mb-6 p-4 rounded-lg" style="background-color:#e6f2f2; border:1px solid #b3d4d4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs" style="color:#047857">Invoice Total</p>
                                <p class="text-lg font-bold" style="color:#064e3b">
                                    TZS {{ number_format($submission->invoice->total_amount, 2) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs" style="color:#047857">Balance Due</p>
                                <p class="text-lg font-bold" style="color:#064e3b">
                                    TZS {{ number_format($submission->invoice->total_amount - $submission->invoice->amount_paid, 2) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs" style="color:#047857">Status After Approval</p>
                                <p class="text-lg font-bold" style="color:#064e3b">
                                    @if ($submission->invoice->amount_paid + $submission->amount_submitted >= $submission->invoice->total_amount)
                                        Fully Paid
                                    @else
                                        Partially Paid
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button"
                                onclick="approvePayment({{ $submission->id }}, event)"
                                class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Approve & Issue Receipt
                        </button>
                        <button type="button"
                                onclick="showRejectForm({{ $submission->id }})"
                                class="px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject
                        </button>
                    </div>

                    <!-- Client Details (collapsible) -->
                    <details class="mt-4 pt-4 border-t">
                        <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-gray-900">
                            View Client Details
                        </summary>
                        <div class="mt-3 p-3 bg-gray-50 rounded text-sm space-y-1">
                            <p><strong>Client Registration:</strong> {{ $submission->client->registration_number }}</p>
                            <p><strong>Email:</strong> {{ $submission->client->email }}</p>
                            <p><strong>Phone:</strong> {{ $submission->client->phone }}</p>
                            <p><strong>Address:</strong> {{ $submission->client->address }}</p>
                        </div>
                    </details>
                </div>
            </div>

            <!-- Reject Modal (hidden, shown on demand) -->
            <div id="reject-modal-{{ $submission->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Payment Submission</h3>

                    <div class="mb-4 p-3 bg-red-50 rounded">
                        <p class="text-sm text-red-800">
                            <strong>Client:</strong> {{ $submission->client->name }}<br>
                            <strong>Amount:</strong> TZS {{ number_format($submission->amount_submitted, 2) }}<br>
                            <strong>Invoice:</strong> {{ $submission->invoice->invoice_number }}
                        </p>
                    </div>

                    <form id="reject-form-{{ $submission->id }}" onsubmit="submitReject(event, {{ $submission->id }})">
                        @csrf
                        <div class="mb-4">
                            <label for="reason-{{ $submission->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Rejection *
                            </label>
                            <textarea id="reason-{{ $submission->id }}"
                                      name="reason"
                                      rows="4"
                                      placeholder="Explain why you're rejecting this payment (e.g., amount doesn't match, wrong payer name)..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      required></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="closeRejectForm({{ $submission->id }})"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                                Reject Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
    function filterSubmissions() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.submission-card');

        cards.forEach(card => {
            const client = card.dataset.client.toLowerCase();
            const payer = card.dataset.payer.toLowerCase();

            if (client.includes(search) || payer.includes(search)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function approvePayment(submissionId, event) {
        if (!confirm('Approve this payment and generate receipt?')) {
            return;
        }

        const button = event.target.closest('button');
        if (button) {
            button.disabled = true;
            button.textContent = 'Processing...';
        }

        fetch(`/payment-submissions/${submissionId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (data.receipt_url) {
                    window.open(data.receipt_url, '_blank');
                }
                // Reload the page after receipt opens
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
                if (button) {
                    button.disabled = false;
                    button.textContent = 'Approve & Issue Receipt';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to approve payment');
            button.disabled = false;
            button.textContent = 'Approve & Issue Receipt';
        });
    }

    function showRejectForm(submissionId) {
        document.getElementById(`reject-modal-${submissionId}`).classList.remove('hidden');
    }

    function closeRejectForm(submissionId) {
        document.getElementById(`reject-modal-${submissionId}`).classList.add('hidden');
    }

    function submitReject(event, submissionId) {
        event.preventDefault();

        const reason = document.getElementById(`reason-${submissionId}`).value;

        if (!reason.trim()) {
            alert('Please provide a reason for rejection');
            return;
        }

        fetch(`/payment-submissions/${submissionId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to reject payment');
        });
    }
</script>
@endsection
