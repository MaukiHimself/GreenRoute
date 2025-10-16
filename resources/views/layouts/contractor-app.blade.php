<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Contractor Dashboard' }} - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            overflow-x: hidden;
        }
        
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .app-sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid var(--border-color);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-teal), #077777);
        }
        
        .sidebar-logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        
        .sidebar-logo:hover {
            color: white;
            opacity: 0.9;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }
        
        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-weight: 500;
        }
        
        .nav-item:hover {
            background: var(--light-bg);
            color: var(--primary-teal);
            border-left-color: var(--primary-teal);
        }
        
        .nav-item.active {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-teal);
            border-left-color: var(--primary-teal);
        }
        
        .nav-item i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .app-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn-back {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-back:hover {
            background: white;
            border-color: var(--primary-teal);
            color: var(--primary-teal);
            transform: translateX(-2px);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--light-bg);
            border-radius: 8px;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .content-body {
            flex: 1;
            padding: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .app-sidebar.show {
                transform: translateX(0);
            }
            
            .app-content {
                margin-left: 0;
            }
            
            .content-header {
                padding: 1rem;
            }
            
            .content-body {
                padding: 1rem;
            }
        }
        
        /* Custom Styles Slot */
        @yield('styles')
    </style>
    
    @stack('head-scripts')
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard.contractor') }}" class="sidebar-logo">
                    <i class="bi bi-recycle me-2"></i>AFIA ORBIT
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('dashboard.contractor') }}" class="nav-item {{ request()->routeIs('dashboard.contractor') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>Dashboard
                </a>
                <a href="{{ route('contractor.clients.index') }}" class="nav-item {{ request()->routeIs('contractor.clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>Clients
                </a>
                <a href="{{ route('schedules.index') }}" class="nav-item {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>Schedules
                </a>
                <a href="{{ route('routes.index') }}" class="nav-item {{ request()->routeIs('routes.*') ? 'active' : '' }}">
                    <i class="bi bi-map"></i>Routes
                </a>
                
                <div class="nav-section-title">Communication</div>
                <a href="{{ route('sms.inbox') }}" class="nav-item {{ request()->routeIs('sms.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i>Chats
                </a>
                
                <div class="nav-section-title">Billing</div>
                <a href="{{ route('invoices.index') }}" class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>Invoices
                </a>
                <a href="{{ route('billing.index') }}" class="nav-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>Billing
                </a>
                
                <div class="nav-section-title">Operations</div>
                <a href="{{ route('disposal.index') }}" class="nav-item {{ request()->routeIs('disposal.*') ? 'active' : '' }}">
                    <i class="bi bi-trash"></i>Disposal
                </a>
                
                <div class="nav-section-title">Account</div>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i>Logout
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="app-content">
            <!-- Header -->
            <header class="content-header">
                <div class="header-left">
                    @if(isset($backUrl))
                        <a href="{{ $backUrl }}" class="btn-back">
                            <i class="bi bi-arrow-left"></i>Back
                        </a>
                    @else
                        <button onclick="window.history.back()" class="btn-back" style="background: none; cursor: pointer;">
                            <i class="bi bi-arrow-left"></i>Back
                        </button>
                    @endif
                    
                    <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="content-body">
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
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
