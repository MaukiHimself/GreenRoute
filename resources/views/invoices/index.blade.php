<x-dashboard-layout title="Invoice Management">
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
        <li class="breadcrumb-item active">Invoices</li>
    </x-slot>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Invoice Management</h4>
                <small class="text-muted">Generate PDFs and track payment status</small>
            </div>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary"><i class="bi bi-file-earmark-plus me-1"></i> New Invoice</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $invoice->client ? $invoice->client->name : 'Unknown Client' }}</div>
                                        <small class="text-muted">{{ $invoice->client ? $invoice->client->email : 'N/A' }}</small>
                                    </td>
                                    <td class="text-muted">{{ $invoice->service_type }}</td>
                                    <td class="text-muted">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td class="fw-semibold">TZS {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        @php $st=$invoice->status; @endphp
                                        <span class="badge {{ $st==='paid' ? 'bg-success' : ($st==='overdue' ? 'bg-danger' : ($st==='sent' ? 'bg-primary' : 'bg-secondary')) }}">{{ ucfirst($st) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-outline-success" title="PDF"><i class="bi bi-filetype-pdf"></i></a>
                                            @if($invoice->status !== 'paid')
                                                <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this invoice as paid?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success" title="Mark Paid"><i class="bi bi-check2-circle"></i></button>
                                                </form>
                                            @endif
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this invoice?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted p-4">No invoices found. <a href="{{ route('invoices.create') }}">Create your first invoice</a></td>
                                </tr>
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