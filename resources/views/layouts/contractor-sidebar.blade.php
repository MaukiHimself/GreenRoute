<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Contractor Dashboard' }} | AFIA ORBIT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --light-teal: #e6f2f2;
            --light-red: #f9eaea;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            width: 250px;
            z-index: 100;
        }
        
        .sidebar .brand {
            background-color: white;
            color: var(--primary-teal);
            padding: 20px 15px;
            font-weight: 700;
            font-size: 1.3rem;
            text-align: center;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--light-teal);
            color: var(--primary-teal);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--primary-teal);
            color: white;
            border-left: 4px solid var(--primary-red);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        
        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        
        /* Header Styling */
        .header {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .breadcrumb {
            margin-bottom: 0;
        }
        
        .breadcrumb-item.active {
            color: var(--primary-teal);
            font-weight: 600;
        }
        
        .user-badge {
            background-color: var(--primary-teal);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .btn-back {
            background: var(--light-teal);
            color: var(--primary-teal);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: var(--primary-teal);
            color: white;
        }
        
        @yield('styles')
    </style>
    @stack('head-scripts')
</head>
<body>
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-2">
            <div class="sidebar">
                <div class="brand">
                    <i class="bi bi-recycle"></i> AFIA ORBIT
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard.contractor') ? 'active' : '' }}" href="{{ route('dashboard.contractor') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('contractor.clients.*') ? 'active' : '' }}" href="{{ route('contractor.clients.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </a>
                    <a class="nav-link" href="/billing">
                        <i class="bi bi-credit-card"></i>
                        <span>Billing & Payments</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Collection Schedules</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('disposal.*') ? 'active' : '' }}" href="{{ route('disposal.index') }}">
                        <i class="bi bi-trash"></i>
                        <span>Disposal Schedules</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('sms.*') ? 'active' : '' }}" href="{{ route('sms.inbox') }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Chats</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('routes.*') ? 'active' : '' }}" href="{{ route('routes.index') }}">
                        <i class="bi bi-geo-alt"></i>
                        <span>Route Optimization</span>
                    </a>
                    <a class="nav-link" href="/gps">
                        <i class="bi bi-pin-map"></i>
                        <span>GPS Tracker</span>
                    </a>
                    <a class="nav-link" href="/reports">
                        <i class="bi bi-graph-up"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-10 main-content">
            <!-- Header -->
            <div class="header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        @if(isset($backUrl))
                            <a href="{{ $backUrl }}" class="btn-back">
                                <i class="bi bi-arrow-left"></i>Back
                            </a>
                        @endif
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Waste Contractor</li>
                                <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="user-badge">
                            <i class="bi bi-bell me-1"></i>Notifications: 2
                        </span>
                        <div class="dropdown">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; cursor: pointer;" data-bs-toggle="dropdown">
                                <i class="bi bi-person-fill text-white"></i>
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
            </div>

            <!-- Content -->
            <div class="content-wrapper">
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
                
                {{ $slot }}
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
