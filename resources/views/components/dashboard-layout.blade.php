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
            /* GreenRoute unified palette — teal chrome + green eco accent */
            --afia-teal: #047857;   /* primary (teal) */
            --afia-cyan: #0d9488;   /* teal-green gradient partner */
            --afia-green: #2e7d32;  /* eco / success accent */
            --afia-green-light: #4caf50;
            --afia-dark: #064e3b;   /* deep teal */
            --afia-red: #dc2626;
            --afia-gray: #6b7280;
            --afia-light: #f4f9f6;  /* app background */
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
            box-shadow: 0 8px 20px rgba(5, 92, 92, 0.3);
        }

        /* ===== GreenRoute brand overrides ===== */
        .btn-success {
            --bs-btn-bg: var(--afia-green);
            --bs-btn-border-color: var(--afia-green);
            --bs-btn-hover-bg: #1b5e20;
            --bs-btn-hover-border-color: #1b5e20;
            --bs-btn-active-bg: #1b5e20;
        }
        .btn-outline-primary {
            --bs-btn-color: var(--afia-teal);
            --bs-btn-border-color: var(--afia-teal);
            --bs-btn-hover-bg: var(--afia-teal);
            --bs-btn-hover-border-color: var(--afia-teal);
            --bs-btn-active-bg: var(--afia-teal);
        }
        .text-primary { color: var(--afia-teal) !important; }
        .bg-primary, .badge.bg-primary, .badge.text-bg-primary { background-color: var(--afia-teal) !important; }
        .border-primary { border-color: var(--afia-teal) !important; }
        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link { background-color: var(--afia-teal); }
        .nav-pills .nav-link { color: var(--afia-teal); }
        .page-link { color: var(--afia-teal); }
        .page-item.active .page-link {
            background-color: var(--afia-teal);
            border-color: var(--afia-teal);
        }
        a { color: var(--afia-teal); }
        a:hover { color: #064e3b; }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--afia-teal);
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.2);
        }
        .form-check-input:checked {
            background-color: var(--afia-teal);
            border-color: var(--afia-teal);
        }
        .table thead th {
            background-color: var(--afia-light);
            color: var(--afia-teal);
        }
        .alert-success {
            background-color: var(--afia-green-light);
            border-color: var(--afia-green);
            color: #fff;
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

    @if(Auth::user()->dark_mode)
    <style>
        body.dark-mode-active,
        body.dark-mode-active .bg-white,
        body.dark-mode-active .card,
        body.dark-mode-active .table-section,
        body.dark-mode-active .search-section,
        body.dark-mode-active .page-header,
        body.dark-mode-active .portal-sidebar {
            background-color: #1a1d23 !important;
            border-color: #2d3139 !important;
            color: #e9ecef !important;
        }
        body.dark-mode-active .text-dark,
        body.dark-mode-active .table-title,
        body.dark-mode-active h1, body.dark-mode-active h2, body.dark-mode-active h3, body.dark-mode-active h4, body.dark-mode-active h5 {
            color: #e9ecef !important;
        }
        body.dark-mode-active .text-muted {
            color: #adb5bd !important;
        }
        body.dark-mode-active .table {
            color: #e9ecef;
        }
        body.dark-mode-active .table thead th {
            background: #0d3d3d !important;
        }
        body.dark-mode-active .table tbody tr {
            border-bottom-color: #2d3139;
        }
        body.dark-mode-active .table tbody tr:hover {
            background-color: #252930 !important;
        }
        body.dark-mode-active .border-bottom,
        body.dark-mode-active .border-top {
            border-color: #2d3139 !important;
        }
        body.dark-mode-active .breadcrumb-item a {
            color: #8bb8b8;
        }
        body.dark-mode-active .dropdown-menu {
            background-color: #252930 !important;
            border-color: #2d3139 !important;
        }
        body.dark-mode-active .dropdown-item {
            color: #e9ecef !important;
        }
        body.dark-mode-active .dropdown-item:hover {
            background-color: #0d3d3d !important;
        }
        body.dark-mode-active .form-control,
        body.dark-mode-active .form-select {
            background-color: #252930;
            border-color: #2d3139;
            color: #e9ecef !important;
        }
        body.dark-mode-active .form-control:focus,
        body.dark-mode-active .form-select:focus {
            background-color: #2d3139;
            border-color: #047857;
            color: #e9ecef !important;
        }
        body.dark-mode-active .form-label {
            color: #e9ecef !important;
        }
        body.dark-mode-active .btn-outline-dark {
            border-color: #8bb8b8 !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .input-group-text {
            background-color: #252930 !important;
            border-color: #2d3139 !important;
            color: #adb5bd !important;
        }
        body.dark-mode-active .input-group-text i {
            color: #adb5bd !important;
        }
        body.dark-mode-active .form-text {
            color: #adb5bd !important;
        }
        body.dark-mode-active .location-badge {
            background: rgba(5, 92, 92, 0.2) !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .badge.bg-info {
            background-color: #0d3d3d !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .badge.bg-success {
            background-color: #155724 !important;
        }
        body.dark-mode-active .badge.bg-warning {
            background-color: #665200 !important;
            color: #fcd34d !important;
        }
        body.dark-mode-active .btn-outline-secondary {
            border-color: #8bb8b8 !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .btn-outline-secondary:hover {
            background-color: #252930 !important;
            color: #e9ecef !important;
        }
        body.dark-mode-active .btn-outline-primary {
            border-color: #047857 !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .btn-outline-primary:hover {
            background-color: #047857 !important;
            color: #fff !important;
        }
        body.dark-mode-active .btn-outline-danger {
            border-color: #dc3545 !important;
            color: #f87171 !important;
        }
        body.dark-mode-active .btn-outline-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        body.dark-mode-active .modal-content {
            background-color: #252930;
            border-color: #2d3139;
            color: #e9ecef;
        }
        body.dark-mode-active .modal-header,
        body.dark-mode-active .modal-footer {
            border-color: #2d3139;
        }
        body.dark-mode-active .close,
        body.dark-mode-active .btn-close {
            filter: invert(1);
        }
    </style>
    @endif
</head>
<body class="has-portal-sidebar{{ Auth::user()->dark_mode ? ' dark-mode-active' : '' }}">
    @php
        $layoutPortal = \App\Support\Portal::forUser();
    @endphp
    <x-portal-sidebar :portal="$layoutPortal" />

    <div class="portal-main">
        <div class="top-navbar px-lg-5 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <x-portal-mobile-toggle />

                    <a href="{{ $portalHomeUrl }}" class="text-decoration-none d-flex align-items-center">
                        <x-greenroute-logo size="lg" />
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
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                            @else
                                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ \App\Support\Portal::profileUrl() }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
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
