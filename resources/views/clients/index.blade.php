<x-dashboard-layout title="Clients">
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
            <li class="nav-item">
                <a class="nav-link" href="{{ route('schedules.index') }}">
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
        <li class="breadcrumb-item active">Clients</li>
    </x-slot>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header + Actions -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body py-3">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-4">
                        <h4 class="mb-0">Client Database</h4>
                        <small class="text-muted">All clients linked to your contractor account</small>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-body"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search by name, email or city">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <select class="form-select">
                            <option selected>All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-lg-2 text-lg-end">
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Add Client
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Sort by:</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active">Name</button>
                        <button type="button" class="btn btn-outline-primary">City</button>
                        <button type="button" class="btn btn-outline-primary">Created</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($clients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:42px"></th>
                                    <th>Client</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>City / State</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-inline-flex">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $client->name }}</div>
                                            <small class="text-muted">Joined {{ optional($client->created_at)->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                        </td>
                                        <td class="text-muted">
                                            <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">{{ $client->city }}, {{ $client->state }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($client->status) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this client?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-muted">No clients found</h5>
                        <p class="text-muted mb-3">Start by adding your first client.</p>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Add Your First Client
                        </a>
                    </div>
                @endif
            </div>
            @if($clients->count() > 0)
                <div class="card-footer bg-white d-md-flex justify-content-between align-items-center py-3">
                    <div class="text-muted small mb-2 mb-md-0">
                        Showing {{ $clients->firstItem() ?? 1 }}–{{ $clients->lastItem() ?? $clients->count() }} of {{ $clients->total() ?? $clients->count() }}
                    </div>
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>