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

    <a href="{{ route('contractor.equipment.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('contractor.equipment.*') ? 'active' : '' }}"
       data-tooltip="Equipment">
        <i class="bi bi-tools"></i>
        <span class="portal-sidebar__label">Equipment</span>
    </a>

    <a href="{{ route('contractor.pricing.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('contractor.pricing.*') ? 'active' : '' }}"
       data-tooltip="Pricing">
        <i class="bi bi-tag"></i>
        <span class="portal-sidebar__label">Pricing</span>
    </a>

    <div class="portal-sidebar__section-title">Communication</div>

    <a href="{{ route('sms.inbox') }}"
       class="portal-sidebar__link {{ request()->routeIs('sms.*') ? 'active' : '' }}"
       data-tooltip="Chats">
        <i class="bi bi-chat-dots"></i>
        <span class="portal-sidebar__label">Chats</span>
    </a>

    <a href="{{ route('contractor.feedback.index') }}"
       class="portal-sidebar__link {{ request()->routeIs('contractor.feedback.*') ? 'active' : '' }}"
       data-tooltip="Feedback">
        <i class="bi bi-chat-square-text"></i>
        <span class="portal-sidebar__label">Feedback</span>
    </a>
@endif
