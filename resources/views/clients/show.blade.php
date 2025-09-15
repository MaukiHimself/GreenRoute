<x-dashboard-layout title="Client Details">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.contractor') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('clients.index') }}">
                    <i class="bi bi-people me-2"></i>Clients
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
        <li class="breadcrumb-item active">Details</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Client Details</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6 class="text-primary">Personal Information</h6>
                        <div class="mt-2">
                            <div class="mb-3">
                                <small class="text-muted d-block">Full Name</small>
                                <div class="fw-semibold">{{ $client->name }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Email Address</small>
                                <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Phone Number</small>
                                <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Status</small>
                                <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h6 class="text-primary">Address Information</h6>
                        <div class="mt-2">
                            <div class="mb-3">
                                <small class="text-muted d-block">Street Address</small>
                                <div class="fw-semibold">{{ $client->address }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted d-block">City</small>
                                    <div class="fw-semibold">{{ $client->city }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted d-block">State</small>
                                    <div class="fw-semibold">{{ $client->state }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small class="text-muted d-block">ZIP Code</small>
                                    <div class="fw-semibold">{{ $client->zip_code }}</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Full Address</small>
                                <div class="text-muted">{{ $client->address }}<br>{{ $client->city }}, {{ $client->state }} {{ $client->zip_code }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($client->notes)
                    <hr>
                    <h6 class="text-primary">Notes</h6>
                    <div class="bg-light p-3 rounded">{{ $client->notes }}</div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted"><strong>Created:</strong> {{ $client->created_at->format('M d, Y \a\t g:i A') }}</small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted"><strong>Last Updated:</strong> {{ $client->updated_at->format('M d, Y \a\t g:i A') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Delete Client
                    </button>
                </form>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Client
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>