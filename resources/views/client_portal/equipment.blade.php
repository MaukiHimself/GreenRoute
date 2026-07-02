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

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #047857 0%, #047857 50%, #047857 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-tools fs-3 text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-white">Equipment & Container Pricing</h5>
                            <p class="mb-0 opacity-75 small">Browse available equipment and request what you need — your contractor will respond promptly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @if($products->count() > 0)
            @foreach($products as $product)
                @php
                    $isPending = in_array($product->id, $pendingIds);
                    $isApproved = in_array($product->id, $approvedIds);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm product-card d-flex flex-column">
                        <div class="card-body p-0 d-flex flex-column">
                            {{-- Image / icon --}}
                            <div class="bg-light rounded-top p-4 text-center" style="min-height: 140px; display: flex; align-items: center; justify-content: center;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="max-height: 120px; object-fit: contain;" alt="{{ $product->name }}">
                                @else
                                    @php
                                        $icons = [
                                            'waste_bins'            => 'bi-trash3',
                                            'recycling_containers'  => 'bi-recycle',
                                            'dumpsters'             => 'bi-box-seam',
                                            'compactors'            => 'bi-compress',
                                            'specialized'           => 'bi-gear',
                                        ];
                                        $icon = $icons[$product->category] ?? 'bi-tools';
                                    @endphp
                                    <i class="bi {{ $icon }} display-4" style="color: #047857; opacity: 0.6;"></i>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="p-3 flex-grow-1 d-flex flex-column">
                                @if($product->category)
                                    <span class="badge bg-light text-dark small mb-2">
                                        {{ ucwords(str_replace('_', ' ', $product->category)) }}
                                    </span>
                                @endif
                                <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                @if($product->description)
                                    <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</p>
                                @endif
                                @if($product->specifications)
                                    <p class="small text-muted mb-2"><i class="bi bi-list-check me-1"></i>{{ \Illuminate\Support\Str::limit($product->specifications, 80) }}</p>
                                @endif

                                {{-- Price + status --}}
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
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

                                {{-- Request button --}}
                                @if($product->is_available)
                                    @if($isPending)
                                        <button class="btn btn-sm btn-secondary mt-3 w-100" disabled>
                                            <i class="bi bi-clock me-1"></i>Request Sent
                                        </button>
                                    @else
                                        @if($isApproved)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-3">
                                                <i class="bi bi-check-circle me-1"></i>Previous request approved
                                            </span>
                                        @endif
                                        <button class="btn btn-sm btn-primary mt-3 w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#requestModal"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price ? 'TZS ' . number_format($product->price, 2) . ($product->unit ? ' / ' . $product->unit : '') : 'Price on request' }}">
                                            <i class="bi bi-cart-plus me-1"></i>{{ $isApproved ? 'Request Again' : 'Request This Equipment' }}
                                        </button>
                                    @endif
                                @endif
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
                        <p class="text-muted">Your contractor hasn't listed any equipment yet. Check back later.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Request Modal --}}
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" id="requestForm" action="">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold" id="requestModalLabel">Request Equipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="text-muted small mb-3">
                            Requesting: <strong id="modalProductName"></strong><br>
                            <span class="text-success" id="modalProductPrice"></span>
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="e.g. Needed for the main compound, delivery by end of month..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .product-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important; }
    </style>

    <script>
        document.getElementById('requestModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('modalProductName').textContent = btn.dataset.productName;
            document.getElementById('modalProductPrice').textContent = btn.dataset.productPrice;
            document.getElementById('requestForm').action =
                '/dashboard/client/equipment/' + btn.dataset.productId + '/request';
        });
    </script>
</x-dashboard-layout>
