<x-dashboard-layout title="Waste Equipment & Containers">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.profile') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.equipment') }}">
                    <i class="bi bi-tools me-2"></i>Equipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.contractor.info') }}">
                    <i class="bi bi-building me-2"></i>Contractor Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.payments') }}">
                    <i class="bi bi-credit-card me-2"></i>Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.feedback') }}">
                    <i class="bi bi-chat-dots me-2"></i>Feedback
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Equipment & Pricing</li>
    </x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-info text-white" style="background: linear-gradient(135deg, #055c5c 0%, #0d9488 50%, #0891b2 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-tools fs-3 text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-white">Equipment & Container Pricing</h5>
                            <p class="mb-0 opacity-75 small">Browse available waste storage equipment offered by your contractor with transparent pricing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="card-body p-0">
                            <div class="bg-light rounded-top p-4 text-center" style="min-height: 140px; display: flex; align-items: center; justify-content: center;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="max-height: 120px; object-fit: contain;" alt="{{ $product->name }}">
                                @else
                                    <div class="text-center">
                                        @php
                                            $icons = [
                                                'waste_bins' => 'bi-trash3',
                                                'recycling_containers' => 'bi-recycle',
                                                'dumpsters' => 'bi-box-seam',
                                                'compactors' => 'bi-compress',
                                                'specialized' => 'bi-gear',
                                            ];
                                            $icon = 'bi-tools';
                                            if($product->category && isset($icons[$product->category])) {
                                                $icon = $icons[$product->category];
                                            }
                                        @endphp
                                        <i class="bi {{ $icon }} display-4" style="color: #0891b2; opacity: 0.6;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                @if($product->category)
                                    <span class="badge bg-light text-dark small mb-2">
                                        {{ ucwords(str_replace('_', ' ', $product->category)) }}
                                    </span>
                                @endif
                                <h6 class="fw-bold mb-2">{{ $product->name ?? 'Equipment' }}</h6>
                                @if($product->description)
                                    <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</p>
                                @endif
                                @if($product->specifications)
                                    <p class="small text-muted mb-2"><i class="bi bi-list-check me-1"></i>{{ $product->specifications }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    @if($product->price)
                                        <div>
                                            <strong class="text-success">TZS {{ number_format($product->price, 2) }}</strong>
                                            @if($product->unit)
                                                <small class="text-muted d-block">per {{ $product->unit }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Price on request</span>
                                    @endif
                                    <span class="badge {{ $product->is_available ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-tools display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No Equipment Listed</h5>
                        <p class="text-muted">Your contractor hasn't listed any equipment yet. Check back later or contact them directly.</p>
                        <a href="{{ route('client.request.service') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-envelope me-2"></i>Request Equipment Info
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .product-card:hover { transform: translateY(-3px); transition: all 0.3s ease; }
    </style>
</x-dashboard-layout>
