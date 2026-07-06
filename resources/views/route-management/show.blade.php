@extends('layouts.contractor-sidebar')

@section('title', $contractorRoute->route_name . ' - Route Details')

@section('styles')
<style>
    :root {
        --primary-teal: #047857;
        --primary-red: #c0392b;
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
@endsection

@section('content')
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
                        <div class="client-card" 
                             data-client-id="{{ $client->id }}" 
                             data-lat="{{ $client->latitude }}" 
                             data-lng="{{ $client->longitude }}"
                             data-name="{{ $client->name }}"
                             data-address="{{ $client->address }}"
                             data-city="{{ $client->city }}"
                             data-state="{{ $client->state }}"
                             data-zipcode="{{ $client->zip_code }}"
                             data-phone="{{ $client->phone }}"
                             data-email="{{ $client->email }}"
                             data-category="{{ $client->category }}">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h5 class="mb-1">
                                        <span class="badge me-2" style="background-color: {{ $contractorRoute->color }}; color: white;">{{ $index + 1 }}</span>{{ $client->name }}
                                    </h5>
                                    <div class="phone-display text-muted small">
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
                    {{-- Base location control --}}
                    <div id="baseLocationBar" class="alert d-flex align-items-center justify-content-between py-2 px-3 mb-3"
                         style="{{ Auth::user()->latitude ? 'background:#ecfdf5;border:1px solid #a7f3d0;' : 'background:#fffbeb;border:1px solid #fde68a;' }}">
                        <div class="small">
                            <i class="bi bi-house-door-fill me-1" style="color:#047857;"></i>
                            <span id="baseLocationText">
                                @if(Auth::user()->latitude)
                                    <strong>Your base:</strong> {{ Auth::user()->location_address ?? (number_format(Auth::user()->latitude,5).', '.number_format(Auth::user()->longitude,5)) }}
                                @else
                                    <strong>No base location set.</strong> The route can't start from your yard until you set it.
                                @endif
                            </span>
                        </div>
                        <button type="button" id="setBaseBtn" class="btn btn-sm btn-outline-success text-nowrap">
                            <i class="bi bi-geo-alt me-1"></i>{{ Auth::user()->latitude ? 'Update base' : 'Set my base' }}
                        </button>
                    </div>

                    <div id="map" style="height: 520px; border-radius: 8px; border: 1px solid #e2e8f0; z-index: 1;"></div>

                    {{-- Legend --}}
                    <div class="d-flex flex-wrap gap-3 mt-3 small">
                        <span><i class="bi bi-house-door-fill" style="color:#2563eb;"></i> Base (start)</span>
                        <span><i class="bi bi-circle-fill" style="color:{{ $contractorRoute->color }};"></i> Client stops</span>
                        <span><i class="bi bi-trash3-fill" style="color:#c0392b;"></i> Dumping site (end)</span>
                    </div>
                    <div class="mt-2 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>Path calculated sequentially along official roads: base → clients → dumping site. Click client cards to locate on map.
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
        const routeColor = "{{ $contractorRoute->color ?? '#047857' }}";
        const token = "{{ config('services.heigit.api_key') }}";
        const markerEntries = {};
        let routeCoordinates = [];

        // Contractor base (start) and the route's dumping site (end).
        @php
            $u = Auth::user();
            $baseLoc = ($u->latitude && $u->longitude)
                ? ['lat' => (float) $u->latitude, 'lng' => (float) $u->longitude, 'address' => $u->location_address]
                : null;
        @endphp
        let contractorBase = @json($baseLoc);
        const dumpingSite = @json($dumpingSite ? ['name' => $dumpingSite['name'], 'lat' => (float) $dumpingSite['latitude'], 'lng' => (float) $dumpingSite['longitude']] : null);
        const saveBaseUrl = "{{ route('location.update') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        GreenRouteMap.whenReady(async function () {
            const mapEl = document.getElementById('map');
            if (!mapEl) return;

            const clientsWithCoords = [];
            const clientsWithoutCoords = [];

            clients.forEach((client, index) => {
                if (client.latitude && client.longitude) {
                    clientsWithCoords.push({...client, originalIndex: index});
                } else {
                    clientsWithoutCoords.push({...client, originalIndex: index});
                }
            });

            if (clientsWithCoords.length === 0 && clientsWithoutCoords.length === 0) {
                GreenRouteMap.showMapError('map', 'No clients assigned to this route.');
                document.getElementById('route-distance-badge').textContent = 'Distance: 0 km';
                return;
            }

            // Geocode clients without coordinates using Nominatim (free OSM geocoder)
            if (clientsWithoutCoords.length > 0) {
                const geocoded = await geocodeClients(clientsWithoutCoords);
                clientsWithCoords.push(...geocoded);
            }

            // Build route coordinates array
            clientsWithCoords.forEach((client) => {
                routeCoordinates.push({
                    id: client.id,
                    lat: parseFloat(client.latitude),
                    lng: parseFloat(client.longitude),
                    name: client.name,
                    address: [client.address, client.city, client.state, client.zip_code].filter(Boolean).join(', '),
                    index: 0
                });
            });

            // Optimize clients using nearest-neighbor, seeded from the base if we have one.
            const optimizedRoute = optimizeRoute(routeCoordinates, contractorBase);

            // Create map centred on the base (or first stop).
            const center = contractorBase || optimizedRoute[0];
            mapCtx = GreenRouteMap.createMap('map', { lat: center.lat, lng: center.lng, zoom: 13 });

            if (!mapCtx) return;

            // Full path drawn on the map: base -> clients -> dumping site.
            const fullPath = [];

            // Base marker (start).
            if (contractorBase) {
                GreenRouteMap.addMarker(mapCtx, contractorBase.lat, contractorBase.lng, {
                    title: 'Your base',
                    icon: L.divIcon({
                        className: 'gr-endpoint-marker',
                        html: `<div style="background:#2563eb;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-house-door-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
                        iconSize: [30, 30], iconAnchor: [15, 28],
                    }),
                    popup: `<strong>Start: Your base</strong><br>${contractorBase.address || ''}`,
                });
                fullPath.push({ lat: contractorBase.lat, lng: contractorBase.lng });
            }

            // Plot optimized client markers with sequence numbers.
            optimizedRoute.forEach((point, i) => {
                point.index = i + 1;
                const markerEntry = GreenRouteMap.addNumberedMarker(mapCtx, point.lat, point.lng, point.index, {
                    title: point.name,
                    popup: `<strong>Stop ${point.index}: ${point.name}</strong><br>${point.address || ''}`,
                });
                markerEntries[point.id] = markerEntry;
                fullPath.push({ lat: point.lat, lng: point.lng });
            });

            // Dumping site marker (end).
            if (dumpingSite) {
                GreenRouteMap.addMarker(mapCtx, dumpingSite.lat, dumpingSite.lng, {
                    title: dumpingSite.name,
                    icon: L.divIcon({
                        className: 'gr-endpoint-marker',
                        html: `<div style="background:#c0392b;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-trash3-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
                        iconSize: [30, 30], iconAnchor: [15, 28],
                    }),
                    popup: `<strong>End: ${dumpingSite.name}</strong><br>Dumping / disposal site`,
                });
                fullPath.push({ lat: dumpingSite.lat, lng: dumpingSite.lng });
            }

            GreenRouteMap.fitBounds(mapCtx, fullPath);

            // Draw driving route along the full path.
            if (fullPath.length >= 2) {
                const summary = await GreenRouteMap.drawRoadRoute(mapCtx, fullPath, token);
                if (summary) {
                    document.getElementById('route-distance-badge').textContent = `Distance: ${summary.distance.toFixed(1)} km`;
                } else {
                    GreenRouteMap.drawPolyline(mapCtx, fullPath, { color: routeColor });
                    const distance = calculateDirectDistance(fullPath);
                    document.getElementById('route-distance-badge').textContent = `Distance: ${distance} km (direct)`;
                }
            } else {
                document.getElementById('route-distance-badge').textContent = 'Distance: 0 km';
            }

            // Client Cards Interactions
            rebuildClientCards(optimizedRoute);
        });

        function rebuildClientCards(optimizedRoute) {
            // Update stop numbers on existing cards
            const stopMap = {};
            optimizedRoute.forEach((point, i) => {
                stopMap[point.id] = { ...point, stopNumber: i + 1 };
            });

            document.querySelectorAll('.client-card').forEach(card => {
                const clientId = parseInt(card.getAttribute('data-client-id'));
                const stop = stopMap[clientId];

                if (!stop) return;

                const lat = stop.lat;
                const lng = stop.lng;
                const name = stop.name;
                const address = stop.address;
                const phone = card.dataset.phone || '';
                const email = card.dataset.email || '';
                const category = card.dataset.category || '';
                const color = "{{ $contractorRoute->color }}";

                card.style.cursor = 'pointer';
                card.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <h5 class="mb-1">
                                <span class="badge me-2" style="background-color: ${color}; color: white;">${stop.stopNumber}</span>${name}
                            </h5>
                            ${phone ? `<div class="text-muted small"><i class="bi bi-telephone me-1"></i>${phone}</div>` : ''}
                            ${email ? `<div class="text-muted small"><i class="bi bi-envelope me-1"></i>${email}</div>` : ''}
                        </div>
                        <div class="col-md-5">
                            <div class="small">
                                <i class="bi bi-geo-alt text-primary me-1"></i>
                                <strong>${address}</strong>
                            </div>
                            ${stop.lat ? `<div class="small text-muted mt-1"><i class="bi bi-pin-map-fill me-1"></i>GPS: ${stop.lat.toFixed(6)}, ${stop.lng.toFixed(6)}</div>` : '<div class="small text-warning mt-1"><i class="bi bi-search me-1"></i>Location estimated from address</div>'}
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="badge bg-${category === 'residential' ? 'success' : 'warning'} d-block mb-1">${category ? category.charAt(0).toUpperCase() + category.slice(1) : ''}</span>
                        </div>
                    </div>`;

                card.addEventListener('click', function () {
                    if (mapCtx) {
                        GreenRouteMap.setView(mapCtx, lat, lng, 15);
                        const markerEntry = markerEntries[clientId];
                        if (markerEntry && markerEntry.leaflet) {
                            markerEntry.leaflet.openPopup();
                        }
                    }
                });

                card.addEventListener('mouseenter', function () {
                    card.style.backgroundColor = '#f1f5f9';
                });
                card.addEventListener('mouseleave', function () {
                    card.style.backgroundColor = '';
                });
            });
        }

        async function geocodeClients(clients) {
            const results = [];
            for (const client of clients) {
                const addressParts = [client.address, client.city, client.state, client.zip_code].filter(Boolean);
                const address = addressParts.join(', ');
                if (!address) {
                    client.latitude = null;
                    client.longitude = null;
                    results.push(client);
                    continue;
                }

                client.latitude = null;
                client.longitude = null;
                results.push(client);

                try {
                    const response = await fetch(`/dashboard/contractor/clients/${client.id}/geocode`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    const data = await response.json();
                    if (data.success && data.data) {
                        client.latitude = data.data.latitude;
                        client.longitude = data.data.longitude;
                    }
                } catch (err) {
                    console.warn('Geocoding failed, will try direct Nominatim', err);
                    try {
                        const fallbackUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`;
                        const fallbackResp = await fetch(fallbackUrl, {
                            headers: { 'User-Agent': '{{ env('NOMINATIM_USER_AGENT', 'GreenRoute/1.0') }}' }
                        });
                        const fallbackData = await fallbackResp.json();
                        if (fallbackData && fallbackData.length > 0) {
                            client.latitude = parseFloat(fallbackData[0].lat);
                            client.longitude = parseFloat(fallbackData[0].lon);
                        }
                        await new Promise(resolve => setTimeout(resolve, 1100));
                    } catch (directErr) {
                        console.warn('Direct Nominatim also failed', directErr);
                    }
                }
            }
            return results;
        }

        function optimizeRoute(coordinates, startPoint = null) {
            // Filter out any entries with invalid coordinates
            const valid = coordinates.filter(c => c.lat && c.lng &&
                !isNaN(parseFloat(c.lat)) && !isNaN(parseFloat(c.lng)));

            if (valid.length <= 1) return valid;

            const optimized = [];
            const used = new Set();

            // Seed from the client nearest to the base (if set), else the first client.
            let current = valid[0];
            if (startPoint && startPoint.lat && startPoint.lng) {
                let seedDist = Infinity;
                valid.forEach(c => {
                    const d = GreenRouteMap.haversineKm(startPoint.lat, startPoint.lng, c.lat, c.lng);
                    if (d < seedDist) { seedDist = d; current = c; }
                });
            }
            optimized.push(current);
            used.add(current.id);

            while (used.size < valid.length) {
                let nearestIdx = -1;
                let nearestDist = Infinity;
                const currLat = optimized[optimized.length - 1].lat;
                const currLng = optimized[optimized.length - 1].lng;

                for (let i = 0; i < valid.length; i++) {
                    if (used.has(valid[i].id)) continue;
                    const d = GreenRouteMap.haversineKm(
                        currLat, currLng,
                        valid[i].lat, valid[i].lng
                    );
                    if (d < nearestDist) {
                        nearestDist = d;
                        nearestIdx = i;
                    }
                }

                if (nearestIdx !== -1) {
                    optimized.push(valid[nearestIdx]);
                    used.add(valid[nearestIdx].id);
                } else {
                    break;
                }
            }

            return optimized;
        }

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

        // --- Set / update the contractor's base (start) location via device GPS ---
        const setBaseBtn = document.getElementById('setBaseBtn');
        if (setBaseBtn) {
            setBaseBtn.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by this browser.');
                    return;
                }
                const original = setBaseBtn.innerHTML;
                setBaseBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Locating...';
                setBaseBtn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        const accuracy = Math.round(pos.coords.accuracy);

                        if (accuracy > 500 && !confirm(`GPS accuracy is low (±${accuracy}m) — you may be on a laptop/Wi-Fi. Save this as your base anyway?`)) {
                            setBaseBtn.innerHTML = original;
                            setBaseBtn.disabled = false;
                            return;
                        }

                        fetch(saveBaseUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ latitude: lat, longitude: lng })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Reload so the route recalculates from the new base.
                                location.reload();
                            } else {
                                throw new Error(data.message || 'Could not save base location.');
                            }
                        })
                        .catch(err => {
                            alert(err.message || 'Failed to save base location.');
                            setBaseBtn.innerHTML = original;
                            setBaseBtn.disabled = false;
                        });
                    },
                    function () {
                        alert('Could not get your location. Please allow location access and try again.');
                        setBaseBtn.innerHTML = original;
                        setBaseBtn.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 }
                );
            });
        }
    </script>
@endsection

@push('scripts')
@endpush
