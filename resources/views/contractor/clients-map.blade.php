@extends('layouts.contractor-app')

@section('title', 'Client Locations Map')

@push('head-scripts')
{{-- Free maps: Leaflet + OpenStreetMap/CARTO tiles (no API key, no Mapbox token) --}}
@include('components.leaflet-assets')
@endpush

@section('styles')
<style>
    :root {
        --gr-green: #047857;
        --gr-green-dark: #065f46;
        --gr-border: #e2e8f0;
        --gr-surface: #ffffff;
        --gr-muted: #64748b;
        --gr-text: #1e293b;
    }

    .map-wrap {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.25rem;
        height: calc(100vh - 200px);
        min-height: 500px;
    }

    #map {
        border-radius: 14px;
        width: 100%;
        height: 100%;
        border: 1px solid var(--gr-border);
        z-index: 1;
    }

    /* dumping-site legend */
    .site-legend {
        display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;
        background: var(--gr-surface); border: 1px solid var(--gr-border);
        border-radius: 12px; padding: .7rem 1rem; margin-bottom: 1.25rem;
        font-size: .82rem; color: var(--gr-text);
    }
    .site-legend .lg-item { display: inline-flex; align-items: center; gap: .4rem; }
    .lg-dot { width: 14px; height: 14px; border-radius: 50%; display: inline-block; border: 2px solid #fff; box-shadow: 0 0 0 1px rgba(0,0,0,.15); }
    .lg-open   { background: #16a34a; }
    .lg-closed { background: #94a3b8; }
    .lg-client { background: #047857; }

    .sidebar-panel {
        background: var(--gr-surface);
        border-radius: 14px;
        border: 1px solid var(--gr-border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gr-border);
        font-weight: 700;
        font-size: .95rem;
        color: var(--gr-text);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .sidebar-search {
        padding: .75rem 1rem;
        border-bottom: 1px solid var(--gr-border);
        flex-shrink: 0;
    }

    .sidebar-search input {
        width: 100%;
        padding: .45rem .75rem;
        border: 1px solid var(--gr-border);
        border-radius: 8px;
        font-size: .85rem;
        outline: none;
        transition: border-color .2s;
    }
    .sidebar-search input:focus { border-color: var(--gr-green); }

    .client-list {
        overflow-y: auto;
        flex: 1;
    }

    .client-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .85rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background .15s;
    }
    .client-item:hover, .client-item.active {
        background: #f0fdf4;
        border-left: 3px solid var(--gr-green);
    }
    .client-item.active .client-name { color: var(--gr-green); }

    .client-dot {
        width: 10px; height: 10px; border-radius: 50%;
        flex-shrink: 0;
    }
    .dot-residential { background: var(--gr-green); }
    .dot-commercial   { background: #c0392b; }
    .dot-industrial   { background: #d97706; }
    .dot-default      { background: #64748b; }

    .client-name  { font-weight: 600; font-size: .85rem; color: var(--gr-text); }
    .client-sub   { font-size: .75rem; color: var(--gr-muted); margin-top: .1rem; }

    .client-item .badge-route {
        margin-left: auto;
        flex-shrink: 0;
        font-size: .7rem;
        padding: .2rem .5rem;
        border-radius: 12px;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }

    /* stat bar */
    .stat-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .stat-chip {
        background: var(--gr-surface);
        border: 1px solid var(--gr-border);
        border-radius: 12px;
        padding: .9rem 1rem;
        text-align: center;
    }
    .stat-chip .val { font-size: 1.5rem; font-weight: 800; color: var(--gr-text); }
    .stat-chip .lbl { font-size: .78rem; color: var(--gr-muted); margin-top: .15rem; }

    /* popup */
    .leaflet-popup-content-wrapper {
        border-radius: 10px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,.15) !important;
    }
    .leaflet-popup-content { margin: 0 !important; }
    .popup-inner { padding: .9rem 1rem; min-width: 200px; }
    .popup-name  { font-weight: 700; font-size: .95rem; color: var(--gr-text); margin-bottom: .4rem; }
    .popup-row   { font-size: .8rem; color: var(--gr-muted); display: flex; align-items: center; gap: .35rem; margin-bottom: .2rem; }
    .popup-status { display:inline-block; font-size:.72rem; font-weight:700; padding:.15rem .55rem; border-radius:20px; margin-bottom:.4rem; }
    .popup-status.open   { background:#dcfce7; color:#15803d; }
    .popup-status.closed { background:#f1f5f9; color:#64748b; }

    @media (max-width: 900px) {
        .map-wrap {
            grid-template-columns: 1fr;
            height: auto;
        }
        #map { height: 420px; }
        .sidebar-panel { height: 300px; }
        .stat-bar { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .stat-bar { grid-template-columns: 1fr 1fr; }
    }
</style>
@endsection

@section('content')

{{-- Stat bar --}}
<div class="stat-bar">
    <div class="stat-chip">
        <div class="val">{{ $clients->count() }}</div>
        <div class="lbl">Total on Map</div>
    </div>
    <div class="stat-chip">
        <div class="val">{{ $clients->where('category','residential')->count() }}</div>
        <div class="lbl">Residential</div>
    </div>
    <div class="stat-chip">
        <div class="val">{{ $clients->where('category','commercial')->count() }}</div>
        <div class="lbl">Commercial</div>
    </div>
    <div class="stat-chip">
        <div class="val">{{ $clients->pluck('route')->filter()->unique()->count() }}</div>
        <div class="lbl">Routes</div>
    </div>
</div>

@if($clients->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-geo-alt" style="font-size:3rem;display:block;margin-bottom:1rem;opacity:.3;"></i>
        <h5>No clients with GPS coordinates</h5>
        <p class="mb-0 small">Add latitude/longitude to your clients to see them on the map.</p>
        <a href="{{ route('contractor.clients.index') }}" class="btn btn-success mt-3">Go to Clients</a>
    </div>
@else
{{-- Legend --}}
<div class="site-legend">
    <span class="fw-semibold me-1"><i class="bi bi-info-circle me-1" style="color:#047857;"></i>Legend:</span>
    <span class="lg-item"><span class="lg-dot lg-client"></span> Client</span>
    <span class="lg-item"><span class="lg-dot lg-open"></span> Dumping site — open</span>
    <span class="lg-item"><span class="lg-dot lg-closed"></span> Dumping site — closed</span>
    <span class="ms-auto text-muted" style="font-size:.78rem;">Only <strong>Pugu Kinyamwezi</strong> is currently open.</span>
</div>

{{-- Map + sidebar --}}
<div class="map-wrap" style="position:relative;">
    {{-- Map --}}
    <div style="position:relative;">
        <div id="map"></div>
    </div>

    {{-- Client list sidebar --}}
    <div class="sidebar-panel">
        <div class="sidebar-head">
            <span><i class="bi bi-list-ul me-2" style="color:#047857;"></i>Clients ({{ $clients->count() }})</span>
            <a href="{{ route('contractor.clients.index') }}" style="font-size:.78rem;color:#047857;">View List</a>
        </div>
        <div class="sidebar-search">
            <input type="text" id="clientSearch" placeholder="Search clients…" autocomplete="off">
        </div>
        <div class="client-list" id="clientList">
            @foreach($clients as $i => $client)
            <div class="client-item"
                 data-id="{{ $client->id }}"
                 data-lat="{{ $client->latitude }}"
                 data-lng="{{ $client->longitude }}"
                 data-name="{{ strtolower($client->name) }}"
                 data-address="{{ strtolower($client->address ?? '') }}"
                 onclick="flyToClient({{ $client->latitude }}, {{ $client->longitude }}, {{ $client->id }}, this)">
                <div class="client-dot dot-{{ $client->category ?? 'default' }}"></div>
                <div style="min-width:0;">
                    <div class="client-name text-truncate">{{ $client->name }}</div>
                    <div class="client-sub text-truncate">{{ $client->address ?? 'No address' }}</div>
                </div>
                @if($client->route)
                <span class="badge-route">{{ $client->route }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    const MAP_CLIENTS = @json($clients);
    const DUMPING_SITES = @json($dumpingSites ?? []);

    let mapCtx, markers = {};

    GreenRouteMap.whenReady(function () {
        mapCtx = GreenRouteMap.createMap('map', { lat: -6.8, lng: 39.25, zoom: 11 });
        if (!mapCtx) return;

        const map = mapCtx.map;
        const boundsPoints = [];

        // --- Client markers ---
        MAP_CLIENTS.forEach(function (client) {
            const lat = parseFloat(client.latitude);
            const lng = parseFloat(client.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const color = client.category === 'commercial' ? '#c0392b'
                        : client.category === 'industrial' ? '#d97706'
                        : '#047857';

            const icon = L.divIcon({
                className: 'gr-client-marker',
                html: `<div style="width:32px;height:32px;border-radius:50%;background:${color};color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;border:2.5px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.25);"><i class="bi bi-person-fill"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });

            const popupHtml = `
                <div class="popup-inner">
                    <div class="popup-name">${client.name}</div>
                    ${client.address ? `<div class="popup-row"><i class="bi bi-geo-alt-fill" style="color:#047857;"></i>${client.address}</div>` : ''}
                    ${client.phone   ? `<div class="popup-row"><i class="bi bi-telephone-fill" style="color:#047857;"></i>${client.phone}</div>` : ''}
                    ${client.category ? `<div class="popup-row"><i class="bi bi-tag-fill" style="color:#047857;"></i>${ucFirst(client.category)}</div>` : ''}
                    ${client.route ? `<div class="popup-row"><i class="bi bi-signpost-split-fill" style="color:#047857;"></i>Route: ${client.route}</div>` : ''}
                    <a href="/dashboard/contractor/clients/${client.id}"
                       style="display:inline-block;margin-top:.6rem;padding:.35rem .85rem;background:#047857;color:white;border-radius:7px;font-size:.78rem;text-decoration:none;">
                       View Client
                    </a>
                </div>`;

            const marker = L.marker([lat, lng], { icon, title: client.name })
                .addTo(map)
                .bindPopup(popupHtml, { maxWidth: 260 });

            markers[client.id] = { marker, lat, lng };
            boundsPoints.push([lat, lng]);
        });

        // --- Dumping-site markers (open = green, closed = grey) ---
        DUMPING_SITES.forEach(function (site) {
            const lat = parseFloat(site.latitude);
            const lng = parseFloat(site.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const open = !!site.is_open;
            const color = open ? '#16a34a' : '#94a3b8';
            const opacity = open ? '1' : '.8';

            const icon = L.divIcon({
                className: 'gr-site-marker',
                html: `<div style="opacity:${opacity};width:34px;height:34px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);background:${color};border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;">
                         <i class="bi bi-trash3-fill" style="transform:rotate(45deg);color:#fff;font-size:15px;"></i>
                       </div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 34],
            });

            const popupHtml = `
                <div class="popup-inner">
                    <span class="popup-status ${open ? 'open' : 'closed'}">
                        <i class="bi ${open ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}"></i>
                        ${open ? 'OPEN' : 'CLOSED'}
                    </span>
                    <div class="popup-name"><i class="bi bi-trash3-fill me-1" style="color:${color};"></i>${site.name}</div>
                    ${site.description ? `<div class="popup-row">${site.description}</div>` : ''}
                    <div class="popup-row"><i class="bi bi-pin-map-fill" style="color:${color};"></i>${lat.toFixed(4)}, ${lng.toFixed(4)}</div>
                </div>`;

            L.marker([lat, lng], { icon, title: site.name + (open ? ' (open)' : ' (closed)'), zIndexOffset: open ? 1000 : 0 })
                .addTo(map)
                .bindPopup(popupHtml, { maxWidth: 260 });

            boundsPoints.push([lat, lng]);
        });

        if (boundsPoints.length > 0) {
            map.fitBounds(boundsPoints, { padding: [60, 60], maxZoom: 14 });
        }
    });

    function flyToClient(lat, lng, id, el) {
        if (!mapCtx) return;
        document.querySelectorAll('.client-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        mapCtx.map.flyTo([lat, lng], 15, { duration: 0.8 });
        if (markers[id]) {
            setTimeout(() => markers[id].marker.openPopup(), 850);
        }
    }

    // Sidebar search filter
    document.getElementById('clientSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.client-item').forEach(item => {
            const match = item.dataset.name.includes(q) || item.dataset.address.includes(q);
            item.style.display = match ? '' : 'none';
        });
    });

    function ucFirst(s) {
        return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
    }
</script>
@endpush
