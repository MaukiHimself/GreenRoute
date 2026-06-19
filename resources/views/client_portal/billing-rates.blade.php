<x-dashboard-layout title="Billing Rates">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.billing-rates') }}"><i class="bi bi-currency-dollar me-2"></i>Billing Rates</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Billing Rates</li>
    </x-slot>

    <div class="container-fluid">
        <div class="mb-3">
            <h4 class="mb-0">Official Collection Billing Rates</h4>
            <small class="text-muted">These are the current active rates used by your contractor when creating schedules.</small>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Frequency</th>
                                <th>Collection Fee</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rates as $rate)
                                <tr>
                                    <td><strong>{{ $rate->category }}</strong></td>
                                    <td>{{ $rate->location }}</td>
                                    <td>{{ $rate->frequency ? ucfirst(str_replace('-', ' ', $rate->frequency)) : 'Any' }}</td>
                                    <td><span class="badge bg-success">TZS {{ number_format($rate->collection_fee, 2) }}</span></td>
                                    <td>{{ $rate->description ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted p-4">No active billing rates found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($rates->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end">{{ $rates->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
