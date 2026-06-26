@extends('layouts.contractor-sidebar')

@section('title', 'Routes Management')

@section('styles')
<style>
    :root {
        --primary-teal: #047857;
        --primary-red: #c0392b;
    }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-teal), #059669);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .route-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border-left: 4px solid var(--primary-teal);
        }
        
        .route-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .route-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .route-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .route-color-indicator {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-block;
            margin-right: 12px;
        }
        
        .route-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        
        .btn-create {
            background: white;
            color: var(--primary-teal);
            border: 2px solid white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-create:hover {
            background: var(--primary-teal);
            color: white;
            border-color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
        
        .badge-active {
            background: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
        }
        
        .badge-inactive {
            background: #ef4444;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-signpost-split me-2"></i>Routes Management
                </h1>
                <p class="mb-0 opacity-90">Organize your clients into routes for efficient collection</p>
            </div>
            <a href="{{ route('route-management.create') }}" class="btn-create">
                <i class="bi bi-plus-circle me-2"></i>Create New Route
            </a>
        </div>
    </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Routes List -->
        @forelse($routes as $route)
            <div class="route-card" style="border-left-color: {{ $route->color }}">
                <div class="route-header">
                    <div class="d-flex align-items-center">
                        <div class="route-color-indicator" style="background-color: {{ $route->color }}"></div>
                        <div>
                            <h3 class="route-name">{{ $route->route_name }}</h3>
                            @if($route->description)
                                <p class="text-muted mb-0">{{ $route->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="badge-{{ $route->is_active ? 'active' : 'inactive' }} me-2">
                            {{ $route->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div class="route-stats">
                    <div class="stat-item">
                        <i class="bi bi-people-fill"></i>
                        <span><strong>{{ $route->clients_count }}</strong> Clients</span>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('route-management.show', $route) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>View Details
                    </a>
                    <a href="{{ route('route-management.edit', $route) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('route-management.destroy', $route) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this route? Clients will be unassigned.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-signpost-split"></i>
                <h3>No Routes Created Yet</h3>
                <p class="text-muted">Create your first route to organize clients for efficient collection</p>
                <a href="{{ route('route-management.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Create First Route
                </a>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
@endpush
