@extends('layouts.contractor-sidebar')

@section('title', 'Edit Route')

@section('styles')
<style>
    :root {
        --primary-teal: #047857;
        --primary-red: #c0392b;
    }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-teal), #059669);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .color-picker-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .color-option {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option.selected {
            border-color: #1e293b;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #1e293b;
        }
        
        .client-checkbox-group {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
        }
        
        .client-item {
            padding: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        
        .client-item:hover {
            background: #f8fafc;
        }
        
        .client-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-pencil me-2"></i>Edit Route
                </h1>
                <p class="mb-0 opacity-90">Update route details and client assignments</p>
            </div>
            <a href="{{ route('route-management.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back to Routes
            </a>
        </div>
    </div>

        <!-- Form -->
        <div class="form-card">
            <form action="{{ route('route-management.update', $contractorRoute) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Route Name <span class="text-danger">*</span></label>
                        <input type="text" name="route_name" class="form-control @error('route_name') is-invalid @enderror" 
                               value="{{ old('route_name', $contractorRoute->route_name) }}" required>
                        @error('route_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Route Color</label>
                        <input type="hidden" name="color" id="selectedColor" value="{{ old('color', $contractorRoute->color) }}">
                        <div class="color-picker-container">
                            @php
                                $colors = ['#047857', '#22c55e', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#64748b'];
                            @endphp
                            @foreach($colors as $color)
                                <div class="color-option {{ $contractorRoute->color == $color ? 'selected' : '' }}" 
                                     style="background-color: {{ $color }}" 
                                     onclick="selectColor('{{ $color }}')"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $contractorRoute->description) }}</textarea>
                </div>

                <!-- Dumping Site (final destination of the route) -->
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-trash3 me-1"></i>Dumping Site (Optional)</label>
                    <select name="dumping_site" class="form-select">
                        <option value="">— None (route ends at last client) —</option>
                        @foreach(collect(config('dumping_sites.sites', []))->where('is_open', true) as $site)
                            <option value="{{ $site['name'] }}" {{ old('dumping_site', $contractorRoute->dumping_site) === $site['name'] ? 'selected' : '' }}>
                                {{ $site['name'] }} (open)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Where the truck offloads. The optimised route ends here: your base → clients → dumping site.</small>
                </div>

                <!-- Site Location Assignment -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Route Site Location <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select id="regionSelect" class="form-select" required onchange="loadDistricts()">
                                <option value="">Select Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}" {{ (old('region', $contractorRoute->region) == $region) ? 'selected' : '' }}>
                                        {{ $region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="districtSelect" class="form-select" required onchange="loadWards()" disabled>
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="wardSelect" class="form-select" required onchange="loadStreets()" disabled>
                                <option value="">Select Ward</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="streetSelect" class="form-select" onchange="updateLocationValue()" disabled>
                                <option value="">Select Street</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="site_location" id="site_location_input" required 
                           value="{{ implode('|', array_filter([$contractorRoute->region, $contractorRoute->district, $contractorRoute->ward, $contractorRoute->street])) }}">
                    <small class="form-text text-muted">Assign this route to a specific site location (Region - District - Ward - Street).</small>
                    @error('site_location')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" 
                               {{ old('is_active', $contractorRoute->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActive">
                            Route is Active
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Assign Clients</label>
                    <div class="mb-3">
                        <input type="text" id="clientSearch" class="form-control" placeholder="Search by name, address, city, or coordinates..." onkeyup="filterClients()">
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                        <span class="ms-3 text-muted" id="selectedCount">0 clients selected</span>
                    </div>
                    <div class="client-checkbox-group">
                        @forelse($allClients as $client)
                            <div class="client-item">
                                <div class="form-check">
                                    <input class="form-check-input client-checkbox" type="checkbox" name="client_ids[]" 
                                           value="{{ $client->id }}" id="client{{ $client->id }}"
                                           {{ in_array($client->id, $assignedClientIds) ? 'checked' : '' }}
                                           onchange="updateCount()">
                                    <label class="form-check-label w-100" for="client{{ $client->id }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <strong>{{ $client->name }}</strong>
                                                <div class="small text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                                                </div>
                                                @if($client->route && $client->route !== $contractorRoute->route_name)
                                                    <span class="badge bg-warning text-dark mt-1">Currently in: {{ $client->route }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-5">
                                                <div class="small">
                                                    <i class="bi bi-geo-alt text-primary"></i>
                                                    <strong>{{ $client->address }}</strong>
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $client->city }}, {{ $client->state }} {{ $client->zip_code }}
                                                </div>
                                                @if($client->latitude && $client->longitude)
                                                    <div class="small text-muted">
                                                        <i class="bi bi-pin-map-fill"></i>
                                                        {{ number_format($client->latitude, 6) }}, {{ number_format($client->longitude, 6) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <span class="badge bg-{{ $client->category == 'residential' ? 'success' : 'info' }}">
                                                    {{ ucfirst($client->category) }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No clients available.</p>
                        @endforelse
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Route
                    </button>
                    <a href="{{ route('route-management.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectColor(color) {
            // Remove selected class from all
            document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
            
            // Add to clicked
            event.target.classList.add('selected');
            
            // Update hidden input
            document.getElementById('selectedColor').value = color;
        }
        
        function updateCount() {
            const checked = document.querySelectorAll('.client-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = checked + ' clients selected';
        }
        
        function selectAll() {
            document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);
            updateCount();
        }
        
        function deselectAll() {
            document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = false);
            updateCount();
        }
        
        function filterClients() {
            const searchTerm = document.getElementById('clientSearch').value.toLowerCase();
            const clientItems = document.querySelectorAll('.client-item');
            
            clientItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        // Dependent Dropdown Logic
        const regionSelect = document.getElementById('regionSelect');
        const districtSelect = document.getElementById('districtSelect');
        const wardSelect = document.getElementById('wardSelect');
        const streetSelect = document.getElementById('streetSelect');
        const siteLocationInput = document.getElementById('site_location_input');
        
        // Initial values
        const initialDistrict = @json($contractorRoute->district);
        const initialWard = @json($contractorRoute->ward);
        const initialStreet = @json($contractorRoute->street);

        function updateLocationValue() {
            const region = regionSelect.value;
            const district = districtSelect.value;
            const ward = wardSelect.value;
            const street = streetSelect.value;

            if (region && district && ward) {
                const parts = [region, district, ward];
                if (street) parts.push(street);
                siteLocationInput.value = parts.join('|');
            } else {
                siteLocationInput.value = '';
            }
        }

        function loadDistricts(selectedValue = null) {
            const region = regionSelect.value;
            districtSelect.innerHTML = '<option value="">Select District</option>';
            districtSelect.disabled = true;
            if (!selectedValue) { // Don't clear if pre-filling chain
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                wardSelect.disabled = true;
                streetSelect.innerHTML = '<option value="">Select Street</option>';
                streetSelect.disabled = true;
            }
            updateLocationValue();

            if (region) {
                fetch(`/location/districts?region=${encodeURIComponent(region)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            data.data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district;
                                option.textContent = district;
                                if (selectedValue && district === selectedValue) {
                                    option.selected = true;
                                }
                                districtSelect.appendChild(option);
                            });
                            districtSelect.disabled = false;
                            
                            // If we just set the value, trigger next level
                            if (selectedValue) {
                                loadWards(initialWard);
                            }
                        }
                    })
                    .catch(error => console.error('Error loading districts:', error));
            }
        }

        function loadWards(selectedValue = null) {
            const region = regionSelect.value;
            const district = districtSelect.value;
            wardSelect.innerHTML = '<option value="">Select Ward</option>';
            wardSelect.disabled = true;
            if (!selectedValue) {
                streetSelect.innerHTML = '<option value="">Select Street</option>';
                streetSelect.disabled = true;
            }
            updateLocationValue();

            if (region && district) {
                fetch(`/location/wards?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            data.data.forEach(ward => {
                                const option = document.createElement('option');
                                option.value = ward;
                                option.textContent = ward;
                                if (selectedValue && ward === selectedValue) {
                                    option.selected = true;
                                }
                                wardSelect.appendChild(option);
                            });
                            wardSelect.disabled = false;
                            
                            if (selectedValue) {
                                loadStreets(initialStreet);
                            }
                        }
                    })
                    .catch(error => console.error('Error loading wards:', error));
            }
        }

        function loadStreets(selectedValue = null) {
            const region = regionSelect.value;
            const district = districtSelect.value;
            const ward = wardSelect.value;
            streetSelect.innerHTML = '<option value="">Select Street</option>';
            streetSelect.disabled = true;
            updateLocationValue();

            if (region && district && ward) {
                fetch(`/location/streets?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}&ward=${encodeURIComponent(ward)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            data.data.forEach(street => {
                                const option = document.createElement('option');
                                option.value = street;
                                option.textContent = street;
                                if (selectedValue && street === selectedValue) {
                                    option.selected = true;
                                }
                                streetSelect.appendChild(option);
                            });
                            streetSelect.disabled = false;
                            
                            // Ensure final value is updated
                            if (selectedValue) {
                                updateLocationValue();
                            }
                        }
                    })
                    .catch(error => console.error('Error loading streets:', error));
            }
        }

        // Initialize
        updateCount();

        // Pre-load if region is selected
        if (regionSelect.value) {
            loadDistricts(initialDistrict);
        }
    </script>
@endsection

@push('scripts')
@endpush
