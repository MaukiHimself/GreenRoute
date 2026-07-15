@extends('layouts.contractor-sidebar')

@section('title', 'Create Schedule')

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

    .btn-primary-custom {
        background: var(--primary-teal);
        color: var(--white);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-custom:hover {
        background: #065f46;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
    }

    .btn-secondary-custom {
        background: var(--white);
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary-custom:hover {
        background: var(--primary-red);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(100, 4, 4, 0.3);
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
    }

    .price-summary .amount {
        font-size: 1.4rem;
        font-weight: 700;
        color: #065f46;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">
                    <i class="bi bi-calendar-plus me-2"></i>Create New Schedule
                </h1>
                <p class="mb-0" style="opacity: 0.95;">Pick a route, choose the clients, set the dates — pricing comes straight from your service price list</p>
            </div>

            <!-- Form Container -->
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

                <form id="scheduleForm" method="POST" action="{{ route('schedules.store') }}">
                    @csrf
                    <input type="hidden" name="service_type" value="collection">

                    <!-- Route & Clients -->
                    <div class="section-card">
                        <h6><i class="bi bi-signpost-split me-1"></i>Route &amp; Clients</h6>

                        <div class="mb-3">
                            <label for="route_select" class="form-label">Route <span class="required-star">*</span></label>
                            <select id="route_select" name="route_name" class="form-select" required>
                                <option value="">Select one of your routes…</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->route_name }}">
                                        {{ $route->route_name }} ({{ $routeClientCounts[$route->route_name] ?? 0 }} client{{ ($routeClientCounts[$route->route_name] ?? 0) == 1 ? '' : 's' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Clients on the selected route are loaded below automatically.</div>
                        </div>

                        <div class="mb-3" id="clientsSection">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Clients <span class="required-star">*</span></label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                                </div>
                            </div>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto; background: #ffffff;">
                                <div id="clientsList">
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-signpost fs-3 d-block mb-2"></i>
                                        Select a route above to load its clients.
                                    </div>
                                </div>
                            </div>
                            <div class="form-text"><span id="selected_count">0</span> clients selected</div>
                        </div>
                    </div>

                    <!-- Timing & Repeat -->
                    <div class="section-card">
                        <h6><i class="bi bi-clock me-1"></i>When &amp; How Often</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pickup_date" class="form-label">First Pickup Date <span class="required-star">*</span></label>
                                <input type="date" name="pickup_date" id="pickup_date" required
                                       min="{{ date('Y-m-d') }}" value="{{ old('pickup_date') }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pickup_time" class="form-label">Pickup Time <span class="required-star">*</span></label>
                                <input type="time" name="pickup_time" id="pickup_time" required value="{{ old('pickup_time', '08:00') }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="frequency" class="form-label">Repeats</label>
                                <select name="frequency" id="frequency" class="form-select" onchange="toggleRepeat()">
                                    <option value="once" {{ old('frequency', 'once') == 'once' ? 'selected' : '' }}>One time only</option>
                                    <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="twice_month" {{ old('frequency') == 'twice_month' ? 'selected' : '' }}>Twice per month</option>
                                    <option value="thrice_month" {{ old('frequency') == 'thrice_month' ? 'selected' : '' }}>Thrice per month</option>
                                    <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="repeat_until_row" style="display:none;">
                            <div class="col-md-4 mb-3">
                                <label for="repeat_until" class="form-label">Repeat Until <span class="required-star">*</span></label>
                                <input type="date" name="repeat_until" id="repeat_until" value="{{ old('repeat_until') }}" class="form-control">
                                <div class="form-text" id="repeat_preview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="section-card">
                        <h6><i class="bi bi-cash-coin me-1"></i>Price (from your service price list)</h6>

                        @if($servicePrices->isEmpty())
                            <div class="alert alert-warning mb-3">
                                You have no active service prices yet.
                                <a href="{{ route('contractor.pricing.create') }}">Add one on your pricing page</a> — schedules use those prices so clients always see the same amount.
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="service_price_id" class="form-label">Service <span class="required-star">*</span></label>
                                <select id="service_price_id" class="form-select" required onchange="updatePrice()">
                                    <option value="">Choose a service…</option>
                                    @foreach($servicePrices as $sp)
                                        <option value="{{ $sp->id }}" data-price="{{ $sp->price }}"
                                                data-label="{{ \App\Models\ServicePrice::getLabel($sp->service_type) }} — {{ \App\Models\ServicePrice::getVolumeLabel($sp->volume_tier) }}">
                                            {{ \App\Models\ServicePrice::getLabel($sp->service_type) }} — {{ \App\Models\ServicePrice::getVolumeLabel($sp->volume_tier) }} — TZS {{ number_format($sp->price) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Same price your clients see on the published price list — no hidden adjustments.</div>
                            </div>
                        @endif

                        <div class="price-summary mb-3">
                            <span class="text-muted">Price per pickup, per client</span>
                            <span class="amount" id="price_display">TZS 0</span>
                        </div>
                        <input type="hidden" name="contractor_adjusted_fee" id="contractor_adjusted_fee" value="">
                        <input type="hidden" name="billing_rate_change_reason" id="billing_rate_change_reason" value="">
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('schedules.index') }}" class="btn-secondary-custom">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle me-1"></i> Create Schedule
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
const allClientsData = @json($clients);

/* ── route → clients ─────────────────────────────────── */
document.getElementById('route_select').addEventListener('change', function () {
    const route = this.value;
    const list = document.getElementById('clientsList');
    list.innerHTML = '';

    if (!route) {
        list.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-signpost fs-3 d-block mb-2"></i>Select a route above to load its clients.</div>';
        updateCount();
        return;
    }

    const matched = allClientsData.filter(c => (c.route || '') === route);

    if (matched.length === 0) {
        list.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-person-x fs-3 d-block mb-2"></i>No clients assigned to this route yet.</div>';
        updateCount();
        return;
    }

    matched.forEach(client => {
        const div = document.createElement('div');
        div.className = 'form-check mb-2 p-2 border rounded bg-white';
        div.innerHTML = `
            <input class="form-check-input client-checkbox mt-1" type="checkbox"
                   name="client_ids[]" value="${client.id}" checked
                   id="client_${client.id}" onchange="updateCount()">
            <label class="form-check-label ms-2 w-100" for="client_${client.id}" style="cursor:pointer;">
                <strong>${client.name}</strong>
                <small class="text-muted ms-1">${client.ward || client.district || ''}</small>
                ${client.phone ? `<small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>${client.phone}</small>` : ''}
            </label>`;
        list.appendChild(div);
    });
    updateCount();
});

function updateCount() {
    document.getElementById('selected_count').textContent = document.querySelectorAll('.client-checkbox:checked').length;
}
function selectAll()   { document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);  updateCount(); }
function deselectAll() { document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = false); updateCount(); }

/* ── repeat options ──────────────────────────────────── */
const freqDays = { weekly: 7, twice_month: 14, thrice_month: 10, monthly: 30 };

function toggleRepeat() {
    const freq = document.getElementById('frequency').value;
    const row = document.getElementById('repeat_until_row');
    row.style.display = freq === 'once' ? 'none' : '';
    document.getElementById('repeat_until').required = freq !== 'once';
    updateRepeatPreview();
}

function updateRepeatPreview() {
    const freq = document.getElementById('frequency').value;
    const start = document.getElementById('pickup_date').value;
    const until = document.getElementById('repeat_until').value;
    const preview = document.getElementById('repeat_preview');
    if (freq === 'once' || !start || !until) { preview.textContent = ''; return; }

    let count = 1;
    let cursor = new Date(start);
    const end = new Date(until);
    while (count < 31) {
        cursor = new Date(cursor.getTime() + freqDays[freq] * 86400000);
        if (cursor > end) break;
        count++;
    }
    preview.textContent = `≈ ${count} pickup date${count === 1 ? '' : 's'} will be created per client.`;
}

document.getElementById('pickup_date').addEventListener('change', updateRepeatPreview);
document.getElementById('repeat_until').addEventListener('change', updateRepeatPreview);
toggleRepeat();

/* ── pricing from service price list ─────────────────── */
function updatePrice() {
    const select = document.getElementById('service_price_id');
    const opt = select.options[select.selectedIndex];
    const price = opt && opt.dataset.price ? parseFloat(opt.dataset.price) : null;

    document.getElementById('price_display').textContent =
        price !== null ? 'TZS ' + price.toLocaleString() : 'TZS 0';
    document.getElementById('contractor_adjusted_fee').value = price !== null ? price : '';
    document.getElementById('billing_rate_change_reason').value =
        price !== null ? 'Priced from published service price list: ' + opt.dataset.label : '';
}

/* ── submit guard ────────────────────────────────────── */
document.getElementById('scheduleForm').addEventListener('submit', function (e) {
    if (document.querySelectorAll('.client-checkbox:checked').length === 0) {
        e.preventDefault();
        alert('Please select at least one client.');
        return;
    }
    const priceSelect = document.getElementById('service_price_id');
    if (priceSelect && !priceSelect.value) {
        e.preventDefault();
        alert('Please choose a service from your price list.');
    }
});
</script>
@endsection
