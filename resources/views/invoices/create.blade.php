<x-dashboard-layout title="Create Invoice">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('invoices.index') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
        <li class="breadcrumb-item active">Create</li>
    </x-slot>

    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create New Invoice</h4>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf
                    
                    <!-- Mode Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Invoice Mode</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="mode_single" value="single" checked onchange="toggleMode()">
                                <label class="form-check-label" for="mode_single">
                                    Single Client
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="mode_group" value="group" onchange="toggleMode()">
                                <label class="form-check-label" for="mode_group">
                                    Group Invoice (by Location)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Single Client Selection -->
                        <div class="col-md-6" id="single_client_section">
                            <label for="client_id" class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select">
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} - {{ $client->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Group Selection Section -->
                        <div class="col-12" id="group_section" style="display: none;">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3">Select Location Group</h6>
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
                                            <label class="form-label mb-0 fw-bold">Select Clients to Invoice</label>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
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
                            <label for="schedule_id" class="form-label">Related Schedule (Optional)</label>
                            <select name="schedule_id" id="schedule_id" class="form-select">
                                <option value="">No related schedule</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->client->name }} - {{ $schedule->pickup_date->format('M d, Y') }} ({{ $schedule->service_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Type *</label>
                            <select name="service_type" id="service_type" class="form-select" required>
                                <option value="">Select service type</option>
                                <option value="Waste Collection" {{ old('service_type') == 'Waste Collection' ? 'selected' : '' }}>Waste Collection</option>
                                <option value="Recycling" {{ old('service_type') == 'Recycling' ? 'selected' : '' }}>Recycling</option>
                                <option value="Hazardous Waste" {{ old('service_type') == 'Hazardous Waste' ? 'selected' : '' }}>Hazardous Waste</option>
                                <option value="Bulk Pickup" {{ old('service_type') == 'Bulk Pickup' ? 'selected' : '' }}>Bulk Pickup</option>
                                <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="subtotal" class="form-label">Subtotal (TZS) *</label>
                            <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" value="{{ old('subtotal') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tax_rate" class="form-label">Tax Rate (%) *</label>
                            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', '0') }}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Detailed description of services provided...">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Additional notes or payment terms...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><span id="display-subtotal">TZS 0.00</span></div>
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax:</span><span id="display-tax">TZS 0.00</span></div>
                                <div class="border-top pt-2 d-flex justify-content-between"><span class="fw-semibold">Total:</span><span id="display-total" class="fw-bold">TZS 0.00</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .autocomplete-dropdown {
        position: absolute;
        z-index: 1000;
        background: white;
        border: 2px solid #0d6efd;
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
        background: #e7f3ff;
        color: #0d6efd;
        font-weight: 600;
    }
    
    .autocomplete-item:last-child {
        border-bottom: none;
    }
    
    .autocomplete-item.active {
        background: #0d6efd;
        color: white;
        font-weight: 600;
    }
    </style>
    
    <script>
    function toggleMode() {
        const mode = document.querySelector('input[name="mode"]:checked').value;
        const singleSection = document.getElementById('single_client_section');
        const groupSection = document.getElementById('group_section');
        const clientSelect = document.getElementById('client_id');
        
        if (mode === 'group') {
            singleSection.style.display = 'none';
            groupSection.style.display = 'block';
            clientSelect.required = false;
        } else {
            singleSection.style.display = 'block';
            groupSection.style.display = 'none';
            clientSelect.required = true;
        }
    }

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
    
    console.log('Invoice form - Unique locations found:', locationList.length);
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
                        <strong>${client.name}</strong> <span class="text-muted">(${client.registration_number})</span><br>
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
    
    // (Dependent dropdowns removed - using autocomplete instead)

    function calculateTotals() {
        const sub = parseFloat(document.getElementById('subtotal').value) || 0;
        const rate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const tax = sub * (rate/100);
        const total = sub + tax;
        document.getElementById('display-subtotal').textContent = 'TZS ' + sub.toFixed(2);
        document.getElementById('display-tax').textContent = 'TZS ' + tax.toFixed(2);
        document.getElementById('display-total').textContent = 'TZS ' + total.toFixed(2);
    }
    document.getElementById('subtotal').addEventListener('input', calculateTotals);
    document.getElementById('tax_rate').addEventListener('input', calculateTotals);
    calculateTotals();
    </script>
</x-dashboard-layout>