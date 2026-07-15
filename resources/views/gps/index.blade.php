@extends('layouts.contractor-sidebar')

@section('title', 'GPS Tracker')

@section('styles')
<style>
    :root {
        --primary-color: #047857;
        --primary-light: #059669;
        --primary-trans: rgba(5, 92, 92, 0.08);
        --secondary-color: #c0392b;
        --white-color: #ffffff;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --radius-lg: 24px;
        --radius-md: 14px;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        --shadow-lg: 0 20px 30px -10px rgba(5, 92, 92, 0.08);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        font-family: 'Outfit', sans-serif;
        min-height: 100vh;
        padding: 0;
        margin: 0;
        color: var(--text-dark);
        -webkit-font-smoothing: antialiased;
    }

    .container-fluid {
        padding: 2.5rem;
        max-width: 1500px;
    }

    /* Header Section */
    .page-header {
        padding-bottom: 1.5rem;
        margin-bottom: 2.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Content Sections */
    .content-section {
        background: var(--white-color);
        border-radius: var(--radius-lg);
        padding: 2.25rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .section-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: var(--text-dark);
        background-color: #f8fafc;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        background-color: var(--white-color);
        box-shadow: 0 0 0 4px var(--primary-trans);
        outline: none;
    }

    /* Buttons */
    .btn {
        border-radius: var(--radius-md);
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        font-size: 0.925rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(5, 92, 92, 0.15);
        width: 100%;
    }

    .btn-primary:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 92, 92, 0.25);
        color: white;
    }

    .btn-sm {
        padding: 0.45rem 0.85rem;
        font-size: 0.825rem;
        border-radius: 10px;
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border: 1.5px solid var(--primary-color);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-success {
        color: #10b981;
        border: 1.5px solid #10b981;
        background: transparent;
    }

    .btn-outline-success:hover {
        background: #10b981;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-secondary {
        color: var(--text-muted);
        border: 1.5px solid var(--border-color);
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: #f1f5f9;
        color: var(--text-dark);
        transform: translateY(-1px);
    }

    .btn-outline-danger {
        color: #ef4444;
        border: 1.5px solid #ef4444;
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-1px);
    }

    /* Truck List */
    .trucks-container {
        background: #f8fafc;
        border-radius: var(--radius-md);
        padding: 1.25rem;
        max-height: 480px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
    }

    .trucks-container::-webkit-scrollbar {
        width: 6px;
    }
    .trucks-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .trucks-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .truck-item {
        background: var(--white-color);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        margin-bottom: 0.85rem;
        border-left: 4px solid var(--primary-color);
        border-top: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
    }

    .truck-item:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: var(--shadow-md);
        border-color: rgba(5, 92, 92, 0.2);
    }

    .truck-item:last-child {
        margin-bottom: 0;
    }

    .truck-plate {
        font-weight: 800;
        color: var(--primary-color);
        font-size: 1.15rem;
        letter-spacing: -0.01em;
    }

    .truck-driver {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
        margin-top: 0.25rem;
    }

    .truck-phone {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .truck-badge {
        background: var(--primary-trans);
        color: var(--primary-color);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-online {
        background: #dcfce7;
        color: #15803d;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        border: 1px solid #bbf7d0;
    }

    .status-offline {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .distance-display {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .truck-actions {
        display: flex;
        gap: 0.35rem;
        margin-top: 1.1rem;
        flex-wrap: wrap;
    }

    .truck-actions form {
        margin: 0;
        display: inline-block;
    }

    /* Map Container */
    .map-container {
        background: var(--white-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        height: 620px;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    #map {
        height: 100%;
        width: 100%;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
        display: block;
    }

    /* Refresh Button */
    .refresh-btn {
        background: var(--white-color);
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 0.5rem 1.25rem;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .refresh-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    /* Slide-out Playback Drawer */
    .playback-drawer {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background: var(--white-color);
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
        z-index: 1050;
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 2rem;
        display: flex;
        flex-direction: column;
    }
    .playback-drawer.open {
        right: 0;
    }
    .playback-drawer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
    }
    .playback-controls {
        background: #f8fafc;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        margin-top: 1.5rem;
    }
    @media (max-width: 576px) {
        .playback-drawer {
            width: 100%;
            right: -100%;
        }
    }

    /* Leaflet Transition for Gliding Markers */
    .gr-truck-marker {
        background: transparent !important;
        border: none !important;
        transition: transform 0.8s linear !important; /* Smooth gliding */
    }

    .gr-truck-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        border: 2px solid white;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    .gr-truck-badge.online {
        background-color: #22c55e;
        animation: markerPulse 2s infinite;
    }
    .gr-truck-badge.offline {
        background-color: #64748b;
    }
    @keyframes markerPulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Progress bar */
    .progress {
        height: 6px;
        background-color: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">GPS Tracker</h1>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-success" onclick="toggleHistoryDrawer()">
                <i class="bi bi-clipboard-check me-1"></i> Collection History
            </button>
            <button class="btn btn-outline-primary" onclick="togglePlaybackDrawer()">
                <i class="bi bi-clock-history me-1"></i> Route Playback Audit
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Contractor base (yard) --}}
    <div class="content-section mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="small">
                <i class="bi bi-house-door-fill me-1" style="color:#2563eb;"></i>
                <strong>Your base (yard):</strong>
                @if($base)
                    {{ $base['address'] ?? (number_format($base['lat'], 5) . ', ' . number_format($base['lng'], 5)) }}
                    <span class="text-muted">— all trucks start & return here.</span>
                @else
                    <span class="text-warning">Not set. Set it from any route's map so trucks know where to start.</span>
                @endif
            </div>
            <span class="badge bg-light text-dark border">
                <i class="bi bi-truck me-1"></i>{{ $trucks->count() }} truck{{ $trucks->count() === 1 ? '' : 's' }}
                &nbsp;|&nbsp;
                <i class="bi bi-signpost-split me-1"></i>{{ $routes->count() }} route{{ $routes->count() === 1 ? '' : 's' }}
            </span>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Truck Management -->
        <div class="col-lg-4">
            <!-- Register New Truck -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Register New Truck</h2>
                </div>

                <form method="POST" action="{{ route('trucks.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Plate Number</label>
                        <input type="text" class="form-control" name="plate_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <input type="text" class="form-control" name="driver_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Phone</label>
                        <input type="text" class="form-control" name="driver_phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Truck Type</label>
                        <select class="form-select" name="truck_type" required>
                            <option value="">Select Type</option>
                            <option value="small">Small Truck</option>
                            <option value="medium">Medium Truck</option>
                            <option value="large">Large Truck</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Empty Truck Weight — Tare (kg)</label>
                        <input type="number" class="form-control" name="tare_weight_kg" min="100" max="50000" step="0.1" placeholder="e.g. 3500" required>
                        <small class="text-muted">Used at the weighbridge: net waste = gross reading − this weight.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Truck</button>
                </form>
            </div>

            <!-- Registered Trucks -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Registered Trucks</h2>
                </div>

                <div class="trucks-container" id="trucksList">
                    @forelse($trucks as $truck)
                    <div class="truck-item" id="truck-{{ $truck->id }}" data-truck-id="{{ $truck->id }}" data-assigned-route="{{ $truck->assigned_route_id ?? '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <div class="truck-plate">{{ $truck->plate_number }}</div>
                                <div class="truck-driver">{{ $truck->driver_name }}</div>
                                <div class="truck-phone">{{ $truck->driver_phone }}</div>
                                <span class="truck-badge">{{ ucfirst($truck->truck_type) }}</span>
                                @if($truck->tare_weight_kg)
                                    <span class="badge" style="background:#e0f2fe;color:#075985;"><i class="bi bi-box-seam me-1"></i>Tare {{ number_format($truck->tare_weight_kg) }} kg</span>
                                @endif
                                <span class="badge ms-1" id="route-badge-{{ $truck->id }}"
                                      style="{{ $truck->assignedRoute ? 'background:'.$truck->assignedRoute->color.';color:#fff;' : 'background:#f1f5f9;color:#64748b;' }}">
                                    <i class="bi bi-signpost-split me-1"></i>{{ $truck->assignedRoute ? $truck->assignedRoute->route_name : 'No route' }}
                                </span>
                            </div>
                            <div class="text-end">
                                <div class="status-badge" id="status-{{ $truck->id }}">
                                    @if($truck->last_updated && $truck->last_updated->diffInMinutes(now()) < 10)
                                        <span class="status-online">Online</span>
                                    @else
                                        <span class="status-offline">Offline</span>
                                    @endif
                                </div>
                                <div class="distance-display mt-1" id="distance-{{ $truck->id }}">
                                    {{ number_format($truck->daily_distance, 2) }} km
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar Metrics -->
                        @if($truck->assignedRoute)
                            @php
                                $clientsCount = \App\Models\Client::where('contractor_id', $truck->contractor_id)
                                    ->where('route', $truck->assignedRoute->route_name)
                                    ->whereNotNull('latitude')
                                    ->whereNotNull('longitude')
                                    ->count();
                                    
                                $stopStatuses = $truck->stop_statuses ?? [];
                                $completedCount = 0;
                                if ($clientsCount > 0) {
                                    $routeClients = \App\Models\Client::where('contractor_id', $truck->contractor_id)
                                        ->where('route', $truck->assignedRoute->route_name)
                                        ->whereNotNull('latitude')
                                        ->whereNotNull('longitude')
                                        ->pluck('id')
                                        ->toArray();
                                        
                                    foreach ($routeClients as $cid) {
                                        if (isset($stopStatuses[$cid]) && in_array($stopStatuses[$cid], ['collected', 'skipped', 'blocked'])) {
                                            $completedCount++;
                                        }
                                    }
                                    $progressPct = round(($completedCount / $clientsCount) * 100);
                                } else {
                                    $progressPct = 0;
                                }
                            @endphp
                            <div class="mt-2 mb-2" id="progress-container-{{ $truck->id }}" style="{{ $clientsCount > 0 ? '' : 'display:none;' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted fw-semibold">Progress: <span id="progress-text-{{ $truck->id }}">{{ $completedCount }} / {{ $clientsCount }} stops</span></small>
                                    <small class="text-primary fw-bold" id="progress-pct-{{ $truck->id }}">{{ $progressPct }}%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" id="progress-bar-{{ $truck->id }}" role="progressbar" style="width: {{ $progressPct }}%;" aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endif

                        <div class="truck-actions">
                            <button class="btn btn-outline-primary btn-sm" onclick="trackTruck({{ $truck->id }})">
                                <i class="bi bi-geo-alt me-1"></i>Track
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="followRoute({{ $truck->id }})" {{ $truck->assigned_route_id ? '' : 'disabled' }} id="follow-{{ $truck->id }}">
                                <i class="bi bi-signpost-split me-1"></i>Follow Route
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="simulateMovement({{ $truck->id }})">
                                <i class="bi bi-play-circle me-1"></i>Simulate
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyTrackingLink('{{ url('/driver/track/' . $truck->tracking_token) }}', this)">
                                <i class="bi bi-link-45deg me-1"></i>Copy Link
                            </button>
                            <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this truck?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Remove
                                </button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('trucks.assign-route', $truck->id) }}" class="d-flex gap-1 mt-2 assign-route-form" id="assign-form-{{ $truck->id }}">
                            @csrf
                            <select name="route_id" class="form-select form-select-sm" onchange="this.closest('form').submit()">
                                <option value="">No route</option>
                                @foreach($routes as $r)
                                    <option value="{{ $r->id }}" @selected($truck->assigned_route_id == $r->id)>{{ $r->route_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap">
                                <i class="bi bi-signpost-split me-1"></i>Assign
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-truck"></i>
                        <p class="mb-0">No trucks registered</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Map -->
        <div class="col-lg-8">
            <div class="content-section p-0">
                <div class="section-header p-3">
                    <h2 class="section-title">Live Truck Locations</h2>
                    <button class="refresh-btn" onclick="refreshLocations()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                </div>
                <div class="map-container">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slide-out Playback Audit Drawer -->
<div id="playbackDrawer" class="playback-drawer">
    <div class="playback-drawer-header">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Route Playback Audit</h5>
        <button type="button" class="btn-close" onclick="togglePlaybackDrawer()"></button>
    </div>
    <div class="mb-3">
        <label class="form-label">Select Truck</label>
        <select id="playbackTruckSelect" class="form-select">
            <option value="">Choose a vehicle...</option>
            @foreach($trucks as $t)
                <option value="{{ $t->id }}">{{ $t->plate_number }} ({{ $t->driver_name }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Select Date</label>
        <input type="date" id="playbackDate" class="form-select" value="{{ date('Y-m-d') }}">
    </div>
    <button class="btn btn-primary w-100 mb-3" onclick="loadPlaybackHistory()">
        <i class="bi bi-play-circle me-1"></i> Load Playback
    </button>

    <!-- Controls (initially hidden) -->
    <div id="playbackControls" class="playback-controls d-none">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small text-muted fw-semibold">Audit Player</span>
            <span id="playbackTime" class="badge bg-secondary">00:00 AM</span>
        </div>
        
        <input type="range" id="playbackRange" class="form-range mb-3" min="0" max="0" value="0">

        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary" id="btnPlayPause" onclick="togglePlayback()">
                    <i class="bi bi-play-fill"></i> Play
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="stopPlayback()">
                    <i class="bi bi-stop-fill"></i> Stop
                </button>
            </div>
            <div class="btn-group" id="speedGroup">
                <button class="btn btn-sm btn-outline-secondary active" onclick="setPlaybackSpeed(1, this)">1x</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="setPlaybackSpeed(2, this)">2x</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="setPlaybackSpeed(4, this)">4x</button>
            </div>
        </div>
    </div>
</div>

<!-- Slide-out Collection History Drawer -->
<div id="historyDrawer" class="playback-drawer">
    <div class="playback-drawer-header">
        <h5 class="fw-bold mb-0"><i class="bi bi-clipboard-check me-2 text-success"></i>Collection History</h5>
        <button type="button" class="btn-close" onclick="toggleHistoryDrawer()"></button>
    </div>
    <p class="text-muted small mb-3">Completed route runs with per-client outcomes.</p>
    <div id="historyList" style="overflow-y:auto; flex:1;">
        <div class="text-center text-muted py-4">
            <div class="spinner-border spinner-border-sm" role="status"></div>
            <div class="small mt-2">Loading…</div>
        </div>
    </div>
</div>

@include('components.leaflet-assets')

<script>
    let mapCtx;
    const truckMarkers = {};
    const routeLayers = {};        // truckId -> array of Leaflet layers (markers + polyline)
    const roadGeometryCache = {};  // truckId -> cached road geometry (avoids re-hitting routing APIs each poll)

    const contractorBase = @json($base);
    const routingToken = "{{ config('services.heigit.api_key') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const truckMeta = @json($truckMeta);

    // Playback state variables
    let isPlaybackMode = false;
    let playbackHistory = [];
    let playbackIndex = 0;
    let playbackInterval = null;
    let playbackSpeed = 1; // 1x = 1000ms, 2x = 500ms, 4x = 250ms
    let playbackPolyline = null;
    let playbackMarker = null;

    GreenRouteMap.whenReady(function () {
        mapCtx = GreenRouteMap.createMap('map', {
            lat: contractorBase ? contractorBase.lat : -6.7924,
            lng: contractorBase ? contractorBase.lng : 39.2083,
            zoom: 12
        });
        loadTruckLocations();
        drawAllRoutes();
        
        // Live polling every 10 seconds for real-time dashboard sync
        setInterval(refreshLocations, 10000);

        // Timeline range scrubber handler
        const scrubber = document.getElementById('playbackRange');
        scrubber.addEventListener('input', function() {
            if (playbackHistory.length > 0) {
                updatePlaybackIndex(parseInt(this.value));
            }
        });
    });

    function loadTruckLocations() {
        if (isPlaybackMode) return; // Don't interrupt playback coordinates

        fetch('/trucks/locations')
            .then(response => response.json())
            .then(trucks => {
                trucks.forEach(truck => {
                    if (truck.current_latitude && truck.current_longitude) {
                        updateTruckMarker(truck);
                    }

                    // Update status badge dynamically
                    const statusBadge = document.getElementById(`status-${truck.id}`);
                    if (statusBadge) {
                        if (truck.is_online) {
                            statusBadge.innerHTML = '<span class="status-online">Online</span>';
                        } else {
                            statusBadge.innerHTML = '<span class="status-offline">Offline</span>';
                        }
                    }

                    // Update distance dynamically
                    const distanceDisplay = document.getElementById(`distance-${truck.id}`);
                    if (distanceDisplay) {
                        distanceDisplay.textContent = parseFloat(truck.daily_distance).toFixed(2) + ' km';
                    }

                    // Update progress metrics dynamically
                    const progressBar = document.getElementById(`progress-bar-${truck.id}`);
                    const progressText = document.getElementById(`progress-text-${truck.id}`);
                    const progressPct = document.getElementById(`progress-pct-${truck.id}`);
                    const progressContainer = document.getElementById(`progress-container-${truck.id}`);
                    
                    if (truck.total_stops > 0) {
                        if (progressContainer) {
                            progressContainer.style.display = 'block';
                            progressBar.style.width = `${truck.progress_percent}%`;
                            progressBar.setAttribute('aria-valuenow', truck.progress_percent);
                            progressText.textContent = `${truck.completed_stops} / ${truck.total_stops} stops`;
                            progressPct.textContent = `${truck.progress_percent}%`;
                        }
                    } else {
                        if (progressContainer) {
                            progressContainer.style.display = 'none';
                        }
                    }
                });
            });
    }

    function updateTruckMarker(truck) {
        if (!mapCtx) return;

        const lat = parseFloat(truck.current_latitude);
        const lng = parseFloat(truck.current_longitude);
        const popup = `
            <div style="min-width: 200px;">
                <div style="font-weight: 700; color: #047857; margin-bottom: 0.5rem;">${truck.plate_number}</div>
                <div><strong>Driver:</strong> ${truck.driver_name}</div>
                <div><strong>Phone:</strong> ${truck.driver_phone}</div>
                <div><strong>Distance Today:</strong> ${parseFloat(truck.daily_distance).toFixed(2)} km</div>
                <div><strong>Last Updated:</strong> ${truck.last_updated ? new Date(truck.last_updated).toLocaleTimeString() : 'Never'}</div>
            </div>`;

        // DivIcon for vehicles
        const truckIcon = L.divIcon({
            className: 'gr-truck-marker',
            html: `<div class="gr-truck-badge ${truck.is_online ? 'online' : 'offline'}"><i class="bi bi-truck"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        if (truckMarkers[truck.id]) {
            // Smoothly glide position
            truckMarkers[truck.id].leaflet.setLatLng([lat, lng]);
            truckMarkers[truck.id].leaflet.setPopupContent(popup);
        } else {
            truckMarkers[truck.id] = GreenRouteMap.addMarker(mapCtx, lat, lng, {
                title: truck.plate_number,
                popup,
                icon: truckIcon
            });
        }
    }

    function trackTruck(truckId) {
        const entry = truckMarkers[truckId];
        if (entry && mapCtx) {
            GreenRouteMap.setView(mapCtx, entry.leaflet.getLatLng().lat, entry.leaflet.getLatLng().lng, 15);
            entry.leaflet.openPopup();
        } else {
            alert('No location data received yet for this vehicle.');
        }
    }

    function refreshLocations() {
        loadTruckLocations();
        // Redraw route pins dynamically so collection updates reflect status changes
        if (!isPlaybackMode) {
            drawAllRoutes();
        }
    }

    function simulateMovement(truckId) {
        // Find truck current position
        let baseLat = contractorBase ? contractorBase.lat : -6.7924;
        let baseLng = contractorBase ? contractorBase.lng : 39.2083;

        if (truckMarkers[truckId]) {
            const pos = truckMarkers[truckId].leaflet.getLatLng();
            baseLat = pos.lat;
            baseLng = pos.lng;
        }

        // Add small random steps
        const lat = baseLat + (Math.random() - 0.5) * 0.005;
        const lng = baseLng + (Math.random() - 0.5) * 0.005;

        fetch(`/trucks/${truckId}/location`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: lng
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshLocations();
            }
        });
    }

    function copyTrackingLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Copied!';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success', 'text-white');
            btn.disabled = true;
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success', 'text-white');
                btn.classList.add('btn-outline-secondary');
                btn.disabled = false;
            }, 2000);
        }).catch(err => {
            console.error('Copy failed: ', err);
            alert('Link: ' + url);
        });
    }

    /* ---------- Route drawing & markers ---------- */

    function baseIcon() {
        return L.divIcon({
            className: 'gr-endpoint-marker',
            html: `<div style="background:#2563eb;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-house-door-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 28],
        });
    }

    function dumpIcon() {
        return L.divIcon({
            className: 'gr-endpoint-marker',
            html: `<div style="background:#c0392b;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;"><i class="bi bi-trash3-fill" style="transform:rotate(45deg);color:#fff;font-size:14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 28],
        });
    }

    function clearTruckRoute(truckId) {
        if (routeLayers[truckId]) {
            routeLayers[truckId].forEach(layer => mapCtx.map.removeLayer(layer));
            delete routeLayers[truckId];
        }
    }

    function drawRouteLayers(truckId, res) {
        if (!mapCtx) return Promise.resolve(res);
        clearTruckRoute(truckId);

        const color = res.route_color || '#047857';
        const layers = drawStopMarkers(truckId, res, color);

        // Prefer the server-computed road geometry (reliable + cached). Only if
        // it's missing do we fall back to client-side routing / a straight line.
        const geom = res.geometry || roadGeometryCache[truckId];
        if (geom && geom.length > 1) {
            const line = L.polyline(geom, { color, weight: 5, opacity: 0.75 }).addTo(mapCtx.map);
            layers.push(line);
            res.geometry = geom;
            roadGeometryCache[truckId] = geom;
            routeLayers[truckId] = layers;
            return Promise.resolve(res);
        }

        return GreenRouteMap.drawRoadRoute(mapCtx, res.waypoints, routingToken, { color: color }).then(road => {
            if (road && road.geometry) {
                const layer = mapCtx.polylines[mapCtx.polylines.length - 1];
                if (layer) layers.push(layer);
                res.geometry = road.geometry;
                roadGeometryCache[truckId] = road.geometry;
            } else {
                layers.push(GreenRouteMap.drawPolyline(mapCtx, res.waypoints, { color }));
                res.geometry = res.waypoints.map(w => [w.lat, w.lng]);
            }
            routeLayers[truckId] = layers;
            return res;
        });
    }

    // Draws base / client / dumping stop markers (cheap, re-run on every poll so
    // collection-status icons stay current). Returns the created marker layers.
    function drawStopMarkers(truckId, res, color) {
        const layers = [];

        res.waypoints.forEach((wp, i) => {
            if (wp.type === 'base') {
                layers.push(GreenRouteMap.addMarker(mapCtx, wp.lat, wp.lng, {
                    icon: baseIcon(),
                    popup: `<strong>Start: Contractor base</strong><br>${wp.name}`
                }).leaflet);
            } else if (wp.type === 'dumping') {
                layers.push(GreenRouteMap.addMarker(mapCtx, wp.lat, wp.lng, {
                    icon: dumpIcon(),
                    popup: `<strong>End: ${wp.name}</strong><br>Dumping / disposal site`
                }).leaflet);
            } else {
                // Client stop!
                const status = (res.stop_statuses && res.stop_statuses[wp.id]) ? res.stop_statuses[wp.id] : 'pending';
                let icon;
                
                if (status === 'collected') {
                    icon = L.divIcon({
                        className: 'gr-stop-marker',
                        html: `<div style="background:#10b981;width:28px;height:28px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-check-lg" style="font-size:14px;font-weight:bold;"></i></div>`,
                        iconSize: [28, 28], iconAnchor: [14, 14]
                    });
                } else if (status === 'skipped') {
                    icon = L.divIcon({
                        className: 'gr-stop-marker',
                        html: `<div style="background:#f59e0b;width:28px;height:28px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-x-lg" style="font-size:12px;font-weight:bold;"></i></div>`,
                        iconSize: [28, 28], iconAnchor: [14, 14]
                    });
                } else if (status === 'blocked') {
                    icon = L.divIcon({
                        className: 'gr-stop-marker',
                        html: `<div style="background:#ef4444;width:28px;height:28px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-exclamation-triangle-fill" style="font-size:12px;color:#fff;"></i></div>`,
                        iconSize: [28, 28], iconAnchor: [14, 14]
                    });
                } else {
                    // Pending numbered route pin
                    icon = L.divIcon({
                        className: 'gr-stop-marker',
                        html: `<div style="background:${color};width:28px;height:28px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:12px;">${i}</div>`,
                        iconSize: [28, 28], iconAnchor: [14, 14]
                    });
                }
                
                const m = L.marker([wp.lat, wp.lng], { icon: icon })
                    .addTo(mapCtx.map)
                    .bindPopup(`<strong>Stop ${i}: ${wp.name}</strong><br>Status: <span class="badge" style="background:${status === 'collected' ? '#10b981' : (status === 'skipped' ? '#f59e0b' : (status === 'blocked' ? '#ef4444' : '#64748b'))};">${status.toUpperCase()}</span>`);
                layers.push(m);
            }
        });

        return layers;
    }

    async function drawAllRoutes() {
        if (!mapCtx || isPlaybackMode) return;

        const trucksToDraw = truckMeta.filter(t => t.assigned_route_id);
        if (trucksToDraw.length === 0) return;

        let allPoints = [];

        for (const meta of trucksToDraw) {
            const res = await fetch(`/trucks/${meta.id}/route-path`).then(r => r.json());
            if (!res.success) continue;
            await drawRouteLayers(meta.id, res);
            allPoints = allPoints.concat(res.waypoints);
            if (res.current && res.current.lat) {
                allPoints.push(res.current);
            }
        }

        if (allPoints.length && !isPlaybackMode) {
            GreenRouteMap.fitBounds(mapCtx, allPoints);
        }
    }

    const activeAnimations = {};

    async function followRoute(truckId) {
        if (!mapCtx) return;

        const meta = truckMeta.find(t => t.id === truckId);
        if (!meta || !meta.assigned_route_id) {
            alert('Assign a route to this truck first.');
            return;
        }

        if (activeAnimations[truckId]) {
            clearInterval(activeAnimations[truckId]);
            delete activeAnimations[truckId];
        }

        const res = await fetch(`/trucks/${truckId}/route-path`).then(r => r.json());
        if (!res.success) {
            alert(res.message || 'No route assigned.');
            return;
        }

        await drawRouteLayers(truckId, res);

        if (!res.geometry || res.geometry.length < 2) {
            alert('Could not determine route coordinates.');
            return;
        }

        const pathCoords = res.geometry;
        let currentIdx = 0;
        let lastServerUpdate = 0;
        const updateIntervalMs = 2500;

        return new Promise((resolve) => {
            const timer = setInterval(() => {
                if (currentIdx >= pathCoords.length) {
                    clearInterval(timer);
                    delete activeAnimations[truckId];
                    
                    const dest = pathCoords[pathCoords.length - 1];
                    updateTruckLocationOnServer(truckId, dest[0], dest[1]);

                    alert(`Truck ${meta.plate} completed the route.`);
                    resolve();
                    return;
                }

                const currentPoint = pathCoords[currentIdx];
                const lat = currentPoint[0];
                const lng = currentPoint[1];

                if (truckMarkers[truckId]) {
                    truckMarkers[truckId].leaflet.setLatLng([lat, lng]);
                }

                const now = Date.now();
                if (now - lastServerUpdate >= updateIntervalMs) {
                    updateTruckLocationOnServer(truckId, lat, lng);
                    lastServerUpdate = now;
                }

                currentIdx++;
            }, 80);

            activeAnimations[truckId] = timer;
        });
    }

    function updateTruckLocationOnServer(truckId, lat, lng) {
        fetch(`/trucks/${truckId}/location`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ latitude: lat, longitude: lng })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshLocations();
            }
        })
        .catch(() => {});
    }

    /* ---------- Playback Audit Drawer Methods ---------- */

    function togglePlaybackDrawer() {
        const drawer = document.getElementById('playbackDrawer');
        drawer.classList.toggle('open');
    }

    function loadPlaybackHistory() {
        const truckId = document.getElementById('playbackTruckSelect').value;
        const date = document.getElementById('playbackDate').value;

        if (!truckId) {
            alert('Please select a truck.');
            return;
        }
        if (!date) {
            alert('Please select a date.');
            return;
        }

        fetch(`/trucks/${truckId}/playback-history?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.history.length > 0) {
                    // Activate Playback Mode
                    isPlaybackMode = true;
                    playbackHistory = data.history;
                    
                    // Stop regular updates and animations
                    Object.values(activeAnimations).forEach(clearInterval);
                    
                    // Clear live markers/polylines from map
                    Object.values(truckMarkers).forEach(m => mapCtx.markerLayer.removeLayer(m.leaflet));
                    Object.keys(routeLayers).forEach(clearTruckRoute);

                    // Show player controls
                    document.getElementById('playbackControls').classList.remove('d-none');
                    
                    // Setup scrubber
                    const scrubber = document.getElementById('playbackRange');
                    scrubber.disabled = false;
                    scrubber.max = playbackHistory.length - 1;
                    scrubber.value = 0;

                    // Draw historical breadcrumb path
                    drawPlaybackBreadcrumbs();
                    
                    // Initialize first index
                    updatePlaybackIndex(0);
                } else {
                    alert('No location history records found for this truck on the selected date.');
                    stopPlayback();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to load playback data.');
            });
    }

    function drawPlaybackBreadcrumbs() {
        if (!mapCtx) return;

        if (playbackPolyline) mapCtx.map.removeLayer(playbackPolyline);

        const latlngs = playbackHistory.map(h => [parseFloat(h.latitude), parseFloat(h.longitude)]);
        
        // Dashed custom polyline
        playbackPolyline = L.polyline(latlngs, {
            color: '#2563eb',
            weight: 4,
            dashArray: '8, 12',
            opacity: 0.85
        }).addTo(mapCtx.map);

        mapCtx.map.fitBounds(playbackPolyline.getBounds(), { padding: [50, 50] });

        // Add Playback Marker
        if (playbackMarker) mapCtx.map.removeLayer(playbackMarker);

        const playbackIcon = L.divIcon({
            className: 'custom-playback-marker',
            html: `<div style="background:#2563eb;width:30px;height:30px;border-radius:50%;border:3px solid white;box-shadow:0 3px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-play-circle-fill" style="font-size:14px;"></i></div>`,
            iconSize: [30, 30], iconAnchor: [15, 15]
        });

        playbackMarker = L.marker(latlngs[0], { icon: playbackIcon }).addTo(mapCtx.map);
    }

    function updatePlaybackIndex(idx) {
        playbackIndex = idx;
        const record = playbackHistory[idx];
        if (!record) return;

        const scrubber = document.getElementById('playbackRange');
        scrubber.value = idx;

        // Position marker
        const lat = parseFloat(record.latitude);
        const lng = parseFloat(record.longitude);
        if (playbackMarker) {
            playbackMarker.setLatLng([lat, lng]);
        }

        // Format recorded_at timestamp
        const timeStr = new Date(record.recorded_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('playbackTime').textContent = timeStr;
    }

    function togglePlayback() {
        const btn = document.getElementById('btnPlayPause');
        
        if (playbackInterval) {
            // Pause
            clearInterval(playbackInterval);
            playbackInterval = null;
            btn.innerHTML = '<i class="bi bi-play-fill"></i> Play';
        } else {
            // Play
            btn.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
            
            // If at the end, reset to start
            if (playbackIndex >= playbackHistory.length - 1) {
                updatePlaybackIndex(0);
            }

            const intervalMs = Math.round(1000 / playbackSpeed);

            playbackInterval = setInterval(() => {
                if (playbackIndex < playbackHistory.length - 1) {
                    updatePlaybackIndex(playbackIndex + 1);
                } else {
                    // Reached the end
                    clearInterval(playbackInterval);
                    playbackInterval = null;
                    btn.innerHTML = '<i class="bi bi-play-fill"></i> Play';
                }
            }, intervalMs);
        }
    }

    function setPlaybackSpeed(speed, btn) {
        playbackSpeed = speed;

        // Update button active states
        const group = document.getElementById('speedGroup');
        Array.from(group.querySelectorAll('button')).forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Restart interval with new speed if playing
        if (playbackInterval) {
            togglePlayback(); // Pause
            togglePlayback(); // Restart with new interval time
        }
    }

    function stopPlayback() {
        // Clear interval
        if (playbackInterval) {
            clearInterval(playbackInterval);
            playbackInterval = null;
        }

        const btn = document.getElementById('btnPlayPause');
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Play';

        // Clear playback layers
        if (playbackPolyline) {
            mapCtx.map.removeLayer(playbackPolyline);
            playbackPolyline = null;
        }
        if (playbackMarker) {
            mapCtx.map.removeLayer(playbackMarker);
            playbackMarker = null;
        }

        // Hide player controls
        document.getElementById('playbackControls').classList.add('d-none');
        isPlaybackMode = false;
        playbackHistory = [];
        playbackIndex = 0;

        // Restore normal tracking
        loadTruckLocations();
        drawAllRoutes();
    }

    /* ---------- Collection History Drawer ---------- */

    let historyLoaded = false;

    function toggleHistoryDrawer() {
        const drawer = document.getElementById('historyDrawer');
        drawer.classList.toggle('open');
        if (drawer.classList.contains('open')) {
            loadCollectionRuns(); // refresh each time it's opened
        }
    }

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, s => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[s]));
    }

    function loadCollectionRuns() {
        const list = document.getElementById('historyList');
        list.innerHTML = '<div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm" role="status"></div><div class="small mt-2">Loading…</div></div>';

        fetch('/trucks/collection-runs')
            .then(r => r.json())
            .then(data => renderCollectionRuns(data.runs || []))
            .catch(() => {
                list.innerHTML = '<div class="text-center text-danger py-4 small">Failed to load history.</div>';
            });
    }

    function renderCollectionRuns(runs) {
        const list = document.getElementById('historyList');

        if (!runs.length) {
            list.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>No completed runs yet.</div>';
            return;
        }

        const statusBadge = {
            collected: '<span class="badge" style="background:#dcfce7;color:#166534;">Collected</span>',
            skipped: '<span class="badge" style="background:#fef3c7;color:#92400e;">Skipped</span>',
            blocked: '<span class="badge" style="background:#fee2e2;color:#991b1b;">Blocked</span>',
        };

        list.innerHTML = runs.map((run, idx) => {
            const when = run.completed_at ? new Date(run.completed_at).toLocaleString() : '—';
            const abandoned = run.status === 'abandoned'
                ? '<span class="badge bg-secondary ms-1">Abandoned</span>' : '';

            const stopsHtml = (run.stops || []).map(s => `
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <span class="small">${escapeHtml(s.client_name || 'Client')}</span>
                    <span class="d-flex align-items-center gap-2">
                        ${s.prorated_weight_kg != null ? `<span class="small text-muted">~${Number(s.prorated_weight_kg).toFixed(1)} kg</span>` : ''}
                        ${statusBadge[s.status] || ''}
                    </span>
                </div>`).join('') || '<div class="small text-muted py-2">No stop records.</div>';

            return `
                <div class="mb-3" style="border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;">
                    <div class="p-3" style="background:#f8fafc;cursor:pointer;" onclick="document.getElementById('run-stops-${idx}').classList.toggle('d-none')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">${escapeHtml(run.route_name || 'Route')}${abandoned}</div>
                                <div class="small text-muted"><i class="bi bi-truck me-1"></i>${escapeHtml(run.plate || '')} &middot; ${when}</div>
                            </div>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <span class="badge" style="background:#dcfce7;color:#166534;">${run.collected} collected</span>
                            <span class="badge" style="background:#fef3c7;color:#92400e;">${run.skipped} skipped</span>
                            <span class="badge" style="background:#fee2e2;color:#991b1b;">${run.blocked} blocked</span>
                            ${run.net_weight_kg != null
                                ? `<span class="badge" style="background:#e0f2fe;color:#075985;"><i class="bi bi-clipboard-data me-1"></i>${Number(run.net_weight_kg).toFixed(1)} kg waste</span>`
                                : '<span class="badge" style="background:#f1f5f9;color:#64748b;">Not weighed</span>'}
                        </div>
                    </div>
                    <div id="run-stops-${idx}" class="d-none px-3 pb-2">${stopsHtml}</div>
                </div>`;
        }).join('');
    }
</script>
@endsection
