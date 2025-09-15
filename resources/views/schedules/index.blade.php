<x-dashboard-layout title="Schedule Management">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.contractor') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('clients.index') }}">
                    <i class="bi bi-people me-2"></i>Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('schedules.index') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('invoices.index') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Schedules</li>
    </x-slot>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Schedule Management</h4>
                <small class="text-muted">Create and manage upcoming pickups</small>
            </div>
            <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus me-1"></i> New Schedule
            </a>
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
                                <th>Date & Time</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $schedule->pickup_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $schedule->pickup_time->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $schedule->client->name }}</div>
                                        <small class="text-muted">{{ $schedule->client->email }}</small>
                                    </td>
                                    <td>
                                        <div class="text-muted">{{ $schedule->pickup_location }}</div>
                                        <small class="text-muted">{{ $schedule->full_address }}</small>
                                    </td>
                                    <td>
                                        @php $svc=$schedule->service_type; @endphp
                                        <span class="badge {{ $svc==='collection' ? 'bg-primary' : ($svc==='disposal' ? 'bg-danger' : 'bg-info') }}">{{ ucfirst($svc) }}</span>
                                    </td>
                                    <td>
                                        @php $st=$schedule->status; @endphp
                                        <span class="badge {{ $st==='scheduled' ? 'bg-warning' : ($st==='in_progress' ? 'bg-primary' : ($st==='completed' ? 'bg-success' : 'bg-danger')) }}">{{ ucfirst(str_replace('_',' ',$st)) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this schedule?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-4">No schedules found. <a href="{{ route('schedules.create') }}">Create your first schedule</a></td>
                                </tr>
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