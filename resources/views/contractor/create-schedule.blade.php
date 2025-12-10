@extends('layouts.contractor-simple')

@section('title', 'Create Schedule')

@section('styles')
<style>
    :root {
        --primary-teal: #055c5c;
        --primary-red: #640404;
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

                    <!-- Contractor Info -->
                    <div class="info-box">
                        <h3><i class="bi bi-person-badge me-2"></i>Your Information</h3>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong style="color: var(--primary-teal);">Registration Number:</strong>
                                <span class="ms-2">{{ $contractor->registration_number }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong style="color: var(--primary-teal);">Assigned Client:</strong>
                                <span class="ms-2">{{ $assignedClient->name ?? 'Not assigned' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Site Location Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Site Location <span class="required-star">*</span></label>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <select id="regionSelect" class="form-select" onchange="loadDistricts()">
                                            <option value="">Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region }}">{{ $region }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="districtSelect" class="form-select" onchange="loadWards()" disabled>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="wardSelect" class="form-select" onchange="loadStreets()" disabled>
                                            <option value="">Select Ward</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="streetSelect" class="form-select" onchange="loadLocationData()" disabled>
                                            <option value="">Select Street (Optional)</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="site_location" id="site_location_input">
                            </div>
                        </div>
                    </div>

                    <!-- Route Name Selection -->
                    <div class="mb-4" id="routeNameSection">
                        <label for="route_name" class="form-label">
                            Route Name <span class="required-star">*</span>
                        </label>
                        <select name="route_name" id="route_name" required class="form-select">
                            <option value="">Select route</option>
                        </select>
                        <small class="text-muted">Select a route for this schedule (filtered by location)</small>
                    </div>

                    <!-- Clients Selection -->
                    <div class="mb-4" id="clientsSection" style="display: none;">
                        <label class="form-label">
                            Select Clients <span class="required-star">*</span>
                        </label>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Clients in selected location</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                            </div>
                        </div>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto; background: #f8f9fa;">
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

                    <div class="mb-4">
                        <label for="pickup_address" class="form-label">
                            Pickup Address <span class="required-star">*</span>
                        </label>
                        <input type="text" name="pickup_address" id="pickup_address" required
                               placeholder="Street address" class="form-control">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">
                                City <span class="required-star">*</span>
                            </label>
                            <input type="text" name="city" id="city" required class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">
                                State <span class="required-star">*</span>
                            </label>
                            <input type="text" name="state" id="state" required class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="zip_code" class="form-label">
                                ZIP Code <span class="required-star">*</span>
                            </label>
                            <input type="text" name="zip_code" id="zip_code" required class="form-control">
                        </div>
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
                        <button type="submit" class="btn-primary-custom" {{ count($siteLocations) == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i> Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Store all clients data and routes data
const allClientsData = @json($clients);
const allRoutesData = @json($routes);

console.log('Clients loaded:', allClientsData.length);
console.log('Routes loaded:', allRoutesData.length);

// Dependent Dropdowns Logic
function loadDistricts() {
    const region = document.getElementById('regionSelect').value;
    const districtSelect = document.getElementById('districtSelect');
    
    // Reset lower dropdowns
    districtSelect.innerHTML = '<option value="">Select District</option>';
    districtSelect.disabled = true;
    document.getElementById('wardSelect').innerHTML = '<option value="">Select Ward</option>';
    document.getElementById('wardSelect').disabled = true;
    document.getElementById('streetSelect').innerHTML = '<option value="">Select Street (Optional)</option>';
    document.getElementById('streetSelect').disabled = true;
    
    // Clear selection
    updateLocationInput();
    loadLocationData(); // Refresh lists based on just Region

    if (region) {
        fetch(`/location/districts?region=${encodeURIComponent(region)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d;
                        opt.textContent = d;
                        districtSelect.appendChild(opt);
                    });
                    districtSelect.disabled = false;
                }
            });
    }
}

function loadWards() {
    const region = document.getElementById('regionSelect').value;
    const district = document.getElementById('districtSelect').value;
    const wardSelect = document.getElementById('wardSelect');
    
    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    wardSelect.disabled = true;
    document.getElementById('streetSelect').innerHTML = '<option value="">Select Street (Optional)</option>';
    document.getElementById('streetSelect').disabled = true;
    
    updateLocationInput();
    loadLocationData();

    if (region && district) {
        fetch(`/location/wards?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(w => {
                        const opt = document.createElement('option');
                        opt.value = w;
                        opt.textContent = w;
                        wardSelect.appendChild(opt);
                    });
                    wardSelect.disabled = false;
                }
            });
    }
}

function loadStreets() {
    const region = document.getElementById('regionSelect').value;
    const district = document.getElementById('districtSelect').value;
    const ward = document.getElementById('wardSelect').value;
    const streetSelect = document.getElementById('streetSelect');
    
    streetSelect.innerHTML = '<option value="">Select Street (Optional)</option>';
    streetSelect.disabled = true;
    
    updateLocationInput();
    loadLocationData();

    if (region && district && ward) {
        fetch(`/location/streets?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}&ward=${encodeURIComponent(ward)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s;
                        opt.textContent = s;
                        streetSelect.appendChild(opt);
                    });
                    streetSelect.disabled = false;
                }
            });
    }
}

function updateLocationInput() {
    const region = document.getElementById('regionSelect').value;
    const district = document.getElementById('districtSelect').value;
    const ward = document.getElementById('wardSelect').value;
    const street = document.getElementById('streetSelect').value;
    
    const parts = [region, district, ward, street].filter(p => p);
    document.getElementById('site_location_input').value = parts.join('|');
}

function loadLocationData() {
    updateLocationInput();
    
    const region = document.getElementById('regionSelect').value;
    const district = document.getElementById('districtSelect').value;
    const ward = document.getElementById('wardSelect').value;
    const street = document.getElementById('streetSelect').value;
    
    if (!region) {
        document.getElementById('routeNameSection').style.display = 'none';
        document.getElementById('clientsSection').style.display = 'none';
        return;
    }
    
    // Filter Routes
    const matchingRoutes = allRoutesData.filter(route => {
        if (route.region !== region) return false;
        if (district && route.district !== district) return false;
        if (ward && route.ward && route.ward !== ward) return false;
        if (street && route.street && route.street !== street) return false;
        return true;
    });
    
    const routeSelect = document.getElementById('route_name');
    routeSelect.innerHTML = '<option value="">Select route</option>';
    matchingRoutes.forEach(route => {
        const option = document.createElement('option');
        option.value = route.route_name;
        option.textContent = route.route_name;
        routeSelect.appendChild(option);
    });
    document.getElementById('routeNameSection').style.display = 'block';
    
    // Filter Clients
    const matchingClients = allClientsData.filter(client => {
        if (client.region !== region) return false;
        if (district && client.district !== district) return false;
        if (ward && client.ward !== ward) return false;
        if (street && client.street !== street) return false;
        return true;
    });
    
    renderClients(matchingClients);
    document.getElementById('clientsSection').style.display = 'block';
}

function renderClients(clients) {
    const list = document.getElementById('clientsList');
    list.innerHTML = '';
    
    if (clients.length === 0) {
        list.innerHTML = '<p class="text-muted text-center py-2">No clients found in this location.</p>';
    } else {
        clients.forEach(client => {
            const div = document.createElement('div');
            div.className = 'form-check mb-2';
            div.innerHTML = `
                <input class="form-check-input client-checkbox" type="checkbox" 
                       name="client_ids[]" value="${client.id}" 
                       id="client_${client.id}"
                       onchange="updateCount()">
                <label class="form-check-label" for="client_${client.id}">
                    <strong>${client.name}</strong> <span class="text-muted">(${client.registration_number})</span><br>
                    <small class="text-muted">${client.address || ''}, ${client.city || ''}</small>
                    ${client.route ? `<span class="badge bg-secondary ms-2">${client.route}</span>` : ''}
                </label>
            `;
            list.appendChild(div);
        });
    }
    updateCount();
}

function updateCount() {
    const count = document.querySelectorAll('.client-checkbox:checked').length;
    document.getElementById('selected_count').textContent = count;
}

function selectAll() {
    document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);
    updateCount();
}

function deselectAll() {
    document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = false);
    updateCount();
}

// Pre-select clients from session (if coming from Client Groups page)
const preSelectedIds = @json(session('selected_client_ids', []));
if (preSelectedIds.length > 0) {
    // Wait for clients to be rendered, then select them
    setTimeout(() => {
        preSelectedIds.forEach(id => {
            const checkbox = document.querySelector(`.client-checkbox[value="${id}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        updateCount();
    }, 500);
}

// Form validation
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const region = document.getElementById('regionSelect').value;
    const routeName = document.getElementById('route_name').value;
    
    if (!region) {
        e.preventDefault();
        alert('Please select a Site Location (at least Region).');
        return false;
    }
    
    if (!routeName) {
        e.preventDefault();
        alert('Please select a Route Name.');
        return false;
    }
    
    const checkedClients = document.querySelectorAll('.client-checkbox:checked');
    if (checkedClients.length === 0) {
        e.preventDefault();
        alert('Please select at least one client.');
        return false;
    }
});
</script>
@endsection
