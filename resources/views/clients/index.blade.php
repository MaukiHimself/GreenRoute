@extends('layouts.contractor-sidebar')

@section('title', 'Clients Management')

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
        
        /* Success Alert */
        .alert-success {
            background: rgba(5, 92, 92, 0.1);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        
        /* Search and Filter Section */
        .search-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .section-subtitle {
            color: var(--text-muted);
            margin: 0;
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        .input-group-text {
            background: var(--light-bg);
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #065f46;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-outline-danger {
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-outline-danger:hover {
            background: var(--secondary-color);
            color: white;
        }
        
        /* Table Section */
        .table-section {
            background: var(--white-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .table-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        .sort-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .sort-btn {
            background: transparent;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .sort-btn.active {
            background: var(--primary-color);
            color: white;
        }
        
        .sort-btn:not(.active):hover {
            background: rgba(5, 92, 92, 0.1);
        }
        
        /* Table Styling */
        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: var(--white-color);
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .client-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(5, 92, 92, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .client-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .client-join-date {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .client-contact {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .location-badge {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: var(--primary-color);
            color: white;
        }
        
        .status-inactive {
            background: var(--text-muted);
            color: white;
        }

        .status-pending {
            background: #f59e0b;
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--text-muted);
            font-size: 2rem;
        }
        
        .empty-title {
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        
        .empty-description {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
        
        /* Pagination */
        .pagination-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-top: 2px solid var(--light-bg);
        }
        
        .pagination-info {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .pagination .page-link {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .pagination .page-link:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1.5rem;
            }
            
            .search-section .row > div {
                margin-bottom: 1rem;
            }
            
            .search-section .row > div:last-child {
                margin-bottom: 0;
            }
        }
        
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .search-section, .table-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .sort-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
            
            .pagination-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">Client Database</h1>
    </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: right;"></button>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="search-section">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4">
                    <h3 class="section-title">Client Database</h3>
                    <p class="section-subtitle">All clients linked to your contractor account</p>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search by name, email or city">
                    </div>
                </div>
                <div class="col-lg-2">
                    <select class="form-select">
                        <option selected>All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-lg-2 text-lg-end">
                    @php $pendingCount = \App\Models\Client::where('contractor_id', auth()->id())->where('status','pending')->where('self_registered',true)->count(); @endphp
                    @if($pendingCount > 0)
                        <a href="{{ route('contractor.clients.pending') }}" class="btn btn-warning btn-sm mb-2 d-block">
                            <i class="bi bi-person-check me-1"></i>{{ $pendingCount }} Pending Approval{{ $pendingCount > 1 ? 's' : '' }}
                        </a>
                    @endif
                    <a href="{{ route('contractor.clients.map') }}" class="btn btn-outline-primary btn-sm mb-2 d-block">
                        <i class="bi bi-geo-alt me-1"></i> View Map
                    </a>
                    <a href="{{ route('contractor.clients.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Add Client
                    </a>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="table-section">
            {{-- Pending self-registrations banner --}}
            @php $pendingBanner = \App\Models\Client::where('contractor_id', auth()->id())->where('status','pending')->where('self_registered',true)->count(); @endphp
            @if($pendingBanner > 0)
                <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3" style="border-radius:10px; border:2px solid #f59e0b;">
                    <div>
                        <i class="bi bi-person-exclamation me-2 fs-5"></i>
                        <strong>{{ $pendingBanner }} client{{ $pendingBanner > 1 ? 's' : '' }} waiting for your approval.</strong>
                        They self-registered and cannot log in until you approve them below.
                    </div>
                    <a href="{{ route('contractor.clients.pending') }}" class="btn btn-warning btn-sm ms-3 text-nowrap">
                        <i class="bi bi-person-check me-1"></i>Review All
                    </a>
                </div>
            @endif
            <div class="table-header">
                <h4 class="table-title">Clients</h4>
                <div class="sort-buttons">
                    <button type="button" class="sort-btn active">Name</button>
                    <button type="button" class="sort-btn">City</button>
                    <button type="button" class="sort-btn">Created</button>
                </div>
            </div>
            
            @if($clients->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;"></th>
                                <th>Client</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City / State</th>
                                <th>Status</th>
                                <th class="text-end" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr style="{{ $client->status === 'pending' ? 'background:#fffbeb;' : '' }}">
                                    <td>
                                        <div class="client-avatar">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-name">{{ $client->name }}</div>
                                        <div class="client-join-date">
                                            Joined {{ optional($client->created_at)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-contact">
                                            <i class="bi bi-envelope"></i>{{ $client->email }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-contact">
                                            <i class="bi bi-telephone"></i>{{ $client->phone }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="location-badge">{{ $client->city }}, {{ $client->state }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $client->status === 'active' ? 'status-active' : ($client->status === 'pending' ? 'status-pending' : 'status-inactive') }}">
                                            @if($client->status === 'pending') <i class="bi bi-hourglass-split me-1"></i> @endif
                                            {{ ucfirst($client->status) }}
                                        </span>
                                        @if($client->self_registered && $client->status === 'pending')
                                            <div class="small text-muted mt-1"><i class="bi bi-person-up"></i> Self-registered</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            @if($client->status === 'pending' && $client->self_registered)
                                                <form action="{{ route('contractor.clients.approve', $client) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve & activate"
                                                            onclick="return confirm('Approve {{ addslashes($client->name) }}? They will receive login credentials by email.')">
                                                        <i class="bi bi-check-lg me-1"></i>Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('contractor.clients.reject', $client) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject"
                                                            onclick="return confirm('Reject {{ addslashes($client->name) }}?')">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('contractor.clients.show', $client) }}" class="btn-outline-primary btn-sm" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('contractor.clients.edit', $client) }}" class="btn-outline-primary btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('contractor.clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this client?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-outline-danger btn-sm" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="empty-title">No clients found</h5>
                    <p class="empty-description">Start by adding your first client.</p>
                    <a href="{{ route('contractor.clients.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Add Your First Client
                    </a>
                </div>
            @endif
            
            @if($clients->count() > 0)
                <div class="pagination-section">
                    <div class="pagination-info">
                        Showing {{ $clients->firstItem() ?? 1 }}–{{ $clients->lastItem() ?? $clients->count() }} of {{ $clients->total() ?? $clients->count() }}
                    </div>
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Add confirmation for delete actions
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[method="POST"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this client?')) {
                        e.preventDefault();
                    }
                });
            });

            // Sort button functionality
            const sortButtons = document.querySelectorAll('.sort-btn');
            sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sortButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection

@push('scripts')
@endpush