<!DOCTYPE html>
<html>
<head>
    <title>Google Maps API Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        .status {
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Google Maps API Test</h1>
    <div id="status" class="status">Loading...</div>
    <div id="map"></div>

    <script>
        function initMap() {
            const statusDiv = document.getElementById('status');
            
            try {
                if (typeof google === 'undefined' || !google.maps) {
                    throw new Error('Google Maps API not loaded');
                }
                
                const map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: { lat: -6.7924, lng: 39.2083 }
                });
                
                new google.maps.Marker({
                    position: { lat: -6.7924, lng: 39.2083 },
                    map: map,
                    title: 'Test Location'
                });
                
                statusDiv.className = 'status success';
                statusDiv.innerHTML = '✓ Google Maps API is working correctly!';
                
            } catch (error) {
                statusDiv.className = 'status error';
                statusDiv.innerHTML = '✗ Error: ' + error.message;
            }
        }
        
        window.gm_authFailure = function() {
            const statusDiv = document.getElementById('status');
            statusDiv.className = 'status error';
            statusDiv.innerHTML = '✗ Google Maps API authentication failed. Check your API key.';
        };
        
        // Fallback if initMap is not called within 10 seconds
        setTimeout(function() {
            const statusDiv = document.getElementById('status');
            if (statusDiv.innerHTML === 'Loading...') {
                statusDiv.className = 'status error';
                statusDiv.innerHTML = '✗ Google Maps API failed to load within 10 seconds.';
            }
        }, 10000);
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>
</body>
</html>