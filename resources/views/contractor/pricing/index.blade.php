<x-dashboard-layout title="Service Pricing">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Service Pricing</li>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Service Pricing</h4>
            <p class="text-muted small mb-0">Set prices for each waste collection service type. Clients see active prices on the Request Service page.</p>
        </div>
        <a href="{{ route('contractor.pricing.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add Price
        </a>
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
                            <th class="ps-4">Service</th>
                            <th>Waste Type</th>
                            <th>Volume</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $price)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ \App\Models\ServicePrice::getLabel($price->service_type) }}</div>
                                    @if($price->description)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($price->description, 55) }}</small>
                                    @endif
                                </td>
                                <td>{{ $price->waste_type ? ucwords(str_replace('_', ' ', $price->waste_type)) : 'All' }}</td>
                                <td>{{ $price->volume_tier ? \App\Models\ServicePrice::getVolumeLabel($price->volume_tier) : 'Standard' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $price->category ? ucfirst($price->category) : 'All' }}</span></td>
                                <td class="fw-bold text-success">TZS {{ number_format($price->price, 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('contractor.pricing.toggle', $price) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $price->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                            <i class="bi bi-{{ $price->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                            {{ $price->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('contractor.pricing.edit', $price) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('contractor.pricing.destroy', $price) }}"
                                              onsubmit="return confirm('Remove this price entry?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-tag display-5 d-block mb-3 text-muted"></i>
                                    <h6 class="text-muted">No service prices set yet</h6>
                                    <p class="small text-muted mb-0">Add pricing for your services — clients will see these on the Request Service page.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($prices->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end">{{ $prices->links() }}</div>
        @endif
    </div>
</x-dashboard-layout>
