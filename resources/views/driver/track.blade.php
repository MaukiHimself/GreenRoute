<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Tracker - GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-teal: #055c5c;
            --dark-slate: #0f172a;
        }

        body {
            background-color: #f8fafc;
            color: var(--dark-slate);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .tracker-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(5, 92, 92, 0.1);
            border: 1px solid #e2e8f0;
            padding: 2rem;
            max-width: 450px;
            margin: auto;
            width: 90%;
            text-align: center;
        }

        .brand-logo {
            color: var(--primary-teal);
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .truck-icon {
            font-size: 3rem;
            color: var(--primary-teal);
            margin: 1rem 0;
            animation: bounce 2s infinite;
        }

        .btn-track {
            background-color: var(--primary-teal);
            border: none;
            color: white;
            padding: 1rem 2rem;
            font-size: 1.25rem;
            font-weight: 700;
            border-radius: 50px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
            transition: all 0.3s;
        }

        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
            background-color: #044a4a;
        }

        .btn-track.active {
            background-color: #ef4444;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-track.active:hover {
            background-color: #dc2626;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        .pulse-dot {
            width: 12px;
            height: 12px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            animation: pulse 1.5s infinite;
        }

        .info-pill {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
        }

        .status-container {
            font-size: 0.9rem;
            margin-top: 1.5rem;
            min-height: 40px;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="tracker-card">
            <!-- Brand -->
            <div class="brand-logo">
                <i class="bi bi-compass"></i> GreenRoute
            </div>
            <p class="text-muted small">Driver Tracking Terminal</p>

            <i class="bi bi-truck truck-icon"></i>

            <!-- Vehicle Info -->
            <div class="mb-4">
                <h3 class="mb-3" style="color: var(--primary-teal); font-weight: 700;">
                    {{ $truck->plate_number }}
                </h3>
                <div class="info-pill d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person me-2"></i>Driver:</span>
                    <strong>{{ $truck->driver_name }}</strong>
                </div>
                <div class="info-pill d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-telephone me-2"></i>Contact:</span>
                    <strong>{{ $truck->driver_phone }}</strong>
                </div>
                <div class="info-pill d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-speedometer2 me-2"></i>Distance Today:</span>
                    <strong id="distance-val">{{ number_format($truck->daily_distance, 2) }} km</strong>
                </div>
            </div>

            <!-- Tracking Toggle -->
            <button id="trackBtn" class="btn btn-track" onclick="toggleTracking()">
                <i class="bi bi-geo-alt-fill me-2"></i>Start Sharing Location
            </button>

            <!-- Status Logs -->
            <div class="status-container">
                <div id="statusIndicator" class="d-none mb-2">
                    <span class="pulse-dot"></span><span class="text-success fw-bold">Live Tracking Active</span>
                </div>
                <p id="statusMsg" class="text-muted mb-0">Location sharing is currently inactive.</p>
                <small id="lastUpdated" class="text-muted d-block mt-1"></small>
            </div>
        </div>
    </div>

    <script>
        let isTracking = false;
        let watchId = null;
        let updateIntervalId = null;
        let lastPosition = null;
        const token = "{{ $truck->tracking_token }}";

        function toggleTracking() {
            const btn = document.getElementById('trackBtn');
            const indicator = document.getElementById('statusIndicator');
            const msg = document.getElementById('statusMsg');

            if (!isTracking) {
                // Request location permissions & start watching
                if (navigator.geolocation) {
                    msg.textContent = "Requesting GPS permissions...";
                    
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            isTracking = true;
                            btn.classList.add('active');
                            btn.innerHTML = '<i class="bi bi-stop-circle-fill me-2"></i>Stop Sharing Location';
                            indicator.classList.remove('d-none');
                            msg.className = "text-success fw-semibold";
                            msg.textContent = "Position acquired. Transmitting...";

                            // Send initial location
                            sendLocationUpdate(position.coords.latitude, position.coords.longitude);

                            // Setup continuous updates via watchPosition for real-time accuracy
                            watchId = navigator.geolocation.watchPosition(
                                (pos) => {
                                    lastPosition = pos;
                                },
                                (err) => {
                                    handleError(err);
                                },
                                { enableHighAccuracy: true, maximumAge: 10000 }
                            );

                            // Post location to server every 20 seconds
                            updateIntervalId = setInterval(() => {
                                if (lastPosition) {
                                    sendLocationUpdate(lastPosition.coords.latitude, lastPosition.coords.longitude);
                                }
                            }, 20000);
                        },
                        (err) => {
                            handleError(err);
                        },
                        { enableHighAccuracy: true }
                    );
                } else {
                    msg.className = "text-danger fw-bold";
                    msg.textContent = "Error: Geolocation is not supported by this browser.";
                }
            } else {
                // Stop tracking
                if (watchId) navigator.geolocation.clearWatch(watchId);
                if (updateIntervalId) clearInterval(updateIntervalId);
                
                isTracking = false;
                btn.classList.remove('active');
                btn.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Start Sharing Location';
                indicator.classList.add('d-none');
                msg.className = "text-muted";
                msg.textContent = "Location sharing is currently inactive.";
                document.getElementById('lastUpdated').textContent = "";
            }
        }

        function sendLocationUpdate(lat, lng) {
            fetch(`/driver/location/${token}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Server error");
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const msg = document.getElementById('statusMsg');
                    msg.className = "text-success fw-semibold";
                    msg.textContent = "Location updated successfully.";
                    document.getElementById('lastUpdated').textContent = "Last sent: " + new Date().toLocaleTimeString();
                    
                    // Periodically refresh stats (optional, but nice)
                    refreshStats();
                }
            })
            .catch(error => {
                console.error("Tracking upload failed:", error);
                const msg = document.getElementById('statusMsg');
                msg.className = "text-warning fw-semibold";
                msg.textContent = "Network issue. Retrying...";
            });
        }

        function refreshStats() {
            fetch(`/trucks/locations`)
                .then(r => r.json())
                .then(trucks => {
                    const currentTruck = trucks.find(t => t.tracking_token === token);
                    if (currentTruck) {
                        document.getElementById('distance-val').textContent = parseFloat(currentTruck.daily_distance).toFixed(2) + " km";
                    }
                })
                .catch(e => console.warn("Failed to update daily distance stat"));
        }

        function handleError(error) {
            const msg = document.getElementById('statusMsg');
            msg.className = "text-danger fw-bold";
            
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    msg.textContent = "GPS Access Denied. Please enable location services in your phone/browser settings.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg.textContent = "GPS signals unavailable. Ensure you are outdoors or near a window.";
                    break;
                case error.TIMEOUT:
                    msg.textContent = "GPS request timed out. Retrying...";
                    break;
                default:
                    msg.textContent = "An unknown error occurred while locating.";
            }
            
            // Turn off tracking button state
            if (isTracking) {
                toggleTracking();
            }
        }
    </script>
</body>
</html>
