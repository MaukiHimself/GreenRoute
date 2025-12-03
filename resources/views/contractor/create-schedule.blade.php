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
                        <label for="site_location" class="form-label">
                            Site Location <span class="required-star">*</span>
                        </label>
                        
                        @if(count($siteLocations) > 0)
                            <select name="site_location" id="site_location" required class="form-select" onchange="loadRoutesBySiteLocation()">
                                <option value="">Select site location</option>
                                @foreach($siteLocations as $location)
                                <option value="{{ $location['full'] }}" 
                                        data-region="{{ $location['region'] }}"
                                        data-district="{{ $location['district'] }}"
                                        data-ward="{{ $location['ward'] }}"
                                        data-street="{{ $location['street'] }}">
                                    {{ $location['full'] }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Format: Region - District - Ward - Street</small>
                        @else
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>No site locations available yet.</strong><br>
                                Please go to <a href="{{ route('route-management.index') }}" class="alert-link">Route Management</a> first and:
                                <ol class="mb-0 mt-2">
                                    <li>Create a new route OR edit an existing route</li>
                                    <li>Assign a site location (Region - District - Ward - Street) to the route</li>
                                    <li>Then return here to create schedules</li>
                                </ol>
                            </div>
                            <select name="site_location" id="site_location" required class="form-select" disabled>
                                <option value="">No locations available - Please create routes first</option>
                            </select>
                        @endif
                    </div>

                    <!-- Route Name Selection (filtered by site location) -->
                    <div class="mb-4" id="routeNameSection" style="display: none;">
                        <label for="route_name" class="form-label">
                            Route Name <span class="required-star">*</span>
                        </label>
                        <select name="route_name" id="route_name" required class="form-select" onchange="loadRouteClients()">
                            <option value="">Select route</option>
                        </select>
                        <small class="text-muted">Routes assigned to the selected location</small>
                    </div>

                    <!-- Hidden field for actual route value -->
                    <input type="hidden" name="route" id="route" value="">

                    <!-- Clients on Selected Route -->
                    <div class="mb-4" id="clientsSection" style="display: none;">
                        <label class="form-label">
                            Select Clients on This Route <span class="required-star">*</span>
                        </label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto; background: #f8f9fa;">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAllClients()">
                                <label class="form-check-label fw-bold" for="selectAll">
                                    Select All Clients
                                </label>
                            </div>
                            <hr>
                            <div id="clientsList"></div>
                        </div>
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

// Function to load routes based on selected site location
function loadRoutesBySiteLocation() {
    const siteLocationSelect = document.getElementById('site_location');
    const selectedOption = siteLocationSelect.options[siteLocationSelect.selectedIndex];
    const selectedLocation = siteLocationSelect.value;
    
    const routeNameSection = document.getElementById('routeNameSection');
    const routeNameSelect = document.getElementById('route_name');
    const clientsSection = document.getElementById('clientsSection');
    
    // Hide sections and clear
    routeNameSection.style.display = 'none';
    clientsSection.style.display = 'none';
    routeNameSelect.innerHTML = '<option value="">Select route</option>';
    document.getElementById('clientsList').innerHTML = '';
    document.getElementById('route').value = '';
    
    if (!selectedLocation) return;
    
    // Get selected location parts
    const region = selectedOption.dataset.region || '';
    const district = selectedOption.dataset.district || '';
    const ward = selectedOption.dataset.ward || '';
    const street = selectedOption.dataset.street || '';
    
    console.log('Selected location:', { region, district, ward, street });
    
    // Filter routes that match this location (handle null/undefined values)
    const matchingRoutes = allRoutesData.filter(route => {
        const regionMatch = route.region === region;
        const districtMatch = route.district === district;
        const wardMatch = (route.ward || '') === ward;
        const streetMatch = (route.street || '') === street;
        
        return regionMatch && districtMatch && wardMatch && streetMatch;
    });
    
    console.log('Matching routes found:', matchingRoutes.length);
    
    if (matchingRoutes.length > 0) {
        routeNameSection.style.display = 'block';
        
        // Populate route dropdown
        matchingRoutes.forEach(route => {
            const option = document.createElement('option');
            option.value = route.route_name;
            option.textContent = route.route_name;
            option.dataset.routeId = route.id;
            routeNameSelect.appendChild(option);
        });
    } else {
        alert('No routes found for this site location. Please create a route in Route Management first and assign it to this location.');
    }
}

// Function to load clients for selected route
function loadRouteClients() {
    const routeNameSelect = document.getElementById('route_name');
    const selectedRouteName = routeNameSelect.value;
    
    const clientsSection = document.getElementById('clientsSection');
    const clientsList = document.getElementById('clientsList');
    const routeInput = document.getElementById('route');
    
    // Hide and clear
    clientsSection.style.display = 'none';
    clientsList.innerHTML = '';
    routeInput.value = '';
    document.getElementById('selectAll').checked = false;
    
    if (!selectedRouteName) return;
    
    // Set hidden route input
    routeInput.value = selectedRouteName;
    
    // Filter clients by selected route
    const routeClients = allClientsData.filter(client => client.route === selectedRouteName);
    
    if (routeClients.length === 0) {
        alert('No clients assigned to this route yet. Please assign clients in Route Management first.');
        return;
    }
    
    // Show clients section
    clientsSection.style.display = 'block';
    
    // Sort by route_sequence if available
    routeClients.sort((a, b) => {
        const seqA = a.route_sequence || 999;
        const seqB = b.route_sequence || 999;
        return seqA - seqB;
    });
    
    // Build checkboxes list
    routeClients.forEach(client => {
        const div = document.createElement('div');
        div.className = 'form-check mb-2';
        div.innerHTML = `
            <input class="form-check-input client-checkbox" type="checkbox" 
                   name="client_ids[]" value="${client.id}" 
                   id="client_${client.id}"
                   data-address="${client.address || ''}"
                   data-city="${client.city || ''}"
                   data-state="${client.state || ''}"
                   data-zip="${client.zip_code || ''}">
            <label class="form-check-label" for="client_${client.id}">
                <strong>${client.name}</strong><br>
                <small class="text-muted">${client.address}, ${client.city}</small>
                ${client.route_sequence ? `<span class="badge bg-secondary ms-2">Seq: ${client.route_sequence}</span>` : ''}
            </label>
        `;
        clientsList.appendChild(div);
    });
}

function toggleAllClients() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.client-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

// Form validation before submit
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const siteLocation = document.getElementById('site_location').value;
    const routeName = document.getElementById('route_name').value;
    const routeInput = document.getElementById('route');
    
    if (!siteLocation) {
        e.preventDefault();
        alert('Please select a site location');
        return false;
    }
    
    if (!routeName) {
        e.preventDefault();
        alert('Please select a route name');
        return false;
    }
    
    const checkedClients = document.querySelectorAll('.client-checkbox:checked');
    if (checkedClients.length === 0) {
        e.preventDefault();
        alert('Please select at least one client for this route');
        return false;
    }
    
    // Ensure route value is set
    routeInput.value = routeName;
});
</script>
@endsection
