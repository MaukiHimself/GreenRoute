<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Optimization</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        .container {
            max-width: 1400px;
            padding: 2rem;
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
        
        /* Route List */
        .route-list-container {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 1.5rem;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .route-item {
            background: var(--white-color);
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .route-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .route-item:last-child {
            margin-bottom: 0;
        }
        
        .route-number {
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 1rem;
        }
        
        .route-details {
            flex: 1;
        }
        
        .client-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .client-address {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .client-gps {
            color: var(--primary-color);
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .client-phone {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        /* Distance Display */
        .distance-display {
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .distance-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }
        
        .distance-value {
            font-size: 1.5rem;
            font-weight: 700;
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
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container {
                padding: 1.5rem;
            }
            
            .map-container {
                height: 400px;
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Route Optimization</h1>
        </div>

        <div class="row">
            <!-- Left Column - Controls and Route List -->
            <div class="col-lg-4">
                <!-- Optimize Route Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Optimize Route</h2>
                    </div>
                    
                    <form id="optimizeForm">
                        <div class="mb-4">
                            <label class="form-label">Site Location</label>
                            <select class="form-select" id="siteLocation" required>
                                <option value="">Select Location</option>
                                @foreach($siteLocations as $region => $districts)
                                    <optgroup label="{{ $region }}">
                                        @foreach($districts as $district)
                                            <option value="{{ $region }} → {{ $district }}">{{ $region }} → {{ $district }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat me-2"></i>Optimize Route
                        </button>
                    </form>
                    
                    <div class="distance-display">
                        <div class="distance-label">Total Distance</div>
                        <div class="distance-value" id="totalDistance">0 km</div>
                    </div>
                </div>
                
                <!-- Optimized Route List -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Optimized Route</h2>
                    </div>
                    
                    <div class="route-list-container" id="routeList">
                        <div class="empty-state">
                            <i class="bi bi-geo-alt"></i>
                            <p class="mb-0">Select location and optimize to see route</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Map -->
            <div class="col-lg-8">
                <div class="map-container">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx;
        
        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -6.7924, lng: 39.2083, zoom: 12 });
        });
        
        document.getElementById('optimizeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const siteLocation = document.getElementById('siteLocation').value;
            
            if (!siteLocation) {
                alert('Please select a site location');
                return;
            }
            
            // Show loading state
            const routeList = document.getElementById('routeList');
            routeList.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-arrow-repeat bi-spin"></i>
                    <p class="mb-0">Optimizing route...</p>
                </div>
            `;
            
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
            })
            .catch(error => {
                console.error('Error:', error);
                routeList.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-exclamation-triangle"></i>
                        <p class="mb-0">Error optimizing route. Please try again.</p>
                    </div>
                `;
            });
        });
        
        async function displayRoute(route) {
            if (!mapCtx) return;

            GreenRouteMap.clearMarkers(mapCtx);
            GreenRouteMap.clearPolylines(mapCtx);
            
            const routeList = document.getElementById('routeList');
            routeList.innerHTML = '';
            
            const routeCoordinates = [];
            
            if (route.length === 0) {
                routeList.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-info-circle"></i>
                        <p class="mb-0">No clients found for this location</p>
                    </div>
                `;
                return;
            }
            
            route.forEach((client, index) => {
                const lat = parseFloat(client.latitude);
                const lng = parseFloat(client.longitude);

                GreenRouteMap.addNumberedMarker(mapCtx, lat, lng, index + 1, {
                    title: client.name,
                    popup: `<strong>${client.name}</strong><br>${client.address || ''}`,
                });
                
                routeCoordinates.push({ lat, lng });
                
                const routeItem = document.createElement('div');
                routeItem.className = 'route-item d-flex align-items-start';
                routeItem.innerHTML = `
                    <div class="route-number">${index + 1}</div>
                    <div class="route-details">
                        <div class="client-name">${client.name}</div>
                        <div class="client-address">${client.address}</div>
                        <div class="client-gps">${client.latitude}, ${client.longitude}</div>
                        <div class="client-phone">${client.phone}</div>
                    </div>
                `;
                routeList.appendChild(routeItem);
            });
            
            const token = "{{ config('services.heigit.api_key') }}";
            const summary = await GreenRouteMap.drawRoadRoute(mapCtx, routeCoordinates, token);
            
            if (summary) {
                document.getElementById('totalDistance').textContent = summary.distance.toFixed(1) + ' km';
            } else {
                const distance = calculateRouteDistance(routeCoordinates);
                GreenRouteMap.drawPolyline(mapCtx, routeCoordinates);
                document.getElementById('totalDistance').textContent = distance + ' km';
            }
            
            GreenRouteMap.fitBounds(mapCtx, routeCoordinates);
        }

        function calculateRouteDistance(coordinates) {
            let totalDistance = 0;
            for (let i = 1; i < coordinates.length; i++) {
                totalDistance += GreenRouteMap.haversineKm(
                    coordinates[i - 1].lat,
                    coordinates[i - 1].lng,
                    coordinates[i].lat,
                    coordinates[i].lng
                );
            }
            return totalDistance.toFixed(1);
        }
    </script>
</body>
</html>