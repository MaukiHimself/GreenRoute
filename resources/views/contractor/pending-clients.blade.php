@extends('layouts.contractor-simple')

@section('title', 'Pending Client Approvals')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color:#055c5c;"><i class="bi bi-person-check me-2"></i>Pending Client Approvals</h2>
                    <p class="text-muted mb-0">Clients who self-registered and are awaiting your verification</p>
                </div>
                <a href="{{ route('contractor.clients.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>All Clients
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    @if(session('client_password'))
                        <br><strong>Temporary Password:</strong> <code>{{ session('client_password') }}</code> — sent to {{ session('client_email') }}
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @forelse($pending as $client)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $client->name }}</h5>
                                <span class="badge bg-warning text-dark me-1">Pending</span>
                                <span class="badge bg-secondary">{{ $client->category }}</span>
                                @if($client->route)
                                    <span class="badge bg-info text-dark ms-1"><i class="bi bi-signpost me-1"></i>{{ $client->route }}</span>
                                @endif
                            </div>
                            <small class="text-muted">Registered {{ $client->created_at->diffForHumans() }}</small>
                        </div>

                        <div class="row mt-3 g-2 small text-muted">
                            <div class="col-md-4">
                                <i class="bi bi-person me-1"></i><strong>Contact:</strong> {{ $client->contact_name }}
                            </div>
                            <div class="col-md-4">
                                <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                            </div>
                            <div class="col-md-4">
                                <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                            </div>
                            <div class="col-12">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ implode(' → ', array_filter([$client->region, $client->district, $client->ward, $client->street])) ?: $client->address }}
                            </div>
                            @if($client->notes)
                                <div class="col-12">
                                    <i class="bi bi-chat-left-text me-1"></i><em>{{ $client->notes }}</em>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <form action="{{ route('contractor.clients.approve', $client) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Approve {{ $client->name }}? They will receive an email with login credentials.')">
                                    <i class="bi bi-check-lg me-1"></i>Approve & Send Credentials
                                </button>
                            </form>
                            <form action="{{ route('contractor.clients.reject', $client) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Reject {{ $client->name }}? This cannot be undone.')">
                                    <i class="bi bi-x-lg me-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-person-check fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No pending approvals</h5>
                    <p class="text-muted small">When clients register themselves and choose your route, they'll appear here.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
