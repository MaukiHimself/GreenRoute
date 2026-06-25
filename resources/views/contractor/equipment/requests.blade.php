<x-dashboard-layout title="Equipment Requests">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.equipment.index') }}">Equipment</a></li>
        <li class="breadcrumb-item active">Client Requests</li>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Equipment Requests</h4>
            <p class="text-muted small mb-0">Equipment requested by your clients</p>
        </div>
        <a href="{{ route('contractor.equipment.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Inventory
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Equipment</th>
                            <th>Client</th>
                            <th>Qty</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($req->product?->image)
                                            <img src="{{ asset('storage/' . $req->product->image) }}" class="rounded" width="44" height="44" style="object-fit:cover;" alt="{{ $req->product->name }}">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center text-white" style="width:44px;height:44px;background:linear-gradient(135deg,#055c5c,#055c5c);">
                                                <i class="bi bi-tools"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $req->product?->name ?? '—' }}</div>
                                            @if($req->product?->price)
                                                <small class="text-success">TZS {{ number_format($req->product->price, 2) }}{{ $req->product->unit ? ' / ' . $req->product->unit : '' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $req->client?->name ?? '—' }}</div>
                                    <small class="text-muted">{{ $req->client?->registration_number }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark fw-semibold">{{ $req->quantity }}</span></td>
                                <td>
                                    @if($req->notes)
                                        <span class="text-muted small" title="{{ $req->notes }}">
                                            {{ \Illuminate\Support\Str::limit($req->notes, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($req->status) {
                                            'pending'   => 'bg-warning text-dark',
                                            'approved'  => 'bg-success',
                                            'rejected'  => 'bg-danger',
                                            'fulfilled' => 'bg-primary',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($req->status) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $req->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $req->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#respondModal"
                                                data-request-id="{{ $req->id }}"
                                                data-client-name="{{ $req->client?->name }}"
                                                data-product-name="{{ $req->product?->name }}"
                                                data-qty="{{ $req->quantity }}">
                                            <i class="bi bi-reply me-1"></i>Respond
                                        </button>
                                    @else
                                        <span class="text-muted small">
                                            @if($req->responded_at)
                                                Responded {{ $req->responded_at->diffForHumans() }}
                                            @else
                                                —
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if($req->contractor_response)
                                <tr class="table-light">
                                    <td colspan="7" class="ps-4 py-2">
                                        <small class="text-muted"><i class="bi bi-chat-left-text me-1"></i>Your response: <em>{{ $req->contractor_response }}</em></small>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox display-5 d-block mb-3 text-muted"></i>
                                    <h6 class="text-muted">No equipment requests yet</h6>
                                    <p class="small text-muted mb-0">When clients request equipment, they'll appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($requests->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end">{{ $requests->links() }}</div>
        @endif
    </div>

    {{-- Respond Modal --}}
    <div class="modal fade" id="respondModal" tabindex="-1" aria-labelledby="respondModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" id="respondForm" action="">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold" id="respondModalLabel">Respond to Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="text-muted small mb-3">
                            <strong id="modalClientName"></strong> is requesting
                            <strong id="modalProductName"></strong>
                            &times; <strong id="modalQty"></strong>
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Action</label>
                            <select name="status" class="form-select" required>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                                <option value="fulfilled">Mark as Fulfilled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Response Note <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="contractor_response" class="form-control" rows="3" placeholder="e.g. Equipment will be delivered on Monday..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Submit Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('respondModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('modalClientName').textContent  = btn.dataset.clientName;
            document.getElementById('modalProductName').textContent = btn.dataset.productName;
            document.getElementById('modalQty').textContent         = btn.dataset.qty;
            document.getElementById('respondForm').action =
                '/dashboard/contractor/equipment-requests/' + btn.dataset.requestId + '/respond';
        });
    </script>
</x-dashboard-layout>
