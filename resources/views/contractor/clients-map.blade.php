@extends('layouts.contractor-sidebar')

@section('title', 'Client Locations Map')

@section('styles')
<style>
    :root {
        --primary-color: #047857;
        --secondary-color: #c0392b;
        --white-color: #ffffff;
        --light-bg: #f8f9fa;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .page-header {
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    .map-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
    }

    #map {
        height: 600px;
        width: 100%;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--white-color);
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-primary:hover {
        background: #065f46;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        color: white;
    }

    .client-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .client-item {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .client-item:hover {
        background: var(--light-bg);
    }

    .client-item.active {
        background: rgba(5, 92, 92, 0.1);
        border-left: 3px solid var(--primary-color);
    }

    .client-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .client-address {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        #map {
            height: 400px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Client Locations Map</h1>
            <p class="text-muted mb-0">View all your clients on an interactive map</p>
        </div>
        <div>
            <a href="{{ route('contractor.clients.index') }}" class="btn btn-primary">
                <i class="bi bi-list me-2"></i>View List
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Clients</div>
            <div class="stat-value">{{ $clients->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">With GPS</div>
            <div class="stat-value">{{ $clients->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Routes</div>
            <div class="stat-value">{{ $clients->pluck('route')->unique()->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Cities</div>
            <div class="stat-value">{{ $clients->pluck('city')->unique()->count() }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Client List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="client-list">
                        @forelse($clients as $client)
                            <div class="client-item" onclick="panToClient({{ $client->latitude }}, {{ $client->longitude }}, this)">
                                <div class="client-name">{{ $client->name }}</div>
                                <div class="client-address">{{ $client->address }}</div>
                                @if($client->route)
                                    <small class="badge bg-info mt-1">{{ $client->route }}</small>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                                <p>No clients with GPS coordinates</p>
                                <small>Add client locations to see them on the map</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.leaflet-assets')

@endsection

@section('scripts')
@verbatim
<script>
    let mapCtx;
    const clients = @json($clients);

    GreenRouteMap.whenReady(function () {
        // Initialize map centered on Tanzania
        mapCtx = GreenRouteMap.createMap('map', { lat: -6.3690, lng: 34.8888, zoom: 6 });

        if (clients.length > 0) {
            const points = [];
            clients.forEach(client => {
                const lat = parseFloat(client.latitude);
                const lng = parseFloat(client.longitude);
                points.push({ lat, lng });

                const markerColor = client.category === 'commercial' ? '#c0392b' : '#047857';
                
                GreenRouteMap.addMarker(mapCtx, lat, lng, {
                    title: client.name,
                    popup: `
                        <div style="min-width: 200px;">
                            <strong style="color: ${markerColor};">${client.name}</strong>
                            <p class="mb-1 mt-1">${client.address || ''}</p>
                            <p class="mb-1"><i class="bi bi-telephone"></i> ${client.phone || 'N/A'}</p>
                            ${client.route ? `<p class="mb-0"><small class="badge bg-info">${client.route}</small></p>` : ''}
                        </div>
                    `,
                });
            });

            // Fit bounds to show all clients
            if (points.length > 0) {
                GreenRouteMap.fitBounds(mapCtx, points);
            }
        }
    });

    function panToClient(lat, lng, element) {
        if (!mapCtx) return;
        
        // Highlight selected client
        document.querySelectorAll('.client-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');

        GreenRouteMap.setView(mapCtx, lat, lng, 16);
    }
</script>
@endverbatim
@endsection
