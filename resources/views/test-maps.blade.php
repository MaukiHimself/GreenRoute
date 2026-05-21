<!DOCTYPE html>
<html>
<head>
    <title>OpenStreetMap Test — GreenRoute</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('components.leaflet-assets')
    <style>
        #map { height: 400px; width: 100%; border-radius: 8px; }
    </style>
</head>
<body class="p-4">
    <h1>OpenStreetMap Test</h1>
    <p class="text-muted">No API key required — uses free OpenStreetMap tiles.</p>
    <div id="status" class="alert alert-secondary">Loading map...</div>
    <div id="map"></div>

    <script>
        GreenRouteMap.whenReady(function () {
            const statusDiv = document.getElementById('status');
            const ctx = GreenRouteMap.createMap('map', { lat: -6.7924, lng: 39.2083, zoom: 10 });

            if (!ctx) {
                statusDiv.className = 'alert alert-danger';
                statusDiv.textContent = 'Map failed to load.';
                return;
            }

            GreenRouteMap.addMarker(ctx, -6.7924, 39.2083, {
                title: 'Dar es Salaam',
                popup: '<strong>Test location</strong><br>Dar es Salaam, Tanzania',
            });

            statusDiv.className = 'alert alert-success';
            statusDiv.innerHTML = '✓ OpenStreetMap is working correctly (free, no API key).';
        });
    </script>
</body>
</html>
