<div class="portal-sidebar__section-title">Menu</div>

<a href="{{ route('dashboard.admin') }}"
   class="portal-sidebar__link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}"
   data-tooltip="Dashboard">
    <i class="bi bi-speedometer2"></i>
    <span class="portal-sidebar__label">Dashboard</span>
</a>

<a href="{{ route('admin.verification') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.verification') ? 'active' : '' }}"
   data-tooltip="Verification">
    <i class="bi bi-check-circle"></i>
    <span class="portal-sidebar__label">Verification</span>
</a>

<a href="{{ route('admin.clients') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.clients') || request()->routeIs('admin.clients.create') || request()->routeIs('admin.clients.edit') ? 'active' : '' }}"
   data-tooltip="Clients">
    <i class="bi bi-people"></i>
    <span class="portal-sidebar__label">Clients</span>
</a>

@php($unassignedCount = \App\Models\Client::whereNull('contractor_id')->where('status', 'pending')->count())
<a href="{{ route('admin.clients.unassigned') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.clients.unassigned') ? 'active' : '' }}"
   data-tooltip="Unassigned Clients">
    <i class="bi bi-person-exclamation"></i>
    <span class="portal-sidebar__label">Unassigned</span>
    @if($unassignedCount > 0)
        <span class="badge rounded-pill bg-danger ms-2">{{ $unassignedCount }}</span>
    @endif
</a>

<div class="portal-sidebar__group {{ request()->routeIs('admin.billing*') ? 'is-open' : '' }}">
    <button type="button"
            class="portal-sidebar__parent {{ request()->routeIs('admin.billing*') ? 'active' : '' }}"
            data-tooltip="Billing"
            aria-expanded="{{ request()->routeIs('admin.billing*') ? 'true' : 'false' }}">
        <i class="bi bi-credit-card"></i>
        <span class="portal-sidebar__label">Billing</span>
        <i class="bi bi-chevron-down portal-sidebar__chevron"></i>
    </button>
    <div class="portal-sidebar__submenu">
        <a href="{{ route('admin.billing') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('admin.billing') && !request()->routeIs('admin.billing.rates*') ? 'active' : '' }}"
           data-tooltip="Payments">
            <i class="bi bi-wallet2"></i>
            <span class="portal-sidebar__label">Payments</span>
        </a>
        <a href="{{ route('admin.billing.rates') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('admin.billing.rates*') ? 'active' : '' }}"
           data-tooltip="Billing rates">
            <i class="bi bi-currency-dollar"></i>
            <span class="portal-sidebar__label">Billing rates</span>
        </a>
        <a href="{{ route('admin.billing.rate-changes') }}"
           class="portal-sidebar__link portal-sidebar__sublink {{ request()->routeIs('admin.billing.rate-changes') ? 'active' : '' }}"
           data-tooltip="Billing changes">
            <i class="bi bi-activity"></i>
            <span class="portal-sidebar__label">Billing changes</span>
        </a>
    </div>
</div>

<a href="{{ route('admin.schedules') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}"
   data-tooltip="Schedules">
    <i class="bi bi-calendar3"></i>
    <span class="portal-sidebar__label">Schedules</span>
</a>

<a href="{{ route('admin.users') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
   data-tooltip="Users">
    <i class="bi bi-person-gear"></i>
    <span class="portal-sidebar__label">Users</span>
</a>

@php($openFeedbackCount = \App\Models\SystemFeedback::where('status', 'open')->count())
<a href="{{ route('admin.feedback') }}"
   class="portal-sidebar__link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}"
   data-tooltip="System Feedback">
    <i class="bi bi-life-preserver"></i>
    <span class="portal-sidebar__label">System Feedback</span>
    @if($openFeedbackCount > 0)
        <span class="badge rounded-pill bg-danger ms-2">{{ $openFeedbackCount }}</span>
    @endif
</a>
