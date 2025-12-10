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
                    <div class="mb-4">
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
                        <button type="submit" class="btn-primary-custom">
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

// Build unique location list from clients
const locationList = [];
const locationMap = new Map();

allClientsData.forEach(client => {
    // Build location from available fields
    const parts = [];
    if (client.region) parts.push(client.region);
    if (client.district) parts.push(client.district);
    if (client.ward) parts.push(client.ward);
    if (client.street) parts.push(client.street);
    
    if (parts.length > 0) {
        const locationString = parts.join(' → ');
        const locationKey = parts.join('|');
        
        if (!locationMap.has(locationKey)) {
            const locData = {
                display: locationString,
                region: client.region || '',
                district: client.district || '',
                ward: client.ward || '',
                street: client.street || '',
                key: locationKey
            };
            locationMap.set(locationKey, locData);
            locationList.push(locData);
        }
    }
});

console.log('Unique locations found:', locationList.length);
console.log('Sample locations:', locationList.slice(0, 5).map(l => l.display));

// Sort locations alphabetically
locationList.sort((a, b) => a.display.localeCompare(b.display));

// Autocomplete functionality
const autocompleteInput = document.getElementById('locationAutocomplete');
const dropdown = document.getElementById('locationDropdown');
let currentFocus = -1;
let selectedLocation = null;

function showLocationDropdown(searchTerm = '') {
    dropdown.innerHTML = '';
    currentFocus = -1;
    
    if (locationList.length === 0) {
        dropdown.innerHTML = '<div class="autocomplete-item" style="color: #999;">No client locations available. Please ensure clients have location data.</div>';
        dropdown.classList.add('show');
        return;
    }
    
    const search = searchTerm.toLowerCase().trim();
    let filtered = locationList;
    
    if (search.length > 0) {
        filtered = locationList.filter(loc => 
            loc.display.toLowerCase().includes(search) ||
            loc.region.toLowerCase().includes(search) ||
            loc.district.toLowerCase().includes(search) ||
            loc.ward.toLowerCase().includes(search) ||
            loc.street.toLowerCase().includes(search)
        );
    }
    
    if (filtered.length === 0) {
        dropdown.innerHTML = `<div class="autocomplete-item" style="color: #999;">No locations matching "${searchTerm}"</div>`;
        dropdown.classList.add('show');
        return;
    }
    
    const maxResults = 50;
    const resultsToShow = filtered.slice(0, maxResults);
    
    resultsToShow.forEach((loc, index) => {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.textContent = loc.display;
        item.dataset.index = index;
        
        item.addEventListener('click', function() {
            selectLocation(loc);
        });
        
        dropdown.appendChild(item);
    });
    
    if (filtered.length > maxResults) {
        const moreItem = document.createElement('div');
        moreItem.className = 'autocomplete-item';
        moreItem.style.color = '#999';
        moreItem.style.fontStyle = 'italic';
        moreItem.textContent = `+ ${filtered.length - maxResults} more locations (refine your search)`;
        dropdown.appendChild(moreItem);
    }
    
    dropdown.classList.add('show');
}

autocompleteInput.addEventListener('input', function() {
    showLocationDropdown(this.value);
});

// Show all locations when clicking the input field
autocompleteInput.addEventListener('focus', function() {
    if (this.value.trim() === '') {
        showLocationDropdown('');
    }
});

// Keyboard navigation
autocompleteInput.addEventListener('keydown', function(e) {
    const items = dropdown.querySelectorAll('.autocomplete-item');
    
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        currentFocus++;
        if (currentFocus >= items.length) currentFocus = 0;
        setActive(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        currentFocus--;
        if (currentFocus < 0) currentFocus = items.length - 1;
        setActive(items);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (currentFocus > -1 && items[currentFocus]) {
            items[currentFocus].click();
        }
    } else if (e.key === 'Escape') {
        dropdown.classList.remove('show');
    }
});

function setActive(items) {
    items.forEach((item, index) => {
        if (index === currentFocus) {
            item.classList.add('active');
            item.scrollIntoView({ block: 'nearest' });
        } else {
            item.classList.remove('active');
        }
    });
}

function selectLocation(location) {
    selectedLocation = location;
    autocompleteInput.value = location.display;
    dropdown.classList.remove('show');
    
    // Update hidden input
    const parts = [location.region, location.district, location.ward, location.street].filter(p => p);
    document.getElementById('site_location_input').value = parts.join('|');
    
    // Load routes and clients for this location
    loadLocationData(location);
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!autocompleteInput.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

function loadLocationData(location) {
    if (!location || !location.region) {
        document.getElementById('routeNameSection').style.display = 'none';
        document.getElementById('clientsSection').style.display = 'none';
        return;
    }
    
    // Filter Routes
    const matchingRoutes = allRoutesData.filter(route => {
        if (route.region !== location.region) return false;
        if (location.district && route.district !== location.district) return false;
        if (location.ward && route.ward && route.ward !== location.ward) return false;
        if (location.street && route.street && route.street !== location.street) return false;
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
        if (client.region !== location.region) return false;
        if (location.district && client.district !== location.district) return false;
        if (location.ward && client.ward !== location.ward) return false;
        if (location.street && client.street !== location.street) return false;
        return true;
    });
    
    renderClients(matchingClients);
    document.getElementById('clientsSection').style.display = 'block';
}

function renderClients(clients) {
    const list = document.getElementById('clientsList');
    list.innerHTML = '';
    
    console.log(`Rendering ${clients.length} clients for selected location`);
    
    if (clients.length === 0) {
        list.innerHTML = '<p class="text-muted text-center py-3"><i class="bi bi-info-circle me-2"></i>No clients found in this location.</p>';
    } else {
        // Add info header
        const infoDiv = document.createElement('div');
        infoDiv.className = 'alert alert-info py-2 mb-3';
        infoDiv.innerHTML = `<i class="bi bi-people-fill me-2"></i><strong>${clients.length}</strong> client${clients.length !== 1 ? 's' : ''} found in this location`;
        list.appendChild(infoDiv);
        
        // Render each client
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

// Form validation
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const locationInput = document.getElementById('site_location_input').value;
    const routeName = document.getElementById('route_name').value;
    
    if (!locationInput || !selectedLocation) {
        e.preventDefault();
        alert('Please select a Site Location from the autocomplete dropdown.');
        autocompleteInput.focus();
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
