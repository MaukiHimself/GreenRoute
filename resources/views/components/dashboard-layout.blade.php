<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GreenRoute') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --afia-teal: #0d9488;
            --afia-cyan: #0891b2;
            --afia-red: #dc2626;
            --afia-gray: #6b7280;
            --afia-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--afia-light);
        }

        .main-content {
            background-color: var(--afia-light);
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-left: 4px solid var(--afia-teal);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--afia-teal) 0%, var(--afia-cyan) 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.3);
        }

        .badge {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--afia-red);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--afia-teal) 0%, var(--afia-cyan) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            color: var(--afia-gray);
        }

        .breadcrumb-item.active {
            color: var(--afia-teal);
            font-weight: 500;
        }

    </style>
</head>
<body class="has-portal-sidebar">
    <x-portal-sidebar portal="client" />

    <div class="portal-main">
        <div class="top-navbar px-lg-5 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ $portalHomeUrl ?? route('client.dashboard') }}" class="text-decoration-none">
                        <x-afia-orbit-logo class="h-10" />
                    </a>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            {{ $breadcrumb ?? '' }}
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications -->
                    <div class="position-relative">
                        <button class="btn btn-link text-muted p-2" type="button">
                            <i class="bi bi-bell fs-5"></i>
                            @if(isset($notificationCount))
                                <span class="notification-badge">{{ $notificationCount }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
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

        <!-- Page Content -->
        <div class="container py-4">
            {{ $slot }}
        </div>

        <x-footer />
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
