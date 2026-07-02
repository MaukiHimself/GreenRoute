<x-dashboard-layout title="Service Pricing">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.pricing') }}">
                    <i class="bi bi-currency-dollar me-2"></i>Pricing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Pricing</li>
    </x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #047857 0%, #047857 50%, #047857 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-currency-dollar fs-3 text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-white">Service Pricing</h5>
                            <p class="mb-0 opacity-75 small">These are your contractor's current service prices. They apply when you request a collection.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <th class="text-end pe-4">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $price)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ \App\Models\ServicePrice::getLabel($price->service_type) }}</div>
                                    @if($price->description)
                                        <small class="text-muted">{{ $price->description }}</small>
                                    @endif
                                </td>
                                <td>{{ $price->waste_type ? ucwords(str_replace('_', ' ', $price->waste_type)) : 'All' }}</td>
                                <td>{{ $price->volume_tier ? \App\Models\ServicePrice::getVolumeLabel($price->volume_tier) : 'Standard' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $price->category ? ucfirst($price->category) : 'All' }}</span></td>
                                <td class="text-end pe-4 fw-bold text-success">TZS {{ number_format($price->price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-tag display-5 d-block mb-3 text-muted"></i>
                                    <h6 class="text-muted">No pricing available yet</h6>
                                    <p class="small text-muted mb-0">Your contractor hasn't published any service prices yet. Please check back later.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
