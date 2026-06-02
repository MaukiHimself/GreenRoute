@props([
    'portal' => 'admin',
    'tabbed' => false,
    'homeUrl' => null,
])

@php
    $tabbed = filter_var($tabbed, FILTER_VALIDATE_BOOLEAN);
@endphp

@php
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
            <img src="{{ asset('result.png') }}" alt="{{ config('app.name', 'GreenRoute') }}">
        </a>
    </div>

    <nav class="portal-sidebar__nav">
        @include('components.sidebars.' . $portal . '-nav', ['tabbed' => $tabbed ?? false])
    </nav>

    <div class="portal-sidebar__footer">
        @if($portal === 'admin')
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="portal-sidebar__link" data-tooltip="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="portal-sidebar__label">Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('profile.edit') }}" class="portal-sidebar__link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-tooltip="Profile">
                <i class="bi bi-person"></i>
                <span class="portal-sidebar__label">Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="portal-sidebar__link" data-tooltip="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="portal-sidebar__label">Logout</span>
                </button>
            </form>
        @endif
    </div>
</aside>

<script src="{{ asset('js/portal-sidebar.js') }}" defer></script>
