<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid py-4">
        <h4 class="text-success mb-4">GPS Tracker</h4>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Register New Truck</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trucks.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Plate Number</label>
                                <input type="text" class="form-control" name="plate_number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Driver Name</label>
                                <input type="text" class="form-control" name="driver_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Driver Phone</label>
                                <input type="text" class="form-control" name="driver_phone" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Truck Type</label>
                                <select class="form-select" name="truck_type" required>
                                    <option value="">Select Type</option>
                                    <option value="small">Small Truck</option>
                                    <option value="medium">Medium Truck</option>
                                    <option value="large">Large Truck</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Register Truck</button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Registered Trucks</h6>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($trucks as $truck)
                        <div class="mb-3 p-3 border rounded" id="truck-{{ $truck->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $truck->plate_number }}</strong>
                                    <br><small class="text-muted">{{ $truck->driver_name }}</small>
                                    <br><small>{{ $truck->driver_phone }}</small>
                                    <br><span class="badge bg-info">{{ ucfirst($truck->truck_type) }}</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success" id="status-{{ $truck->id }}">
                                        @if($truck->last_updated && $truck->last_updated->diffInMinutes(now()) < 10)
                                            Online
                                        @else
                                            Offline
                                        @endif
                                    </span>
                                    <br><small class="text-muted" id="distance-{{ $truck->id }}">{{ number_format($truck->daily_distance, 2) }} km</small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="trackTruck({{ $truck->id }})">Track</button>
                                <button class="btn btn-sm btn-outline-success" onclick="simulateMovement({{ $truck->id }})">Simulate</button>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">No trucks registered</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Live Truck Locations</h6>
                        <button class="btn btn-sm btn-success" onclick="refreshLocations()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map, truckMarkers = {};
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: -6.7924, lng: 39.2083 }
            });
            
            loadTruckLocations();
            setInterval(refreshLocations, 30000); // Refresh every 30 seconds
        }
        
        function loadTruckLocations() {
            fetch('/trucks/locations')
                .then(response => response.json())
                .then(trucks => {
                    trucks.forEach(truck => {
                        updateTruckMarker(truck);
                    });
                });
        }
        
        function updateTruckMarker(truck) {
            if (truckMarkers[truck.id]) {
                truckMarkers[truck.id].setMap(null);
            }
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(truck.current_latitude), 
                    lng: parseFloat(truck.current_longitude) 
                },
                map: map,
                title: truck.plate_number,
                icon: 'http://maps.google.com/mapfiles/ms/icons/truck.png'
            });
            
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <strong>${truck.plate_number}</strong><br>
                        Driver: ${truck.driver_name}<br>
                        Phone: ${truck.driver_phone}<br>
                        Distance Today: ${parseFloat(truck.daily_distance).toFixed(2)} km<br>
                        Last Updated: ${new Date(truck.last_updated).toLocaleTimeString()}
                    </div>
                `
            });
            
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
            
            truckMarkers[truck.id] = marker;
        }
        
        function trackTruck(truckId) {
            if (truckMarkers[truckId]) {
                map.setCenter(truckMarkers[truckId].getPosition());
                map.setZoom(15);
            }
        }
        
        function refreshLocations() {
            loadTruckLocations();
        }
        
        function simulateMovement(truckId) {
            // Simulate truck movement for demo purposes
            const lat = -6.7924 + (Math.random() - 0.5) * 0.1;
            const lng = 39.2083 + (Math.random() - 0.5) * 0.1;
            
            fetch(`/trucks/${truckId}/location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    refreshLocations();
                }
            });
        }
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcwt701YioUFnzbJp9Bktla31qjKwM304&callback=initMap"></script>
</x-guest-layout>