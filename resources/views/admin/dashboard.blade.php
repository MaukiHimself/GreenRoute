<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - AFIA ORBIT</title>
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

        .main-content {
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

        .breadcrumb-item {
            color: #666;
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

        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge i {
            font-size: 1.5rem;
            color: var(--primary-teal);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-red);
            color: var(--white);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-teal);
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .stat-card.contractors {
            border-left-color: var(--primary-teal);
        }

        .stat-card.clients {
            border-left-color: #10b981;
        }

        .stat-card.routes {
            border-left-color: #f59e0b;
        }

        .stat-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.2;
            float: right;
        }

        /* Tasks Section */
        .tasks-section {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-teal);
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }

        .task-item:hover {
            background: #f8f9fa;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .task-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e6f2f2;
            color: var(--primary-teal);
        }

        .task-details h5 {
            margin: 0;
            font-size: 1rem;
            color: #1e293b;
        }

        .task-details p {
            margin: 0;
            font-size: 0.85rem;
            color: #666;
        }

        .task-action {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view {
            background: var(--primary-teal);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-view:hover {
            background: #044a4a;
        }

        .badge-count {
            background: var(--primary-red);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="has-portal-sidebar">
    <x-portal-sidebar portal="admin" />

    <div class="admin-layout">
        <div class="main-content portal-main">
            <!-- Header -->
            <div class="header">
                <div class="d-flex align-items-center gap-3">
                    <x-portal-mobile-toggle />
                    <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2" style="border-color: #e0e0e0; background: #f8f9fa;">
                        <i class="bi bi-house-door-fill text-teal" style="color: var(--primary-teal);"></i> <span style="color: #333;">Home</span>
                    </a>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Administrator</a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Administrator - {{ auth()->user()->name }}</li>
                    </ol>
                </nav>
                <div class="header-right">
                    <div class="notification-badge">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-count">{{ $pendingVerifications ?? 0 }}</span>
                    </div>
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
                                <form method="POST" action="{{ route('admin.logout') }}">
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
                <h2 class="mb-4">System Parameters</h2>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card contractors">
                        <div class="stat-icon"><i class="bi bi-building"></i></div>
                        <div class="stat-title">Contractors</div>
                        <div class="stat-value">{{ $contractorsCount ?? 0 }}</div>
                    </div>
                    <div class="stat-card clients">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div class="stat-title">Clients</div>
                        <div class="stat-value">{{ $clientsCount ?? 0 }}</div>
                    </div>
                    <div class="stat-card routes">
                        <div class="stat-icon"><i class="bi bi-signpost-split"></i></div>
                        <div class="stat-title">Active Routes</div>
                        <div class="stat-value">{{ $activeRoutesCount ?? 0 }}</div>
                    </div>
                </div>

                <!-- Pending Tasks -->
                <div class="tasks-section">
                    <h3 class="section-title">Pending Tasks</h3>

                    @if(isset($pendingTasks) && count($pendingTasks) > 0)
                        @foreach($pendingTasks as $task)
                            <div class="task-item">
                                <div class="task-info">
                                    <div class="task-icon">
                                        <i class="bi bi-{{ $task['icon'] ?? 'exclamation-circle' }}"></i>
                                    </div>
                                    <div class="task-details">
                                        <h5>{{ $task['title'] }}</h5>
                                        <p>{{ $task['description'] }}</p>
                                    </div>
                                </div>
                                <div class="task-action">
                                    <span class="badge-count">{{ $task['count'] }}</span>
                                    <a href="{{ $task['link'] }}" class="btn-view">View</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="bi bi-check-circle"></i>
                            <p>No pending tasks. All caught up!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
