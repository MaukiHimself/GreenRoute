<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #055c5c;
            --secondary-color: #640404;
            --white-color: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }
        
        .container-fluid {
            padding: 2rem;
            max-width: 1400px;
        }
        
        /* Header Section */
        .page-header {
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Content Sections */
        .content-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Truck List */
        .trucks-container {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 1.5rem;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .truck-item {
            background: var(--white-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .truck-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .truck-item:last-child {
            margin-bottom: 0;
        }
        
        .truck-plate {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }
        
        .truck-driver {
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .truck-phone {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .truck-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-online {
            background: var(--primary-color);
            color: white;
        }
        
        .status-offline {
            background: #e2e8f0;
            color: var(--text-muted);
        }
        
        .distance-display {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .truck-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        /* Map Container */
        .map-container {
            background: var(--white-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 600px;
        }
        
        #map {
            height: 100%;
            width: 100%;
            border-radius: 16px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* Refresh Button */
        .refresh-btn {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .refresh-btn:hover {
            background: #044a4a;
            transform: translateY(-1px);
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1.5rem;
            }
            
            .map-container {
                height: 400px;
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .truck-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">GPS Tracker</h1>
        </div>

        <div class="row">
            <!-- Left Column - Truck Management -->
            <div class="col-lg-4">
                <!-- Register New Truck -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Register New Truck</h2>
                    </div>
                    
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
                        <button type="submit" class="btn btn-primary">Register Truck</button>
                    </form>
                </div>
                
                <!-- Registered Trucks -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Registered Trucks</h2>
                    </div>
                    
                    <div class="trucks-container" id="trucksList">
                        @forelse($trucks as $truck)
                        <div class="truck-item" id="truck-{{ $truck->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <div class="truck-plate">{{ $truck->plate_number }}</div>
                                    <div class="truck-driver">{{ $truck->driver_name }}</div>
                                    <div class="truck-phone">{{ $truck->driver_phone }}</div>
                                    <span class="truck-badge">{{ ucfirst($truck->truck_type) }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="status-badge" id="status-{{ $truck->id }}">
                                        @if($truck->last_updated && $truck->last_updated->diffInMinutes(now()) < 10)
                                            <span class="status-online">Online</span>
                                        @else
                                            <span class="status-offline">Offline</span>
                                        @endif
                                    </div>
                                    <div class="distance-display mt-1" id="distance-{{ $truck->id }}">
                                        {{ number_format($truck->daily_distance, 2) }} km
                                    </div>
                                </div>
                            </div>
                            <div class="truck-actions">
                                <button class="btn btn-outline-primary btn-sm" onclick="trackTruck({{ $truck->id }})">
                                    <i class="bi bi-geo-alt me-1"></i>Track
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="simulateMovement({{ $truck->id }})">
                                    <i class="bi bi-play-circle me-1"></i>Simulate
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="bi bi-truck"></i>
                            <p class="mb-0">No trucks registered</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Map -->
            <div class="col-lg-8">
                <div class="content-section p-0">
                    <div class="section-header p-3">
                        <h2 class="section-title">Live Truck Locations</h2>
                        <button class="refresh-btn" onclick="refreshLocations()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                    <div class="map-container">
                        <div id="map"></div>
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
                center: { lat: -6.7924, lng: 39.2083 },
                styles: [
                    {
                        "featureType": "administrative",
                        "elementType": "geometry",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "poi",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.icon",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "transit",
                        "stylers": [{"visibility": "off"}]
                    }
                ]
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
            
            // Create custom truck icon
            const truckIcon = {
                path: 'M-20,0 L20,0 L20,40 L-20,40 Z M-15,40 L15,40 L15,60 L-15,60 Z',
                fillColor: '#055c5c',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2,
                scale: 1,
                anchor: new google.maps.Point(0, 30)
            };
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(truck.current_latitude), 
                    lng: parseFloat(truck.current_longitude) 
                },
                map: map,
                title: truck.plate_number,
                icon: truckIcon
            });
            
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 1rem; min-width: 200px;">
                        <div style="font-weight: 700; color: #055c5c; margin-bottom: 0.5rem;">${truck.plate_number}</div>
                        <div><strong>Driver:</strong> ${truck.driver_name}</div>
                        <div><strong>Phone:</strong> ${truck.driver_phone}</div>
                        <div><strong>Distance Today:</strong> ${parseFloat(truck.daily_distance).toFixed(2)} km</div>
                        <div><strong>Last Updated:</strong> ${new Date(truck.last_updated).toLocaleTimeString()}</div>
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
</body>
</html>