@extends('layouts.contractor-simple')

@section('title', 'Pending Client Approvals')

@section('content')
<style>
    :root { --teal: #047857; --teal-dark: #065f46; }
    .pending-hero {
        background: linear-gradient(135deg, var(--teal), #059669);
        border-radius: 16px;
        color: #fff;
        padding: 1.75rem 2rem;
    }
    .count-badge {
        background: #fff; color: var(--teal);
        border-radius: 999px; padding: .35rem 1rem;
        font-weight: 700; font-size: 1rem;
    }
    .client-card {
        border: none; border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border-left: 4px solid #f59e0b;
        transition: box-shadow .2s, transform .2s;
    }
    .client-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.1); transform: translateY(-2px); }
    .client-avatar {
        width: 48px; height: 48px; flex-shrink: 0;
        background: rgba(4,120,87,.1); color: var(--teal);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.2rem;
    }
    .info-item { display: flex; align-items: center; gap: .4rem; color: #64748b; font-size: .9rem; }
    .info-item i { color: var(--teal); }
    .btn-approve { background: var(--teal); border: none; color: #fff; font-weight: 600; }
    .btn-approve:hover { background: var(--teal-dark); color: #fff; }
    .gps-yes { color: #059669; }
    .gps-no  { color: #d97706; }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Hero header --}}
            <div class="pending-hero d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-person-check me-2"></i>Pending Client Approvals</h2>
                    <p class="mb-0" style="opacity:.9;">Clients matched to you who are awaiting verification</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="count-badge">{{ $pending->count() }} pending</span>
                    <a href="{{ route('contractor.clients.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>All Clients
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    @if(session('client_password'))
                        <div class="mt-2 p-2 bg-white rounded border">
                            <strong>Temporary Password:</strong> <code>{{ session('client_password') }}</code>
                            <span class="text-muted">— emailed to {{ session('client_email') }}</span>
                        </div>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @forelse($pending as $client)
                <div class="card client-card mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div class="client-avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ $client->name }}</h5>
                                        <span class="badge bg-warning text-dark me-1"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        <span class="badge bg-secondary">{{ $client->category }}</span>
                                        @if($client->route)
                                            <span class="badge bg-info text-dark ms-1"><i class="bi bi-signpost me-1"></i>{{ $client->route }}</span>
                                        @endif
                                        @if($client->latitude && $client->longitude)
                                            <span class="badge bg-success-subtle gps-yes ms-1"><i class="bi bi-pin-map-fill me-1"></i>GPS set</span>
                                        @else
                                            <span class="badge bg-warning-subtle gps-no ms-1"><i class="bi bi-pin-map me-1"></i>No GPS</span>
                                        @endif
                                    </div>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $client->created_at->diffForHumans() }}</small>
                                </div>

                                <div class="row mt-3 g-2">
                                    <div class="col-md-4"><div class="info-item"><i class="bi bi-person"></i>{{ $client->contact_name }}</div></div>
                                    <div class="col-md-4"><div class="info-item"><i class="bi bi-envelope"></i>{{ $client->email }}</div></div>
                                    <div class="col-md-4"><div class="info-item"><i class="bi bi-telephone"></i>{{ $client->phone }}</div></div>
                                    <div class="col-12">
                                        <div class="info-item">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ implode(' → ', array_filter([$client->region, $client->district, $client->ward, $client->street])) ?: $client->address }}
                                            @if($client->latitude && $client->longitude)
                                                <a href="https://www.openstreetmap.org/?mlat={{ $client->latitude }}&mlon={{ $client->longitude }}#map=17/{{ $client->latitude }}/{{ $client->longitude }}"
                                                   target="_blank" rel="noopener" class="ms-2 text-decoration-none small">
                                                    <i class="bi bi-box-arrow-up-right"></i> map
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @if($client->notes)
                                        <div class="col-12"><div class="info-item"><i class="bi bi-chat-left-text"></i><em>{{ $client->notes }}</em></div></div>
                                    @endif
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <form action="{{ route('contractor.clients.approve', $client) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-approve"
                                                onclick="return confirm('Approve {{ $client->name }}? They will receive an email with login credentials.')">
                                            <i class="bi bi-check-lg me-1"></i>Approve &amp; Send Credentials
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
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="client-avatar mx-auto mb-3" style="width:72px;height:72px;font-size:2rem;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <h5 class="fw-bold" style="color:var(--teal);">All caught up!</h5>
                        <p class="text-muted small mb-0">No pending approvals. When clients self-register in your area, they'll appear here for verification.</p>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
