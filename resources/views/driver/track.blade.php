<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Terminal - GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #047857;
            --primary-light: #059669;
            --dark-slate: #0f172a;
            --border-color: #e2e8f0;
            --shadow-lg: 0 10px 25px rgba(5, 92, 92, 0.1);
        }

        body {
            background-color: #f8fafc;
            color: var(--dark-slate);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
        }

        .header-panel {
            background: linear-gradient(135deg, var(--primary-teal), #065f46);
            color: white;
            padding: 1.5rem;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 1.5rem;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .terminal-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-pill {
            background: #f8fafc;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-track {
            background-color: var(--primary-teal);
            border: none;
            color: white;
            padding: 0.85rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 50px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.2);
            transition: all 0.3s;
        }

        .btn-track:hover {
            background-color: #065f46;
            transform: translateY(-1px);
        }

        .btn-track.active {
            background-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            animation: pulse 1.5s infinite;
        }

        /* Stops List */
        .stop-item {
            border-radius: 16px;
            border: 1.5px solid var(--border-color);
            padding: 1.25rem;
            margin-bottom: 1rem;
            background-color: white;
            transition: all 0.25s ease;
        }

        .stop-item.active {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
            background: linear-gradient(to right, #ffffff, #f0fdf4);
        }

        .stop-item.completed {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            opacity: 0.85;
        }

        .stop-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--primary-teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .stop-item.completed .stop-number {
            background-color: #64748b;
        }

        .action-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .action-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .action-btn.btn-collect { background: #dcfce7; color: #166534; }
        .action-btn.btn-collect:hover { background: #bbf7d0; }
        .action-btn.btn-skip { background: #fef3c7; color: #92400e; }
        .action-btn.btn-skip:hover { background: #fde68a; }
        .action-btn.btn-block { background: #fee2e2; color: #991b1b; }
        .action-btn.btn-block:hover { background: #fecaca; }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        #map {
            height: 250px;
            width: 100%;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <!-- Top Header Panel -->
    <div class="header-panel">
        <div class="d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <i class="bi bi-compass-fill"></i> GreenRoute Driver
            </div>
            <span class="badge bg-light text-success fw-bold">{{ ucfirst($truck->truck_type) }}</span>
        </div>
        <div class="mt-3 text-center">
            <h1 class="mb-1 fw-bold fs-3">{{ $truck->plate_number }}</h1>
            <p class="mb-0 opacity-75 small"><i class="bi bi-person me-1"></i>Driver: {{ $truck->driver_name }}</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <!-- Left Side: GPS & Map -->
            <div class="col-lg-5">
                <div class="terminal-card">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill me-2 text-success"></i>GPS Controller</h5>
                    
                    <div class="info-pill">
                        <span><i class="bi bi-speedometer2 me-2"></i>Daily Distance:</span>
                        <strong id="distance-val">{{ number_format($truck->daily_distance, 2) }} km</strong>
                    </div>

                    <button id="trackBtn" class="btn btn-track" onclick="toggleTracking()">
                        <i class="bi bi-geo-alt-fill me-2"></i>Start Location Sharing
                    </button>

                    <div class="text-center mt-3 small">
                        <div id="statusIndicator" class="d-none mb-1">
                            <span class="pulse-dot"></span><span class="text-success fw-bold">Active and Transmitting</span>
                        </div>
                        <span id="statusMsg" class="text-muted">Tracking offline.</span>
                        <small id="lastUpdated" class="text-muted d-block mt-1"></small>
                    </div>
                </div>

                <div class="terminal-card p-2">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Right Side: Checklist -->
            <div class="col-lg-7">
                <div class="terminal-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-success"></i>Route Stops Checklist</h5>
                        <span class="badge bg-primary rounded-pill" id="route-progress-badge">0 / 0 Completed</span>
                    </div>

                    <div id="stopsContainer">
                        <!-- Dynamic Stops checklist here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        const token = "{{ $truck->tracking_token }}";
        const routingToken = "{{ config('services.heigit.api_key') }}";
        const availableRoutes = @json($availableRoutes ?? []);
        let currentRouteId = {{ $truck->assigned_route_id ?? 'null' }};
        let waypoints = @json($waypoints);
        let geometry = @json($geometry ?? null);
        let etaList = @json($etaList);
        let stopStatuses = @json($truck->stop_statuses ?? []);
        const truckTareKg = {{ $truck->tare_weight_kg ?? 'null' }};
        let recordedNetKg = {{ $latestRunWeight ?? 'null' }};
        let pendingDisposals = [];
        let disposalSites = [];
        let isTracking = false;
        let watchId = null;
        let updateIntervalId = null;
        let lastPosition = null;

        let mapCtx;
        let driverMarker;
        let routePolyline;
        let liveRoutePolyline;
        let lastLiveGeometryKey = null;
        const stopMarkers = [];

        GreenRouteMap.whenReady(function () {
            // Initialize Map
            const initialLat = {{ $truck->current_latitude ?? -6.7924 }};
            const initialLng = {{ $truck->current_longitude ?? 39.2083 }};
            
            mapCtx = GreenRouteMap.createMap('map', {
                lat: initialLat,
                lng: initialLng,
                zoom: 13
            });

            // Add standard custom markers for stops
            drawRouteOnMap();
            
            // Initial render of UI
            renderChecklist();

            // Completed collections awaiting disposal records (kg entry).
            loadPendingDisposals();
        });

        function drawRouteOnMap() {
            if (!mapCtx || waypoints.length === 0) return;

            // Clear old layers
            stopMarkers.forEach(m => mapCtx.map.removeLayer(m));
            stopMarkers.length = 0;
            if (routePolyline) mapCtx.map.removeLayer(routePolyline);
            if (liveRoutePolyline) { mapCtx.map.removeLayer(liveRoutePolyline); liveRoutePolyline = null; lastLiveGeometryKey = null; }
            GreenRouteMap.clearPolylines(mapCtx);

            const points = [];

            waypoints.forEach((wp, idx) => {
                const lat = parseFloat(wp.lat);
                const lng = parseFloat(wp.lng);
                points.push([lat, lng]);

                let markerIcon;
                if (wp.type === 'base') {
                    markerIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background:#2563eb;width:24px;height:24px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-house-door" style="font-size:11px;"></i></div>`,
                        iconSize: [24, 24], iconAnchor: [12, 12]
                    });
                } else if (wp.type === 'dumping') {
                    markerIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background:#ef4444;width:24px;height:24px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-trash" style="font-size:11px;"></i></div>`,
                        iconSize: [24, 24], iconAnchor: [12, 12]
                    });
                } else {
                    const status = stopStatuses[wp.id] || 'pending';
                    let color = '#64748b'; // default grey
                    let content = idx;

                    if (status === 'collected') { color = '#10b981'; content = '<i class="bi bi-check-lg" style="font-size:10px;"></i>'; }
                    else if (status === 'skipped') { color = '#f59e0b'; content = '<i class="bi bi-x-lg" style="font-size:10px;"></i>'; }
                    else if (status === 'blocked') { color = '#ef4444'; content = '!'; }
                    else { color = '#047857'; } // active pending route color

                    markerIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background:${color};width:24px;height:24px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:11px;">${content}</div>`,
                        iconSize: [24, 24], iconAnchor: [12, 12]
                    });
                }

                const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(mapCtx.map);
                stopMarkers.push(marker);
            });

            // Prefer the server-computed road geometry; fall back to client-side
            // routing, then a straight dashed line, only if it's unavailable.
            if (geometry && geometry.length > 1) {
                routePolyline = L.polyline(geometry, { color: '#047857', weight: 4, opacity: 0.75 }).addTo(mapCtx.map);
            } else {
                GreenRouteMap.drawRoadRoute(mapCtx, waypoints, routingToken, { color: '#047857' }).then(road => {
                    if (road && road.geometry) {
                        routePolyline = mapCtx.polylines[mapCtx.polylines.length - 1];
                    } else {
                        routePolyline = L.polyline(points, { color: '#047857', weight: 3, opacity: 0.6, dashArray: '5, 8' }).addTo(mapCtx.map);
                    }
                });
            }

            // Add Driver Marker
            const currentLat = {{ $truck->current_latitude ?? 'null' }};
            const currentLng = {{ $truck->current_longitude ?? 'null' }};
            if (currentLat && currentLng) {
                updateDriverMarker(currentLat, currentLng);
            }
        }

        function updateDriverMarker(lat, lng) {
            if (!mapCtx) return;
            
            if (driverMarker) {
                driverMarker.setLatLng([lat, lng]);
            } else {
                const driverIcon = L.divIcon({
                    className: 'custom-driver-marker',
                    html: `<div style="background:#10b981;width:28px;height:28px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;color:white;animation:pulse 1.5s infinite;"><i class="bi bi-truck" style="font-size:12px;"></i></div>`,
                    iconSize: [28, 28], iconAnchor: [14, 14]
                });
                driverMarker = L.marker([lat, lng], { icon: driverIcon }).addTo(mapCtx.map);
            }
        }

        // Draw/refresh the live navigation line: current position -> remaining
        // pending stops -> dumping site. The planned route stays visible but is
        // faded underneath, so the driver always sees "the plan" vs "guidance
        // from where I am now" (e.g. after detouring around traffic or skipping
        // a congested stop).
        function drawLiveRoute(liveRoute) {
            if (!mapCtx || !liveRoute) return;

            const line = (liveRoute.geometry && liveRoute.geometry.length > 1)
                ? liveRoute.geometry
                : (liveRoute.waypoints || []).map(w => [parseFloat(w.lat), parseFloat(w.lng)]);

            if (!line || line.length < 2) return;

            // Skip redrawing when the geometry hasn't changed (server reuses
            // the cached line while the driver is still on it).
            const key = line.length + ':' + line[0] + ':' + line[line.length - 1];
            if (key === lastLiveGeometryKey && liveRoutePolyline) {
                return;
            }
            lastLiveGeometryKey = key;

            if (liveRoutePolyline) mapCtx.map.removeLayer(liveRoutePolyline);
            liveRoutePolyline = L.polyline(line, {
                color: '#2563eb', weight: 5, opacity: 0.9
            }).addTo(mapCtx.map);

            // Fade the planned route into the background.
            if (routePolyline) routePolyline.setStyle({ opacity: 0.25, dashArray: '6, 10' });
        }

        function renderChecklist() {
            const container = document.getElementById('stopsContainer');
            container.innerHTML = '';

            let firstPendingIdx = -1;
            let clientsCount = 0;
            let completedCount = 0;

            // Find first pending client stop
            waypoints.forEach((wp, index) => {
                if (wp.type === 'client') {
                    clientsCount++;
                    const status = stopStatuses[wp.id] || 'pending';
                    if (status !== 'pending') {
                        completedCount++;
                    } else if (firstPendingIdx === -1) {
                        firstPendingIdx = index;
                    }
                }
            });

            // Update progress header
            document.getElementById('route-progress-badge').textContent = `${completedCount} / ${clientsCount} Completed`;

            if (waypoints.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4"><i class="bi bi-info-circle fs-3 d-block mb-2"></i>No route assigned to this vehicle.</div>';
                return;
            }

            waypoints.forEach((wp, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'stop-item';

                let name = wp.name;
                let details = '';
                let statusBadge = '';
                let actionHtml = '';

                if (wp.type === 'base') {
                    itemDiv.className += ' completed';
                    name = 'Start Yard';
                    details = 'Contractor base terminal';
                    statusBadge = '<span class="badge bg-primary text-uppercase">Start</span>';
                } else if (wp.type === 'dumping') {
                    name = `Disposal Site: ${wp.name}`;
                    const eta = etaList.find(e => e.type === 'dumping');
                    details = eta ? `ETA: ${eta.eta_minutes} mins (${eta.distance} km remaining)` : 'End of route';
                    statusBadge = '<span class="badge bg-danger text-uppercase">Disposal</span>';
                } else {
                    // Client stop
                    const status = stopStatuses[wp.id] || 'pending';
                    const eta = etaList.find(e => e.client_id == wp.id);

                    if (status === 'collected') {
                        itemDiv.className += ' completed';
                        statusBadge = '<span class="badge bg-success text-uppercase"><i class="bi bi-check-circle-fill me-1"></i>Collected</span>';
                        details = 'Waste collected successfully.';
                    } else if (status === 'skipped') {
                        itemDiv.className += ' completed';
                        statusBadge = '<span class="badge bg-warning text-dark text-uppercase"><i class="bi bi-slash-circle-fill me-1"></i>Skipped</span>';
                        details = 'Stop skipped (no waste set out).';
                    } else if (status === 'blocked') {
                        itemDiv.className += ' completed';
                        statusBadge = '<span class="badge bg-danger text-uppercase"><i class="bi bi-exclamation-triangle-fill me-1"></i>Blocked</span>';
                        details = 'Access blocked (e.g. locked gate).';
                    } else {
                        // Pending stop
                        statusBadge = '<span class="badge bg-secondary text-uppercase">Pending</span>';
                        
                        if (index === firstPendingIdx) {
                            itemDiv.className += ' active';
                            details = eta ? `<strong>Upcoming:</strong> approx ${eta.eta_minutes} mins (${eta.distance} km away)` : 'Active next stop.';
                            
                            // Show active controls
                            actionHtml = `
                                <div class="action-group">
                                    <button class="action-btn btn-collect" onclick="updateStopStatus(${wp.id}, 'collected', this)">
                                        <i class="bi bi-check2"></i>Collect
                                    </button>
                                    <button class="action-btn btn-skip" onclick="updateStopStatus(${wp.id}, 'skipped', this)">
                                        <i class="bi bi-x-circle"></i>Skip
                                    </button>
                                    <button class="action-btn btn-block" onclick="updateStopStatus(${wp.id}, 'blocked', this)">
                                        <i class="bi bi-dash-circle"></i>Block
                                    </button>
                                </div>
                            `;
                        } else {
                            details = 'Awaiting preceding stop collections.';
                        }
                    }
                }

                itemDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stop-number">${index}</div>
                            <div>
                                <h6 class="mb-1 fw-bold">${name}</h6>
                                <p class="mb-0 text-muted small">${details}</p>
                            </div>
                        </div>
                        <div>${statusBadge}</div>
                    </div>
                    ${actionHtml}
                `;

                container.appendChild(itemDiv);
            });

            // When every client stop is actioned, show the completion summary
            // and let the driver start another route.
            if (clientsCount > 0 && completedCount === clientsCount) {
                renderCompletionCard(container);
                renderDisposalCard(container);
            }
        }

        // ---- Disposal records: filled by the driver at the dumping site ----
        // Completed collections that still need waste data (kg + category +
        // site). The contractor confirms each record on the Disposal page.

        async function loadPendingDisposals() {
            try {
                const res = await fetch(`/driver/pending-disposals/${token}`, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (data.success) {
                    pendingDisposals = data.pending || [];
                    disposalSites = data.sites || [];
                    renderChecklist();
                }
            } catch (err) {
                console.warn('Could not load pending disposals', err);
            }
        }

        function renderDisposalCard(container) {
            if (!pendingDisposals.length) return;

            const siteOptions = disposalSites.map(s => `<option value="${s}">${s}</option>`).join('');

            const itemsHtml = pendingDisposals.map(p => `
                <div class="p-3 rounded mb-2" style="background:#f8fafc;border:1px solid #e2e8f0;" id="disposal-item-${p.id}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="small">${p.client_name}</strong>
                        <span class="badge bg-light text-muted">${p.pickup_date ?? ''}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-4">
                            <input type="number" class="form-control form-control-sm" id="disp-weight-${p.id}" min="0.1" step="0.1" placeholder="Weight (kg)">
                        </div>
                        <div class="col-4">
                            <select class="form-select form-select-sm" id="disp-category-${p.id}">
                                <option value="">Category</option>
                                <option value="general">General</option>
                                <option value="organic">Organic</option>
                                <option value="recyclable">Recyclable</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-select form-select-sm" id="disp-site-${p.id}">
                                <option value="">Site</option>
                                ${siteOptions}
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success btn-sm w-100 mt-2 fw-bold" onclick="submitDisposal(${p.id}, this)">
                        <i class="bi bi-check2"></i> Save Record
                    </button>
                    <div class="small text-danger mt-1" id="disp-feedback-${p.id}"></div>
                </div>`).join('');

            const card = document.createElement('div');
            card.className = 'terminal-card mt-2';
            card.innerHTML = `
                <h5 class="fw-bold mb-1"><i class="bi bi-clipboard-data me-2 text-success"></i>Disposal Records</h5>
                <p class="text-muted small mb-3">Fill the waste data for each completed collection. Your contractor will confirm the records.</p>
                ${itemsHtml}
            `;
            container.appendChild(card);
        }

        async function submitDisposal(scheduleId, btn) {
            const weight = parseFloat(document.getElementById(`disp-weight-${scheduleId}`).value);
            const category = document.getElementById(`disp-category-${scheduleId}`).value;
            const site = document.getElementById(`disp-site-${scheduleId}`).value;
            const feedback = document.getElementById(`disp-feedback-${scheduleId}`);

            if (isNaN(weight) || weight <= 0 || !category || !site) {
                feedback.textContent = 'Fill weight, category and site first.';
                return;
            }

            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...'; }

            try {
                const response = await fetch(`/driver/record-disposal/${token}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId,
                        weight_kg: weight,
                        waste_category: category,
                        disposal_site: site
                    })
                });
                const data = await response.json();
                if (data.success) {
                    pendingDisposals = pendingDisposals.filter(p => p.id !== scheduleId);
                    renderChecklist();
                } else {
                    feedback.textContent = data.message || 'Failed to save the record.';
                    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2"></i> Save Record'; }
                }
            } catch (err) {
                console.error(err);
                feedback.textContent = 'Connection error occurred.';
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2"></i> Save Record'; }
            }
        }

        function renderCompletionCard(container) {
            // Tally outcomes from the current stop statuses.
            let collected = 0, skipped = 0, blocked = 0;
            waypoints.forEach(wp => {
                if (wp.type !== 'client') return;
                const st = stopStatuses[wp.id];
                if (st === 'collected') collected++;
                else if (st === 'skipped') skipped++;
                else if (st === 'blocked') blocked++;
            });

            const others = availableRoutes.filter(r => r.id !== currentRouteId);
            const optionsHtml = others.length
                ? others.map(r => `<option value="${r.id}">${r.route_name}</option>`).join('')
                : '';

            // Weighbridge entry: after finishing the route the driver drives to
            // the dumping site, is weighed, and types the gross reading here.
            // Net waste = gross - the truck's registered empty (tare) weight.
            let weighHtml = '';
            if (recordedNetKg !== null) {
                weighHtml = `
                    <div class="mt-3 p-3 rounded text-center" style="background:#e0f2fe;">
                        <i class="bi bi-clipboard-check text-primary me-1"></i>
                        <strong>${Number(recordedNetKg).toFixed(1)} kg</strong> of waste recorded for this trip.
                    </div>`;
            } else if (truckTareKg !== null) {
                weighHtml = `
                    <div class="mt-3 p-3 rounded" style="background:#f0f9ff;border:1px solid #bae6fd;">
                        <label class="fw-semibold small mb-2 d-block"><i class="bi bi-clipboard-data me-1"></i>Weighbridge reading at dumping site</label>
                        <div class="d-flex gap-2">
                            <input type="number" id="grossWeightInput" class="form-control form-control-sm" min="1" step="0.1"
                                   placeholder="Gross weight (kg), truck + waste">
                            <button class="btn btn-primary btn-sm text-nowrap fw-bold" id="recordWeightBtn" onclick="recordWeight()">
                                <i class="bi bi-check2"></i> Record
                            </button>
                        </div>
                        <div class="small text-muted mt-2">Truck empty weight: ${Number(truckTareKg).toFixed(0)} kg — waste weight is calculated automatically.</div>
                        <div class="small mt-1" id="weighFeedback"></div>
                    </div>`;
            }

            const selectorHtml = others.length ? `
                <div class="mt-3">
                    <label class="fw-semibold small mb-2 d-block"><i class="bi bi-signpost-2 me-1"></i>Start another route</label>
                    <div class="d-flex gap-2">
                        <select id="nextRouteSelect" class="form-select form-select-sm">${optionsHtml}</select>
                        <button class="btn btn-success btn-sm text-nowrap fw-bold" onclick="startRoute()">
                            <i class="bi bi-play-fill"></i> Start
                        </button>
                    </div>
                </div>` : `
                <div class="mt-3 text-center text-muted small">
                    <i class="bi bi-info-circle me-1"></i>No other active routes available. Contact your contractor to be assigned a new route.
                </div>`;

            const card = document.createElement('div');
            card.className = 'terminal-card mt-2';
            card.style.background = 'linear-gradient(135deg, #f0fdf4, #ffffff)';
            card.style.borderColor = '#86efac';
            card.innerHTML = `
                <div class="text-center mb-3">
                    <div style="width:56px;height:56px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                        <i class="bi bi-flag-fill text-success" style="font-size:1.75rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-success">Route Completed</h5>
                    <p class="text-muted small mb-0">All stops on this route have been actioned.</p>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#dcfce7;">
                            <div class="fw-bold fs-5 text-success">${collected}</div>
                            <div class="small text-muted">Collected</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#fef3c7;">
                            <div class="fw-bold fs-5" style="color:#92400e;">${skipped}</div>
                            <div class="small text-muted">Skipped</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#fee2e2;">
                            <div class="fw-bold fs-5" style="color:#991b1b;">${blocked}</div>
                            <div class="small text-muted">Blocked</div>
                        </div>
                    </div>
                </div>
                ${weighHtml}
                ${selectorHtml}
            `;
            container.appendChild(card);
        }

        async function recordWeight() {
            const input = document.getElementById('grossWeightInput');
            const btn = document.getElementById('recordWeightBtn');
            const feedback = document.getElementById('weighFeedback');
            if (!input || !input.value) return;

            const gross = parseFloat(input.value);
            if (isNaN(gross) || gross <= 0) {
                feedback.className = 'small mt-1 text-danger';
                feedback.textContent = 'Enter the gross weight shown on the weighbridge.';
                return;
            }

            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...'; }

            try {
                const response = await fetch(`/driver/record-weight/${token}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ gross_weight_kg: gross })
                });

                const data = await response.json();
                if (data.success) {
                    recordedNetKg = data.net_weight_kg;
                    renderChecklist();
                } else {
                    feedback.className = 'small mt-1 text-danger';
                    feedback.textContent = data.message || 'Failed to record the weight.';
                    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2"></i> Record'; }
                }
            } catch (err) {
                console.error(err);
                feedback.className = 'small mt-1 text-danger';
                feedback.textContent = 'Connection error occurred.';
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2"></i> Record'; }
            }
        }

        async function startRoute() {
            const sel = document.getElementById('nextRouteSelect');
            if (!sel || !sel.value) return;

            const routeId = sel.value;
            const btn = sel.nextElementSibling;
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Starting...'; }

            try {
                const response = await fetch(`/driver/start-route/${token}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ route_id: routeId })
                });

                const data = await response.json();
                if (data.success) {
                    // Load the fresh route in place — no full page reload.
                    waypoints = data.waypoints || [];
                    geometry = data.geometry || null;
                    stopStatuses = data.stop_statuses || {};
                    etaList = data.eta_list || [];
                    currentRouteId = parseInt(routeId);
                    recordedNetKg = null; // fresh trip -> fresh weighbridge entry

                    drawRouteOnMap();
                    renderChecklist();
                    if (mapCtx && waypoints.length) {
                        GreenRouteMap.fitBounds(mapCtx, waypoints.map(w => [w.lat, w.lng]));
                    }
                } else {
                    alert('Failed to start the selected route.');
                    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-play-fill"></i> Start'; }
                }
            } catch (err) {
                console.error(err);
                alert('Connection error occurred.');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-play-fill"></i> Start'; }
            }
        }

        async function updateStopStatus(clientId, status, buttonEl) {
            if (buttonEl) {
                const group = buttonEl.closest('.action-group');
                if (group) {
                    Array.from(group.querySelectorAll('button')).forEach(b => b.disabled = true);
                }
            }

            try {
                const response = await fetch(`/driver/stop-status/${token}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        client_id: clientId,
                        status: status
                    })
                });

                const data = await response.json();
                if (data.success) {
                    stopStatuses = data.stop_statuses;
                    if (data.eta_list) {
                        etaList = data.eta_list;
                    }

                    // Rerender Map & Checklist
                    drawRouteOnMap();
                    renderChecklist();

                    // Redirect guidance to the next pending client immediately
                    // (e.g. after skipping a stop whose road is congested).
                    if (data.live_route) {
                        drawLiveRoute(data.live_route);
                    }
                } else {
                    alert('Failed to update stop status.');
                }
            } catch (err) {
                console.error(err);
                alert('Connection error occurred.');
            }
        }

        function toggleTracking() {
            const btn = document.getElementById('trackBtn');
            const indicator = document.getElementById('statusIndicator');
            const msg = document.getElementById('statusMsg');

            if (!isTracking) {
                if (navigator.geolocation) {
                    msg.textContent = "Acquiring GPS signal...";
                    
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            isTracking = true;
                            btn.classList.add('active');
                            btn.innerHTML = '<i class="bi bi-stop-circle-fill me-2"></i>Stop Location Sharing';
                            indicator.classList.remove('d-none');
                            msg.className = "text-success fw-semibold";
                            msg.textContent = "Live updates broadcasting.";

                            // Initial transmit
                            sendLocationUpdate(position.coords.latitude, position.coords.longitude);

                            // Watch GPS stream
                            watchId = navigator.geolocation.watchPosition(
                                (pos) => { lastPosition = pos; },
                                (err) => { handleError(err); },
                                { enableHighAccuracy: true, maximumAge: 10000 }
                            );

                            // Periodic updates
                            updateIntervalId = setInterval(() => {
                                if (lastPosition) {
                                    sendLocationUpdate(lastPosition.coords.latitude, lastPosition.coords.longitude);
                                }
                            }, 20000);
                        },
                        (err) => { handleError(err); },
                        { enableHighAccuracy: true }
                    );
                } else {
                    msg.className = "text-danger fw-bold";
                    msg.textContent = "Geolocation not supported.";
                }
            } else {
                if (watchId) navigator.geolocation.clearWatch(watchId);
                if (updateIntervalId) clearInterval(updateIntervalId);
                
                isTracking = false;
                btn.classList.remove('active');
                btn.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Start Location Sharing';
                indicator.classList.add('d-none');
                msg.className = "text-muted";
                msg.textContent = "Tracking offline.";
                document.getElementById('lastUpdated').textContent = "";
            }
        }

        function sendLocationUpdate(lat, lng) {
            // Update Map Marker
            updateDriverMarker(lat, lng);

            fetch(`/driver/location/${token}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ latitude: lat, longitude: lng })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const msg = document.getElementById('statusMsg');
                    msg.className = "text-success fw-semibold";
                    msg.textContent = "Signal transmitted successfully.";
                    document.getElementById('lastUpdated').textContent = "Last sync: " + new Date().toLocaleTimeString();
                    
                    // Update state variables
                    stopStatuses = data.stop_statuses;
                    etaList = data.eta_list;

                    // Live guidance from the driver's actual position.
                    if (data.live_route) {
                        drawLiveRoute(data.live_route);
                    }

                    // Rerender UI
                    renderChecklist();
                    refreshDistance();
                }
            })
            .catch(err => {
                console.warn("Location sync failed", err);
                const msg = document.getElementById('statusMsg');
                msg.className = "text-warning fw-semibold";
                msg.textContent = "Weak signal. Re-syncing...";
            });
        }

        function refreshDistance() {
            fetch(`/trucks/locations`)
                .then(r => r.json())
                .then(trucks => {
                    const currentTruck = trucks.find(t => t.tracking_token === token);
                    if (currentTruck) {
                        document.getElementById('distance-val').textContent = parseFloat(currentTruck.daily_distance).toFixed(2) + " km";
                    }
                })
                .catch(e => console.warn(e));
        }

        function handleError(error) {
            const msg = document.getElementById('statusMsg');
            msg.className = "text-danger fw-bold";
            
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    msg.textContent = "Access Denied. Please enable GPS permissions.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg.textContent = "Signal Unavailable.";
                    break;
                case error.TIMEOUT:
                    msg.textContent = "GPS timeout.";
                    break;
                default:
                    msg.textContent = "An error occurred.";
            }
            if (isTracking) toggleTracking();
        }
    </script>
</body>
</html>
