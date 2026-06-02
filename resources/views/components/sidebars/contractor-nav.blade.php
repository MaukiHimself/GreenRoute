@php
    $tabbed = $tabbed ?? false;
@endphp

@if($tabbed)
    <a class="portal-sidebar__link active" href="#" data-tab="dashboard" data-tooltip="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span class="portal-sidebar__label">Dashboard</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="clients" data-tooltip="Clients">
        <i class="bi bi-people"></i>
        <span class="portal-sidebar__label">Clients</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="billing" data-tooltip="Billing">
        <i class="bi bi-credit-card"></i>
        <span class="portal-sidebar__label">Billing</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="collection" data-tooltip="Collection">
        <i class="bi bi-calendar3"></i>
        <span class="portal-sidebar__label">Collection</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="disposal" data-tooltip="Disposal">
        <i class="bi bi-trash"></i>
        <span class="portal-sidebar__label">Disposal</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="chats" data-tooltip="Chats">
        <i class="bi bi-chat-dots"></i>
        <span class="portal-sidebar__label">Chats</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="route-management" data-tooltip="Routes">
        <i class="bi bi-signpost-split"></i>
        <span class="portal-sidebar__label">Routes</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="route-optimization" data-tooltip="Optimize">
        <i class="bi bi-geo-alt"></i>
        <span class="portal-sidebar__label">Optimize</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="gps" data-tooltip="GPS">
        <i class="bi bi-pin-map"></i>
        <span class="portal-sidebar__label">GPS</span>
    </a>
    <a class="portal-sidebar__link" href="#" data-tab="reports" data-tooltip="Reports">
        <i class="bi bi-graph-up"></i>
        <span class="portal-sidebar__label">Reports</span>
    </a>
@else
    <div class="portal-sidebar__section-title">Main</div>

    <a href="{{ route('dashboard.contractor') }}"
       class="portal-sidebar__link {{ request()->routeIs('dashboard.contractor') ? 'active' : '' }}"
       data-tooltip="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span class="portal-sidebar__label">Dashboard</span>
    </a>

    <a href="{{ route('contractor.clients.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('contractor.clients.*') ? 'active' : '' }}"
       data-tooltip="Clients">
        <i class="bi bi-people"></i>
        <span class="portal-sidebar__label">Clients</span>
    </a>

    <a href="{{ route('schedules.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('schedules.*') ? 'active' : '' }}"
       data-tooltip="Schedules">
        <i class="bi bi-calendar3"></i>
        <span class="portal-sidebar__label">Schedules</span>
    </a>

    <a href="{{ route('routes.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('routes.*') ? 'active' : '' }}"
       data-tooltip="Routes">
        <i class="bi bi-geo-alt"></i>
        <span class="portal-sidebar__label">Routes</span>
    </a>

    <div class="portal-sidebar__section-title">Communication</div>

    <a href="{{ route('sms.inbox') }}"
       class="portal-sidebar__link {{ request()->routeIs('sms.*') ? 'active' : '' }}"
       data-tooltip="Chats">
        <i class="bi bi-chat-dots"></i>
        <span class="portal-sidebar__label">Chats</span>
    </a>

    <div class="portal-sidebar__section-title">Operations</div>

    <a href="{{ route('invoices.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('invoices.*') ? 'active' : '' }}"
       data-tooltip="Invoices">
        <i class="bi bi-file-earmark-text"></i>
        <span class="portal-sidebar__label">Invoices</span>
    </a>

    <a href="{{ route('billing.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('billing.*') ? 'active' : '' }}"
       data-tooltip="Billing">
        <i class="bi bi-credit-card"></i>
        <span class="portal-sidebar__label">Billing</span>
    </a>

    <a href="{{ route('disposal.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('disposal.*') ? 'active' : '' }}"
       data-tooltip="Disposal">
        <i class="bi bi-trash"></i>
        <span class="portal-sidebar__label">Disposal</span>
    </a>

    <a href="/gps"
       class="portal-sidebar__link {{ request()->is('gps*') ? 'active' : '' }}"
       data-tooltip="GPS">
        <i class="bi bi-pin-map"></i>
        <span class="portal-sidebar__label">GPS Tracker</span>
    </a>

    <a href="/reports"
       class="portal-sidebar__link {{ request()->is('reports*') ? 'active' : '' }}"
       data-tooltip="Reports">
        <i class="bi bi-graph-up"></i>
        <span class="portal-sidebar__label">Reports</span>
    </a>
@endif
