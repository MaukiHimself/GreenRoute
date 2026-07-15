<x-dashboard-layout title="Create Invoice">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contractor.clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
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
                                    Group Invoice (by Route)
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
                                    <h6 class="card-title fw-bold mb-3"><i class="bi bi-signpost-split me-1"></i>Select Route</h6>
                                    <div class="mb-3">
                                        <select id="route_select" class="form-select">
                                            <option value="">Choose one of your routes…</option>
                                            @foreach($routes as $routeName)
                                                <option value="{{ $routeName }}">
                                                    {{ $routeName }} ({{ $routeClientCounts[$routeName] ?? 0 }} client{{ ($routeClientCounts[$routeName] ?? 0) == 1 ? '' : 's' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Every client assigned to the route is listed below — untick anyone you don't want to invoice.</small>
                                    </div>

                                    <div id="clients_list_container" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0 fw-bold">Clients to Invoice</label>
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
                                        {{ $schedule->client->name }} - {{ $schedule->pickup_date->format('M d, Y') }} ({{ $schedule->service_type }})@if($schedule->displayed_price !== null) - TZS {{ number_format($schedule->displayed_price, 2) }}@endif
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
                        @if(!$servicePrices->isEmpty())
                        <div class="col-md-6">
                            <label for="price_list_select" class="form-label">Fill Amount From Your Price List</label>
                            <select id="price_list_select" class="form-select" onchange="applyPriceList()">
                                <option value="">Enter amount manually…</option>
                                @foreach($servicePrices as $sp)
                                    <option value="{{ $sp->price }}">
                                        {{ \App\Models\ServicePrice::getLabel($sp->service_type) }} — {{ \App\Models\ServicePrice::getVolumeLabel($sp->volume_tier) }} — TZS {{ number_format($sp->price) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label for="subtotal" class="form-label">Amount (TZS) *</label>
                            <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" value="{{ old('subtotal') }}" class="form-control" required>
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
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Amount per client:</span><span id="display-subtotal">TZS 0.00</span></div>
                                <div class="border-top pt-2 d-flex justify-content-between"><span class="fw-semibold">Total (all selected):</span><span id="display-total" class="fw-bold">TZS 0.00</span></div>
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
        calculateTotals();
    }

    const allClients = @json($clients);
    const schedulePrices = @json($schedules->mapWithKeys(fn($schedule) => [$schedule->id => $schedule->displayed_price]));

    /* ── route → clients ─────────────────────────────── */
    document.getElementById('route_select').addEventListener('change', function () {
        const route = this.value;
        const listContainer = document.getElementById('clients_list_container');
        const list = document.getElementById('clients_list');

        if (!route) {
            listContainer.style.display = 'none';
            list.innerHTML = '';
            updateCount();
            return;
        }

        const filtered = allClients.filter(c => (c.route || '') === route);
        listContainer.style.display = 'block';
        list.innerHTML = '';

        if (filtered.length === 0) {
            list.innerHTML = '<p class="text-muted text-center my-2">No clients assigned to this route yet.</p>';
        } else {
            filtered.forEach(client => {
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input client-checkbox" type="checkbox" name="client_ids[]" value="${client.id}" id="client_${client.id}" checked onchange="updateCount()">
                    <label class="form-check-label" for="client_${client.id}">
                        <strong>${client.name}</strong> <span class="text-muted">(${client.registration_number || 'N/A'})</span><br>
                        <small class="text-muted">${client.ward || client.district || ''}${client.phone ? ' · ' + client.phone : ''}</small>
                    </label>
                `;
                list.appendChild(div);
            });
        }
        updateCount();
    });

    function updateCount() {
        const count = document.querySelectorAll('.client-checkbox:checked').length;
        document.getElementById('selected_count').textContent = count;
        calculateTotals();
    }

    function selectAll() {
        document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);
        updateCount();
    }

    function deselectAll() {
        document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = false);
        updateCount();
    }

    /* ── amounts ─────────────────────────────────────── */
    function applyPriceList() {
        const select = document.getElementById('price_list_select');
        if (select && select.value) {
            document.getElementById('subtotal').value = select.value;
        }
        calculateTotals();
    }

    function calculateTotals() {
        const sub = parseFloat(document.getElementById('subtotal').value) || 0;
        const mode = document.querySelector('input[name="mode"]:checked').value;
        const clientCount = mode === 'group'
            ? Math.max(document.querySelectorAll('.client-checkbox:checked').length, 0)
            : 1;

        document.getElementById('display-subtotal').textContent = 'TZS ' + sub.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('display-total').textContent = 'TZS ' + (sub * Math.max(clientCount, 1)).toLocaleString(undefined, {minimumFractionDigits: 2});
    }
    document.getElementById('subtotal').addEventListener('input', calculateTotals);

    const scheduleSelect = document.getElementById('schedule_id');
    if (scheduleSelect) {
        scheduleSelect.addEventListener('change', function() {
            const price = schedulePrices[this.value];
            if (price !== null && price !== undefined && price !== '') {
                document.getElementById('subtotal').value = price;
                calculateTotals();
            }
        });
    }

    calculateTotals();
    </script>
</x-dashboard-layout>
