@extends('layouts.contractor-sidebar')

@section('title', 'Edit Schedule')

@section('styles')
<style>
    :root {
        --primary-teal: #047857;
        --primary-red: #c0392b;
        --white: #ffffff;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-teal) 0%, #059669 100%);
        color: var(--white);
        padding: 2rem;
        border-radius: 12px 12px 0 0;
        margin-bottom: 0;
    }

    .form-container {
        background: var(--white);
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .required-star {
        color: var(--primary-red);
        font-weight: bold;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-teal);
        box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        outline: none;
    }

    .section-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.25rem 1.25rem 0.5rem;
        margin-bottom: 1.5rem;
        background: #fbfdfd;
    }

    .section-card h6 {
        color: var(--primary-teal);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .price-summary {
        background: #ecfdf5;
        border: 2px solid #a7f3d0;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .price-summary .amount {
        font-size: 1.4rem;
        font-weight: 700;
        color: #065f46;
    }

    .btn-primary-custom {
        background: var(--primary-teal);
        color: var(--white);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary-custom:hover { background: #065f46; color: var(--white); }

    .btn-secondary-custom {
        background: var(--white);
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary-custom:hover { background: var(--primary-red); color: var(--white); }

    .client-chip {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1" style="font-size: 1.75rem; font-weight: 700;">
                        <i class="bi bi-pencil-square me-2"></i>Edit Schedule
                    </h1>
                    <p class="mb-0" style="opacity: 0.95;">{{ $schedule->route }} — {{ $schedule->pickup_date->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye me-1"></i>View
                </a>
            </div>

            <div class="form-container p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Client & Status -->
                    <div class="section-card">
                        <h6><i class="bi bi-person-circle me-1"></i>Client &amp; Status</h6>

                        <div class="client-chip mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <strong>{{ $schedule->client->name ?? 'Client' }}</strong>
                                <small class="text-muted d-block">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $schedule->client->ward ?? '' }}{{ $schedule->client->ward && $schedule->client->district ? ', ' : '' }}{{ $schedule->client->district ?? '' }}
                                    @if($schedule->client->phone) &nbsp;·&nbsp; <i class="bi bi-telephone me-1"></i>{{ $schedule->client->phone }} @endif
                                </small>
                            </div>
                            <span class="badge bg-success">{{ $schedule->route }}</span>
                        </div>
                        <input type="hidden" name="client_id" value="{{ $schedule->client_id }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="required-star">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ old('status', $schedule->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Service Type <span class="required-star">*</span></label>
                                <select name="service_type" id="service_type" class="form-select" required>
                                    <option value="collection" {{ old('service_type', $schedule->service_type) == 'collection' ? 'selected' : '' }}>Collection</option>
                                    <option value="disposal" {{ old('service_type', $schedule->service_type) == 'disposal' ? 'selected' : '' }}>Disposal</option>
                                    <option value="both" {{ old('service_type', $schedule->service_type) == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div class="section-card">
                        <h6><i class="bi bi-clock me-1"></i>Timing</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_date" class="form-label">Pickup Date <span class="required-star">*</span></label>
                                <input type="date" name="pickup_date" id="pickup_date" class="form-control"
                                       value="{{ old('pickup_date', $schedule->pickup_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pickup_time" class="form-label">Pickup Time <span class="required-star">*</span></label>
                                <input type="time" name="pickup_time" id="pickup_time" class="form-control"
                                       value="{{ old('pickup_time', $schedule->pickup_time->format('H:i')) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="section-card">
                        <h6><i class="bi bi-cash-coin me-1"></i>Price (from your service price list)</h6>

                        @if($servicePrices->isEmpty())
                            <div class="alert alert-warning mb-3">
                                You have no active service prices yet.
                                <a href="{{ route('contractor.pricing.create') }}">Add one on your pricing page</a>.
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="service_price_id" class="form-label">Service</label>
                                <select id="service_price_id" class="form-select" onchange="updatePrice()">
                                    <option value="" data-price="{{ $schedule->contractor_adjusted_fee ?? $schedule->schedule_price ?? '' }}">Keep current price</option>
                                    @foreach($servicePrices as $sp)
                                        <option value="{{ $sp->id }}" data-price="{{ $sp->price }}"
                                                data-label="{{ \App\Models\ServicePrice::getLabel($sp->service_type) }} — {{ \App\Models\ServicePrice::getVolumeLabel($sp->volume_tier) }}">
                                            {{ \App\Models\ServicePrice::getLabel($sp->service_type) }} — {{ \App\Models\ServicePrice::getVolumeLabel($sp->volume_tier) }} — TZS {{ number_format($sp->price) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Prices come from your published price list, so the client sees the same amount everywhere.</div>
                            </div>
                        @endif

                        <div class="price-summary">
                            <span class="text-muted">Price for this pickup</span>
                            <span class="amount" id="price_display">TZS {{ number_format($schedule->contractor_adjusted_fee ?? $schedule->schedule_price ?? 0) }}</span>
                        </div>
                        <input type="hidden" name="contractor_adjusted_fee" id="contractor_adjusted_fee"
                               value="{{ old('contractor_adjusted_fee', $schedule->contractor_adjusted_fee ?? $schedule->schedule_price) }}">
                        <input type="hidden" name="billing_rate_change_reason" id="billing_rate_change_reason"
                               value="{{ old('billing_rate_change_reason', $schedule->billing_rate_change_reason) }}">
                    </div>

                    <!-- Notes -->
                    <div class="section-card">
                        <h6><i class="bi bi-sticky me-1"></i>Notes</h6>
                        <div class="mb-3">
                            <textarea name="notes" id="notes" rows="3" class="form-control"
                                      placeholder="Special instructions for the driver (optional)">{{ old('notes', $schedule->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('schedules.show', $schedule) }}" class="btn-secondary-custom">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updatePrice() {
    const select = document.getElementById('service_price_id');
    if (!select) return;
    const opt = select.options[select.selectedIndex];
    const price = opt && opt.dataset.price !== '' ? parseFloat(opt.dataset.price) : null;

    document.getElementById('price_display').textContent =
        price !== null && !isNaN(price) ? 'TZS ' + price.toLocaleString() : 'TZS 0';
    if (price !== null && !isNaN(price)) {
        document.getElementById('contractor_adjusted_fee').value = price;
    }
    if (opt && opt.value) {
        document.getElementById('billing_rate_change_reason').value =
            'Priced from published service price list: ' + opt.dataset.label;
    }
}
</script>
@endsection
