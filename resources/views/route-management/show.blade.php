<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contractorRoute->route_name }} - Route Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, {{ $contractorRoute->color }}, {{ $contractorRoute->color }}dd);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .client-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid {{ $contractorRoute->color }};
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .client-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transform: translateX(4px);
        }
        
        .badge-custom {
            background: {{ $contractorRoute->color }};
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="bi bi-signpost-split me-2"></i>{{ $contractorRoute->route_name }}
                    </h1>
                    @if($contractorRoute->description)
                        <p class="mb-0 opacity-90">{{ $contractorRoute->description }}</p>
                    @endif
                    <div class="mt-3">
                        <span class="badge-custom">
                            {{ $contractorRoute->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('route-management.edit', $contractorRoute) }}" class="btn btn-light">
                        <i class="bi bi-pencil me-2"></i>Edit Route
                    </a>
                    <a href="{{ route('route-management.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->count() }}</h3>
                            <p class="text-muted mb-0">Total Clients</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->where('category', 'residential')->count() }}</h3>
                            <p class="text-muted mb-0">Residential</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-building text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $clients->where('category', 'commercial')->count() }}</h3>
                            <p class="text-muted mb-0">Commercial</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients List -->
        <div class="info-card">
            <h4 class="mb-4">
                <i class="bi bi-people me-2"></i>Clients on This Route
            </h4>
            
            @forelse($clients as $client)
                <div class="client-card">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-1">{{ $client->name }}</h5>
                            <div class="text-muted small">
                                <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                            </div>
                            @if($client->email)
                                <div class="text-muted small">
                                    <i class="bi bi-envelope me-1"></i>{{ $client->email }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="small">
                                <i class="bi bi-geo-alt text-primary me-1"></i>
                                <strong>{{ $client->address }}</strong>
                            </div>
                            <div class="small text-muted">
                                {{ $client->city }}, {{ $client->state }} {{ $client->zip_code }}
                            </div>
                            @if($client->latitude && $client->longitude)
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-pin-map-fill me-1"></i>
                                    GPS: {{ number_format($client->latitude, 6) }}, {{ number_format($client->longitude, 6) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-{{ $client->category == 'residential' ? 'success' : 'warning' }}">
                                {{ ucfirst($client->category) }}
                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            @if($client->email)
                                <a href="mailto:{{ $client->email }}" class="btn btn-sm btn-outline-primary me-1" title="Email">
                                    <i class="bi bi-envelope"></i>
                                </a>
                            @endif
                            <a href="tel:{{ $client->phone }}" class="btn btn-sm btn-outline-success me-1" title="Call">
                                <i class="bi bi-telephone"></i>
                            </a>
                            @if($client->latitude && $client->longitude)
                                <a href="https://www.openstreetmap.org/?mlat={{ $client->latitude }}&mlon={{ $client->longitude }}#map=16/{{ $client->latitude }}/{{ $client->longitude }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info" title="View on Map">
                                    <i class="bi bi-map"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">No clients assigned to this route yet</p>
                    <a href="{{ route('route-management.edit', $contractorRoute) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Clients
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
