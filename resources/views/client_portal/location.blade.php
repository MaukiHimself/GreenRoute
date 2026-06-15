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
                    <h2 class="section-title"><i class="bi bi-geo-alt-fill me-2"></i>Register Your GPS Location</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Why register your GPS location?</strong>
                    <p class="mb-0 mt-1">Providing your exact GPS coordinates helps your contractor plan efficient pickup routes and schedule visits at the right time. This is optional but highly recommended.</p>
                </div>

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
                        <button type="button" id="watchLocation" class="btn btn-primary">
                            <i class="bi bi-crosshair me-2"></i>Detect My GPS Coordinates
                        </button>
                        <span class="text-muted small ms-2">Click to capture your current location</span>
                    </div>

                    <div id="locationStatus" class="alert alert-info py-2 mb-3 small">
                        📍 Click "Detect My GPS Coordinates" to capture your location.
                    </div>

                    <div class="mb-4">
                        <div id="map" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
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
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-lightbulb me-2"></i>Benefits</h2>
                </div>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Optimized pickup routes for faster service</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Accurate scheduling based on your location</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Reduced wait times for collection</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Better route planning for your contractor</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Lower operational costs and emissions</li>
                </ul>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title"><i class="bi bi-shield-check me-2"></i>Privacy</h2>
                </div>
                <p class="text-muted small mb-0">
                    Your GPS coordinates are stored securely and are only visible to your assigned waste contractor for route planning purposes. We do not share your location with third parties.
                </p>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx, locationMarker;
        const existingLat = {{ $client->latitude ?? 'null' }};
        const existingLng = {{ $client->longitude ?? 'null' }};

        GreenRouteMap.whenReady(function () {
            let defaultLat = -3.3731;
            let defaultLng = 36.8822;

            if (existingLat && existingLng) {
                defaultLat = parseFloat(existingLat);
                defaultLng = parseFloat(existingLng);
            }

            mapCtx = GreenRouteMap.createMap('map', { lat: defaultLat, lng: defaultLng, zoom: existingLat ? 16 : 12 });

            if (existingLat && existingLng) {
                locationMarker = L.marker([defaultLat, defaultLng], {
                    draggable: true,
                    title: 'Your Location'
                }).addTo(mapCtx);
                document.getElementById('locationStatus').innerHTML = `📍 Existing location loaded: ${defaultLat.toFixed(6)}, ${defaultLng.toFixed(6)}. Drag marker to adjust or click "Detect My GPS Coordinates".`;
            } else {
                document.getElementById('locationStatus').innerHTML = '📍 No location set. Click "Detect My GPS Coordinates" or drag the marker to your exact location.';
            }

            mapCtx.on('click', function (event) {
                const pos = event.latlng;
                setLocation(pos.lat, pos.lng);
            });

            if (locationMarker) {
                locationMarker.on('dragend', function (event) {
                    const pos = event.target.getLatLng();
                    setLocation(pos.lat, pos.lng);
                });
            }

            document.getElementById('watchLocation').addEventListener('click', watchPreciseLocation);
        });

        function setLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (locationMarker) {
                locationMarker.setLatLng([lat, lng]);
            } else if (mapCtx) {
                locationMarker = L.marker([lat, lng], { draggable: true, title: 'Your Location' }).addTo(mapCtx);
                locationMarker.on('dragend', function (event) {
                    const pos = event.target.getLatLng();
                    setLocation(pos.lat, pos.lng);
                });
            }
            if (mapCtx) mapCtx.setView([lat, lng], 16);
            document.getElementById('locationStatus').innerHTML = `📍 Location set: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
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
            statusEl.innerHTML = '🎯 Detecting your GPS location... Please allow access if prompted.';
            statusEl.className = 'alert alert-warning py-2 mb-3 small';

            let bestAccuracy = Infinity;
            let bestPosition = null;
            const maxAttempts = 20;
            const targetAccuracy = 20;

            if (!navigator.geolocation) {
                statusEl.innerHTML = '❌ GPS not supported by this browser.';
                statusEl.className = 'alert alert-danger py-2 mb-3 small';
                return;
            }

            watchId = navigator.geolocation.watchPosition(
                function (position) {
                    locationAttempts++;
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    statusEl.innerHTML = `📍 Detecting (${locationAttempts}/${maxAttempts}) — Accuracy: ${Math.round(accuracy)}m`;

                    if (accuracy < bestAccuracy) {
                        bestAccuracy = accuracy;
                        bestPosition = position;
                        setLocation(lat, lng);
                    }

                    if (accuracy <= targetAccuracy || locationAttempts >= maxAttempts) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                        const finalAccuracy = bestPosition ? Math.round(bestPosition.coords.accuracy) : Math.round(accuracy);
                        statusEl.className = 'alert alert-success py-2 mb-3 small';
                        statusEl.innerHTML = `✅ GPS captured! Accuracy: ±${finalAccuracy}m. Click "Save My Location" to confirm.`;

                        if (bestPosition && bestPosition !== position) {
                            setLocation(bestPosition.coords.latitude, bestPosition.coords.longitude);
                        }
                    }
                },
                function (error) {
                    let msg = 'Could not acquire coordinates.';
                    if (error.code === error.PERMISSION_DENIED) {
                        msg = 'Permission denied. Please enable location access in your browser settings.';
                    }
                    statusEl.className = 'alert alert-danger py-2 mb-3 small';
                    statusEl.innerHTML = `❌ GPS Error: ${msg}`;
                    if (watchId) { navigator.geolocation.clearWatch(watchId); watchId = null; }
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }
    </script>
</x-dashboard-layout>
