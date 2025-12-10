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
                                    <div class="row g-2 mb-3">
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
                                            <select id="streetSelect" class="form-select" onchange="loadGroupClients()" disabled>
                                                <option value="">Select Street (Optional)</option>
                                            </select>
                                        </div>
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
    
    function loadGroupClients() {
        const region = document.getElementById('regionSelect').value;
        const district = document.getElementById('districtSelect').value;
        const ward = document.getElementById('wardSelect').value;
        const street = document.getElementById('streetSelect').value;
        
        if (!region) return;
        
        // Filter clients based on selection
        const filteredClients = allClients.filter(client => {
            if (client.region !== region) return false;
            if (district && client.district !== district) return false;
            if (ward && client.ward !== ward) return false;
            if (street && client.street !== street) return false;
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
    
    // Dependent Dropdowns (Reuse logic)
    function loadDistricts() {
        const region = document.getElementById('regionSelect').value;
        const districtSelect = document.getElementById('districtSelect');
        districtSelect.innerHTML = '<option value="">Select District</option>';
        districtSelect.disabled = true;
        document.getElementById('wardSelect').innerHTML = '<option value="">Select Ward</option>';
        document.getElementById('wardSelect').disabled = true;
        document.getElementById('streetSelect').innerHTML = '<option value="">Select Street (Optional)</option>';
        document.getElementById('streetSelect').disabled = true;
        
        loadGroupClients(); // Reload with just region filter

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
        
        loadGroupClients();

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
        
        loadGroupClients();

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
    
    // Pre-select clients from session (if coming from Client Groups page)
    const preSelectedIds = @json(session('selected_client_ids', []));
    if (preSelectedIds.length > 0) {
        // Auto-enable group mode
        document.querySelector('input[name="mode"][value="group"]').checked = true;
        toggleMode();
        
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
    </script>
</x-dashboard-layout>