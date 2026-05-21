<x-guest-layout>
    <div class="text-center mb-4">
        <div class="icon-circle">
            <i class="bi bi-person-fill text-success"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Client Registration</h2>
        <p class="text-muted">Create your account to start managing your waste collection services</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if($errors->has('location'))
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-geo-alt-fill me-2 mt-1"></i>
                <div>
                    <strong>Location Required:</strong>
                    <p class="mb-0 mt-1">{{ $errors->first('location') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register.client.store') }}">
        @csrf
        <input type="hidden" name="user_type" value="client">
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-medium">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="phone" class="form-label fw-medium">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="address" class="form-label fw-medium">Physical Address</label>
                <div class="position-relative">
                    <input id="address" type="text" name="address" value="{{ old('address') }}" required
                           class="form-control form-control-lg" placeholder="Will be auto-filled from GPS location">
                    <small class="text-muted">📍 Address will be automatically filled when you get your location</small>
                </div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label fw-medium">Password</label>
                <input id="password" type="password" name="password" required
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <!-- Location Capture -->
        <div class="card border-primary mb-4">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Location Services</h6>
                <div class="mb-3">
                    <button type="button" id="watchLocation" class="btn btn-success">
                        <i class="bi bi-crosshair me-2"></i>Get My Precise Location (Required)
                    </button>
                    <small class="d-block text-muted mt-1">This will detect your exact GPS coordinates in Moshi, Tanzania</small>
                </div>
                <div id="locationStatus" class="alert alert-info py-2 mb-3"></div>
                <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                <small class="text-muted">Your precise GPS location is required for accurate waste collection services</small>
            </div>
        </div>
        
        <div class="alert alert-success mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                <div>
                    <strong>What you'll get:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Schedule waste pickups</li>
                        <li>Track your collection history</li>
                        <li>Manage your account settings</li>
                        <li>View invoices and payments</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success btn-lg w-100 mb-3" onclick="return validateLocation()">
            <i class="bi bi-person-plus me-2"></i>Create Account
        </button>
    </form>

    <div class="text-center">
        <p class="text-muted mb-0">Already have an account? 
            <a href="{{ route('login.client') }}" class="text-success text-decoration-none fw-medium">Sign in here</a>
        </p>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx, locationMarker;
        
        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -3.3731, lng: 36.8822, zoom: 13 });
            document.getElementById('watchLocation').addEventListener('click', watchPreciseLocation);
            document.getElementById('locationStatus').innerHTML = '📍 Click "Get My Precise Location" to detect your exact GPS coordinates in Moshi, Tanzania';
        });
        
        let watchId = null;
        let locationAttempts = 0;
        
        function watchPreciseLocation() {
            // Clear any existing watch
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
            
            // Reset attempts counter
            locationAttempts = 0;
            
            document.getElementById('locationStatus').innerHTML = '🎯 Acquiring your precise GPS location in Moshi, Tanzania... Please ensure location services are enabled and wait for the best accuracy.';
            
            let bestAccuracy = Infinity;
            let bestPosition = null;
            const maxAttempts = 25;
            const targetAccuracy = 20; // Target accuracy in meters
            
            if (!navigator.geolocation) {
                document.getElementById('locationStatus').innerHTML = '❌ GPS not supported by this browser.';
                return;
            }
            

            
            // Then start watching for high accuracy position
            watchId = navigator.geolocation.watchPosition(
                function(position) {
                    locationAttempts++;
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    const timestamp = new Date(position.timestamp);
                    
                    console.log(`GPS Reading ${locationAttempts}: ${lat.toFixed(8)}, ${lng.toFixed(8)} (±${Math.round(accuracy)}m) at ${timestamp.toLocaleTimeString()}`);
                    
                    document.getElementById('locationStatus').innerHTML = `📍 Reading ${locationAttempts}/${maxAttempts} - Current: ${Math.round(accuracy)}m accuracy`;
                    
                    // Update if this is more accurate than previous readings
                    if (accuracy < bestAccuracy) {
                        bestAccuracy = accuracy;
                        bestPosition = position;
                        updateLocation(lat, lng, accuracy, `GPS Reading ${locationAttempts}`);
                        
                        // Show accuracy status
                        let accuracyStatus = '';
                        if (accuracy < 10) {
                            accuracyStatus = '🎯 Excellent accuracy!';
                        } else if (accuracy < 30) {
                            accuracyStatus = '✅ Good accuracy';
                        } else if (accuracy < 100) {
                            accuracyStatus = '⚠️ Fair accuracy';
                        } else {
                            accuracyStatus = '📍 Basic accuracy';
                        }
                        
                        document.getElementById('locationStatus').innerHTML = `📍 Reading ${locationAttempts}/${maxAttempts} - Best: ${Math.round(bestAccuracy)}m ${accuracyStatus}`;
                    }
                    
                    // Stop if we have excellent accuracy or reached max attempts
                    if (accuracy <= targetAccuracy || locationAttempts >= maxAttempts) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                        
                        const finalAccuracy = bestPosition ? Math.round(bestPosition.coords.accuracy) : Math.round(accuracy);
                        let locationMessage = `✅ Location detection complete! Final accuracy: ${finalAccuracy}m`;
                        
                        // Add location quality indicator
                        if (finalAccuracy < 20) {
                            locationMessage += ' 🎯 (Excellent - Perfect for waste collection routing!)';
                        } else if (finalAccuracy < 50) {
                            locationMessage += ' ✅ (Good - Suitable for service delivery)';
                        } else {
                            locationMessage += ' ⚠️ (Fair - Location detected but may need refinement)';
                        }
                        
                        document.getElementById('locationStatus').innerHTML = locationMessage;
                        
                        // Ensure we use the best position found
                        if (bestPosition && bestPosition !== position) {
                            updateLocation(bestPosition.coords.latitude, bestPosition.coords.longitude, bestPosition.coords.accuracy, 'Final Best Position');
                        }
                        
                        // Validate the final location
                        validateLocationWithServer(bestPosition ? bestPosition.coords : position.coords);
                    }
                },
                function(error) {
                    let errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location access denied. Please enable location services and refresh the page.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information unavailable. Please try again.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage = 'An unknown error occurred while retrieving location.';
                            break;
                    }
                    
                    document.getElementById('locationStatus').innerHTML = `❌ GPS Error: ${errorMessage}`;
                    console.error('GPS Error:', error);
                    
                    // Clear the watch on error
                    if (watchId) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                    }
                },
                {
                    enableHighAccuracy: true,
                    timeout: 20000,
                    maximumAge: 0 // Always get fresh location data
                }
            );
        }
        
        function updateLocation(lat, lng, accuracy, source) {
            if (mapCtx) {
                if (locationMarker) {
                    GreenRouteMap.setMarkerPosition(locationMarker, lat, lng);
                } else {
                    locationMarker = GreenRouteMap.addMarker(mapCtx, lat, lng, { title: 'Your location' });
                }
                GreenRouteMap.setView(mapCtx, lat, lng, 18);
            }
            
            // Store coordinates with high precision
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            
            // Auto-fill address using reverse geocoding
            reverseGeocodeAddress(lat, lng);
            
            console.log(`GPS Update: ${lat.toFixed(8)}, ${lng.toFixed(8)} (±${Math.round(accuracy)}m) - ${source}`);
            
            console.log('GPS Location:', {
                coordinates: `${lat.toFixed(8)}, ${lng.toFixed(8)}`,
                accuracy: `±${Math.round(accuracy)}m`,
                source: source,
                timestamp: new Date().toLocaleString()
            });
        }
        
        function reverseGeocodeAddress(lat, lng) {
            const addressInput = document.getElementById('address');
            const statusEl = document.getElementById('locationStatus');
            
            // Show loading state
            addressInput.placeholder = 'Looking up address...';
            addressInput.style.backgroundColor = '#fff3cd';
            
            fetch('/location/reverse-geocode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.address) {
                    addressInput.value = data.address;
                    addressInput.style.backgroundColor = '#d1e7dd';
                    addressInput.placeholder = 'Address auto-filled from GPS';
                    
                    // Show success message
                    statusEl.innerHTML += ' 📍 Address auto-filled successfully!';
                    
                    console.log('Address auto-filled:', data.address);
                } else {
                    addressInput.style.backgroundColor = '#f8d7da';
                    addressInput.placeholder = 'Please enter address manually';
                    console.warn('Address lookup failed:', data.message);
                }
            })
            .catch(error => {
                addressInput.style.backgroundColor = '#f8d7da';
                addressInput.placeholder = 'Please enter address manually';
                console.error('Address lookup error:', error);
            });
        }
        

        
        function validateLocationWithServer(coords) {
            fetch('/location/validate-public', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    latitude: coords.latitude,
                    longitude: coords.longitude,
                    accuracy: coords.accuracy
                })
            })
            .then(response => response.json())
            .then(data => {
                const statusEl = document.getElementById('locationStatus');
                if (data.in_moshi) {
                    statusEl.innerHTML += ' 🎯 Perfect! Location confirmed in Moshi, Tanzania.';
                } else if (data.in_tanzania) {
                    statusEl.innerHTML += ' ✅ Location confirmed in Tanzania.';
                } else {
                    statusEl.innerHTML += ' ⚠️ Warning: Location may not be in Tanzania.';
                }
            })
            .catch(error => {
                console.warn('Location validation failed:', error);
            });
        }
        
        function validateLocation() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            
            if (!lat || !lng || lat === '' || lng === '') {
                alert('Please click "Get My Precise Location" to capture your GPS coordinates before registering.');
                return false;
            }
            
            // Check if coordinates are within Tanzania bounds
            const latitude = parseFloat(lat);
            const longitude = parseFloat(lng);
            
            if (latitude < -11.7 || latitude > -0.95 || longitude < 29.3 || longitude > 40.5) {
                alert('The detected location does not appear to be in Tanzania. Please ensure location services are enabled and try "Get My Precise Location" again.');
                return false;
            }
            
            return true;
        }
    </script>
</x-guest-layout>