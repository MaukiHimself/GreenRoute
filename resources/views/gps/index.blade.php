<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #055c5c;
            --primary-light: #088b8b;
            --primary-trans: rgba(5, 92, 92, 0.08);
            --secondary-color: #640404;
            --white-color: #ffffff;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --radius-lg: 24px;
            --radius-md: 14px;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 20px 30px -10px rgba(5, 92, 92, 0.08);
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            padding: 0;
            margin: 0;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }
        
        .container-fluid {
            padding: 2.5rem;
            max-width: 1500px;
        }
        
        /* Header Section */
        .page-header {
            padding-bottom: 1.5rem;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        /* Content Sections */
        .content-section {
            background: var(--white-color);
            border-radius: var(--radius-lg);
            padding: 2.25rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .section-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Form Elements */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--text-dark);
            background-color: #f8fafc;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            background-color: var(--white-color);
            box-shadow: 0 0 0 4px var(--primary-trans);
            outline: none;
        }
        
        /* Buttons */
        .btn {
            border-radius: var(--radius-md);
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.925rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.15);
            width: 100%;
        }
        
        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 92, 92, 0.25);
            color: white;
        }
        
        .btn-sm {
            padding: 0.45rem 0.85rem;
            font-size: 0.825rem;
            border-radius: 10px;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 1.5px solid var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }
        
        .btn-outline-success {
            color: #10b981;
            border: 1.5px solid #10b981;
            background: transparent;
        }
        
        .btn-outline-success:hover {
            background: #10b981;
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            color: var(--text-muted);
            border: 1.5px solid var(--border-color);
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #f1f5f9;
            color: var(--text-dark);
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            color: #ef4444;
            border: 1.5px solid #ef4444;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-1px);
        }
        
        /* Truck List */
        .trucks-container {
            background: #f8fafc;
            border-radius: var(--radius-md);
            padding: 1.25rem;
            max-height: 480px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
        }
        
        .trucks-container::-webkit-scrollbar {
            width: 6px;
        }
        .trucks-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .trucks-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .truck-item {
            background: var(--white-color);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            margin-bottom: 0.85rem;
            border-left: 4px solid var(--primary-color);
            border-top: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
        }
        
        .truck-item:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-md);
            border-color: rgba(5, 92, 92, 0.2);
        }
        
        .truck-item:last-child {
            margin-bottom: 0;
        }
        
        .truck-plate {
            font-weight: 800;
            color: var(--primary-color);
            font-size: 1.15rem;
            letter-spacing: -0.01em;
        }
        
        .truck-driver {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }
        
        .truck-phone {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        
        .truck-badge {
            background: var(--primary-trans);
            color: var(--primary-color);
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.775rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .status-badge {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .status-online {
            background: #dcfce7;
            color: #15803d;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            border: 1px solid #bbf7d0;
        }
        
        .status-offline {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .distance-display {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.95rem;
        }
        
        .truck-actions {
            display: flex;
            gap: 0.35rem;
            margin-top: 1.1rem;
            flex-wrap: wrap;
        }

        .truck-actions form {
            margin: 0;
            display: inline-block;
        }
        
        /* Map Container */
        .map-container {
            background: var(--white-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            height: 620px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        #map {
            height: 100%;
            width: 100%;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-muted);
        }
        
        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 0.75rem;
            color: #cbd5e1;
            display: block;
        }
        
        /* Refresh Button */
        .refresh-btn {
            background: var(--white-color);
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 0.5rem 1.25rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        
        .refresh-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1.5rem;
            }
            
            .map-container {
                height: 400px;
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .truck-actions {
                flex-direction: column;
            }
        }

        /* Custom Leaflet Truck Markers */
        .gr-truck-marker {
            background: transparent !important;
            border: none !important;
        }
        .gr-truck-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            border: 2px solid white;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        .gr-truck-badge.online {
            background-color: #22c55e;
            animation: markerPulse 2s infinite;
        }
        .gr-truck-badge.offline {
            background-color: #64748b;
        }
        @keyframes markerPulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">GPS Tracker</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column - Truck Management -->
            <div class="col-lg-4">
                <!-- Register New Truck -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Register New Truck</h2>
                    </div>
                    
                    <form method="POST" action="{{ route('trucks.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Plate Number</label>
                            <input type="text" class="form-control" name="plate_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Driver Name</label>
                            <input type="text" class="form-control" name="driver_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Driver Phone</label>
                            <input type="text" class="form-control" name="driver_phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Truck Type</label>
                            <select class="form-select" name="truck_type" required>
                                <option value="">Select Type</option>
                                <option value="small">Small Truck</option>
                                <option value="medium">Medium Truck</option>
                                <option value="large">Large Truck</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Register Truck</button>
                    </form>
                </div>
                
                <!-- Registered Trucks -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Registered Trucks</h2>
                    </div>
                    
                    <div class="trucks-container" id="trucksList">
                        @forelse($trucks as $truck)
                        <div class="truck-item" id="truck-{{ $truck->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <div class="truck-plate">{{ $truck->plate_number }}</div>
                                    <div class="truck-driver">{{ $truck->driver_name }}</div>
                                    <div class="truck-phone">{{ $truck->driver_phone }}</div>
                                    <span class="truck-badge">{{ ucfirst($truck->truck_type) }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="status-badge" id="status-{{ $truck->id }}">
                                        @if($truck->last_updated && $truck->last_updated->diffInMinutes(now()) < 10)
                                            <span class="status-online">Online</span>
                                        @else
                                            <span class="status-offline">Offline</span>
                                        @endif
                                    </div>
                                    <div class="distance-display mt-1" id="distance-{{ $truck->id }}">
                                        {{ number_format($truck->daily_distance, 2) }} km
                                    </div>
                                </div>
                            </div>
                            <div class="truck-actions">
                                <button class="btn btn-outline-primary btn-sm" onclick="trackTruck({{ $truck->id }})">
                                    <i class="bi bi-geo-alt me-1"></i>Track
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="simulateMovement({{ $truck->id }})">
                                    <i class="bi bi-play-circle me-1"></i>Simulate
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="copyTrackingLink('{{ url('/driver/track/' . $truck->tracking_token) }}', this)">
                                    <i class="bi bi-link-45deg me-1"></i>Copy Link
                                </button>
                                <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this truck?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i>Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="bi bi-truck"></i>
                            <p class="mb-0">No trucks registered</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Map -->
            <div class="col-lg-8">
                <div class="content-section p-0">
                    <div class="section-header p-3">
                        <h2 class="section-title">Live Truck Locations</h2>
                        <button class="refresh-btn" onclick="refreshLocations()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="map-container">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx;
        const truckMarkers = {};
        
        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -6.7924, lng: 39.2083, zoom: 12 });
            loadTruckLocations();
            setInterval(refreshLocations, 30000);
        });
        
        function loadTruckLocations() {
            fetch('/trucks/locations')
                .then(response => response.json())
                .then(trucks => {
                    trucks.forEach(truck => {
                        if (truck.current_latitude && truck.current_longitude) {
                            updateTruckMarker(truck);
                        }

                        // Update status badge dynamically
                        const statusBadge = document.getElementById(`status-${truck.id}`);
                        if (statusBadge) {
                            if (truck.is_online) {
                                statusBadge.innerHTML = '<span class="status-online">Online</span>';
                            } else {
                                statusBadge.innerHTML = '<span class="status-offline">Offline</span>';
                            }
                        }

                        // Update distance dynamically
                        const distanceDisplay = document.getElementById(`distance-${truck.id}`);
                        if (distanceDisplay) {
                            distanceDisplay.textContent = parseFloat(truck.daily_distance).toFixed(2) + ' km';
                        }
                    });
                });
        }
        
        function updateTruckMarker(truck) {
            if (!mapCtx) return;

            if (truckMarkers[truck.id]) {
                mapCtx.markerLayer.removeLayer(truckMarkers[truck.id].leaflet);
            }

            const lat = parseFloat(truck.current_latitude);
            const lng = parseFloat(truck.current_longitude);
            const popup = `
                <div style="min-width: 200px;">
                    <div style="font-weight: 700; color: #055c5c; margin-bottom: 0.5rem;">${truck.plate_number}</div>
                    <div><strong>Driver:</strong> ${truck.driver_name}</div>
                    <div><strong>Phone:</strong> ${truck.driver_phone}</div>
                    <div><strong>Distance Today:</strong> ${parseFloat(truck.daily_distance).toFixed(2)} km</div>
                    <div><strong>Last Updated:</strong> ${truck.last_updated ? new Date(truck.last_updated).toLocaleTimeString() : 'Never'}</div>
                </div>`;

            // Custom Leaflet DivIcon for vehicles
            const truckIcon = L.divIcon({
                className: 'gr-truck-marker',
                html: `<div class="gr-truck-badge ${truck.is_online ? 'online' : 'offline'}"><i class="bi bi-truck"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18]
            });

            truckMarkers[truck.id] = GreenRouteMap.addMarker(mapCtx, lat, lng, {
                title: truck.plate_number,
                popup,
                icon: truckIcon
            });
        }
        
        function trackTruck(truckId) {
            const entry = truckMarkers[truckId];
            if (entry && mapCtx) {
                GreenRouteMap.setView(mapCtx, entry.lat, entry.lng, 15);
                if (entry.leaflet) {
                    entry.leaflet.openPopup();
                }
            } else {
                alert('No location data received yet for this vehicle.');
            }
        }
        
        function refreshLocations() {
            loadTruckLocations();
        }
        
        function simulateMovement(truckId) {
            // Simulate truck movement for demo purposes
            const lat = -6.7924 + (Math.random() - 0.5) * 0.1;
            const lng = 39.2083 + (Math.random() - 0.5) * 0.1;
            
            fetch(`/trucks/${truckId}/location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    refreshLocations();
                }
            });
        }

        function copyTrackingLink(url, btn) {
            navigator.clipboard.writeText(url).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Copied!';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success', 'text-white');
                btn.disabled = true;
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success', 'text-white');
                    btn.classList.add('btn-outline-secondary');
                    btn.disabled = false;
                }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
                alert('Copy failed. Link is: ' + url);
            });
        }
    </script>
</body>
</html>