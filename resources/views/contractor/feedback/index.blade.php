<x-dashboard-layout title="Client Feedback">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Feedback</li>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Client Feedback</h4>
            <p class="text-muted small mb-0">Review and respond to feedback from your clients.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-warning text-dark">Open: {{ $feedback->where('status', 'open')->count() }}</span>
            <span class="badge bg-info">Responded: {{ $feedback->where('status', 'responded')->count() }}</span>
            <span class="badge bg-success">Resolved: {{ $feedback->where('status', 'resolved')->count() }}</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Received</th>
                            <th>Client</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedback as $item)
                            <tr>
                                <td class="ps-4">
                                    <small class="text-muted">{{ $item->created_at->format('M d, Y') }}</small>
                                    <br><small class="text-muted">{{ $item->created_at->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->client->name }}</div>
                                    <small class="text-muted">{{ $item->client->email }}</small>
                                    @if($item->client->phone)
                                        <br><small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $item->client->phone }}</small>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $item->subject }}</td>
                                <td class="text-muted" style="max-width: 250px;">{{ \Illuminate\Support\Str::limit($item->message, 100) }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $item->status === 'open' ? 'bg-warning text-dark' : '' }}
                                        {{ $item->status === 'responded' ? 'bg-info' : '' }}
                                        {{ $item->status === 'resolved' ? 'bg-success' : '' }}
                                        {{ $item->status === 'closed' ? 'bg-secondary' : '' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    @if($item->responded_at)
                                        <br><small class="text-muted">Replied {{ $item->responded_at->diffForHumans() }}</small>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('contractor.feedback.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        @if($item->status === 'open')
                                            <a href="{{ route('contractor.feedback.show', $item) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-reply me-1"></i>Reply
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-chat-dots display-5 d-block mb-3 text-muted"></i>
                                    <h6 class="text-muted">No feedback received yet</h6>
                                    <p class="small text-muted mb-0">Client feedback will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($feedback->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end">{{ $feedback->links() }}</div>
        @endif
    </div>
</x-dashboard-layout>
