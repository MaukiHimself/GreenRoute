<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .route-container { background: #f8f9fa; min-height: 100vh; padding: 20px; }
        .route-header { background: linear-gradient(135deg, #198754, #20c997); color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .optimization-card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; border-radius: 10px; }
        .map-container { height: 500px; border-radius: 10px; border: 2px solid #dee2e6; }
        .route-list { max-height: 400px; overflow-y: auto; }
        .route-item { padding: 10px; border-left: 4px solid #198754; margin-bottom: 8px; background: white; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .optimize-btn { background: linear-gradient(135deg, #198754, #20c997); border: none; border-radius: 8px; padding: 12px 30px; font-weight: 600; }
        .stats-card { background: linear-gradient(135deg, #e3f2fd, #f3e5f5); border: none; border-radius: 10px; }
    </style>
    
    <div class="container-fluid route-container">
        <div class="route-header text-center">
            <h3 class="mb-2"><i class="bi bi-geo-alt me-2"></i>Route Optimization</h3>
            <p class="mb-0">Optimize waste collection routes for maximum efficiency</p>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card optimization-card">
                    <div class="card-header bg-white border-0">
                        <h5 class="text-success mb-0"><i class="bi bi-sliders me-2"></i>Route Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="optimizeForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Site Location</label>
                                <select class="form-select" id="siteLocation" required>
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Starting Point</label>
                                <button type="button" class="btn btn-outline-primary w-100" onclick="getCurrentLocation()">
                                    <i class="bi bi-geo-alt-fill me-2"></i>Use My Current Location
                                </button>
                                <small class="text-muted">Or click on map to set starting point</small>
                            </div>
                            
                            <button type="submit" class="btn btn-success optimize-btn w-100">
                                <i class="bi bi-arrow-repeat me-2"></i>Optimize Route
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card optimization-card mt-3">
                    <div class="card-header bg-white border-0">
                        <h6 class="text-success mb-0"><i class="bi bi-list-ol me-2"></i>Optimized Route</h6>
                    </div>
                    <div class="card-body">
                        <div id="routeList" class="route-list">
                            <p class="text-muted text-center">Select location and optimize to see route</p>
                        </div>
                    </div>
                </div>
                
                <div class="card stats-card mt-3">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-primary mb-0" id="totalDistance">0 km</h4>
                                <small class="text-muted">Total Distance</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0" id="estimatedTime">0 min</h4>
                                <small class="text-muted">Estimated Time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card optimization-card">
                    <div class="card-header bg-white border-0">
                        <h5 class="text-success mb-0"><i class="bi bi-map me-2"></i>Route Map</h5>
                    </div>
                    <div class="card-body">
                        <div id="map" class="map-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map, markers = [], routePath, startMarker;
        let startLat = null, startLng = null;
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: -6.7924, lng: 39.2083 } // Dar es Salaam
            });
            
            map.addListener('click', function(event) {
                setStartingPoint(event.latLng.lat(), event.latLng.lng());
            });
        }
        
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    setStartingPoint(position.coords.latitude, position.coords.longitude);
                    map.setCenter({ lat: position.coords.latitude, lng: position.coords.longitude });
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
        
        function setStartingPoint(lat, lng) {
            startLat = lat;
            startLng = lng;
            
            if (startMarker) {
                startMarker.setMap(null);
            }
            
            startMarker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: 'Starting Point',
                icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            });
        }
        
        document.getElementById('optimizeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const siteLocation = document.getElementById('siteLocation').value;
            
            if (!siteLocation) {
                alert('Please select a site location');
                return;
            }
            
            if (!startLat || !startLng) {
                alert('Please set a starting point');
                return;
            }
            
            optimizeRoute(siteLocation);
        });
        
        function optimizeRoute(siteLocation) {
            fetch('/routes/optimize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    site_location: siteLocation,
                    start_latitude: startLat,
                    start_longitude: startLng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRoute(data.route);
                    updateStats(data.total_distance, data.estimated_time);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error optimizing route');
            });
        }
        
        function displayRoute(route) {
            // Clear existing markers and path
            markers.forEach(marker => marker.setMap(null));
            markers = [];
            if (routePath) routePath.setMap(null);
            
            // Create route list
            const routeList = document.getElementById('routeList');
            routeList.innerHTML = '';
            
            const routeCoordinates = [{ lat: startLat, lng: startLng }];
            
            route.forEach((client, index) => {
                // Add marker
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(client.latitude), lng: parseFloat(client.longitude) },
                    map: map,
                    title: client.name,
                    label: (index + 1).toString()
                });
                markers.push(marker);
                
                // Add to route coordinates
                routeCoordinates.push({ 
                    lat: parseFloat(client.latitude), 
                    lng: parseFloat(client.longitude) 
                });
                
                // Add to route list
                const routeItem = document.createElement('div');
                routeItem.className = 'route-item';
                routeItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${index + 1}. ${client.name}</strong>
                            <small class="d-block text-muted">${client.category} • ${client.phone}</small>
                            <small class="text-muted">${client.address}</small>
                        </div>
                        <span class="badge bg-success">${index + 1}</span>
                    </div>
                `;
                routeList.appendChild(routeItem);
            });
            
            // Draw route path
            routePath = new google.maps.Polyline({
                path: routeCoordinates,
                geodesic: true,
                strokeColor: '#198754',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            routePath.setMap(map);
            
            // Fit map to show all markers
            const bounds = new google.maps.LatLngBounds();
            routeCoordinates.forEach(coord => bounds.extend(coord));
            map.fitBounds(bounds);
        }
        
        function updateStats(distance, time) {
            document.getElementById('totalDistance').textContent = distance + ' km';
            document.getElementById('estimatedTime').textContent = time + ' min';
        }
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcwt701YioUFnzbJp9Bktla31qjKwM304&callback=initMap"></script>
</x-guest-layout>