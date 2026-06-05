<x-dashboard-layout title="My Invoices">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Invoices</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                    <td class="text-muted">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td class="text-muted">{{ $invoice->service_type }}</td>
                                    <td class="fw-semibold">TZS {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        @php $st=$invoice->status; @endphp
                                        <span class="badge {{ $st==='paid' ? 'bg-success' : ($st==='overdue' ? 'bg-danger' : ($st==='sent' ? 'bg-primary' : 'bg-secondary')) }}">{{ ucfirst($st) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($invoice->status !== 'paid')
                                                <a href="{{ route('client.payment-methods', $invoice) }}" class="btn btn-primary" title="Pay Now">
                                                    Pay Now
                                                </a>
                                            @endif
                                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-outline-success" title="Download PDF">
                                                <i class="bi bi-filetype-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted p-4">No invoices found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($invoices->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end">{{ $invoices->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
