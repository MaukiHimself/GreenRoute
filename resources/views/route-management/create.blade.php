@extends('layouts.contractor-sidebar')

@section('title', 'Create New Route')

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
                    <i class="bi bi-plus-circle me-2"></i>Create New Route
                </h1>
                <p class="mb-0 opacity-90">Define a new route and assign clients to it</p>
            </div>
            <a href="{{ route('route-management.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back to Routes
            </a>
        </div>
    </div>

        <!-- Form -->
        <div class="form-card">
            <form action="{{ route('route-management.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Route Name <span class="text-danger">*</span></label>
                        <input type="text" name="route_name" class="form-control @error('route_name') is-invalid @enderror" 
                               value="{{ old('route_name') }}" placeholder="e.g., Downtown Route" required>
                        @error('route_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Route Color</label>
                        <input type="hidden" name="color" id="selectedColor" value="#047857">
                        <div class="color-picker-container">
                            <div class="color-option selected" style="background-color: #047857" onclick="selectColor('#047857')"></div>
                            <div class="color-option" style="background-color: #22c55e" onclick="selectColor('#22c55e')"></div>
                            <div class="color-option" style="background-color: #10b981" onclick="selectColor('#10b981')"></div>
                            <div class="color-option" style="background-color: #f59e0b" onclick="selectColor('#f59e0b')"></div>
                            <div class="color-option" style="background-color: #ef4444" onclick="selectColor('#ef4444')"></div>
                            <div class="color-option" style="background-color: #8b5cf6" onclick="selectColor('#8b5cf6')"></div>
                            <div class="color-option" style="background-color: #ec4899" onclick="selectColor('#ec4899')"></div>
                            <div class="color-option" style="background-color: #64748b" onclick="selectColor('#64748b')"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Add notes about this route...">{{ old('description') }}</textarea>
                </div>

                <!-- Dumping Site (final destination of the route) -->
                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-trash3 me-1"></i>Dumping Site (Optional)</label>
                    <select name="dumping_site" class="form-select">
                        <option value="">— None (route ends at last client) —</option>
                        @foreach(collect(config('dumping_sites.sites', []))->where('is_open', true) as $site)
                            <option value="{{ $site['name'] }}" {{ old('dumping_site') === $site['name'] ? 'selected' : '' }}>
                                {{ $site['name'] }} (open)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Where the truck offloads. The optimised route ends here: your base → clients → dumping site.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Assign Clients</label>
                    
                    <!-- Site Location Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Filter by Region</label>
                            <select class="form-select" id="siteLocationFilter" onchange="filterByLocation()">
                                <option value="">All Regions</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Search Clients</label>
                            <input type="text" id="clientSearch" class="form-control" placeholder="Search by name, address, city, or coordinates..." onkeyup="filterClients()">
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                        <span class="ms-3 text-muted" id="selectedCount">0 clients selected</span>
                    </div>
                    <div class="client-checkbox-group">
                        @forelse($clients as $client)
                            <div class="client-item">
                                <div class="form-check">
                                    <input class="form-check-input client-checkbox" type="checkbox" name="client_ids[]" 
                                           value="{{ $client->id }}" id="client{{ $client->id }}"
                                           onchange="updateCount()">
                                    <label class="form-check-label w-100" for="client{{ $client->id }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <strong>{{ $client->name }}</strong>
                                                <div class="small text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                                                </div>
                                                @if($client->route)
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
                            <p class="text-muted text-center py-3">No clients available. Add clients first.</p>
                        @endforelse
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Route
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
            const locationFilter = document.getElementById('siteLocationFilter').value.toLowerCase();
            const clientItems = document.querySelectorAll('.client-item');
            
            clientItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const matchesSearch = searchTerm === '' || text.includes(searchTerm);
                const matchesLocation = locationFilter === '' || text.includes(locationFilter);
                
                if (matchesSearch && matchesLocation) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        function filterByLocation() {
            filterClients();
        }

        // Initialize count
        updateCount();
    </script>
@endsection

@push('scripts')
@endpush
