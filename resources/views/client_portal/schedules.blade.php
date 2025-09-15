<x-dashboard-layout title="My Schedules">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.client') }}">Client</a></li>
        <li class="breadcrumb-item active">Schedules</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Contractor</th>
                                <th>Service</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->pickup_date->format('M d, Y') }}</td>
                                    <td>{{ $schedule->pickup_time->format('g:i A') }}</td>
                                    <td class="text-muted">{{ $schedule->pickup_location }}</td>
                                    <td class="text-muted">{{ $schedule->full_address }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $schedule->contractor->company_name ?? $schedule->contractor->name }}</span>
                                    </td>
                                    <td><span class="badge bg-primary">{{ ucfirst($schedule->service_type) }}</span></td>
                                    <td>
                                        @php $st=$schedule->status; @endphp
                                        <span class="badge {{ $st==='scheduled' ? 'bg-warning' : ($st==='completed' ? 'bg-success' : 'bg-secondary') }}">{{ ucfirst(str_replace('_',' ',$st)) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted p-4">No schedules found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($schedules->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end">{{ $schedules->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
