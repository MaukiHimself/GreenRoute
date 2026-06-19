<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .primary-dark { color: #055c5c; }
        .primary-light { 
            background-color: rgba(5, 92, 92, 0.08);
            border-left: 4px solid #055c5c;
        }
        .accent-color { color: #640404; }
        .btn-primary-custom {
            background-color: #055c5c;
            border-color: #055c5c;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #044a4a;
            border-color: #044a4a;
        }
        .btn-outline-custom {
            border-color: #055c5c;
            color: #055c5c;
        }
        .btn-outline-custom:hover {
            background-color: #055c5c;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #055c5c;
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            border: 1px solid rgba(5, 92, 92, 0.1);
        }
        
        /* Autocomplete Dropdown Styles */
        .autocomplete-dropdown {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 2px solid #055c5c;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 300px;
            overflow-y: auto;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
            box-sizing: border-box;
        }
        
        .autocomplete-dropdown.show {
            display: block;
        }
        
        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
            white-space: normal;
            word-break: break-word;
        }
        
        .autocomplete-item:hover {
            background: #f0f9f9;
            color: #055c5c;
            font-weight: 600;
        }
        
        .autocomplete-item.active {
            background: #055c5c;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 primary-light rounded">
            <h4 class="primary-dark mb-0">Create Invoice</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-custom btn-sm d-flex align-items-center gap-2" target="_parent">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
                <button onclick="window.history.back()" class="btn btn-outline-custom btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
        </div>

        <!-- Invoice Form -->
        <div class="info-card">
            <form method="POST" action="{{ route('billing.store') }}">
                @csrf
                
                <!-- Invoice Mode Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold primary-dark">Invoice Mode</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode_single" value="single" 
                                   {{ old('mode', 'single') == 'single' ? 'checked' : '' }} onchange="toggleMode()">
                            <label class="form-check-label" for="mode_single">
                                Single Client
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mode" id="mode_group" value="group" 
                                   {{ old('mode') == 'group' ? 'checked' : '' }} onchange="toggleMode()">
                            <label class="form-check-label" for="mode_group">
                                Group Invoice (by Location)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6" id="single_client_section">
                        <label for="client_id" class="form-label">Client *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} - {{ $client->category }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Group Selection Section -->
                    <div class="col-12" id="group_section" style="display: none;">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-3 primary-dark">Select Location Group</h6>
                                <div class="mb-3 position-relative">
                                    <input type="text" 
                                           id="locationAutocomplete" 
                                           class="form-control" 
                                           placeholder="Type to search location (e.g., ARUSHA → ARUMERU → Ward → Street)"
                                           autocomplete="off">
                                    <div id="locationDropdown" class="autocomplete-dropdown"></div>
                                    <small class="text-muted">Search and select a location in format: Region → District → Ward → Street</small>
                                </div>
                                
                                <div id="clients_list_container" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label mb-0 fw-bold primary-dark">Select Clients to Invoice</label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-custom" onclick="selectAll()">Select All</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                                        </div>
                                    </div>
                                    <div class="border rounded p-3 bg-white" style="max-height: 250px; overflow-y: auto;">
                                        <div id="clients_list"></div>
                                    </div>
                                    <div class="form-text text-muted mt-1"><span id="selected_count">0</span> clients selected</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="service_type" class="form-label">Service Type *</label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type" required>
                            <option value="">Select Service</option>
                            <option value="waste_collection" {{ old('service_type') == 'waste_collection' ? 'selected' : '' }}>Waste Collection</option>
                            <option value="disposal" {{ old('service_type') == 'disposal' ? 'selected' : '' }}>Waste Disposal</option>
                            <option value="recycling" {{ old('service_type') == 'recycling' ? 'selected' : '' }}>Recycling</option>
                            <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        </select>
                        @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="service_price_id" class="form-label">Saved Service Price</label>
                        <select class="form-select @error('service_price_id') is-invalid @enderror" id="service_price_id" name="service_price_id" data-price="0">
                            <option value="">Select saved service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ old('service_price_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_type }} - {{ $service->volume_tier ? $service->volume_tier : 'Standard' }} - TZS {{ number_format($service->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_price_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="product_id" class="form-label">Equipment</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" data-price="0">
                            <option value="">Select equipment</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}" data-price="{{ $equipment->price ?? 0 }}" {{ old('product_id') == $equipment->id ? 'selected' : '' }}>
                                    {{ $equipment->name }} - TZS {{ number_format($equipment->price ?? 0, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6" id="subtotal_section">
                        <label for="subtotal" class="form-label">Manual Subtotal (TZS)</label>
                        <input type="number" class="form-control @error('subtotal') is-invalid @enderror" id="subtotal" name="subtotal" step="0.01" value="{{ old('subtotal') }}">
                        <div class="form-text text-muted">Leave blank when using saved services/equipment; selected item prices are added automatically.</div>
                        @error('subtotal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="due_date" class="form-label">Due Date *</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle"></i> Create Invoice
                    </button>
                    <a href="{{ route('billing.index') }}" class="btn btn-outline-custom">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleMode() {
        const mode = document.querySelector('input[name="mode"]:checked').value;
        const singleSection = document.getElementById('single_client_section');
        const groupSection = document.getElementById('group_section');
        const clientSelect = document.getElementById('client_id');
        const subtotalSection = document.getElementById('subtotal_section');
        const subtotalInput = document.getElementById('subtotal');
        
        if (mode === 'group') {
            singleSection.style.display = 'none';
            groupSection.style.display = 'block';
            clientSelect.required = false;
            
            // Hide subtotal for group mode (calculated automatically)
            subtotalSection.style.display = 'none';
            subtotalInput.required = false;
        } else {
            singleSection.style.display = 'block';
            groupSection.style.display = 'none';
            clientSelect.required = true;
            
            // Show subtotal for single mode
            subtotalSection.style.display = 'block';
            subtotalInput.required = false;
        }

        updateSelectedItems();
    }

    function updateSelectedItems() {
        const serviceSelect = document.getElementById('service_price_id');
        const equipmentSelect = document.getElementById('product_id');
        const subtotalInput = document.getElementById('subtotal');
        const mode = document.querySelector('input[name="mode"]:checked').value;

        if (!serviceSelect || !equipmentSelect || !subtotalInput || mode === 'group') {
            return;
        }

        const selectedServicePrice = parseFloat(serviceSelect.options[serviceSelect.selectedIndex]?.dataset.price || 0) || 0;
        const selectedEquipmentPrice = parseFloat(equipmentSelect.options[equipmentSelect.selectedIndex]?.dataset.price || 0) || 0;
        const selectedItemsTotal = selectedServicePrice + selectedEquipmentPrice;
        const manualSubtotal = parseFloat(subtotalInput.value) || 0;

        if (selectedItemsTotal > 0) {
            subtotalInput.value = (manualSubtotal + selectedItemsTotal).toFixed(2);
        }
    }

    document.getElementById('service_price_id')?.addEventListener('change', updateSelectedItems);
    document.getElementById('product_id')?.addEventListener('change', updateSelectedItems);

    // Get clients data from server
    const allClients = @json($clients);
    
    // Build unique location list from clients
    const locationList = [];
    const locationMap = new Map();
    
    allClients.forEach(client => {
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
        loadGroupClients(location);
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (autocompleteInput && !autocompleteInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    function loadGroupClients(location) {
        if (!location || !location.region) return;
        
        // Filter clients based on selection
        const filteredClients = allClients.filter(client => {
            if (client.region !== location.region) return false;
            if (location.district && client.district !== location.district) return false;
            if (location.ward && client.ward !== location.ward) return false;
            if (location.street && client.street !== location.street) return false;
            return true;
        });
        
        const listContainer = document.getElementById('clients_list_container');
        const list = document.getElementById('clients_list');
        listContainer.style.display = 'block';
        list.innerHTML = '';
        
        if (filteredClients.length === 0) {
            list.innerHTML = '<p class="text-muted text-center my-2">No clients found in this location.</p>';
        } else {
            filteredClients.forEach(client => {
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input client-checkbox" type="checkbox" name="client_ids[]" value="${client.id}" id="client_${client.id}" onchange="updateCount()">
                    <label class="form-check-label" for="client_${client.id}">
                        <strong>${client.name}</strong> <span class="text-muted">(${client.category})</span><br>
                        <small class="text-muted">${client.street ? client.street + ', ' : ''}${client.ward}</small>
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

    // Initialize mode on page load
    document.addEventListener('DOMContentLoaded', toggleMode);
    </script>
</body>
</html>