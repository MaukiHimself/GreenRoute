<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contractorRoute->route_name }} - Route Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, {{ $contractorRoute->color }}, {{ $contractorRoute->color }}dd);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .client-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid {{ $contractorRoute->color }};
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .client-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transform: translateX(4px);
        }
        
        .badge-custom {
            background: {{ $contractorRoute->color }};
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="bi bi-signpost-split me-2"></i>{{ $contractorRoute->route_name }}
                    </h1>
                    @if($contractorRoute->description)
                        <p class="mb-0 opacity-90">{{ $contractorRoute->description }}</p>
                    @endif
                    <div class="mt-3">
                        <span class="badge-custom">
                            {{ $contractorRoute->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('route-management.edit', $contractorRoute) }}" class="btn btn-light">
                        <i class="bi bi-pencil me-2"></i>Edit Route
                    </a>
                    <a href="{{ route('route-management.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->count() }}</h3>
                            <p class="text-muted mb-0">Total Clients</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->where('category', 'residential')->count() }}</h3>
                            <p class="text-muted mb-0">Residential</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-building text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->where('category', 'commercial')->count() }}</h3>
                            <p class="text-muted mb-0">Commercial</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients & Map Section -->
        <div class="row mb-4">
            <!-- Left: Clients List -->
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <div class="info-card" style="max-height: 600px; overflow-y: auto;">
                    <h4 class="mb-4">
                        <i class="bi bi-people me-2"></i>Clients on This Route
                    </h4>
                    
                    @forelse($clients as $index => $client)
                        <div class="client-card" data-client-id="{{ $client->id }}" data-lat="{{ $client->latitude }}" data-lng="{{ $client->longitude }}" data-name="{{ $client->name }}">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h5 class="mb-1">
                                        <span class="badge me-2" style="background-color: {{ $contractorRoute->color }}; color: white;">{{ $index + 1 }}</span>{{ $client->name }}
                                    </h5>
                                    <div class="text-muted small">
                                        <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                                    </div>
                                    @if($client->email)
                                        <div class="text-muted small">
                                            <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <div class="small">
                                        <i class="bi bi-geo-alt text-primary me-1"></i>
                                        <strong>{{ $client->address }}</strong>
                                    </div>
                                    <div class="small text-muted">
                                        {{ $client->city }}, {{ $client->state }} {{ $client->zip_code }}
                                    </div>
                                    @if($client->latitude && $client->longitude)
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-pin-map-fill me-1"></i>
                                            GPS: {{ number_format($client->latitude, 6) }}, {{ number_format($client->longitude, 6) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="badge bg-{{ $client->category == 'residential' ? 'success' : 'warning' }} d-block mb-1">
                                        {{ ucfirst($client->category) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">No clients assigned to this route yet</p>
                            <a href="{{ route('route-management.edit', $contractorRoute) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add Clients
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Map -->
            <div class="col-lg-6 col-md-12">
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="bi bi-map me-2"></i>Route Path on Map
                        </h4>
                        <span class="badge" id="route-distance-badge" style="background-color: {{ $contractorRoute->color }}; color: white; padding: 0.5rem 1rem;">Distance: Calculating...</span>
                    </div>
                    <div id="map" style="height: 520px; border-radius: 8px; border: 1px solid #e2e8f0; z-index: 1;"></div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>Path calculated sequentially along official roads. Click client cards to locate on map.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let mapCtx;
        const clients = @json($clients);
        const routeColor = "{{ $contractorRoute->color ?? '#055c5c' }}";
        const token = "{{ config('services.heigit.api_key') }}";
        const markerEntries = {};

        GreenRouteMap.whenReady(async function () {
            const mapEl = document.getElementById('map');
            if (!mapEl) return;

            const routeCoordinates = [];
            
            clients.forEach((client, index) => {
                if (client.latitude && client.longitude) {
                    routeCoordinates.push({
                        id: client.id,
                        lat: parseFloat(client.latitude),
                        lng: parseFloat(client.longitude),
                        name: client.name,
                        address: client.address,
                        index: index + 1
                    });
                }
            });

            if (routeCoordinates.length === 0) {
                GreenRouteMap.showMapError('map', 'No client coordinates available for mapping.');
                document.getElementById('route-distance-badge').textContent = 'Distance: 0 km';
                return;
            }

            // Create map centered on first client
            mapCtx = GreenRouteMap.createMap('map', { 
                lat: routeCoordinates[0].lat, 
                lng: routeCoordinates[0].lng, 
                zoom: 13 
            });

            if (!mapCtx) return;

            // Plot markers
            routeCoordinates.forEach(point => {
                const markerEntry = GreenRouteMap.addNumberedMarker(mapCtx, point.lat, point.lng, point.index, {
                    title: point.name,
                    popup: `<strong>Stop ${point.index}: ${point.name}</strong><br>${point.address || ''}`,
                });
                markerEntries[point.id] = markerEntry;
            });

            GreenRouteMap.fitBounds(mapCtx, routeCoordinates);

            // Draw road route path sequentially
            if (routeCoordinates.length >= 2) {
                const summary = await GreenRouteMap.drawRoadRoute(mapCtx, routeCoordinates, token);
                if (summary) {
                    document.getElementById('route-distance-badge').textContent = `Distance: ${summary.distance.toFixed(1)} km`;
                } else {
                    GreenRouteMap.drawPolyline(mapCtx, routeCoordinates, { color: routeColor });
                    const distance = calculateDirectDistance(routeCoordinates);
                    document.getElementById('route-distance-badge').textContent = `Distance: ${distance} km (direct)`;
                }
            } else {
                document.getElementById('route-distance-badge').textContent = 'Distance: 0 km';
            }

            // Client Cards Interactions
            document.querySelectorAll('.client-card').forEach(card => {
                const clientId = card.getAttribute('data-client-id');
                const lat = parseFloat(card.getAttribute('data-lat'));
                const lng = parseFloat(card.getAttribute('data-lng'));

                if (!isNaN(lat) && !isNaN(lng)) {
                    card.style.cursor = 'pointer';
                    
                    // Click interaction to focus map on Stop
                    card.addEventListener('click', function () {
                        if (mapCtx) {
                            GreenRouteMap.setView(mapCtx, lat, lng, 15);
                            const markerEntry = markerEntries[clientId];
                            if (markerEntry && markerEntry.leaflet) {
                                markerEntry.leaflet.openPopup();
                            }
                        }
                    });

                    // Hover hover states
                    card.addEventListener('mouseenter', function () {
                        card.style.backgroundColor = '#f1f5f9';
                    });

                    card.addEventListener('mouseleave', function () {
                        card.style.backgroundColor = '';
                    });
                }
            });
        });

        function calculateDirectDistance(coordinates) {
            let totalDistance = 0;
            for (let i = 1; i < coordinates.length; i++) {
                totalDistance += GreenRouteMap.haversineKm(
                    coordinates[i - 1].lat,
                    coordinates[i - 1].lng,
                    coordinates[i].lat,
                    coordinates[i].lng
                );
            }
            return totalDistance.toFixed(1);
        }
    </script>
</body>
</html>
