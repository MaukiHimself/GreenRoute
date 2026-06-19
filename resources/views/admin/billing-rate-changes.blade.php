<x-dashboard-layout title="Billing Rate Changes">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.admin') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.clients') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.billing') }}"><i class="bi bi-credit-card me-2"></i>Billing</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.billing.rate-changes') }}"><i class="bi bi-activity me-2"></i>Billing Changes</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Admin</a></li>
        <li class="breadcrumb-item active">Billing Changes</li>
    </x-slot>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Contractor Billing Rate Changes</h4>
                <small class="text-muted">Audit trail for contractor schedule billing selections, overrides, and removals.</small>
            </div>
            <a href="{{ route('admin.billing.rates') }}" class="btn btn-primary">Manage Billing Rates</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.billing.rate-changes') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Contractor</label>
                        <select name="contractor_id" class="form-select">
                            <option value="">All Contractors</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ ($filters['contractor_id'] ?? '') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            <option value="created" {{ ($filters['action'] ?? '') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="selected_rate" {{ ($filters['action'] ?? '') == 'selected_rate' ? 'selected' : '' }}>Selected Rate</option>
                            <option value="changed_rate" {{ ($filters['action'] ?? '') == 'changed_rate' ? 'selected' : '' }}>Changed Rate</option>
                            <option value="changed_price" {{ ($filters['action'] ?? '') == 'changed_price' ? 'selected' : '' }}>Changed Price</option>
                            <option value="price_added" {{ ($filters['action'] ?? '') == 'price_added' ? 'selected' : '' }}>Price Added</option>
                            <option value="price_removed" {{ ($filters['action'] ?? '') == 'price_removed' ? 'selected' : '' }}>Price Removed</option>
                            <option value="changed_reason" {{ ($filters['action'] ?? '') == 'changed_reason' ? 'selected' : '' }}>Changed Reason</option>
                            <option value="removed_rate" {{ ($filters['action'] ?? '') == 'removed_rate' ? 'selected' : '' }}>Removed Rate</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" name="date_from" class="form-select" value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" name="date_to" class="form-select" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Contractor</th>
                                <th>Client</th>
                                <th>Schedule</th>
                                <th>Action</th>
                                <th>Old</th>
                                <th>New</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($changes as $change)
                                <tr>
                                    <td>{{ $change->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $change->contractor->name ?? 'N/A' }}</td>
                                    <td>{{ $change->client->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($change->schedule)
                                            {{ $change->schedule->pickup_date->format('M d, Y') }} - {{ $change->schedule->pickup_location }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ str_replace('_', ' ', ucfirst($change->action)) }}</span></td>
                                    <td>
                                        <div>{{ $change->old_rate_label ?? 'No rate' }}</div>
                                        <small class="text-muted">{{ $change->old_fee !== null ? 'TZS ' . number_format($change->old_fee, 2) : 'No fee' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $change->new_rate_label ?? 'No rate' }}</div>
                                        <small class="text-muted">{{ $change->new_fee !== null ? 'TZS ' . number_format($change->new_fee, 2) : 'No fee' }}</small>
                                    </td>
                                    <td>{{ $change->reason ? \Illuminate\Support\Str::limit($change->reason, 80) : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted p-4">No billing changes found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($changes->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end">{{ $changes->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
