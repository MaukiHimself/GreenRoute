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

                <form id="locationForm" method="POST" action="{{ route('client.location.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Current Address</label>
                        <input type="text" class="form-control" value="{{ $client->address ?? 'Not set' }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Search Address (Manual Lookup)</label>
                        <div class="input-group">
                            <input type="text" id="addressSearch" class="form-control" placeholder="Type address (e.g. Street Name, District, Region)...">
                            <button type="button" id="btnGeocode" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>Search Address
                            </button>
                        </div>
                        <small class="text-muted">Use this manual lookup if automatic GPS detection is inaccurate or unavailable.</small>
                        <div id="searchResults" class="list-group mt-2" style="display:none;"></div>
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

            {{-- ── Route Summary Card (no other clients' personal info) ── --}}
            @if($routeClients->count() > 0)
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-signpost-split me-2"></i>Your Collection Route</h2>
                </div>

                {{-- Route name / badge --}}
                @php $myRouteClient = $routeClients->firstWhere('id', $client->id); @endphp
                @if($client->route)
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge rounded-pill text-white px-3 py-2" style="background:{{ $contractorRoute->color ?? '#047857' }}; font-size:.85rem;">
                        <i class="bi bi-map me-1"></i>{{ $client->route }}
                    </span>
                    <span class="text-muted small">— your assigned route</span>
                </div>
                @endif

                {{-- Your position on the route --}}
                @if($client->route_sequence)
                <div class="alert alert-success py-2 px-3 mb-3 small">
                    <i class="bi bi-pin-map-fill me-2"></i>
                    You are stop <strong>#{{ $client->route_sequence }}</strong> on this route.
                </div>
                @endif

                {{-- Stats row --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-2 text-center">
                            <div class="fw-bold fs-5 text-success" id="routeDistance">—</div>
                            <div class="text-muted" style="font-size:.75rem;">Total route distance</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 p-2 text-center">
                            <div class="fw-bold fs-5 text-primary">{{ $routeClients->count() }}</div>
                            <div class="text-muted" style="font-size:.75rem;">Stops on your route</div>
                        </div>
                    </div>
                </div>

                {{-- Locate-me button --}}
                @if($client->latitude && $client->longitude)
                <button type="button" class="btn btn-sm btn-outline-success w-100 mb-1"
                        onclick="panToClient({{ $client->latitude }}, {{ $client->longitude }})">
                    <i class="bi bi-geo-alt-fill me-1"></i>Show my position on map
                </button>
                @endif

                <p class="text-muted small mt-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    The map on the left shows all stops on your route. Other clients' names and addresses are kept private.
                </p>
            </div>
            @endif

            {{-- ── Location Tips Card ── --}}
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-lightbulb me-2"></i>Location Tips</h2>
                </div>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2">
                        <i class="bi bi-phone me-2 text-success"></i>
                        <strong>Best accuracy:</strong> use your smartphone with GPS switched on.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-wifi me-2 text-warning"></i>
                        <strong>On a laptop/desktop?</strong> Wi-Fi positioning can be several kilometres off — use <em>Search Address</em> or drag the map pin instead.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                        <strong>Moved house?</strong> Simply detect or search your new address and click <em>Save My Location</em>.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-cloud-slash me-2 text-secondary"></i>
                        <strong>No internet?</strong> Your location is saved on this device and synced automatically when you come back online.
                    </li>
                    <li>
                        <i class="bi bi-cursor me-2 text-info"></i>
                        <strong>Quick set:</strong> tap anywhere on the map to pin your location, or drag the marker to fine-tune.
                    </li>
                </ul>
            </div>

            {{-- ── Next Pickup Card ── --}}
            @php
                $nextSchedule = $client->schedules()
                    ->where('status', '!=', 'cancelled')
                    ->where('pickup_date', '>=', now()->toDateString())
                    ->orderBy('pickup_date')
                    ->first();
            @endphp
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-calendar-check me-2"></i>Next Pickup</h2>
                </div>
                @if($nextSchedule)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 text-center p-2 text-white" style="background:{{ $contractorRoute->color ?? '#047857' }};min-width:54px;">
                            <div class="fw-bold fs-5 lh-1">{{ \Carbon\Carbon::parse($nextSchedule->pickup_date)->format('d') }}</div>
                            <div style="font-size:.7rem;">{{ \Carbon\Carbon::parse($nextSchedule->pickup_date)->format('M Y') }}</div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $nextSchedule->service_type ?? 'Collection')) }}</div>
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($nextSchedule->pickup_date)->diffForHumans() }}</div>
                            @if($nextSchedule->notes)
                                <div class="text-muted small">{{ Str::limit($nextSchedule->notes, 60) }}</div>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-muted small mb-0">
                        <i class="bi bi-calendar-x me-2"></i>No upcoming pickups scheduled. Contact your contractor to arrange a collection.
                    </p>
                @endif
            </div>

            {{-- ── Privacy Card ── --}}
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-shield-check me-2"></i>Privacy</h2>
                </div>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><i class="bi bi-lock-fill me-2 text-success"></i>Your GPS coordinates are encrypted in transit and stored securely.</li>
                    <li class="mb-2"><i class="bi bi-eye-slash me-2 text-primary"></i>Only your assigned waste contractor can see your location — for route planning only.</li>
                    <li class="mb-2"><i class="bi bi-people-fill me-2 text-warning"></i>Other clients on your route <strong>cannot</strong> see your name, address, or position.</li>
                    <li><i class="bi bi-share me-2 text-danger"></i>We never share your data with third parties.</li>
                </ul>
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

                        // Only show full details for the current client; other stops are anonymous.
                        const popupContent = isYou
                            ? `<strong>📍 Your location</strong><br><small>${client.address || ''}</small>`
                            : `<span class="text-muted small">Stop on your route</span>`;

                        L.marker([lat, lng], { icon: markerIcon })
                            .addTo(mapCtx.map)
                            .bindPopup(popupContent);
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
            document.getElementById('btnGeocode').addEventListener('click', geocodeManualAddress);
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

                        if (bestPosition && bestPosition !== position) {
                            setLocation(bestPosition.coords.latitude, bestPosition.coords.longitude);
                        }

                        // Accuracy > 500m almost always means a WiFi/IP-based fix (e.g. a laptop with no
                        // GPS chip) — it can be several km off. Warn the user instead of trusting it blindly.
                        if (finalAccuracy > 500) {
                            statusEl.className = 'alert alert-warning py-3 mb-3';
                            statusEl.innerHTML = `<i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Low GPS accuracy (±${finalAccuracy}m).</strong> This is usually because you're on a
                                laptop/desktop or Wi-Fi, which can place you several kilometres away.
                                For a precise location, open this page on your <strong>phone with GPS on</strong>,
                                or use <strong>Search Address</strong> above / <strong>drag the marker</strong> to your exact spot before saving.`;
                        } else {
                            statusEl.className = 'alert alert-success py-3 mb-3';
                            statusEl.innerHTML = `<i class="bi bi-check-circle me-2"></i>GPS captured! Accuracy: ±${finalAccuracy}m. Drag the marker to fine-tune, then click "Save My Location".`;
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

        function geocodeManualAddress() {
            const query = document.getElementById('addressSearch').value.trim();
            if (query.length < 3) {
                alert('Please enter at least 3 characters to search.');
                return;
            }

            const btn = document.getElementById('btnGeocode');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Searching...';
            btn.disabled = true;

            const resultsBox = document.getElementById('searchResults');
            resultsBox.style.display = 'none';
            resultsBox.innerHTML = '';

            fetch('{{ route("location.geocode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ address: query })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Geocoding failed'); });
                }
                return response.json();
            })
            .then(data => {
                const results = data.results || (data.success ? [{ latitude: data.latitude, longitude: data.longitude, display_name: data.display_name }] : []);
                if (!results.length) {
                    throw new Error(data.message || 'Could not find location');
                }

                // Single match → select it directly.
                if (results.length === 1) {
                    selectSearchResult(results[0]);
                    return;
                }

                // Multiple matches → let the user pick the correct one.
                resultsBox.innerHTML = `<div class="list-group-item bg-light small fw-semibold text-muted">${results.length} matches found — pick the correct one:</div>`;
                results.forEach(r => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<i class="bi bi-geo-alt text-primary me-2"></i>${r.display_name}`;
                    item.addEventListener('click', () => {
                        selectSearchResult(r);
                        resultsBox.style.display = 'none';
                    });
                    resultsBox.appendChild(item);
                });
                resultsBox.style.display = 'block';
            })
            .catch(error => {
                console.error('Geocoding error:', error);
                const statusEl = document.getElementById('locationStatus');
                statusEl.className = 'alert alert-danger py-3 mb-3';
                statusEl.innerHTML = `<i class="bi bi-x-circle me-2"></i>${error.message || 'Error occurred while searching address.'}`;
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function selectSearchResult(r) {
            setLocation(r.latitude, r.longitude);
            const statusEl = document.getElementById('locationStatus');
            statusEl.className = 'alert alert-success py-3 mb-3';
            statusEl.innerHTML = `<i class="bi bi-check-circle me-2"></i>Found: ${r.display_name}. Drag the marker to fine-tune, then click "Save My Location".`;
        }

        // Register Service Worker for offline support
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registered successfully with scope:', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }

        // Intercept form submission for offline storage
        document.getElementById('locationForm').addEventListener('submit', function(e) {
            if (!navigator.onLine) {
                e.preventDefault();

                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;

                if (!lat || !lng) {
                    alert('Please detect or choose your location first.');
                    return;
                }

                const offlineLoc = { latitude: parseFloat(lat), longitude: parseFloat(lng), timestamp: Date.now() };
                localStorage.setItem('pending_offline_location', JSON.stringify(offlineLoc));

                const statusEl = document.getElementById('locationStatus');
                if (statusEl) {
                    statusEl.className = 'alert alert-warning py-3 mb-3';
                    statusEl.innerHTML = `<i class="bi bi-cloud-slash me-2"></i><strong>Offline Mode:</strong> Your location coordinates (${offlineLoc.latitude.toFixed(6)}, ${offlineLoc.longitude.toFixed(6)}) have been saved locally on your device. They will be synchronized automatically with your contractor as soon as you reconnect to the internet.`;
                }

                alert('Offline Mode: Your location has been saved locally on this device. It will automatically sync once you go online.');
            }
        });

        // Offline synchronization logic
        window.addEventListener('online', syncOfflineLocation);
        
        // Check for offline updates on page load
        if (navigator.onLine) {
            syncOfflineLocation();
        }

        function syncOfflineLocation() {
            const savedLoc = localStorage.getItem('pending_offline_location');
            if (!savedLoc) return;

            const parsed = JSON.parse(savedLoc);
            const statusEl = document.getElementById('locationStatus');

            if (statusEl) {
                statusEl.className = 'alert alert-info py-3 mb-3';
                statusEl.innerHTML = '<i class="bi bi-arrow-repeat spinner me-2"></i>Syncing offline GPS location coordinates with server...';
            }

            fetch('{{ route("client.location.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify({
                    latitude: parsed.latitude,
                    longitude: parsed.longitude
                })
            })
            .then(response => {
                if (response.ok) {
                    localStorage.removeItem('pending_offline_location');
                    if (statusEl) {
                        statusEl.className = 'alert alert-success py-3 mb-3';
                        statusEl.innerHTML = '<i class="bi bi-cloud-check me-2"></i>Offline GPS location synced successfully!';
                    }
                } else {
                    throw new Error('Sync failed');
                }
            })
            .catch(err => {
                console.error('Offline location sync failed:', err);
                if (statusEl) {
                    statusEl.className = 'alert alert-danger py-3 mb-3';
                    statusEl.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Failed to sync offline location. Will retry later.';
                }
            });
        }
    </script>
</x-dashboard-layout>
