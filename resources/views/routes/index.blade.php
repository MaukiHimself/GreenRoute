@extends('layouts.contractor-sidebar')

@section('title', 'Route Optimization')

@section('styles')
<style>
    :root {
        --primary-color: #047857;
        --secondary-color: #c0392b;
        --white-color: #ffffff;
        --light-bg: #f8f9fa;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        padding: 0;
        margin: 0;
    }

    .container {
        max-width: 1400px;
        padding: 2rem;
    }

    /* Header Section */
    .page-header {
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    /* Content Sections */
    .content-section {
        background: var(--white-color);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-bg);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
    }

    /* Buttons */
    .btn-primary, .btn-teal {
        background: var(--primary-color);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-align: center;
        text-decoration: none;
    }

    .btn-primary:hover, .btn-teal:hover {
        background: #065f46;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        color: white;
    }

    .btn-primary:disabled {
        background: #9ca3af;
        transform: none;
        box-shadow: none;
    }

    /* Route Option Card */
    .route-option-card {
        transition: all 0.3s ease;
        background: #ffffff;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
    }

    .route-option-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .route-option-card.active {
        background: #ecfdf5;
        border-color: #047857 !important;
    }

    #opt-2.route-option-card.active {
        background: #eff6ff;
        border-color: #2563eb !important;
    }

    #opt-3.route-option-card.active {
        background: #fffbeb;
        border-color: #d97706 !important;
    }

    .text-teal {
        color: #047857 !important;
    }

    /* Client Checkbox List */
    .client-checkbox-list {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1rem;
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
    }

    .client-item-row {
        transition: background 0.2s;
    }

    .client-item-row:hover {
        background: rgba(4, 120, 87, 0.05);
    }

    /* Route List */
    .route-list-container {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1rem;
        max-height: 380px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
    }

    .route-item {
        background: var(--white-color);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .route-number {
        color: white;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .route-details {
        flex: 1;
        min-width: 0;
    }

    .client-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.15rem;
    }

    .client-address {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0.15rem;
    }

    .client-phone {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    /* Map Container */
    .map-container {
        background: var(--white-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        height: 650px;
        border: 1px solid var(--border-color);
    }

    #map {
        height: 100%;
        width: 100%;
        border-radius: 16px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    /* Spinner */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .bi-spin {
        display: inline-block;
        animation: spin 1s linear infinite;
    }
</style>
@endsection

@section('content')
@php
    $contractorLat = Auth::user()->latitude;
    $contractorLng = Auth::user()->longitude;
    $hasBaseLocation = !is_null($contractorLat) && !is_null($contractorLng);
    
    // Fallback coordinates (Dar es Salaam center)
    $baseLat = $contractorLat ?? -6.7924;
    $baseLng = $contractorLng ?? 39.2083;
    $contractorName = Auth::user()->name;
@endphp

<div class="container">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title"><i class="bi bi-compass me-2"></i>Route Optimization</h1>
        <a href="{{ route('route-management.index') }}" class="btn btn-teal py-2">
            <i class="bi bi-list-task me-2"></i>Route Management
        </a>
    </div>

    @if(!$hasBaseLocation)
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-start gap-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>
                <strong>Base Location Missing!</strong> You have not configured your office base location. We will default your start point to Dar es Salaam center, but please set your location on the dashboard to optimize routes accurately.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Controls, Options and Route List -->
        <div class="col-lg-4">
            <!-- Client Selection Card -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Select Clients</h2>
                    <span class="badge bg-primary rounded-pill" id="selectedCountBadge">0 selected</span>
                </div>

                <div class="mb-3">
                    <input type="text" id="clientSearch" class="form-control py-2" placeholder="Search clients by name...">
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="selectAllClients" checked>
                    <label class="form-check-label fw-bold text-dark" for="selectAllClients">Select All</label>
                </div>

                <div class="client-checkbox-list mb-3">
                    @forelse($clients as $client)
                        <div class="form-check client-item-row border-bottom py-2" data-name="{{ strtolower($client->name) }}">
                            <input class="form-check-input client-select-cb" type="checkbox" value="{{ $client->id }}" 
                                   data-name="{{ $client->name }}" 
                                   data-address="{{ $client->address }}"
                                   data-phone="{{ $client->phone }}"
                                   data-lat="{{ $client->latitude }}" 
                                   data-lng="{{ $client->longitude }}" 
                                   id="client-{{ $client->id }}" checked>
                            <label class="form-check-label text-truncate w-100" for="client-{{ $client->id }}" title="{{ $client->name }}">
                                <span class="fw-semibold text-dark">{{ $client->name }}</span><br>
                                <span class="text-muted small">{{ $client->address }}</span>
                            </label>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-people display-6 opacity-25"></i>
                            <p class="mb-0 mt-2">No clients with coordinates found.</p>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn btn-teal w-100 py-2.5" id="optimizeBtn" onclick="runRouteOptimization()" {{ $clients->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-arrow-repeat me-2" id="optimizeIcon"></i>Optimize Route
                </button>
            </div>

            <!-- Route Options list -->
            <div class="content-section" id="optionsContainer" style="display: none;">
                <div class="section-header">
                    <h2 class="section-title">Possible Route Options</h2>
                </div>
                
                <div class="d-flex flex-column gap-2 mb-3">
                    <!-- Option 1 -->
                    <div class="route-option-card border rounded p-3 cursor-pointer active" id="opt-1" onclick="selectRouteOption(1)" style="border-left: 5px solid #047857 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-teal"><i class="bi bi-record-circle-fill me-2"></i>Option 1: Nearest Neighbor (Greedy)</h6>
                            <span class="badge bg-success" id="dist-val-1">-- km</span>
                        </div>
                        <small class="text-muted" id="dur-val-1">Est. Duration: --</small>
                    </div>
                    
                    <!-- Option 2 -->
                    <div class="route-option-card border rounded p-3 cursor-pointer" id="opt-2" onclick="selectRouteOption(2)" style="border-left: 5px solid #2563eb !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-record-circle-fill me-2"></i>Option 2: 2-Opt Optimized (Shortest)</h6>
                            <span class="badge bg-primary" id="dist-val-2">-- km</span>
                        </div>
                        <small class="text-muted" id="dur-val-2">Est. Duration: --</small>
                    </div>

                    <!-- Option 3 -->
                    <div class="route-option-card border rounded p-3 cursor-pointer" id="opt-3" onclick="selectRouteOption(3)" style="border-left: 5px solid #d97706 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-warning" style="color: #d97706 !important;"><i class="bi bi-record-circle-fill me-2"></i>Option 3: Polar Sweep (Sector-Based)</h6>
                            <span class="badge bg-warning text-dark" id="dist-val-3">-- km</span>
                        </div>
                        <small class="text-muted" id="dur-val-3">Est. Duration: --</small>
                    </div>
                </div>
            </div>

            <!-- Route Stop Sequence -->
            <div class="content-section" id="routeListSection" style="display: none;">
                <div class="section-header">
                    <h2 class="section-title">Optimal Stop Sequence</h2>
                </div>

                <div class="route-list-container" id="routeList">
                </div>
            </div>

            <!-- Save Form -->
            <div class="content-section" id="saveRouteContainer" style="display: none;">
                <div class="section-header">
                    <h2 class="section-title">Save Selected Route</h2>
                </div>
                <form id="saveRouteForm" onsubmit="saveRoute(event)">
                    <div class="mb-3">
                        <label class="form-label">Route Name <span class="text-danger">*</span></label>
                        <input type="text" id="newRouteName" class="form-control" placeholder="e.g., Ilala Morning Route" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea id="newRouteDesc" class="form-control" rows="2" placeholder="Optional notes about this route..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-teal w-100 py-2.5" id="saveBtn">
                        <i class="bi bi-save me-2"></i>Save to Route Management
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Map -->
        <div class="col-lg-8">
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@include('components.leaflet-assets')

<script>
    const baseLat = parseFloat("{{ $baseLat }}");
    const baseLng = parseFloat("{{ $baseLng }}");
    const baseName = "{{ $contractorName }} Base";
    const dumpsiteLat = -6.9333; // Pugu Kinyamwezi
    const dumpsiteLng = 39.1333;
    const dumpsiteName = "Pugu Kinyamwezi Dumpsite";

    let mapCtx;
    let routeData1 = null;
    let routeData2 = null;
    let routeData3 = null;
    let drawnLayers = [];
    let currentSelectedOption = 1;

    GreenRouteMap.whenReady(function () {
        mapCtx = GreenRouteMap.createMap('map', { lat: baseLat, lng: baseLng, zoom: 12 });
        updateSelectedCount();
        updateClientMarkersOnMap();

        // Add Map Legend Overlay
        const LegendControl = L.Control.extend({
            options: { position: 'bottomleft' },
            onAdd: function (map) {
                const div = L.DomUtil.create('div', 'map-legend-box');
                div.style.backgroundColor = 'white';
                div.style.padding = '12px 16px';
                div.style.borderRadius = '12px';
                div.style.boxShadow = '0 4px 15px rgba(0,0,0,0.15)';
                div.style.border = '1px solid #cbd5e1';
                div.style.fontFamily = "inherit";
                div.style.fontSize = '12px';
                div.style.lineHeight = '1.8';
                div.style.color = '#1e293b';
                
                div.innerHTML = `
                    <h6 style="margin: 0 0 8px 0; font-weight: 700; color: #047857; font-size: 13px;">Route Components</h6>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; background: #2563eb; color: white; width: 22px; height: 22px; border-radius: 50%;"><i class="bi bi-house-door-fill" style="font-size: 11px;"></i></span>
                        <strong>Base Location (Start)</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; background: #047857; color: white; width: 22px; height: 22px; border-radius: 50%; font-weight: 700; font-size: 11px;" id="legendClientStop">#</span>
                        <strong>Client Collection Stops</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; background: #c0392b; color: white; width: 22px; height: 22px; border-radius: 50%;"><i class="bi bi-trash3-fill" style="font-size: 11px;"></i></span>
                        <strong>Dumping Site (End Point)</strong>
                    </div>
                `;
                return div;
            }
        });
        new LegendControl().addTo(mapCtx.map);
    });

    // Checkbox and Search Event Handlers
    const selectAllCheckbox = document.getElementById('selectAllClients');
    const clientCheckboxes = document.querySelectorAll('.client-select-cb');
    const clientSearchInput = document.getElementById('clientSearch');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            clientCheckboxes.forEach(cb => {
                const parentRow = cb.closest('.client-item-row');
                // Only select visible rows
                if (parentRow.style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
            updateSelectedCount();
            updateClientMarkersOnMap();
        });
    }

    clientCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateSelectedCount();
            updateClientMarkersOnMap();
            
            // Sync Select All checkbox
            const visibleCbs = Array.from(clientCheckboxes).filter(c => c.closest('.client-item-row').style.display !== 'none');
            const checkedVisibleCbs = visibleCbs.filter(c => c.checked);
            selectAllCheckbox.checked = visibleCbs.length === checkedVisibleCbs.length;
        });
    });

    if (clientSearchInput) {
        clientSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            clientCheckboxes.forEach(cb => {
                const parentRow = cb.closest('.client-item-row');
                const name = parentRow.dataset.name;
                if (name.includes(query)) {
                    parentRow.style.display = 'block';
                } else {
                    parentRow.style.display = 'none';
                }
            });
            
            // Recalculate Select All checkbox state
            const visibleCbs = Array.from(clientCheckboxes).filter(c => c.closest('.client-item-row').style.display !== 'none');
            const checkedVisibleCbs = visibleCbs.filter(c => c.checked);
            selectAllCheckbox.checked = visibleCbs.length > 0 && visibleCbs.length === checkedVisibleCbs.length;
        });
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.client-select-cb:checked').length;
        document.getElementById('selectedCountBadge').textContent = count + ' selected';
    }

    // Map Updates
    function updateClientMarkersOnMap() {
        if (!mapCtx) return;
        GreenRouteMap.clearMarkers(mapCtx);

        const markersCoords = [];

        // 1. Add Base Marker (Blue Building)
        const baseIcon = L.divIcon({
            className: 'gr-endpoint-marker',
            html: `<div style="background:#2563eb;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-house-door-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 28]
        });
        GreenRouteMap.addMarker(mapCtx, baseLat, baseLng, {
            title: baseName,
            popup: `<strong>${baseName}</strong><br>Contractor Base Location`,
            icon: baseIcon
        });
        markersCoords.push({ lat: baseLat, lng: baseLng });

        // 2. Add Selected Clients Markers (Green Client Pin)
        const selectedCbs = document.querySelectorAll('.client-select-cb:checked');
        selectedCbs.forEach(cb => {
            const lat = parseFloat(cb.dataset.lat);
            const lng = parseFloat(cb.dataset.lng);
            const name = cb.dataset.name;
            const address = cb.dataset.address;

            const clientIcon = L.divIcon({
                className: 'gr-endpoint-marker',
                html: `<div style="background:#047857;width:24px;height:24px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.25);display:flex;align-items:center;justify-content:center;"><i class="bi bi-person-fill" style="transform:rotate(45deg);color:#fff;font-size:11px;"></i></div>`,
                iconSize: [24, 24], iconAnchor: [12, 22]
            });

            GreenRouteMap.addMarker(mapCtx, lat, lng, {
                title: name,
                popup: `<strong>${name}</strong><br>${address}`,
                icon: clientIcon
            });
            markersCoords.push({ lat, lng });
        });

        // 3. Add Pugu Dumpsite Marker (Red Trash Can)
        const dumpIcon = L.divIcon({
            className: 'gr-endpoint-marker',
            html: `<div style="background:#c0392b;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-trash3-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 28]
        });
        GreenRouteMap.addMarker(mapCtx, dumpsiteLat, dumpsiteLng, {
            title: dumpsiteName,
            popup: `<strong>${dumpsiteName}</strong><br>Operational Landfill (End point)`,
            icon: dumpIcon
        });
        markersCoords.push({ lat: dumpsiteLat, lng: dumpsiteLng });

        // Fit map bounds to show all markers
        if (markersCoords.length > 0) {
            GreenRouteMap.fitBounds(mapCtx, markersCoords);
        }
    }

    // 3 Optimization Algorithms
    function getNearestNeighborPath(base, pugu, clients) {
        const path = [base];
        let current = base;
        while (clients.length > 0) {
            let nearestIdx = 0;
            let minD = GreenRouteMap.haversineKm(current.lat, current.lng, clients[0].lat, clients[0].lng);
            for (let i = 1; i < clients.length; i++) {
                let d = GreenRouteMap.haversineKm(current.lat, current.lng, clients[i].lat, clients[i].lng);
                if (d < minD) {
                    minD = d;
                    nearestIdx = i;
                }
            }
            current = clients[nearestIdx];
            path.push(current);
            clients.splice(nearestIdx, 1);
        }
        path.push(pugu);
        return path;
    }

    function getPolarSweepPath(base, pugu, clients) {
        // Sort clients by polar angle relative to base coordinates
        clients.sort((a, b) => {
            let angleA = Math.atan2(a.lat - base.lat, a.lng - base.lng);
            let angleB = Math.atan2(b.lat - base.lat, b.lng - base.lng);
            return angleA - angleB;
        });
        return [base, ...clients, pugu];
    }

    function get2OptOptimizedPath(base, pugu, clients) {
        if (clients.length === 0) return [base, pugu];

        // Start with Nearest Neighbor path as the initial tour
        const path = getNearestNeighborPath(base, pugu, [...clients]);
        
        let improved = true;
        let iterations = 0;
        const maxIterations = 500; // safety limit to prevent page freeze
        
        while (improved && iterations < maxIterations) {
            improved = false;
            iterations++;
            
            // We want to optimize stops between index 1 and path.length - 2.
            // Index 0 (base) and index path.length - 1 (pugu) must remain fixed!
            for (let i = 1; i < path.length - 2; i++) {
                for (let j = i + 1; j < path.length - 1; j++) {
                    const dCurrent = 
                        GreenRouteMap.haversineKm(path[i-1].lat, path[i-1].lng, path[i].lat, path[i].lng) +
                        GreenRouteMap.haversineKm(path[j].lat, path[j].lng, path[j+1].lat, path[j+1].lng);
                        
                    const dNew = 
                        GreenRouteMap.haversineKm(path[i-1].lat, path[i-1].lng, path[j].lat, path[j].lng) +
                        GreenRouteMap.haversineKm(path[i].lat, path[i].lng, path[j+1].lat, path[j+1].lng);
                    
                    if (dNew < dCurrent - 0.0001) { // small tolerance to avoid floating-point infinite loops
                        // Reverse the segment in-place
                        let left = i;
                        let right = j;
                        while (left < right) {
                            const temp = path[left];
                            path[left] = path[right];
                            path[right] = temp;
                            left++;
                            right--;
                        }
                        improved = true;
                    }
                }
            }
        }
        return path;
    }

    // OSRM API Request wrapper
    async function getRoadRoute(points) {
        const osrmCoords = points.map(p => `${p.lng},${p.lat}`).join(';');
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${osrmCoords}?overview=full&geometries=geojson`;
        const response = await fetch(osrmUrl);
        if (!response.ok) throw new Error('OSRM service failure');
        const data = await response.json();
        if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
            return {
                geometry: data.routes[0].geometry,
                distance: data.routes[0].distance / 1000, // in km
                duration: data.routes[0].duration,
                points: points
            };
        }
        throw new Error('No route found');
    }

    function calculateDirectDistance(coords) {
        let total = 0;
        for (let i = 1; i < coords.length; i++) {
            total += GreenRouteMap.haversineKm(coords[i-1].lat, coords[i-1].lng, coords[i].lat, coords[i].lng);
        }
        return total;
    }

    function formatDuration(seconds) {
        if (!seconds) return 'N/A';
        const mins = Math.round(seconds / 60);
        if (mins < 60) return mins + ' mins';
        const hrs = Math.floor(mins / 60);
        const remMins = mins % 60;
        return hrs + ' hr ' + remMins + ' mins';
    }

    function showLoading(isLoading) {
        const btn = document.getElementById('optimizeBtn');
        const icon = document.getElementById('optimizeIcon');
        if (isLoading) {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat bi-spin me-2"></i>Optimizing...';
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Optimize Route';
        }
    }

    // Main optimization trigger
    async function runRouteOptimization() {
        const selectedCbs = document.querySelectorAll('.client-select-cb:checked');
        if (selectedCbs.length === 0) {
            alert('Please select at least one client to optimize the route.');
            return;
        }

        const base = { id: 0, name: baseName, lat: baseLat, lng: baseLng };
        const pugu = { id: 99999, name: dumpsiteName, lat: dumpsiteLat, lng: dumpsiteLng };
        
        const clients = Array.from(selectedCbs).map(cb => ({
            id: parseInt(cb.value),
            name: cb.dataset.name,
            address: cb.dataset.address,
            phone: cb.dataset.phone,
            lat: parseFloat(cb.dataset.lat),
            lng: parseFloat(cb.dataset.lng)
        }));

        showLoading(true);

        const paths = [
            getNearestNeighborPath(base, pugu, [...clients]),
            get2OptOptimizedPath(base, pugu, [...clients]),
            getPolarSweepPath(base, pugu, [...clients])
        ];

        try {
            // Retrieve OSRM geometry and distance concurrently for all 3 paths
            const results = await Promise.all([
                getRoadRoute(paths[0]),
                getRoadRoute(paths[1]),
                getRoadRoute(paths[2])
            ]);

            routeData1 = results[0];
            routeData2 = results[1];
            routeData3 = results[2];

            document.getElementById('dist-val-1').textContent = routeData1.distance.toFixed(1) + ' km';
            document.getElementById('dist-val-2').textContent = routeData2.distance.toFixed(1) + ' km';
            document.getElementById('dist-val-3').textContent = routeData3.distance.toFixed(1) + ' km';

            document.getElementById('dur-val-1').textContent = 'Est. Duration: ' + formatDuration(routeData1.duration);
            document.getElementById('dur-val-2').textContent = 'Est. Duration: ' + formatDuration(routeData2.duration);
            document.getElementById('dur-val-3').textContent = 'Est. Duration: ' + formatDuration(routeData3.duration);

            document.getElementById('optionsContainer').style.display = 'block';
            document.getElementById('routeListSection').style.display = 'block';
            document.getElementById('saveRouteContainer').style.display = 'block';

            selectRouteOption(1);
        } catch (error) {
            console.warn('OSRM road route fetch failed. Falling back to straight lines:', error);

            routeData1 = { distance: calculateDirectDistance(paths[0]), duration: 0, points: paths[0], fallback: true };
            routeData2 = { distance: calculateDirectDistance(paths[1]), duration: 0, points: paths[1], fallback: true };
            routeData3 = { distance: calculateDirectDistance(paths[2]), duration: 0, points: paths[2], fallback: true };

            document.getElementById('dist-val-1').textContent = routeData1.distance.toFixed(1) + ' km';
            document.getElementById('dist-val-2').textContent = routeData2.distance.toFixed(1) + ' km';
            document.getElementById('dist-val-3').textContent = routeData3.distance.toFixed(1) + ' km';

            document.getElementById('dur-val-1').textContent = 'Direct Line Distance';
            document.getElementById('dur-val-2').textContent = 'Direct Line Distance';
            document.getElementById('dur-val-3').textContent = 'Direct Line Distance';

            document.getElementById('optionsContainer').style.display = 'block';
            document.getElementById('routeListSection').style.display = 'block';
            document.getElementById('saveRouteContainer').style.display = 'block';

            selectRouteOption(1);
        } finally {
            showLoading(false);
        }
    }

    // Visual route switching on map
    function drawAllRoutesOnMap() {
        if (!mapCtx) return;

        // Clear existing polylines
        drawnLayers.forEach(layer => mapCtx.map.removeLayer(layer));
        drawnLayers = [];

        const colors = {
            1: '#047857', // Emerald Green
            2: '#2563eb', // Royal Blue
            3: '#d97706'  // Amber Orange
        };

        const routes = {
            1: routeData1,
            2: routeData2,
            3: routeData3
        };

        [1, 2, 3].forEach(opt => {
            const route = routes[opt];
            if (!route) return;

            const isSelected = opt === currentSelectedOption;
            const color = colors[opt];
            
            let layer;
            if (route.fallback) {
                const latlngs = route.points.map(p => [p.lat, p.lng]);
                layer = L.polyline(latlngs, {
                    color: color,
                    weight: isSelected ? 6 : 3,
                    opacity: isSelected ? 0.9 : 0.35
                }).addTo(mapCtx.map);
            } else {
                layer = L.geoJSON(route.geometry, {
                    style: {
                        color: color,
                        weight: isSelected ? 6 : 3,
                        opacity: isSelected ? 0.9 : 0.35
                    }
                }).addTo(mapCtx.map);
            }

            drawnLayers.push(layer);
        });

        // Fit active route bounds
        const activeRoute = routes[currentSelectedOption];
        if (activeRoute) {
            if (activeRoute.fallback) {
                GreenRouteMap.fitBounds(mapCtx, activeRoute.points);
            } else {
                const points = activeRoute.geometry.coordinates.map(c => ({ lat: c[1], lng: c[0] }));
                GreenRouteMap.fitBounds(mapCtx, points);
            }
        }
    }

    function selectRouteOption(num) {
        currentSelectedOption = num;

        // Update active class in options list
        document.querySelectorAll('.route-option-card').forEach(card => card.classList.remove('active'));
        document.getElementById('opt-' + num).classList.add('active');

        // Draw and highlight
        drawAllRoutesOnMap();

        // Update stops list sequence in UI
        const routeList = document.getElementById('routeList');
        routeList.innerHTML = '';

        const activeRoute = num === 1 ? routeData1 : (num === 2 ? routeData2 : routeData3);
        const path = activeRoute.points;

        // Redraw markers to show sequence numbers on map
        if (mapCtx) {
            GreenRouteMap.clearMarkers(mapCtx);
            const colors = {
                1: '#047857', // Emerald Green
                2: '#2563eb', // Royal Blue
                3: '#d97706'  // Amber Orange
            };
            const activeColor = colors[num] || '#047857';

            path.forEach((stop, index) => {
                let label = 'Stop';
                let name = stop.name;
                let address = stop.address;
                let markerIcon;

                if (index === 0) {
                    label = 'Start (Base Location)';
                    name = baseName;
                    address = '{{ Auth::user()->address }}' || 'Office base';
                    markerIcon = L.divIcon({
                        className: 'gr-endpoint-marker',
                        html: `<div style="background:#2563eb;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-house-door-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
                        iconSize: [30, 30], iconAnchor: [15, 28]
                    });
                } else if (index === path.length - 1) {
                    label = 'End (Landfill Site)';
                    name = dumpsiteName;
                    address = 'Pugu Ward, Ilala';
                    markerIcon = L.divIcon({
                        className: 'gr-endpoint-marker',
                        html: `<div style="background:#c0392b;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-trash3-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
                        iconSize: [30, 30], iconAnchor: [15, 28]
                    });
                } else {
                    label = `Stop ${index}`;
                    markerIcon = L.divIcon({
                        className: 'gr-map-number-marker',
                        html: `<div class="gr-map-number-badge" style="background: ${activeColor};">${index}</div>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 14]
                    });
                }

                GreenRouteMap.addMarker(mapCtx, stop.lat, stop.lng, {
                    title: name,
                    popup: `<strong>${label}: ${name}</strong><br>${address}`,
                    icon: markerIcon
                });
            });

            // Update client stop color in legend
            const legendStop = document.getElementById('legendClientStop');
            if (legendStop) {
                legendStop.style.background = activeColor;
            }
        }

        path.forEach((stop, index) => {
            let label = 'Stop';
            let color = num === 1 ? '#047857' : (num === 2 ? '#2563eb' : '#d97706');
            let name = stop.name;
            let address = stop.address;

            if (index === 0) {
                label = 'Start (Base Location)';
                name = baseName;
                address = '{{ Auth::user()->address }}' || 'Office base';
            } else if (index === path.length - 1) {
                label = 'End (Landfill Site)';
                name = dumpsiteName;
                address = 'Pugu Ward, Ilala';
            }

            const item = document.createElement('div');
            item.className = 'route-item d-flex align-items-start';
            item.innerHTML = `
                <div class="route-number" style="background: ${color}">${index + 1}</div>
                <div class="route-details">
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">${label}</span>
                    <div class="client-name">${name}</div>
                    <div class="client-address">${address}</div>
                    ${stop.phone ? `<div class="client-phone"><i class="bi bi-telephone me-1"></i>${stop.phone}</div>` : ''}
                </div>
            `;
            routeList.appendChild(item);
        });
    }

    // Save route to database
    function saveRoute(e) {
        e.preventDefault();

        const nameInput = document.getElementById('newRouteName');
        const descInput = document.getElementById('newRouteDesc');
        const routeName = nameInput.value.trim();
        const description = descInput.value.trim();

        if (!routeName) {
            alert('Please specify a route name.');
            return;
        }

        const activeRoute = currentSelectedOption === 1 ? routeData1 : (currentSelectedOption === 2 ? routeData2 : routeData3);
        const path = activeRoute.points;

        // Skip base and landfill stops to get client IDs in order
        const clientIds = path
            .slice(1, -1)
            .map(stop => stop.id);

        if (clientIds.length === 0) {
            alert('You must select at least one client to save.');
            return;
        }

        const color = currentSelectedOption === 1 ? '#047857' : (currentSelectedOption === 2 ? '#2563eb' : '#d97706');

        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-arrow-repeat bi-spin me-2"></i>Saving...';

        fetch('{{ route('route-management.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                route_name: routeName,
                description: description || `Optimized route Option ${currentSelectedOption} containing ${clientIds.length} stops.`,
                color: color,
                dumping_site: dumpsiteName,
                client_ids: clientIds,
                site_location: 'DAR-ES-SALAAM|ILALA|PUGU|KINYAMWEZI' // Pugu location mapping structure
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            // Standard redirect is followed by the fetch client returning index page
            alert('Route saved successfully!');
            window.location.href = '{{ route('route-management.index') }}';
        })
        .catch(err => {
            console.error('Error saving route:', err);
            const errorMsg = err.errors && err.errors.route_name 
                ? err.errors.route_name[0] 
                : (err.message || 'Error occurred while saving. Please make sure the route name is unique.');
            alert(errorMsg);
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-save me-2"></i>Save to Route Management';
        });
    }
</script>
@endpush
