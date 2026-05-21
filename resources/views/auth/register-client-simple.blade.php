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
                <input id="address" type="text" name="address" value="{{ old('address') }}" required
                       class="form-control form-control-lg" placeholder="Your address">
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
                    <button type="button" id="getLocation" class="btn btn-success">
                        <i class="bi bi-crosshair me-2"></i>Get My Device Location
                    </button>
                </div>
                <div id="locationStatus" class="alert alert-info py-2 mb-3">Click button to get your device location</div>
                <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success btn-lg w-100 mb-3" onclick="return validateLocation()">
            <i class="bi bi-person-plus me-2"></i>Create Account
        </button>
    </form>

    @include('components.leaflet-assets')

    <script>
        let mapCtx, locationMarker;
        
        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -6.369028, lng: 34.888822, zoom: 6 });
            document.getElementById('getLocation').addEventListener('click', getDeviceLocation);
        });
        
        function getDeviceLocation() {
            document.getElementById('locationStatus').innerHTML = 'Getting your device location...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    if (mapCtx) {
                        if (locationMarker) {
                            GreenRouteMap.setMarkerPosition(locationMarker, lat, lng);
                        } else {
                            locationMarker = GreenRouteMap.addMarker(mapCtx, lat, lng, { title: 'Your location' });
                        }
                        GreenRouteMap.setView(mapCtx, lat, lng, 16);
                    }
                    
                    document.getElementById('locationStatus').innerHTML = 'Location captured: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
                    console.log('Device Location:', lat, lng);
                },
                function(error) {
                    document.getElementById('locationStatus').innerHTML = 'Could not get location. Enable GPS and try again.';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        
        function validateLocation() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            
            if (!lat || !lng) {
                alert('Please get your location first.');
                return false;
            }
            return true;
        }
    </script>
</x-guest-layout>