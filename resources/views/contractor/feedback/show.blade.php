<x-dashboard-layout title="Feedback Details">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.feedback.index') }}">Feedback</a></li>
        <li class="breadcrumb-item active">Details</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $feedback->subject }}</h5>
                            <p class="text-muted small mb-0">From {{ $feedback->client->name }} &bull; {{ $feedback->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <span class="badge 
                            {{ $feedback->status === 'open' ? 'bg-warning text-dark' : '' }}
                            {{ $feedback->status === 'responded' ? 'bg-info' : '' }}
                            {{ $feedback->status === 'resolved' ? 'bg-success' : '' }}
                            {{ $feedback->status === 'closed' ? 'bg-secondary' : '' }}">
                            {{ ucfirst($feedback->status) }}
                        </span>
                    </div>

                    <div class="bg-light rounded p-3 mb-4">
                        <div class="row g-2 small text-muted">
                            <div class="col-sm-6"><strong>Client:</strong> {{ $feedback->client->name }}</div>
                            <div class="col-sm-6"><strong>Email:</strong> {{ $feedback->client->email }}</div>
                            @if($feedback->client->phone)
                                <div class="col-sm-6"><strong>Phone:</strong> {{ $feedback->client->phone }}</div>
                            @endif
                            <div class="col-sm-6"><strong>Received:</strong> {{ $feedback->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-4 bg-white">
                        <h6 class="fw-semibold mb-2">Client's Message</h6>
                        <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $feedback->message }}</p>
                    </div>

                    @if($feedback->response)
                        <div class="border rounded p-3 mb-4" style="background: #f0fdfa; border-color: #055c5c;">
                            <h6 class="fw-semibold mb-2" style="color: #055c5c;">Your Response</h6>
                            <p class="mb-0" style="white-space: pre-line;">{{ $feedback->response }}</p>
                            @if($feedback->responded_at)
                                <small class="text-muted">Sent {{ $feedback->responded_at->format('M d, Y g:i A') }}</small>
                            @endif
                        </div>
                    @endif

                    <hr class="my-4">

                    <form method="POST" action="{{ route('contractor.feedback.respond', $feedback) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                @if($feedback->response) Edit Response @else Write a Response @endif
                            </label>
                            <textarea class="form-control @error('response') is-invalid @enderror" name="response" rows="5"
                                      placeholder="Type your response to this client...">{{ old('response', $feedback->response) }}</textarea>
                            @error('response')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <form method="POST" action="{{ route('contractor.feedback.status', $feedback) }}" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <select class="form-select" name="status" onchange="this.form.submit()">
                                        <option value="open" {{ $feedback->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="responded" {{ $feedback->status === 'responded' ? 'selected' : '' }}>Responded</option>
                                        <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $feedback->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </form>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>{{ $feedback->response ? 'Update Response' : 'Send Response' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('contractor.feedback.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Feedback
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>
