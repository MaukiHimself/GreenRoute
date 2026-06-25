<x-dashboard-layout title="Account Settings">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            @php $user = Auth::user(); @endphp
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('client.profile*') || request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('client.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.request.service') }}"><i class="bi bi-plus-circle me-2"></i>Request Service</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.equipment') }}"><i class="bi bi-tools me-2"></i>Equipment</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.contractor.info') }}"><i class="bi bi-building me-2"></i>Contractor Info</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.payments') }}"><i class="bi bi-credit-card me-2"></i>Payments</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.feedback') }}"><i class="bi bi-chat-dots me-2"></i>Feedback</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Account Settings</li>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @if($client && $client->user && $client->user->profile_picture)
                            <img src="{{ asset('storage/' . $client->user->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr($client->name ?? Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $client->name ?? Auth::user()->name }}</h5>
                    <p class="text-muted small mb-3">{{ $client->email ?? Auth::user()->email }}</p>
                    <form method="POST" action="{{ route('client.profile.picture') }}" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <input type="file" name="profile_picture" accept="image/*" class="form-control form-control-sm mb-2" required>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-camera me-1"></i>Change Photo
                        </button>
                    </form>
                    @if($client && $client->user && $client->user->dark_mode)
                        <span class="badge bg-dark"><i class="bi bi-moon me-1"></i> Dark Mode</span>
                    @else
                        <span class="badge bg-light text-dark"><i class="bi bi-sun me-1"></i> Light Mode</span>
                    @endif
                </div>
            </div>
            <div class="list-group">
                <a href="#profile-section" class="list-group-item list-group-item-action active" data-bs-toggle="list">Profile Information</a>
                <a href="#settings-section" class="list-group-item list-group-item-action" data-bs-toggle="list">Settings</a>
                <a href="#password-section" class="list-group-item list-group-item-action" data-bs-toggle="list">Change Password</a>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profile-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form method="POST" action="{{ route('client.profile.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Company/Organization Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $client->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Person</label>
                                            <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $client->contact_name) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Primary Phone</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $client->phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Secondary Phone</label>
                                            <input type="text" class="form-control" name="phone_2" value="{{ old('phone_2', $client->phone_2) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Third Phone</label>
                                            <input type="text" class="form-control" name="phone_3" value="{{ old('phone_3', $client->phone_3) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Primary Email</label>
                                            <input type="email" class="form-control" value="{{ $client->email }}" disabled>
                                            <small class="text-muted">Primary email cannot be changed</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Secondary Email</label>
                                            <input type="email" class="form-control" name="email_2" value="{{ old('email_2', $client->email_2) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Third Email</label>
                                            <input type="email" class="form-control" name="email_3" value="{{ old('email_3', $client->email_3) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3" required>{{ old('address', $client->address) }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $client->latitude) }}" readonly placeholder="Detect location or click on map">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $client->longitude) }}" readonly placeholder="Detect location or click on map">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label d-block fw-semibold text-teal"><i class="bi bi-geo-alt-fill me-1"></i>GPS Location Capture</label>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <button type="button" id="watchLocation" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-crosshair me-1"></i>Detect Precise GPS Coordinates
                                        </button>
                                        <span class="text-muted small align-self-center">You can click on the map or drag the marker to adjust coordinates.</span>
                                    </div>
                                    <div id="locationStatus" class="alert alert-info py-2 mb-2 small">
                                        📍 Click the button to fetch coordinates, or click/drag marker on map.
                                    </div>
                                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px;" class="mb-2"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" name="city" value="{{ old('city', $client->city) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" class="form-control" name="state" value="{{ old('state', $client->state) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">ZIP Code</label>
                                            <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code', $client->zip_code) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Registration Number</label>
                                            <input type="text" class="form-control" value="{{ $client->registration_number }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <input type="text" class="form-control" value="{{ ucfirst($client->category) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="settings-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Settings</h5>
                        </div>
                        <div class="card-body">
                            @if(session('status') === 'profile-picture-updated')
                                <div class="alert alert-success">Profile picture updated successfully.</div>
                            @endif
                            @if(session('status') === 'dark-mode-toggled')
                                <div class="alert alert-success">Display preference updated.</div>
                            @endif
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-moon-stars me-2"></i>Dark Mode</h6>
                                    <p class="text-muted small mb-0">Switch between light and dark display theme</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <form method="POST" action="{{ route('profile.toggle-dark-mode') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->dark_mode ? 'btn-dark' : 'btn-outline-dark' }}">
                                            @if($user->dark_mode)
                                                <i class="bi bi-moon-fill me-1"></i>Dark Mode ON
                                            @else
                                                <i class="bi bi-sun-fill me-1"></i>Light Mode
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-bell me-2"></i>Notifications</h6>
                                    <p class="text-muted small mb-0">Receive email notifications for schedules and invoices</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input" type="checkbox" checked disabled id="notificationsSwitch">
                                        <label class="form-check-label" for="notificationsSwitch">Enabled</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center py-3">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-language me-2"></i>Language</h6>
                                    <p class="text-muted small mb-0">Choose your preferred language (coming soon)</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <select class="form-select form-select-sm" style="max-width: 150px;" disabled>
                                        <option>English</option>
                                        <option>Swahili</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="password-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-key me-2"></i>Change Password</h5>
                        </div>
                        <div class="card-body">
                            @if(session('status') === 'password-updated'))
                                <div class="alert alert-success">Password changed successfully.</div>
                            @endif
                            <form method="POST" action="{{ route('profile.update-password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" required autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-warning"><i class="bi bi-shield-lock me-1"></i>Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx, locationMarker;
        
        GreenRouteMap.whenReady(function () {
            // Default coordinates: Moshi/Tanzania, or existing client coordinates
            let defaultLat = -3.3731;
            let defaultLng = 36.8822;
            
            const dbLat = document.getElementById('latitude').value;
            const dbLng = document.getElementById('longitude').value;
            const hasExistingCoords = dbLat && dbLng && dbLat !== '' && dbLng !== '';
            
            if (hasExistingCoords) {
                defaultLat = parseFloat(dbLat);
                defaultLng = parseFloat(dbLng);
            }
            
            mapCtx = GreenRouteMap.createMap('map', { lat: defaultLat, lng: defaultLng, zoom: hasExistingCoords ? 16 : 12 });
            
            // Add draggable marker to allow fine-tuning coordinates
            locationMarker = L.marker([defaultLat, defaultLng], {
                draggable: true,
                title: 'Your Location'
            }).addTo(mapCtx.map);
            
            if (hasExistingCoords) {
                document.getElementById('locationStatus').innerHTML = `📍 Existing coordinates loaded: ${defaultLat}, ${defaultLng}`;
            } else {
                document.getElementById('locationStatus').innerHTML = `📍 No coordinates stored yet. Click "Detect Precise GPS Coordinates" or drag the marker to your building.`;
            }
            
            // Event handler when marker is dragged
            locationMarker.on('dragend', function (event) {
                const marker = event.target;
                const position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
                document.getElementById('locationStatus').innerHTML = `📍 Location adjusted by dragging marker: ${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}`;
            });
            
            // Map click handler to move marker
            mapCtx.map.on('click', function (event) {
                const clickedCoords = event.latlng;
                locationMarker.setLatLng(clickedCoords);
                updateInputs(clickedCoords.lat, clickedCoords.lng);
                document.getElementById('locationStatus').innerHTML = `📍 Location set by map click: ${clickedCoords.lat.toFixed(6)}, ${clickedCoords.lng.toFixed(6)}`;
            });
            
            document.getElementById('watchLocation').addEventListener('click', watchPreciseLocation);
        });
        
        let watchId = null;
        let locationAttempts = 0;
        
        function watchPreciseLocation() {
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
            
            locationAttempts = 0;
            const statusEl = document.getElementById('locationStatus');
            statusEl.innerHTML = '🎯 Detecting precise coordinates from your browser GPS...';
            statusEl.className = 'alert alert-warning py-2 mb-2 small';
            
            let bestAccuracy = Infinity;
            let bestPosition = null;
            const maxAttempts = 20;
            const targetAccuracy = 20;
            
            if (!navigator.geolocation) {
                statusEl.innerHTML = '❌ Geolocation not supported by this browser.';
                statusEl.className = 'alert alert-danger py-2 mb-2 small';
                return;
            }
            
            watchId = navigator.geolocation.watchPosition(
                function (position) {
                    locationAttempts++;
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    statusEl.innerHTML = `📍 Detecting location (Attempt ${locationAttempts}/${maxAttempts}) - Accuracy: ${Math.round(accuracy)}m`;
                    
                    if (accuracy < bestAccuracy) {
                        bestAccuracy = accuracy;
                        bestPosition = position;
                        updateMarkerAndInputs(lat, lng);
                    }
                    
                    if (accuracy <= targetAccuracy || locationAttempts >= maxAttempts) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                        
                        const finalAccuracy = bestPosition ? Math.round(bestPosition.coords.accuracy) : Math.round(accuracy);
                        statusEl.className = 'alert alert-success py-2 mb-2 small';
                        statusEl.innerHTML = `✅ Precise GPS location detected! Accuracy: ±${finalAccuracy}m.`;
                        
                        if (bestPosition && bestPosition !== position) {
                            updateMarkerAndInputs(bestPosition.coords.latitude, bestPosition.coords.longitude);
                        }
                    }
                },
                function (error) {
                    let errMessage = 'Unable to detect GPS coordinates.';
                    if (error.code === error.PERMISSION_DENIED) {
                        errMessage = 'Permission denied. Please allow location/GPS access in your browser settings.';
                    }
                    statusEl.className = 'alert alert-danger py-2 mb-2 small';
                    statusEl.innerHTML = `❌ GPS Error: ${errMessage}`;
                    if (watchId) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                    }
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
        
        function updateMarkerAndInputs(lat, lng) {
            if (locationMarker) {
                locationMarker.setLatLng([lat, lng]);
            }
            if (mapCtx) {
                mapCtx.map.setView([lat, lng], 16);
            }
            updateInputs(lat, lng);
        }
        
        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        }
    </script>
</x-dashboard-layout>
