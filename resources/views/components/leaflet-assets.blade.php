{{-- Free maps: OpenStreetMap tiles + Leaflet (no API key required) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/greenroute-map.js') }}"></script>
<style>
    .leaflet-container { font-family: inherit; z-index: 1; }
    .gr-map-number-marker { background: transparent !important; border: none !important; }
    .gr-map-number-badge {
        background: #055c5c;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }
    #map, #dashboardMap { min-height: 200px; }
</style>
