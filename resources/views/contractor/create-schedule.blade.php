@extends('layouts.contractor-sidebar')

@section('title', 'Create Schedule')

@section('styles')
<style>
    :root {
        --primary-teal: #055c5c;
        --primary-red: #c0392b;
        --white: #ffffff;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-teal) 0%, #077777 100%);
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

    .info-box {
        background: linear-gradient(135deg, #f0f9f9 0%, #e6f4f4 100%);
        border-left: 4px solid var(--primary-teal);
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box h3 {
        color: var(--primary-teal);
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
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
        background: #044a4a;
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

    /* Autocomplete Dropdown Styles */
    .autocomplete-dropdown {
        position: absolute;
        z-index: 1000;
        background: white;
        border: 2px solid var(--primary-teal);
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 300px;
        overflow-y: auto;
        width: 100%; /* Fix overflow: Use 100% of parent container */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        box-sizing: border-box; /* Ensure padding/border is included in width */
    }

    .autocomplete-dropdown.show {
        display: block;
    }

    .autocomplete-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s;
        white-space: normal; /* Allow text wrapping */
        word-break: break-word; /* Break long words if needed */
    }

    .autocomplete-item:hover {
        background: #f0f9f9;
        color: var(--primary-teal);
        font-weight: 600;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-item.active {
        background: var(--primary-teal);
        color: white;
        font-weight: 600;
    }

    #locationAutocomplete {
        position: relative;
    }

    #locationAutocomplete:focus {
        border-color: var(--primary-teal);
        box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
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
                <p class="mb-0" style="opacity: 0.95;">Schedule will be automatically visible to your assigned client</p>
            </div>

            <!-- Form Container -->
            <div class="form-container p-4 p-md-5">
                <form id="scheduleForm" method="POST" action="{{ route('schedules.store') }}">
                    @csrf

                    <!-- Contractor Info Removed -->

                    <!-- Site Location Selection -->
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-bold">Site Location <span class="required-star">*</span></label>
                        <input type="text"
                               id="locationAutocomplete"
                               class="form-control"
                               placeholder="Click here or start typing to search locations..."
                               autocomplete="off"
                               required>
                        <input type="hidden" name="site_location" id="site_location_input">
                        <div id="locationDropdown" class="autocomplete-dropdown"></div>
                        <small class="text-muted"><i class="bi bi-info-circle"></i> Select a location to load clients. Format: Region → District → Ward → Street</small>
                    </div>

                    <!-- Route Name Selection Removed -->

                    <!-- Clients Selection -->
                    <div class="mb-4" id="clientsSection">
                        <label class="form-label">
                            Select Clients <span class="required-star">*</span>
                        </label>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small" id="clientsLabel">All your clients — select a location above to filter</span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="showAllBtn" onclick="showAllClients()" style="display:none!important;">
                                    <i class="bi bi-people me-1"></i>Show All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                            </div>
                        </div>
                        <div class="border rounded p-3" style="max-height: 320px; overflow-y: auto; background: #f8f9fa;">
                            <div id="clientsList"></div>
                        </div>
                        <div class="form-text text-muted mt-1"><span id="selected_count">0</span> clients selected</div>
                    </div>


                    <!-- Schedule Details -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="pickup_date" class="form-label">
                                Pickup Date <span class="required-star">*</span>
                            </label>
                            <input type="date" name="pickup_date" id="pickup_date" required
                                   min="{{ date('Y-m-d') }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pickup_time" class="form-label">
                                Pickup Time <span class="required-star">*</span>
                            </label>
                            <input type="time" name="pickup_time" id="pickup_time" required class="form-control">
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="mb-4">
                        <label for="pickup_location" class="form-label">
                            Pickup Location Name <span class="required-star">*</span>
                        </label>
                        <input type="text" name="pickup_location" id="pickup_location" required
                               placeholder="e.g., Main Office, Warehouse A" class="form-control">
                    </div>


                    <!-- Service Details -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label">
                                Service Type <span class="required-star">*</span>
                            </label>
                            <select name="service_type" id="service_type" required class="form-select">
                                <option value="collection">Collection</option>
                                <option value="disposal">Disposal</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estimated_duration" class="form-label">
                                Estimated Duration (hours)
                            </label>
                            <input type="number" name="estimated_duration" id="estimated_duration"
                                   step="0.5" min="0" class="form-control">
                        </div>
                    </div>

                    <!-- Billing Rate -->
                    <div class="mb-4">
                        <label for="billing_rate_id" class="form-label">
                            Official Billing Rate
                        </label>
                        <select name="billing_rate_id" id="billing_rate_id" class="form-select">
                            <option value="">Select an official billing rate</option>
                            @foreach($billingRates as $rate)
                                <option value="{{ $rate->id }}" data-fee="{{ $rate->collection_fee }}">
                                    {{ $rate->label }} - TZS {{ number_format($rate->collection_fee, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Official rates come from the admin billing rate table. Leave blank to enter a contractor price manually.</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="contractor_adjusted_fee" class="form-label">
                                Contractor Adjusted Price (TZS)
                            </label>
                            <input type="number" name="contractor_adjusted_fee" id="contractor_adjusted_fee"
                                   step="0.01" min="0" class="form-control"
                                   placeholder="Leave blank to use the official rate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schedule_price_preview" class="form-label">
                                Schedule Price (TZS)
                            </label>
                            <input type="text" name="schedule_price_preview" id="schedule_price_preview"
                                   class="form-control" readonly value="TZS 0.00">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="billing_rate_change_reason" class="form-label">
                            Reason for Rate Selection or Price Override
                        </label>
                        <textarea name="billing_rate_change_reason" id="billing_rate_change_reason" rows="3"
                                  class="form-control"
                                  placeholder="Example: Client requested additional service, difficult access, or volume difference"></textarea>
                    </div>

                    <!-- Additional Fields -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="total_volume" class="form-label">
                                Total Volume (cubic yards)
                            </label>
                            <input type="number" name="total_volume" id="total_volume"
                                   step="0.1" min="0" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="disposal_site" class="form-label">
                                Disposal Site
                            </label>
                            <input type="text" name="disposal_site" id="disposal_site"
                                   placeholder="e.g., Landfill A, Recycling Center" class="form-control">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                                  placeholder="Special instructions or additional information"
                                  class="form-control"></textarea>
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
const billingRates = @json($billingRatesData);

const autocompleteInput = document.getElementById('locationAutocomplete');
const dropdown          = document.getElementById('locationDropdown');
let currentFocus  = -1;
let selectedLocation = null;
let searchTimeout = null;

/* ── helpers ─────────────────────────────────────────── */
const eq  = (a, b) => (a || '').trim().toLowerCase() === (b || '').trim().toLowerCase();
const has = v => v && String(v).trim() !== '';

/* ── initial render: show all clients on page load ────── */
document.addEventListener('DOMContentLoaded', function () {
    renderClients(allClientsData, 'All your clients — select a location above to filter');
});

/* ── autocomplete API calls ───────────────────────────── */
function showLoadingDropdown() {
    dropdown.innerHTML = '<div class="autocomplete-item" style="color:#999;"><i class="bi bi-hourglass-split me-2"></i>Searching…</div>';
    dropdown.classList.add('show');
}

function buildDropdown(results, searchTerm) {
    dropdown.innerHTML = '';
    currentFocus = -1;

    if (!results || results.length === 0) {
        dropdown.innerHTML = `<div class="autocomplete-item" style="color:#999;">No locations matching "${searchTerm}"</div>`;
        dropdown.classList.add('show');
        return;
    }

    results.forEach(loc => {
        let display, region, district, ward, street;
        if (typeof loc === 'object' && loc !== null && 'value' in loc) {
            display = loc.value; region = loc.region||''; district = loc.district||''; ward = loc.ward||''; street = loc.street||'';
        } else {
            display = String(loc); region = display; district = ''; ward = ''; street = '';
        }
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.textContent = display;
        item.addEventListener('click', () => selectLocation({ display, region, district, ward, street }));
        dropdown.appendChild(item);
    });
    dropdown.classList.add('show');
}

autocompleteInput.addEventListener('input', function () {
    const query = this.value.trim();
    clearTimeout(searchTimeout);
    dropdown.classList.remove('show');
    if (query.length < 2) return;
    showLoadingDropdown();
    searchTimeout = setTimeout(() => {
        fetch(`/location/autocomplete?q=${encodeURIComponent(query)}&type=all&limit=30`)
            .then(r => r.json())
            .then(data => {
                if (data.success) buildDropdown(data.data, query);
                else { dropdown.innerHTML = '<div class="autocomplete-item" style="color:#999;">Could not load locations.</div>'; dropdown.classList.add('show'); }
            })
            .catch(() => { dropdown.innerHTML = '<div class="autocomplete-item" style="color:#999;">Error loading locations.</div>'; dropdown.classList.add('show'); });
    }, 300);
});

autocompleteInput.addEventListener('focus', function () {
    if (this.value.trim() === '') {
        fetch('/location/regions')
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data.length)
                    buildDropdown(data.data.map(r => ({ value: r, region: r, district: '', ward: '', street: '' })), '');
            })
            .catch(() => {});
    }
});

autocompleteInput.addEventListener('keydown', function (e) {
    const items = dropdown.querySelectorAll('.autocomplete-item');
    if (e.key === 'ArrowDown')  { e.preventDefault(); currentFocus = (currentFocus+1) % items.length; setActive(items); }
    else if (e.key === 'ArrowUp')   { e.preventDefault(); currentFocus = (currentFocus-1+items.length) % items.length; setActive(items); }
    else if (e.key === 'Enter') { e.preventDefault(); if (currentFocus > -1 && items[currentFocus]) items[currentFocus].click(); }
    else if (e.key === 'Escape') dropdown.classList.remove('show');
});

function setActive(items) {
    items.forEach((item, i) => {
        item.classList.toggle('active', i === currentFocus);
        if (i === currentFocus) item.scrollIntoView({ block: 'nearest' });
    });
}

function selectLocation(location) {
    selectedLocation = location;
    autocompleteInput.value = location.display;
    dropdown.classList.remove('show');
    const parts = [location.region, location.district, location.ward, location.street].filter(p => p);
    document.getElementById('site_location_input').value = parts.join('|');
    loadLocationData(location);
}

document.addEventListener('click', function (e) {
    if (!autocompleteInput.contains(e.target) && !dropdown.contains(e.target))
        dropdown.classList.remove('show');
});

/* ── client filtering ─────────────────────────────────── */
function loadLocationData(location) {
    if (!location || !location.region) {
        renderClients(allClientsData, 'All your clients');
        document.getElementById('showAllBtn').style.display = 'none';
        return;
    }

    const matched = allClientsData.filter(client => {
        if (!eq(client.region, location.region)) return false;
        if (has(location.district) && has(client.district) && !eq(client.district, location.district)) return false;
        if (has(location.ward)     && has(client.ward)     && !eq(client.ward,     location.ward))     return false;
        if (has(location.street)   && has(client.street)   && !eq(client.street,   location.street))   return false;
        return true;
    });

    const label = `Clients in <strong>${location.display}</strong>`;
    renderClients(matched, label, true);

    // Show "Show All" button only when filtered
    const btn = document.getElementById('showAllBtn');
    btn.style.cssText = matched.length < allClientsData.length ? '' : 'display:none!important;';
}

function showAllClients() {
    selectedLocation = null;
    autocompleteInput.value = '';
    document.getElementById('site_location_input').value = '';
    renderClients(allClientsData, 'All your clients — select a location above to filter');
    document.getElementById('showAllBtn').style.cssText = 'display:none!important;';
}

/* ── render ───────────────────────────────────────────── */
function renderClients(clients, labelHtml, isFiltered = false) {
    document.getElementById('clientsLabel').innerHTML = labelHtml || '';
    const list = document.getElementById('clientsList');
    list.innerHTML = '';

    if (!clients || clients.length === 0) {
        list.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                ${isFiltered
                    ? 'No clients in this location. <a href="javascript:showAllClients()" class="text-primary">Show all clients</a> or <a href="{{ route("contractor.clients.create") }}" class="text-primary">add a new client</a>.'
                    : 'No clients yet. <a href="{{ route("contractor.clients.create") }}" class="text-primary">Add your first client</a>.'}
            </div>`;
        updateCount();
        return;
    }

    const badge = `<span class="badge bg-info text-dark ms-2">${clients.length} client${clients.length !== 1 ? 's' : ''}</span>`;
    const infoDiv = document.createElement('div');
    infoDiv.className = 'alert alert-info py-2 mb-2 d-flex align-items-center';
    infoDiv.innerHTML = `<i class="bi bi-people-fill me-2"></i>${badge} found`;
    list.appendChild(infoDiv);

    clients.forEach(client => {
        const locParts = [client.region, client.district, client.ward, client.street].filter(p => p && String(p).trim());
        const locStr   = locParts.length ? locParts.join(' → ') : (client.city || 'No location set');

        const div = document.createElement('div');
        div.className = 'form-check mb-2 p-2 border rounded bg-white';
        div.innerHTML = `
            <input class="form-check-input client-checkbox mt-1" type="checkbox"
                   name="client_ids[]" value="${client.id}"
                   id="client_${client.id}" onchange="updateCount()">
            <label class="form-check-label ms-2 w-100" for="client_${client.id}" style="cursor:pointer;">
                <div>
                    <strong>${client.name}</strong>
                    <span class="text-muted small ms-1">(${client.registration_number || 'N/A'})</span>
                    ${client.route ? `<span class="badge bg-secondary ms-1">${client.route}</span>` : '<span class="badge bg-warning text-dark ms-1">No Route</span>'}
                </div>
                <small class="text-muted d-block"><i class="bi bi-geo-alt me-1"></i>${locStr}</small>
                ${client.phone ? `<small class="text-muted"><i class="bi bi-telephone me-1"></i>${client.phone}</small>` : ''}
            </label>`;
        list.appendChild(div);
    });
    updateCount();
}

function updateCount() {
    document.getElementById('selected_count').textContent = document.querySelectorAll('.client-checkbox:checked').length;
}
function selectAll()   { document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);  updateCount(); }
function deselectAll() { document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = false); updateCount(); }

function updateBillingPreview() {
    const rateSelect = document.getElementById('billing_rate_id');
    const overrideInput = document.getElementById('contractor_adjusted_fee');
    const preview = document.getElementById('schedule_price_preview');
    const selectedOption = rateSelect.options[rateSelect.selectedIndex];
    const officialFee = selectedOption.dataset.fee ? parseFloat(selectedOption.dataset.fee) : null;
    const overrideFee = overrideInput.value.trim() === '' ? null : parseFloat(overrideInput.value);
    const finalFee = overrideFee !== null ? overrideFee : officialFee;

    preview.value = finalFee !== null ? 'TZS ' + finalFee.toFixed(2) : 'TZS 0.00';
}

document.getElementById('billing_rate_id').addEventListener('change', updateBillingPreview);
document.getElementById('contractor_adjusted_fee').addEventListener('input', updateBillingPreview);

document.getElementById('scheduleForm').addEventListener('submit', function (e) {
    if (document.querySelectorAll('.client-checkbox:checked').length === 0) {
        e.preventDefault();
        alert('Please select at least one client.');
    }
});
</script>
