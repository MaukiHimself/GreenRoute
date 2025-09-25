<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <h4 class="text-success mb-4">Route Optimization</h4>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Optimize Route</h6>
                    </div>
                    <div class="card-body">
                        <form id="optimizeForm">
                            <div class="mb-3">
                                <label class="form-label">Site Location</label>
                                <select class="form-select" id="siteLocation" required>
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-arrow-repeat me-2"></i>Optimize Route
                            </button>
                        </form>
                        
                        <div class="mt-3">
                            <small class="text-muted">Total Distance: <span id="totalDistance">0 km</span></small>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Optimized Route</h6>
                    </div>
                    <div class="card-body">
                        <div id="routeList" style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted">Select location and optimize to see route</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Route Map</h6>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map, markers = [], routePath;
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: -6.7924, lng: 39.2083 }
            });
        }
        
        document.getElementById('optimizeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const siteLocation = document.getElementById('siteLocation').value;
            
            if (!siteLocation) {
                alert('Please select a site location');
                return;
            }
            
            fetch('/routes/optimize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    site_location: siteLocation
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRoute(data.route);
                    document.getElementById('totalDistance').textContent = data.total_distance + ' km';
                }
            });
        });
        
        function displayRoute(route) {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
            if (routePath) routePath.setMap(null);
            
            const routeList = document.getElementById('routeList');
            routeList.innerHTML = '';
            
            const routeCoordinates = [];
            
            route.forEach((client, index) => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(client.latitude), lng: parseFloat(client.longitude) },
                    map: map,
                    title: client.name,
                    label: (index + 1).toString()
                });
                markers.push(marker);
                
                routeCoordinates.push({ 
                    lat: parseFloat(client.latitude), 
                    lng: parseFloat(client.longitude) 
                });
                
                const routeItem = document.createElement('div');
                routeItem.className = 'mb-2 p-2 border-start border-success border-3 bg-light';
                routeItem.innerHTML = `
                    <strong>${index + 1}. ${client.name}</strong><br>
                    <small class="text-muted">${client.address}</small><br>
                    <small class="text-info">GPS: ${client.latitude}, ${client.longitude}</small><br>
                    <small>${client.phone}</small>
                `;
                routeList.appendChild(routeItem);
            });
            
            routePath = new google.maps.Polyline({
                path: routeCoordinates,
                geodesic: true,
                strokeColor: '#198754',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            routePath.setMap(map);
            
            const bounds = new google.maps.LatLngBounds();
            routeCoordinates.forEach(coord => bounds.extend(coord));
            map.fitBounds(bounds);
        }
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcwt701YioUFnzbJp9Bktla31qjKwM304&callback=initMap"></script>
</x-guest-layout>