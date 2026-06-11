@props([
    'portal' => 'admin',
    'tabbed' => false,
    'homeUrl' => null,
])

@php
    use App\Support\Portal;

    $tabbed = filter_var($tabbed, FILTER_VALIDATE_BOOLEAN);
    $portal = $portal ?? Portal::forUser();

    $homeUrl = $homeUrl ?? match ($portal) {
        'admin' => route('dashboard.admin'),
        'contractor' => route('dashboard.contractor'),
        'client' => route('client.dashboard'),
        default => url('/'),
    };
@endphp

<link rel="stylesheet" href="{{ asset('css/portal-sidebar.css') }}">

<aside id="portal-sidebar" class="portal-sidebar" data-portal="{{ $portal }}" aria-label="Main navigation">
    <div class="portal-sidebar__header">
        <button type="button" class="portal-sidebar__toggle" id="portal-sidebar-toggle" aria-label="Toggle sidebar" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ $homeUrl }}" class="portal-sidebar__brand">
            <x-greenroute-logo size="sidebar-expanded" class="portal-sidebar__logo portal-sidebar__logo--full" />
            <x-greenroute-logo size="sidebar" class="portal-sidebar__logo portal-sidebar__logo--icon" />
        </a>
        <!-- Mobile close button -->
        <button type="button" class="portal-sidebar__close-mobile btn btn-link text-white p-2 ms-auto d-lg-none" id="portal-sidebar-close" aria-label="Close sidebar">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    <nav class="portal-sidebar__nav">
        @include('components.sidebars.' . $portal . '-nav', ['tabbed' => $tabbed ?? false])
    </nav>

    <div class="portal-sidebar__footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="portal-sidebar__link" data-tooltip="Logout">
                <i class="bi bi-box-arrow-right"></i>
                <span class="portal-sidebar__label">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile overlay -->
<div class="portal-sidebar-overlay" id="portal-sidebar-overlay"></div>

<script src="{{ asset('js/portal-sidebar.js') }}" defer></script>
