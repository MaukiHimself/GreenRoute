<!DOCTYPE html>
<html>
<head>
    <title>Route Planning Dashboard</title>
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
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 2rem 0;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Dashboard Section */
        .dashboard-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Map Container */
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        #map {
            height: 600px;
            width: 100%;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--white-color);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card.clients {
            background: rgba(5, 92, 92, 0.05);
        }

        .stat-card.routes {
            background: rgba(5, 92, 92, 0.05);
        }

        .stat-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card.clients .stat-value {
            color: var(--primary-color);
        }

        .stat-description {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }

        /* Loading States */
        .loading {
            color: var(--text-muted);
            font-style: italic;
        }

        /* Map Controls */
        .map-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 0.5rem;
            }

            .dashboard-section {
                padding: 1.5rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            #map {
                height: 400px;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-section {
                padding: 1rem;
            }

            #map {
                height: 300px;
            }

            .map-controls {
                flex-direction: column;
            }

            .map-controls .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-container">
            <!-- Route Planning Dashboard -->
            <div class="dashboard-section">
                <!-- Header -->
                <div class="section-header">
                    <h2 class="section-title">Route Planning Dashboard</h2>
                    <button id="updateLocation" class="btn-primary">
                        <i class="bi bi-geo-alt"></i> Update My Location
                    </button>
                </div>

                <!-- Map Controls -->
                <div class="map-controls">
                    <button id="optimizeRoute" class="btn-primary btn-sm">
                        <i class="bi bi-gear"></i> Optimize Route
                    </button>
                    <button id="clearRoute" class="btn-primary btn-sm" style="background: var(--secondary-color);">
                        <i class="bi bi-x-circle"></i> Clear Route
                    </button>
                </div>

                <!-- Map Container -->
                <div class="map-container">
                    <div id="map"></div>
                </div>

                 <!-- Stats Grid -->
                 <div class="stats-grid">
                     <div class="stat-card clients">
                         <div class="stat-title">
                             <i class="bi bi-people"></i> Assigned Clients
                         </div>
                         <div class="stat-value" id="clientCount">0</div>
                         <p class="stat-description">Total clients in your current route</p>
                     </div>

                     <div class="stat-card routes">
                         <div class="stat-title">
                             <i class="bi bi-signpost-split"></i> Route Information
                         </div>
                         <div class="stat-value" id="routeDistance">-- km</div>
                         <p class="stat-description">Estimated total distance</p>
                     </div>
                 </div>

                 <!-- Recent Payments -->
                 <div class="dashboard-section">
                     <div class="section-header">
                         <h2 class="section-title">Recent Payment Activity</h2>
                     </div>
                     <div id="recentPaymentsList" class="space-y-4">
                         <!-- Payments will be loaded here via JavaScript -->
                         <div class="text-center py-8">
                             <p class="text-gray-500">Loading recent payments...</p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx;
        let currentLocationMarker = null;

        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -6.7924, lng: 39.2083, zoom: 12 });
            loadClientLocations();
            getCurrentLocation();
            document.getElementById('updateLocation').addEventListener('click', updateMyLocation);
            document.getElementById('optimizeRoute').addEventListener('click', optimizeRoute);
            document.getElementById('clearRoute').addEventListener('click', clearRoute);
        });

        function loadClientLocations() {
            fetch('/contractor/clients/locations')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(clients => {
                    if (!mapCtx) return;

                    GreenRouteMap.clearMarkers(mapCtx);

                    const points = [];
                    clients.forEach(client => {
                        const lat = parseFloat(client.latitude);
                        const lng = parseFloat(client.longitude);
                        points.push({ lat, lng });

                        GreenRouteMap.addMarker(mapCtx, lat, lng, {
                            title: client.name,
                            popup: `
                                <div style="min-width: 200px;">
                                    <strong style="color: #055c5c;">${client.name}</strong>
                                    <p class="mb-0 mt-1">${client.address || ''}</p>
                                    <p class="mb-0">${client.phone || ''}</p>
                                </div>`,
                        });
                    });

                    document.getElementById('clientCount').textContent = clients.length;

                    if (points.length > 0) {
                        GreenRouteMap.fitBounds(mapCtx, points);
                    }
                })
                .catch(error => {
                    console.error('Error loading client locations:', error);
                    document.getElementById('clientCount').textContent = '0';
                    showNotification('Failed to load client locations', 'error');
                });
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    if (!mapCtx) return;

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (currentLocationMarker) {
                        GreenRouteMap.setMarkerPosition(currentLocationMarker, lat, lng);
                    } else {
                        currentLocationMarker = GreenRouteMap.addMarker(mapCtx, lat, lng, {
                            title: 'My Current Location',
                            popup: '<strong>Your location</strong>',
                        });
                    }

                    GreenRouteMap.setView(mapCtx, lat, lng, 14);
                }, error => {
                    console.warn('Error getting current location:', error);
                });
            }
        }

        function updateMyLocation() {
            const button = document.getElementById('updateLocation');
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Updating...';
            button.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    fetch('/location/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('Location updated successfully!', 'success');
                            getCurrentLocation(); // Refresh location on map
                        } else {
                            throw new Error('Update failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating location:', error);
                        showNotification('Failed to update location', 'error');
                    })
                    .finally(() => {
                        // Restore button
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
                }, error => {
                    let errorMessage = 'Error getting location: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Location access denied. Please enable location permissions.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out.';
                            break;
                        default:
                            errorMessage += 'An unknown error occurred.';
                    }

                    showNotification(errorMessage, 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            } else {
                showNotification('Geolocation is not supported by your browser.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        async function optimizeRoute() {
            const button = document.getElementById('optimizeRoute');
            const originalText = button.innerHTML;
            const token = "{{ config('services.heigit.api_key') }}";

            // Show loading state
            button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Optimizing...';
            button.disabled = true;

            if (!mapCtx || mapCtx.markers.length < 2) {
                showNotification('Need at least 2 points to optimize route', 'warning');
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            }

            GreenRouteMap.clearPolylines(mapCtx);
            const routeCoordinates = mapCtx.markers.map(m => ({ lat: m.lat, lng: m.lng }));

            const summary = await GreenRouteMap.drawRoadRoute(mapCtx, routeCoordinates, token);

            if (summary) {
                document.getElementById('routeDistance').textContent = summary.distance.toFixed(1) + ' km';
                    showNotification('Route optimized successfully!', 'success');
            } else {
                showNotification('Could not calculate road path. Using direct lines instead.', 'warning');
                const distance = calculateRouteDistance(routeCoordinates);
                GreenRouteMap.drawPolyline(mapCtx, routeCoordinates);
                document.getElementById('routeDistance').textContent = distance + ' km';
            }

            button.innerHTML = originalText;
            button.disabled = false;
        }

         function clearRoute() {
             if (!mapCtx) return;
             GreenRouteMap.clearPolylines(mapCtx);
             document.getElementById('routeDistance').textContent = '-- km';
             showNotification('Route cleared', 'info');
         }

         function loadRecentPayments() {
             fetch('/contractor/dashboard-stats') // This endpoint doesn't exist for payments, need to create one
                 .then(response => {
                     if (!response.ok) {
                         throw new Error('Network response was not ok');
                     }
                     return response.json();
                 })
                 .then(data => {
                     // This won't work as getDashboardStats doesn't return payments
                     // Need to call a different endpoint
                 })
                 .catch(error => {
                     console.error('Error loading recent payments:', error);
                     document.getElementById('recentPaymentsList').innerHTML = '<p class="text-gray-500">Failed to load recent payments</p>';
                 });
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

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                animation: slideIn 0.3s ease;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;

            if (type === 'success') {
                notification.style.background = 'var(--primary-color)';
            } else if (type === 'error') {
                notification.style.background = 'var(--secondary-color)';
            } else if (type === 'warning') {
                notification.style.background = '#d97706';
            } else {
                notification.style.background = 'var(--text-muted)';
            }

            notification.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="bi ${
                        type === 'success' ? 'bi-check-circle' :
                        type === 'error' ? 'bi-exclamation-circle' :
                        type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle'
                    }"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .spinner {
                animation: spin 1s linear infinite;
            }
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
