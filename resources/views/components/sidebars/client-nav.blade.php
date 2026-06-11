<div class="portal-sidebar__section-title">Portal</div>

<a href="{{ route('client.dashboard') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}"
   data-tooltip="Dashboard">
    <i class="bi bi-house"></i>
    <span class="portal-sidebar__label">Dashboard</span>
</a>

<a href="{{ route('client.schedules') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.schedules') ? 'active' : '' }}"
   data-tooltip="Schedules">
    <i class="bi bi-calendar3"></i>
    <span class="portal-sidebar__label">Schedules</span>
</a>

<a href="{{ route('client.invoices') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.invoices') ? 'active' : '' }}"
   data-tooltip="Invoices">
    <i class="bi bi-receipt"></i>
    <span class="portal-sidebar__label">Invoices</span>
</a>

<a href="{{ route('client.payments') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.payments*') ? 'active' : '' }}"
   data-tooltip="Payments">
    <i class="bi bi-wallet2"></i>
    <span class="portal-sidebar__label">Payments</span>
</a>

<a href="{{ route('client.chats') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.chats') ? 'active' : '' }}"
   data-tooltip="Chats">
    <i class="bi bi-chat-dots"></i>
    <span class="portal-sidebar__label">Chats</span>
</a>

<div class="portal-sidebar__group {{ request()->routeIs('client.request*', 'client.equipment', 'client.contractor*', 'client.feedback*') ? 'is-open' : '' }}">
    <button type="button"
            class="portal-sidebar__parent {{ request()->routeIs('client.request*', 'client.equipment', 'client.contractor*', 'client.feedback*') ? 'active' : '' }}"
            data-tooltip="Services"
            aria-expanded="{{ request()->routeIs('client.request*', 'client.equipment', 'client.contractor*', 'client.feedback*') ? 'true' : 'false' }}">
        <i class="bi bi-grid"></i>
        <span class="portal-sidebar__label">Services</span>
        <i class="bi bi-chevron-down portal-sidebar__chevron"></i>
    </button>
    <div class="portal-sidebar__submenu">
        <a href="{{ route('client.request.service') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('client.request*') ? 'active' : '' }}"
           data-tooltip="Request service">
            <i class="bi bi-plus-circle"></i>
            <span class="portal-sidebar__label">Request service</span>
        </a>
        <a href="{{ route('client.equipment') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('client.equipment') ? 'active' : '' }}"
           data-tooltip="Equipment">
            <i class="bi bi-box-seam"></i>
            <span class="portal-sidebar__label">Equipment</span>
        </a>
        <a href="{{ route('client.contractor.info') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('client.contractor*') ? 'active' : '' }}"
           data-tooltip="Contractor">
            <i class="bi bi-building"></i>
            <span class="portal-sidebar__label">Contractor</span>
        </a>
        <a href="{{ route('client.feedback') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('client.feedback*') ? 'active' : '' }}"
           data-tooltip="Feedback">
            <i class="bi bi-chat-square-text"></i>
            <span class="portal-sidebar__label">Feedback</span>
        </a>
    </div>
</div>

<a href="{{ route('client.support') }}"
   class="portal-sidebar__link {{ request()->routeIs('client.support*') ? 'active' : '' }}"
   data-tooltip="Support">
    <i class="bi bi-headset"></i>
    <span class="portal-sidebar__label">Support</span>
</a>
