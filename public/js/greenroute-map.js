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
        fitBounds,
        setView,
        setMarkerPosition,
        showMapError,
        haversineKm,
        whenReady,
        osmLink,
    };
})(window);
