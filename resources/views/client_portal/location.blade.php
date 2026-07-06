<x-dashboard-layout title="My Location">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.location') }}"><i class="bi bi-geo-alt me-2"></i>My Location</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.chats') }}"><i class="bi bi-chat-dots me-2"></i>Chats</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.support') }}"><i class="bi bi-headset me-2"></i>Support</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Client</a></li>
        <li class="breadcrumb-item active">My Location</li>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-geo-alt-fill me-2"></i>Your Location</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($client->route)
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-signpost-split me-2"></i>
                        <strong>Route: {{ $client->route }}</strong>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.location.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Current Address</label>
                        <input type="text" class="form-control" value="{{ $client->address ?? 'Not set' }}" readonly>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $client->latitude) }}" placeholder="e.g. -3.3731000" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $client->longitude) }}" placeholder="e.g. 36.8822000" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="watchLocation" class="btn btn-primary btn-lg">
                            <i class="bi bi-crosshair me-2"></i>Detect My Location
                        </button>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetLocation()">
                            <i class="bi bi-x-circle me-2"></i>Reset
                        </button>
                    </div>

                    <div id="locationStatus" class="alert alert-info py-3 mb-3">
                        <i class="bi bi-info-circle me-2"></i>Click "Detect My Location" to capture your GPS coordinates.
                    </div>

                    <div class="mb-4">
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Save My Location
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            @if($routeClients->count() > 0)
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-signpost-split me-2"></i>Route Clients ({{ $routeClients->count() }})</h2>
                </div>
                <div class="mb-3">
                    <strong>Distance:</strong>
                    <span id="routeDistance" class="badge bg-primary ms-2">Calculating...</span>
                </div>
                <div class="list-group">
                    @foreach($routeClients as $rc)
                    <div class="list-group-item @if($rc->id === $client->id) list-group-item-success @endif">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $rc->name }}</strong>
                                @if($rc->id === $client->id)
                                    <span class="badge bg-success ms-2">You</span>
                                @endif
                                <div class="small text-muted">{{ $rc->address }}</div>
                            </div>
                            @if($rc->latitude && $rc->longitude)
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="panToClient({{ $rc->latitude }}, {{ $rc->longitude }})">
                                    <i class="bi bi-geo-alt"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-shield-check me-2"></i>Privacy</h2>
                </div>
                <p class="text-muted small mb-0">
                    Your GPS coordinates are stored securely and are only visible to your assigned waste contractor for route planning. We do not share your location with third parties.
                </p>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx, locationMarker;
        const existingLat = {{ $client->latitude ?? 'null' }};
        const existingLng = {{ $client->longitude ?? 'null' }};
        const routeClients = @json($routeClients);
        const routeColor = "{{ $contractorRoute->color ?? '#047857' }}";

        GreenRouteMap.whenReady(function () {
            let defaultLat = -3.3731;
            let defaultLng = 36.8822;

            if (existingLat && existingLng) {
                defaultLat = parseFloat(existingLat);
                defaultLng = parseFloat(existingLng);
            }

            mapCtx = GreenRouteMap.createMap('map', { lat: defaultLat, lng: defaultLng, zoom: existingLat ? 14 : 12 });

            if (existingLat && existingLng) {
                locationMarker = L.marker([defaultLat, defaultLng], {
                    draggable: true,
                    title: 'Your Location'
                }).addTo(mapCtx.map);
                document.getElementById('locationStatus').innerHTML = `<i class="bi bi-check-circle me-2"></i>Location loaded: ${defaultLat.toFixed(6)}, ${defaultLng.toFixed(6)}. Drag marker to adjust or click "Detect My Location".`;
            } else {
                document.getElementById('locationStatus').innerHTML = '<i class="bi bi-info-circle me-2"></i>No location set. Click "Detect My Location" or click on the map to set your location.';
            }

            mapCtx.map.on('click', function (event) {
                const pos = event.latlng;
                setLocation(pos.lat, pos.lng);
            });

            if (locationMarker) {
                locationMarker.on('dragend', function (event) {
                    const pos = event.target.getLatLng();
                    setLocation(pos.lat, pos.lng);
                });
            }

            // Add route clients to map
            if (routeClients.length > 0) {
                const routeCoordinates = [];
                routeClients.forEach(client => {
                    if (client.latitude && client.longitude) {
                        const lat = parseFloat(client.latitude);
                        const lng = parseFloat(client.longitude);
                        routeCoordinates.push({ lat, lng });

                        const isYou = client.id === {{ $client->id }};
                        const markerColor = isYou ? '#10b981' : routeColor;
                        const markerIcon = L.divIcon({
                            className: 'custom-marker',
                            html: `<div style="background: ${markerColor}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });

                        L.marker([lat, lng], { icon: markerIcon })
                            .addTo(mapCtx.map)
                            .bindPopup(`<strong>${client.name}</strong>${isYou ? ' <span class="badge bg-success">You</span>' : ''}<br>${client.address}`);
                    }
                });

                // Fit bounds to show all route clients
                if (routeCoordinates.length > 0) {
                    const bounds = L.latLngBounds(routeCoordinates.map(c => [c.lat, c.lng]));
                    mapCtx.map.fitBounds(bounds, { padding: [50, 50] });

                    // Calculate and draw driving road route
                    const token = "{{ config('services.heigit.api_key') }}";
                    if (routeCoordinates.length >= 2) {
                        GreenRouteMap.drawRoadRoute(mapCtx, routeCoordinates, token).then(summary => {
                            if (summary) {
                                document.getElementById('routeDistance').textContent = `${summary.distance.toFixed(1)} km`;
                            } else {
                                // Fallback to direct polyline
                                L.polyline(routeCoordinates.map(c => [c.lat, c.lng]), {
                                    color: routeColor,
                                    weight: 3,
                                    opacity: 0.7,
                                    dashArray: '10, 10'
                                }).addTo(mapCtx.map);
                                const totalDistance = calculateRouteDistance(routeCoordinates);
                                document.getElementById('routeDistance').textContent = `${totalDistance.toFixed(1)} km (direct)`;
                            }
                        }).catch(err => {
                            console.error('Error drawing road route:', err);
                            L.polyline(routeCoordinates.map(c => [c.lat, c.lng]), {
                                color: routeColor,
                                weight: 3,
                                opacity: 0.7,
                                dashArray: '10, 10'
                            }).addTo(mapCtx.map);
                            const totalDistance = calculateRouteDistance(routeCoordinates);
                            document.getElementById('routeDistance').textContent = `${totalDistance.toFixed(1)} km (direct)`;
                        });
                    } else {
                        document.getElementById('routeDistance').textContent = '0 km';
                    }
                }
            }

            document.getElementById('watchLocation').addEventListener('click', watchPreciseLocation);
        });

        function setLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (locationMarker) {
                locationMarker.setLatLng([lat, lng]);
            } else if (mapCtx) {
                locationMarker = L.marker([lat, lng], { draggable: true, title: 'Your Location' }).addTo(mapCtx.map);
                locationMarker.on('dragend', function (event) {
                    const pos = event.target.getLatLng();
                    setLocation(pos.lat, pos.lng);
                });
            }
            if (mapCtx) mapCtx.map.setView([lat, lng], 16);
            document.getElementById('locationStatus').innerHTML = `<i class="bi bi-check-circle me-2"></i>Location set: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }

        function resetLocation() {
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            if (locationMarker) {
                mapCtx.map.removeLayer(locationMarker);
                locationMarker = null;
            }
            document.getElementById('locationStatus').innerHTML = '<i class="bi bi-info-circle me-2"></i>Location reset. Click "Detect My Location" or click on the map to set your location.';
        }

        function panToClient(lat, lng) {
            if (mapCtx) {
                mapCtx.map.setView([lat, lng], 16);
            }
        }

        function calculateRouteDistance(coordinates) {
            let totalDistance = 0;
            for (let i = 1; i < coordinates.length; i++) {
                totalDistance += haversineDistance(
                    coordinates[i - 1].lat,
                    coordinates[i - 1].lng,
                    coordinates[i].lat,
                    coordinates[i].lng
                );
            }
            return totalDistance;
        }

        function haversineDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        let watchId = null;
        let locationAttempts = 0;

        function watchPreciseLocation() {
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            locationAttempts = 0;
            const statusEl = document.getElementById('locationStatus');
            statusEl.innerHTML = '<i class="bi bi-crosshair me-2"></i>Detecting your GPS location... Please allow access if prompted.';
            statusEl.className = 'alert alert-warning py-3 mb-3';

            let bestAccuracy = Infinity;
            let bestPosition = null;
            const maxAttempts = 20;
            const targetAccuracy = 20;

            if (!navigator.geolocation) {
                statusEl.innerHTML = '<i class="bi bi-x-circle me-2"></i>GPS not supported by this browser. Please try a different browser.';
                statusEl.className = 'alert alert-danger py-3 mb-3';
                return;
            }

            watchId = navigator.geolocation.watchPosition(
                function (position) {
                    locationAttempts++;
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    statusEl.innerHTML = `<i class="bi bi-crosshair me-2"></i>Detecting (${locationAttempts}/${maxAttempts}) — Accuracy: ±${Math.round(accuracy)}m`;

                    if (accuracy < bestAccuracy) {
                        bestAccuracy = accuracy;
                        bestPosition = position;
                        setLocation(lat, lng);
                    }

                    if (accuracy <= targetAccuracy || locationAttempts >= maxAttempts) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                        const finalAccuracy = bestPosition ? Math.round(bestPosition.coords.accuracy) : Math.round(accuracy);
                        statusEl.className = 'alert alert-success py-3 mb-3';
                        statusEl.innerHTML = `<i class="bi bi-check-circle me-2"></i>GPS captured! Accuracy: ±${finalAccuracy}m. Click "Save My Location" to confirm.`;

                        if (bestPosition && bestPosition !== position) {
                            setLocation(bestPosition.coords.latitude, bestPosition.coords.longitude);
                        }
                    }
                },
                function (error) {
                    let msg = 'Could not acquire coordinates.';
                    if (error.code === error.PERMISSION_DENIED) {
                        msg = 'Permission denied. Please enable location access in your browser settings and try again.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        msg = 'Location information unavailable. Please try again or click on the map to set your location manually.';
                    } else if (error.code === error.TIMEOUT) {
                        msg = 'Location request timed out. Please try again or click on the map to set your location manually.';
                    }
                    statusEl.className = 'alert alert-danger py-3 mb-3';
                    statusEl.innerHTML = `<i class="bi bi-x-circle me-2"></i>${msg}`;
                    if (watchId) { navigator.geolocation.clearWatch(watchId); watchId = null; }
                },
                { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 }
            );
        }
    </script>
</x-dashboard-layout>
