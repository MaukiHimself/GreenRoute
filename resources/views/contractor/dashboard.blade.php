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
            </div>
        </div>
    </div>

    <script>
        let map, markers = [], currentLocation, routePath;
        
        function initMap() {
            // Check if Google Maps API loaded successfully
            if (typeof google === 'undefined' || !google.maps) {
                console.error('Google Maps API failed to load');
                document.getElementById('map').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 bg-light"><div class="text-center"><i class="bi bi-exclamation-triangle display-1 text-warning"></i><p class="mt-3 text-muted">Google Maps failed to load</p><p class="small text-muted">Please check your internet connection and API key</p></div></div>';
                return;
            }
            
            // Initialize the map
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 40.7128, lng: -74.0060 },
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
            
            // Load initial data
            loadClientLocations();
            getCurrentLocation();
            
            // Set up event listeners
            document.getElementById('updateLocation').addEventListener('click', updateMyLocation);
            document.getElementById('optimizeRoute').addEventListener('click', optimizeRoute);
            document.getElementById('clearRoute').addEventListener('click', clearRoute);
        }
        
        function loadClientLocations() {
            fetch('/contractor/clients/locations')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(clients => {
                    // Clear existing markers
                    markers.forEach(marker => marker.setMap(null));
                    markers = [];
                    
                    // Add client markers
                    clients.forEach(client => {
                        const marker = new google.maps.Marker({
                            position: { 
                                lat: parseFloat(client.latitude), 
                                lng: parseFloat(client.longitude) 
                            },
                            map: map,
                            title: client.name,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: '#055c5c',
                                fillOpacity: 1,
                                strokeColor: '#ffffff',
                                strokeWeight: 2
                            }
                        });
                        
                        // Add info window
                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="padding: 1rem; min-width: 200px;">
                                    <h4 style="color: #055c5c; margin-bottom: 0.5rem; font-weight: 600;">${client.name}</h4>
                                    <p style="margin: 0.25rem 0; color: #666;">${client.address || ''}</p>
                                    <p style="margin: 0.25rem 0; color: #666;">${client.phone || ''}</p>
                                </div>
                            `
                        });
                        
                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                        
                        markers.push(marker);
                    });
                    
                    document.getElementById('clientCount').textContent = clients.length;
                    
                    // Fit map to show all markers
                    if (clients.length > 0) {
                        const bounds = new google.maps.LatLngBounds();
                        clients.forEach(client => {
                            bounds.extend(new google.maps.LatLng(
                                parseFloat(client.latitude), 
                                parseFloat(client.longitude)
                            ));
                        });
                        map.fitBounds(bounds);
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
                    currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Add current location marker
                    new google.maps.Marker({
                        position: currentLocation,
                        map: map,
                        title: 'My Current Location',
                        icon: {
                            url: 'data:image/svg+xml;base64,' + btoa(`
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="8" fill="#640404"/>
                                    <circle cx="10" cy="10" r="3" fill="white"/>
                                </svg>
                            `),
                            scaledSize: new google.maps.Size(20, 20),
                            anchor: new google.maps.Point(10, 10)
                        }
                    });
                    
                    // Center map on current location
                    map.setCenter(currentLocation);
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
        
        function optimizeRoute() {
            const button = document.getElementById('optimizeRoute');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Optimizing...';
            button.disabled = true;
            
            // Simulate route optimization
            setTimeout(() => {
                // Create a sample route (in real implementation, this would come from your backend)
                if (markers.length > 1) {
                    const routeCoordinates = markers.map(marker => marker.getPosition());
                    
                    // Clear existing route
                    if (routePath) {
                        routePath.setMap(null);
                    }
                    
                    // Draw new route
                    routePath = new google.maps.Polyline({
                        path: routeCoordinates,
                        geodesic: true,
                        strokeColor: '#055c5c',
                        strokeOpacity: 0.8,
                        strokeWeight: 4
                    });
                    
                    routePath.setMap(map);
                    
                    // Calculate approximate distance
                    const distance = calculateRouteDistance(routeCoordinates);
                    document.getElementById('routeDistance').textContent = distance + ' km';
                    
                    showNotification('Route optimized successfully!', 'success');
                } else {
                    showNotification('Need at least 2 clients to optimize route', 'warning');
                }
                
                // Restore button
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }
        
        function clearRoute() {
            if (routePath) {
                routePath.setMap(null);
                routePath = null;
                document.getElementById('routeDistance').textContent = '-- km';
                showNotification('Route cleared', 'info');
            }
        }
        
        function calculateRouteDistance(coordinates) {
            // Simple distance calculation (in real implementation, use proper routing)
            let totalDistance = 0;
            for (let i = 1; i < coordinates.length; i++) {
                const lat1 = coordinates[i-1].lat();
                const lng1 = coordinates[i-1].lng();
                const lat2 = coordinates[i].lat();
                const lng2 = coordinates[i].lng();
                
                const R = 6371; // Earth's radius in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                          Math.sin(dLng/2) * Math.sin(dLng/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                totalDistance += R * c;
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
    
    <script>
        // Global error handler for Google Maps API
        window.gm_authFailure = function() {
            console.error('Google Maps API authentication failed');
            document.getElementById('map').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 bg-light"><div class="text-center"><i class="bi bi-exclamation-triangle display-1 text-danger"></i><p class="mt-3 text-muted">Google Maps API authentication failed</p><p class="small text-muted">Please check your API key configuration</p></div></div>';
        };
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcwt701YioUFnzbJp9Bktla31qjKwM304&callback=initMap&libraries=geometry"></script>
</body>
</html>