<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Contractor Dashboard' }} | GreenRoute</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #047857;
            --primary-green: #2e7d32;
            --primary-red: #c0392b;
            --light-teal: #e6f2f2;
            --light-green: #e8f5e9;
            --light-red: #f9eaea;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content {
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
            font-weight: 500;
        }

        /* ===== GreenRoute brand overrides (make teal/green actually visible) ===== */
        .btn-primary {
            --bs-btn-bg: var(--primary-teal);
            --bs-btn-border-color: var(--primary-teal);
            --bs-btn-hover-bg: #064e3b;
            --bs-btn-hover-border-color: #064e3b;
            --bs-btn-active-bg: #064e3b;
            --bs-btn-active-border-color: #064e3b;
            --bs-btn-disabled-bg: var(--primary-teal);
            --bs-btn-disabled-border-color: var(--primary-teal);
        }
        .btn-success {
            --bs-btn-bg: var(--primary-green);
            --bs-btn-border-color: var(--primary-green);
            --bs-btn-hover-bg: #1b5e20;
            --bs-btn-hover-border-color: #1b5e20;
            --bs-btn-active-bg: #1b5e20;
            --bs-btn-active-border-color: #1b5e20;
        }
        .btn-outline-primary {
            --bs-btn-color: var(--primary-teal);
            --bs-btn-border-color: var(--primary-teal);
            --bs-btn-hover-bg: var(--primary-teal);
            --bs-btn-hover-border-color: var(--primary-teal);
            --bs-btn-active-bg: var(--primary-teal);
            --bs-btn-active-border-color: var(--primary-teal);
        }
        .text-primary { color: var(--primary-teal) !important; }
        .bg-primary { background-color: var(--primary-teal) !important; }
        .border-primary { border-color: var(--primary-teal) !important; }
        .badge.bg-primary { background-color: var(--primary-teal) !important; }
        .badge.text-bg-primary { background-color: var(--primary-teal) !important; color: #fff !important; }
        a { color: var(--primary-teal); }
        a:hover { color: #064e3b; }
        .nav-link { color: var(--primary-teal); }
        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link { background-color: var(--primary-teal); }
        .page-link { color: var(--primary-teal); }
        .page-item.active .page-link {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.2);
        }
        .form-check-input:checked {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }
        .table thead th {
            background-color: var(--light-teal);
            color: var(--primary-teal);
        }
        .card-header {
            background-color: var(--light-teal);
            color: var(--primary-teal);
            font-weight: 600;
        }
        .alert-success {
            background-color: var(--light-green);
            border-color: var(--primary-green);
            color: #1b5e20;
        }
        .spinner-border.text-primary,
        .text-info { color: var(--primary-teal) !important; }

        @if(Auth::user()->dark_mode)
        body.dark-mode-active {
            background-color: #1a1d23;
        }
        body.dark-mode-active .content-wrapper,
        body.dark-mode-active .main-content {
            background-color: #1a1d23;
        }
        body.dark-mode-active .nav-sidebar {
            background-color: #111318 !important;
        }
        body.dark-mode-active .nav-menu > li > a {
            color: #adb5bd;
        }
        body.dark-mode-active .nav-menu > li > a:hover,
        body.dark-mode-active .nav-menu > li.active > a {
            background-color: #0d3d3d;
            color: #8bb8b8;
        }
        body.dark-mode-active .page-header {
            background-color: #252930 !important;
            border-color: #2d3139 !important;
            color: #e9ecef !important;
        }
        body.dark-mode-active .header-title {
            color: #e9ecef !important;
        }
        body.dark-mode-active .card {
            background-color: #252930;
            border-color: #2d3139;
            color: #e9ecef;
        }
        body.dark-mode-active .card-header {
            background-color: #1a1d23;
            border-color: #2d3139 !important;
            color: #e9ecef !important;
        }
        body.dark-mode-active .form-control,
        body.dark-mode-active .form-select {
            background-color: #252930;
            border-color: #2d3139;
            color: #e9ecef;
        }
        body.dark-mode-active .form-label {
            color: #e9ecef;
        }
        body.dark-mode-active .table {
            color: #e9ecef;
        }
        body.dark-mode-active .table thead th {
            background: #0d3d3d !important;
        }
        body.dark-mode-active .table tbody td {
            border-color: #2d3139;
        }
        body.dark-mode-active .dropdown-menu {
            background-color: #252930;
            border-color: #2d3139;
        }
        body.dark-mode-active .dropdown-item {
            color: #e9ecef;
        }
        body.dark-mode-active .dropdown-item:hover {
            background-color: #0d3d3d;
        }
        body.dark-mode-active .btn-outline-secondary {
            border-color: #8bb8b8 !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .breadcrumb a {
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .alert-success {
            background: rgba(5, 92, 92, 0.2);
            border-color: #047857;
            color: #8bb8b8;
        }
        body.dark-mode-active .input-group-text {
            background-color: #252930 !important;
            border-color: #2d3139 !important;
            color: #adb5bd !important;
        }
        body.dark-mode-active .btn-outline-primary {
            border-color: #047857 !important;
            color: #8bb8b8 !important;
        }
        body.dark-mode-active .text-muted {
            color: #adb5bd !important;
        }
        @endif

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
    </style>

    @yield('styles')
    @stack('head-scripts')
</head>
<body class="has-portal-sidebar" @if(Auth::user()->dark_mode) style="background-color: #1a1d23;" @endif>
    <x-portal-sidebar portal="contractor" :tabbed="true" />

    <div class="main-content portal-main">
            <!-- Header -->
            <div class="header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <x-portal-mobile-toggle />
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
                        <x-notification-bell />
                        <div class="dropdown">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" data-bs-toggle="dropdown">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; cursor: pointer;" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            @endif
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
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

                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
