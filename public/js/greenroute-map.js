/**
 * GreenRoute map helper — OpenStreetMap tiles via Leaflet (no API key).
 */
(function (global) {
    const DEFAULT = { lat: -6.7924, lng: 39.2083, zoom: 12 };
    const BRAND = '#055c5c';

    function createMap(containerId, options = {}) {
        const el = typeof containerId === 'string'
            ? document.getElementById(containerId)
            : containerId;

        if (!el) {
            return null;
        }

        if (typeof L === 'undefined') {
            showMapError(containerId, 'Map library failed to load. Check your internet connection.');
            return null;
        }

        const lat = options.lat ?? DEFAULT.lat;
        const lng = options.lng ?? DEFAULT.lng;
        const zoom = options.zoom ?? DEFAULT.zoom;

        const map = L.map(el, { scrollWheelZoom: options.scrollWheelZoom !== false })
            .setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors',
        }).addTo(map);

        const markerLayer = L.layerGroup().addTo(map);

        return {
            map,
            markerLayer,
            markers: [],
            polylines: [],
        };
    }

    function clearMarkers(ctx) {
        if (!ctx) return;
        ctx.markerLayer.clearLayers();
        ctx.markers = [];
    }

    function clearPolylines(ctx) {
        if (!ctx) return;
        ctx.polylines.forEach((line) => ctx.map.removeLayer(line));
        ctx.polylines = [];
    }

    function addMarker(ctx, lat, lng, options = {}) {
        const marker = L.marker([lat, lng], {
            title: options.title || '',
            icon: options.icon,
        }).addTo(ctx.markerLayer);

        if (options.popup) {
            marker.bindPopup(options.popup);
        }

        if (options.onClick) {
            marker.on('click', options.onClick);
        }

        const entry = { leaflet: marker, lat: parseFloat(lat), lng: parseFloat(lng) };
        ctx.markers.push(entry);
        return entry;
    }

    function addNumberedMarker(ctx, lat, lng, number, options = {}) {
        const icon = L.divIcon({
            className: 'gr-map-number-marker',
            html: `<div class="gr-map-number-badge">${number}</div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });

        return addMarker(ctx, lat, lng, { ...options, icon });
    }

    function drawPolyline(ctx, coordinates, options = {}) {
        const latlngs = coordinates.map((c) => {
            if (Array.isArray(c)) {
                return c;
            }
            return [parseFloat(c.lat), parseFloat(c.lng)];
        });

        const line = L.polyline(latlngs, {
            color: options.color || BRAND,
            weight: options.weight || 4,
            opacity: options.opacity ?? 0.8,
        }).addTo(ctx.map);

        ctx.polylines.push(line);
        return line;
    }

    async function drawRoadRoute(ctx, points, apiKey) {
        if (!points || points.length < 2) return null;

        // Try OpenRouteService first if apiKey is provided
        if (apiKey && apiKey.trim() !== "") {
            try {
                const coordinates = points.map(p => [p.lng, p.lat]);
                const response = await fetch('https://api.openrouteservice.org/v2/directions/driving-car/geojson', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                        'Authorization': apiKey,
                        'Accept': 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8'
                    },
                    body: JSON.stringify({
                        coordinates: coordinates,
                        preference: 'shortest',
                        units: 'km'
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    const routeLayer = L.geoJSON(data, {
                        style: {
                            color: BRAND,
                            weight: 5,
                            opacity: 0.75
                        }
                    }).addTo(ctx.map);

                    ctx.polylines.push(routeLayer);

                    // Return metadata for UI updates (distance/duration)
                    return data.features[0].properties.summary;
                }
                console.warn('OpenRouteService failed, falling back to OSRM');
            } catch (error) {
                console.warn('OpenRouteService failed with error, falling back to OSRM:', error);
            }
        }

        // Fallback to OSRM
        try {
            const osrmCoords = points.map(p => `${p.lng},${p.lat}`).join(';');
            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${osrmCoords}?overview=full&geometries=geojson`;
            const osrmResponse = await fetch(osrmUrl);
            
            if (!osrmResponse.ok) throw new Error('OSRM routing API error');

            const osrmData = await osrmResponse.json();
            if (osrmData.code === 'Ok' && osrmData.routes && osrmData.routes.length > 0) {
                const route = osrmData.routes[0];
                const routeLayer = L.geoJSON(route.geometry, {
                    style: {
                        color: BRAND,
                        weight: 5,
                        opacity: 0.75
                    }
                }).addTo(ctx.map);

                ctx.polylines.push(routeLayer);

                // OSRM returns distance in meters, convert to km
                return {
                    distance: route.distance / 1000,
                    duration: route.duration
                };
            }
            throw new Error('OSRM returned no routes');
        } catch (error) {
            console.error('All routing engines failed:', error);
            return null;
        }
    }

    function fitBounds(ctx, points, padding = [40, 40]) {
        if (!ctx || !points || points.length === 0) {
            return;
        }

        const bounds = L.latLngBounds(
            points.map((p) => {
                if (Array.isArray(p)) {
                    return p;
                }
                return [parseFloat(p.lat), parseFloat(p.lng)];
            })
        );

        ctx.map.fitBounds(bounds, { padding });
    }

    function setView(ctx, lat, lng, zoom) {
        if (!ctx) return;
        ctx.map.setView([lat, lng], zoom ?? ctx.map.getZoom());
    }

    function setMarkerPosition(entry, lat, lng) {
        if (!entry || !entry.leaflet) return;
        entry.leaflet.setLatLng([lat, lng]);
        entry.lat = lat;
        entry.lng = lng;
    }

    function showMapError(containerId, message) {
        const el = typeof containerId === 'string'
            ? document.getElementById(containerId)
            : containerId;
        if (!el) return;

        el.innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded p-4">
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                    <p class="mt-3 text-muted mb-1">Map unavailable</p>
                    <p class="small text-muted mb-0">${message}</p>
                </div>
            </div>`;
    }

    function haversineKm(lat1, lng1, lat2, lng2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2
            + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
            * Math.sin(dLng / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    function whenReady(callback) {
        const run = () => {
            if (typeof L !== 'undefined') {
                callback();
            } else {
                showMapError('map', 'Map library failed to load.');
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', run);
        } else {
            run();
        }
    }

    function osmLink(lat, lng, zoom = 16) {
        return `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=${zoom}/${lat}/${lng}`;
    }

    global.GreenRouteMap = {
        DEFAULT,
        BRAND,
        createMap,
        clearMarkers,
        clearPolylines,
        addMarker,
        addNumberedMarker,
        drawPolyline,
        drawRoadRoute,
        fitBounds,
        setView,
        setMarkerPosition,
        showMapError,
        haversineKm,
        whenReady,
        osmLink,

        initGlobalReachability: function(ctx, apiKey) {
            if (!ctx || !ctx.map) {
                console.error('GreenRouteMap helper error: Valid map context instance missing.');
                return null;
            }
            if (typeof L.Reachability !== 'function') {
                console.warn('Reachability scripts not detected on this page view component.');
                return null;
            }

            // Injects the fully interactive user control panel widget to choose settings
            return L.reachability({
                apiKey: apiKey,
                styleFn: function (value, intervalType) {
                    return {
                        color: intervalType === 'time' ? '#22c55e' : '#3b82f6', // Green for Time, Blue for Distance
                        weight: 2,
                        opacity: 0.7,
                        fillOpacity: 0.15
                    };
                },
                settings: {
                    profile: 'driving-car',       // Set default routing profile optimized for driving vehicles
                    rangeType: 'time',            // Defaults view option to time, allow users to choose/switch to distance
                    range: '300,600,900',         // 5, 10, and 15-minute calculations
                    showOriginMarker: true
                },
                controls: {
                    position: 'topright',
                    expandDirection: 'left',      // Dynamic sliding UI layout clear of standard Zoom maps controls
                    buttons: {
                        draw: 'Click to choose and calculate reachability routing',
                        clear: 'Clear route paths'
                    }
                }
            }).addTo(ctx.map);
        }
    };
})(window);
