<x-dashboard-layout title="Client Feedback">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('invoices.index') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('contractor.feedback.index') }}"><i class="bi bi-chat-dots me-2"></i>Feedback</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ $portalHomeUrl }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Feedback</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Received</th>
                                <th>Client</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedback as $item)
                                <tr>
                                    <td><small class="text-muted">{{ $item->created_at->format('M d, Y g:i A') }}</small></td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->client->name }}</div>
                                        <small class="text-muted">{{ $item->client->email }}</small>
                                    </td>
                                    <td class="fw-semibold">{{ $item->subject }}</td>
                                    <td class="text-muted">{{ Str::limit($item->message, 140) }}</td>
                                    <td><span class="badge {{ $item->status === 'open' ? 'bg-warning' : 'bg-secondary' }}">{{ ucfirst($item->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted p-4">No feedback yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($feedback->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end">{{ $feedback->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
