<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Verification - AFIA ORBIT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--white);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .logo-section {
            padding: 1.5rem;
            border-bottom: 2px solid var(--primary-teal);
            text-align: center;
            background: var(--white);
        }
        
        .logo-section img {
            max-height: 60px;
            width: 90%;
            object-fit: contain;
        }
        
        .menu-section {
            padding: 1rem 0;
        }
        
        .menu-header {
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--primary-teal);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            background: #e6f2f2;
            color: var(--primary-teal);
        }
        
        .menu-item.active {
            background: var(--primary-teal);
            color: var(--white);
            border-left: 4px solid var(--primary-red);
        }
        
        .menu-item i {
            margin-right: 10px;
        }
        
        .menu-item .bi-chevron-right {
            margin-right: 0;
            margin-left: auto;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
        }
        
        /* Header */
        .header {
            background: var(--white);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breadcrumb {
            margin: 0;
            background: none;
            padding: 0;
            font-size: 0.9rem;
        }
        
        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--primary-teal);
            font-weight: 600;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-teal);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Content Area */
        .content-area {
            padding: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        /* Contractor Cards */
        .contractor-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-teal);
        }
        
        .contractor-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        
        .contractor-info h4 {
            margin: 0 0 0.5rem;
            color: #1e293b;
        }
        
        .contractor-meta {
            font-size: 0.9rem;
            color: #666;
        }
        
        .contractor-meta i {
            color: var(--primary-teal);
            margin-right: 0.25rem;
        }
        
        .contractor-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            font-weight: 600;
            color: #1e293b;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-approve {
            background: #10b981;
            color: var(--white);
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-approve:hover {
            background: #059669;
        }
        
        .btn-reject {
            background: var(--primary-red);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-reject:hover {
            background: #7c0505;
        }
        
        .btn-view {
            background: var(--primary-teal);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-view:hover {
            background: #044a4a;
        }
        
        .empty-state {
            background: var(--white);
            border-radius: 12px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #9ca3af;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-teal);
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <img src="/your-logo2.png" alt="AFIA ORBIT Logo">
            </div>
            
            <div class="menu-section">
                <div class="menu-header">MENU</div>
                <a href="{{ route('dashboard.admin') }}" class="menu-item">
                    <span><i class="bi bi-speedometer2"></i>Administrator Dashboard</span>
                </a>
                <a href="{{ route('admin.verification') }}" class="menu-item active">
                    <span><i class="bi bi-check-circle"></i>Verification</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.clients') }}" class="menu-item">
                    <span><i class="bi bi-people"></i>Clients Information</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.billing') }}" class="menu-item">
                    <span><i class="bi bi-credit-card"></i>Billing & Payments</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.schedules') }}" class="menu-item">
                    <span><i class="bi bi-calendar3"></i>Schedules</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item">
                    <span><i class="bi bi-person-gear"></i>Users</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Administrator</a></li>
                        <li class="breadcrumb-item active">Verification</li>
                    </ol>
                </nav>
                <div class="header-right">
                    <div class="dropdown">
                        <div class="user-profile" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <h1 class="page-title">Contractor Verification & Management</h1>
                <p class="page-description">Review and manage contractor registrations</p>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card" style="border-left-color: #fbbf24;">
                            <div class="stats-number">{{ $stats['pending'] }}</div>
                            <div class="stats-label">Pending Approval</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="border-left-color: #10b981;">
                            <div class="stats-number">{{ $stats['approved'] }}</div>
                            <div class="stats-label">Approved</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="border-left-color: var(--primary-red);">
                            <div class="stats-number">{{ $stats['rejected'] }}</div>
                            <div class="stats-label">Rejected</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="border-left-color: var(--primary-teal);">
                            <div class="stats-number">{{ $stats['total'] }}</div>
                            <div class="stats-label">Total Contractors</div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Tabs for different statuses -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                            <i class="bi bi-hourglass-split me-2"></i>Pending ({{ $stats['pending'] }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                            <i class="bi bi-check-circle me-2"></i>Approved ({{ $stats['approved'] }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                            <i class="bi bi-x-circle me-2"></i>Rejected ({{ $stats['rejected'] }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Pending Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @if($pendingContractors->count() > 0)
                            @foreach($pendingContractors as $contractor)
                                <div class="contractor-card">
                                    <div class="contractor-header">
                                        <div class="contractor-info">
                                            <h4>{{ $contractor->name }}</h4>
                                            <div class="contractor-meta">
                                                <i class="bi bi-envelope"></i>{{ $contractor->email }}
                                                <span class="ms-3"><i class="bi bi-calendar"></i>Registered: {{ $contractor->created_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <span class="status-badge status-pending">Pending Review</span>
                                    </div>

                                    @if($contractor->contractor)
                                        <div class="contractor-details">
                                            <div class="detail-item">
                                                <span class="detail-label">Company Name</span>
                                                <span class="detail-value">{{ $contractor->contractor->company_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Phone</span>
                                                <span class="detail-value">{{ $contractor->contractor->phone ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">License Number</span>
                                                <span class="detail-value">{{ $contractor->contractor->license_number ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Service Locations</span>
                                                <span class="detail-value">{{ $contractor->contractor->site_locations ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('admin.contractors.approve', $contractor->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-approve" onclick="return confirm('Approve this contractor? They will be able to access their dashboard immediately.')">
                                                <i class="bi bi-check-circle me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.contractors.reject', $contractor->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-reject" onclick="return confirm('Reject this contractor? They will not be able to login.')">
                                                <i class="bi bi-x-circle me-1"></i>Reject
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.contractors.show', $contractor->id) }}" class="btn-view">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="bi bi-check-circle"></i>
                                <h3>All Caught Up!</h3>
                                <p>No contractors pending verification at this time.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Approved Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        @if($approvedContractors->count() > 0)
                            @foreach($approvedContractors as $contractor)
                                <div class="contractor-card">
                                    <div class="contractor-header">
                                        <div class="contractor-info">
                                            <h4>{{ $contractor->name }}</h4>
                                            <div class="contractor-meta">
                                                <i class="bi bi-envelope"></i>{{ $contractor->email }}
                                                <span class="ms-3"><i class="bi bi-calendar-check"></i>Approved: {{ $contractor->updated_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <span class="status-badge status-approved">Approved</span>
                                    </div>

                                    @if($contractor->contractor)
                                        <div class="contractor-details">
                                            <div class="detail-item">
                                                <span class="detail-label">Company Name</span>
                                                <span class="detail-value">{{ $contractor->contractor->company_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Phone</span>
                                                <span class="detail-value">{{ $contractor->contractor->phone ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Registration Number</span>
                                                <span class="detail-value">{{ $contractor->contractor->registration_number ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('admin.contractors.toggle', $contractor->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-reject" onclick="return confirm('Suspend this contractor? They will not be able to login.')">
                                                <i class="bi bi-pause-circle me-1"></i>Suspend
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.contractors.show', $contractor->id) }}" class="btn-view">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h3>No Approved Contractors</h3>
                                <p>No contractors have been approved yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Rejected Tab -->
                    <div class="tab-pane fade" id="rejected" role="tabpanel">
                        @if($rejectedContractors->count() > 0)
                            @foreach($rejectedContractors as $contractor)
                                <div class="contractor-card">
                                    <div class="contractor-header">
                                        <div class="contractor-info">
                                            <h4>{{ $contractor->name }}</h4>
                                            <div class="contractor-meta">
                                                <i class="bi bi-envelope"></i>{{ $contractor->email }}
                                                <span class="ms-3"><i class="bi bi-calendar-x"></i>Rejected: {{ $contractor->updated_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <span class="status-badge status-rejected">Rejected</span>
                                    </div>

                                    @if($contractor->contractor)
                                        <div class="contractor-details">
                                            <div class="detail-item">
                                                <span class="detail-label">Company Name</span>
                                                <span class="detail-value">{{ $contractor->contractor->company_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Phone</span>
                                                <span class="detail-value">{{ $contractor->contractor->phone ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="action-buttons">
                                        <a href="{{ route('admin.contractors.show', $contractor->id) }}" class="btn-view">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h3>No Rejected Contractors</h3>
                                <p>No contractors have been rejected.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
