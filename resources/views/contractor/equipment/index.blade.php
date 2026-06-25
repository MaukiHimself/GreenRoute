<x-dashboard-layout title="Equipment Management">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Equipment</li>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Equipment Inventory</h4>
            <p class="text-muted small mb-0">Manage waste storage equipment and containers offered to clients</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contractor.equipment.requests') }}" class="btn btn-outline-primary">
                <i class="bi bi-inbox me-2"></i>Client Requests
            </a>
            <a href="{{ route('contractor.equipment.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Add Equipment
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Equipment</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $eq)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($eq->image)
                                            <img src="{{ asset('storage/' . $eq->image) }}" class="rounded" width="50" height="50" style="object-fit: cover;" alt="{{ $eq->name }}">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px; background: linear-gradient(135deg, #055c5c, #055c5c);">
                                                <i class="bi bi-tools fs-4"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $eq->name }}</div>
                                            @if($eq->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($eq->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $eq->category ?? 'General' }}</span></td>
                                <td>{{ $eq->unit ?? '—' }}</td>
                                <td class="fw-semibold text-success">
                                    {{ $eq->price ? 'TZS ' . number_format($eq->price, 2) : '—' }}
                                </td>
                                <td>
                                    @if($eq->is_available)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-secondary">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('contractor.equipment.edit', $eq) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            form="toggle-{{ $eq->id }}"
                                            class="btn btn-sm {{ $eq->is_available ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $eq->is_available ? 'Mark Unavailable' : 'Mark Available' }}">
                                            <i class="bi bi-{{ $eq->is_available ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <form id="toggle-{{ $eq->id }}" method="POST" action="{{ route('contractor.equipment.toggle', $eq) }}" class="d-none">
                                            @csrf @method('PATCH')
                                        </form>
                                        <form method="POST" action="{{ route('contractor.equipment.destroy', $eq) }}"
                                              onsubmit="return confirm('Delete this equipment? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-tools display-5 d-block mb-3 text-muted"></i>
                                    <h6 class="text-muted">No equipment added yet</h6>
                                    <p class="small text-muted mb-0">Start by adding your first equipment listing.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($equipments->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end">{{ $equipments->links() }}</div>
        @endif
    </div>
</x-dashboard-layout>
