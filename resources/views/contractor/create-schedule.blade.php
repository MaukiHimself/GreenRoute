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

                    <!-- Route Selection -->
                    <div class="mb-4">
                        <label for="route_type" class="form-label">
                            Schedule Type <span class="required-star">*</span>
                        </label>
                        <select name="route_type" id="route_type" required class="form-select" onchange="loadRouteClients()">
                            <option value="">Select schedule type</option>
                            @php
                                $routes = $clients->whereNotNull('route')->pluck('route')->unique()->sort();
                            @endphp
                            @foreach($routes as $route)
                            <option value="existing" data-route="{{ $route }}">{{ $route }} (Multi-Client)</option>
                            @endforeach
                            <option value="custom">Custom Route (Single Client)</option>
                        </select>
                    </div>

                    <!-- Custom Route Name (for custom schedules) -->
                    <div class="mb-4" id="customRouteNameSection" style="display: none;">
                        <label for="custom_route_name" class="form-label">
                            Route Name <span class="required-star">*</span>
                        </label>
                        <input type="text" name="custom_route_name" id="custom_route_name" 
                               class="form-control" placeholder="e.g., Route A, Downtown Route, Emergency Pickup">
                        <small class="text-muted">Enter a descriptive name for this route</small>
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

                    <!-- Custom Client Selection (for custom route) -->
                    <div class="mb-4" id="customClientSection" style="display: none;">
                        <label for="custom_client_id" class="form-label">
                            Select Client <span class="required-star">*</span>
                        </label>
                        <select name="custom_client_id" id="custom_client_id" class="form-select" onchange="loadSingleClientAddress()">
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" 
                                    data-address="{{ $client->address }}"
                                    data-city="{{ $client->city }}"
                                    data-state="{{ $client->state }}"
                                    data-zip="{{ $client->zip_code }}">
                                {{ $client->name }} - {{ $client->address }}
                            </option>
                            @endforeach
                        </select>
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
// Store all clients data
const allClientsData = @json($clients);

function loadRouteClients() {
    const routeTypeSelect = document.getElementById('route_type');
    const selectedOption = routeTypeSelect.options[routeTypeSelect.selectedIndex];
    const routeType = routeTypeSelect.value;
    
    const clientsSection = document.getElementById('clientsSection');
    const customClientSection = document.getElementById('customClientSection');
    const customRouteNameSection = document.getElementById('customRouteNameSection');
    const clientsList = document.getElementById('clientsList');
    const routeInput = document.getElementById('route');
    const customRouteNameInput = document.getElementById('custom_route_name');
    
    // Hide all sections first
    clientsSection.style.display = 'none';
    customClientSection.style.display = 'none';
    customRouteNameSection.style.display = 'none';
    clientsList.innerHTML = '';
    routeInput.value = '';
    
    if (!routeType) return;
    
    if (routeType === 'custom') {
        // Show custom client selection and route name input
        customClientSection.style.display = 'block';
        customRouteNameSection.style.display = 'block';
        customRouteNameInput.required = true;
    } else if (routeType === 'existing') {
        // Get route name from data attribute
        const routeName = selectedOption.dataset.route;
        routeInput.value = routeName;
        customRouteNameInput.required = false;
        
        // Show route clients selection
        clientsSection.style.display = 'block';
        
        // Filter clients by selected route
        const routeClients = allClientsData.filter(client => client.route === routeName);
        
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
}

function toggleAllClients() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.client-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

function loadSingleClientAddress() {
    const select = document.getElementById('custom_client_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('pickup_address').value = option.dataset.address || '';
        document.getElementById('city').value = option.dataset.city || '';
        document.getElementById('state').value = option.dataset.state || '';
        document.getElementById('zip_code').value = option.dataset.zip || '';
    }
}

// Form validation before submit
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const routeType = document.getElementById('route_type').value;
    const customRouteName = document.getElementById('custom_route_name').value;
    const routeInput = document.getElementById('route');
    
    if (routeType === 'custom') {
        const customClient = document.getElementById('custom_client_id').value;
        if (!customClient) {
            e.preventDefault();
            alert('Please select a client');
            return false;
        }
        if (!customRouteName) {
            e.preventDefault();
            alert('Please enter a route name');
            return false;
        }
        // Set the route value from custom route name
        routeInput.value = customRouteName;
    } else if (routeType === 'existing') {
        const checkedClients = document.querySelectorAll('.client-checkbox:checked');
        if (checkedClients.length === 0) {
            e.preventDefault();
            alert('Please select at least one client for this route');
            return false;
        }
    }
});
</script>
@endsection
