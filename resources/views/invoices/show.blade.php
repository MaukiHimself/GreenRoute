<x-dashboard-layout title="Invoice {{ $invoice->invoice_number }}">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contractor.clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('invoices.index') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
        <li class="breadcrumb-item active">{{ $invoice->invoice_number }}</li>
    </x-slot>

    @php
        $statusBadges = [
            'draft'          => 'bg-secondary',
            'sent'           => 'bg-primary',
            'paid'           => 'bg-success',
            'partially_paid' => 'bg-warning',
            'overdue'        => 'bg-danger',
            'cancelled'      => 'bg-dark',
        ];
        $statusBadge = $statusBadges[$invoice->status] ?? 'bg-secondary';
    @endphp

    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-0">Invoice Details</h4>
                <small class="text-muted">View and manage this invoice</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-success"><i class="bi bi-filetype-pdf me-1"></i> Download PDF</a>
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Main invoice --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        {{-- Invoice header --}}
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <h3 class="fw-bold mb-1">{{ $invoice->invoice_number }}</h3>
                                <div class="text-muted small">Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}</div>
                                <div class="text-muted small">Due Date: {{ $invoice->due_date->format('M d, Y') }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $statusBadge }} fs-6">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
                                @if($invoice->is_overdue && $invoice->status !== 'paid')
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Overdue</div>
                                @endif
                            </div>
                        </div>

                        {{-- Bill To --}}
                        <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size:.78rem;letter-spacing:.03em;">Bill To</h6>
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="fw-semibold">{{ $invoice->client->name ?? 'Unknown Client' }}</div>
                            @if($invoice->client?->email)<div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $invoice->client->email }}</div>@endif
                            @if($invoice->client?->phone)<div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $invoice->client->phone }}</div>@endif
                            @if($invoice->client?->address)<div class="text-muted small mt-1"><i class="bi bi-geo-alt me-1"></i>{{ $invoice->client->address }}</div>@endif
                        </div>

                        {{-- Service Details --}}
                        <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size:.78rem;letter-spacing:.03em;">Service Details</h6>
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="text-muted small">Service Type</div>
                                    <div class="fw-semibold">{{ $invoice->service_type }}</div>
                                </div>
                                @if($invoice->schedule)
                                    <div class="col-md-6">
                                        <div class="text-muted small">Related Schedule</div>
                                        <a href="{{ route('schedules.show', $invoice->schedule) }}" class="fw-semibold text-decoration-none">
                                            {{ $invoice->schedule->pickup_date->format('M d, Y') }} — {{ $invoice->schedule->pickup_time }}
                                        </a>
                                        @if($invoice->schedule->displayed_price !== null)
                                            <div class="text-muted small">Schedule price: TZS {{ number_format($invoice->schedule->displayed_price, 2) }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if($invoice->description)
                                <div class="mt-3">
                                    <div class="text-muted small">Description</div>
                                    <div>{{ $invoice->description }}</div>
                                </div>
                            @endif
                        </div>

                        {{-- Financial breakdown --}}
                        <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size:.78rem;letter-spacing:.03em;">Financial Details</h6>
                        <div class="bg-light rounded p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">TZS {{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax ({{ $invoice->tax_rate }}%)</span>
                                <span class="fw-semibold">TZS {{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 fs-5 fw-bold">
                                <span>Total Amount</span>
                                <span>TZS {{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                            @if($invoice->amount_paid > 0)
                                <div class="d-flex justify-content-between text-success mt-2">
                                    <span>Amount Paid</span>
                                    <span class="fw-semibold">TZS {{ number_format($invoice->amount_paid, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between text-danger fw-semibold">
                                    <span>Balance Due</span>
                                    <span>TZS {{ number_format($invoice->balance_due, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        @if($invoice->notes)
                            <h6 class="fw-bold text-uppercase text-muted mb-2 mt-4" style="font-size:.78rem;letter-spacing:.03em;">Notes</h6>
                            <div class="bg-light rounded p-3">{{ $invoice->notes }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Payment status --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold text-start mb-3">Payment Status</h6>
                        @if($invoice->status === 'paid')
                            <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
                            <div class="text-success fw-semibold mt-2">Paid in Full</div>
                            @if($invoice->paid_at)<div class="text-muted small">Paid on {{ $invoice->paid_at->format('M d, Y') }}</div>@endif
                            @if($invoice->payment_method)<div class="text-muted small">via {{ $invoice->payment_method }}</div>@endif
                        @else
                            <i class="bi bi-clock-fill text-warning" style="font-size:2.5rem;"></i>
                            <div class="text-warning fw-semibold mt-2">Pending Payment</div>
                            <div class="text-muted small mb-3">Balance: TZS {{ number_format($invoice->balance_due, 2) }}</div>
                            <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" onsubmit="return confirm('Mark this invoice as paid?')">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="payment_method" placeholder="Payment method (optional)" class="form-control form-control-sm mb-2">
                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check2-circle me-1"></i> Mark as Paid</button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-outline-success"><i class="bi bi-download me-1"></i> Download PDF</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit Invoice</a>
                            @if($invoice->client)
                                <a href="{{ route('contractor.clients.show', $invoice->client) }}" class="btn btn-outline-secondary"><i class="bi bi-person me-1"></i> View Client</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Invoice Info</h6>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Created</span><span>{{ $invoice->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Updated</span><span>{{ $invoice->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Contractor</span><span>{{ $invoice->contractor->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
