@extends('layouts.contractor-sidebar')

@section('title', 'Dashboard')

@section('styles')
<style>
    :root {
        --gr-green: #047857;
        --gr-green-dark: #065f46;
        --gr-green-light: #d1fae5;
        --gr-red: #c0392b;
        --gr-amber: #d97706;
        --gr-blue: #2563eb;
        --gr-border: #e2e8f0;
        --gr-surface: #ffffff;
        --gr-bg: #f1f5f9;
        --gr-text: #1e293b;
        --gr-muted: #64748b;
    }

    /* welcome hero */
    .welcome-hero {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        padding: 1.9rem 2rem;
        background: linear-gradient(120deg, #065f46 0%, #047857 45%, #0d9488 100%);
        box-shadow: 0 12px 30px -8px rgba(4,120,87,.45);
    }
    .welcome-hero-content { position: relative; z-index: 2; }
    .welcome-eyebrow {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .78rem; font-weight: 600; letter-spacing: .03em;
        color: #d1fae5; background: rgba(255,255,255,.12);
        padding: .28rem .8rem; border-radius: 20px; margin-bottom: .7rem;
    }
    .welcome-title { color: #fff; font-weight: 800; font-size: 1.75rem; margin: 0; }
    .welcome-sub   { color: rgba(255,255,255,.82); margin: .35rem 0 0; font-size: .95rem; }
    .welcome-hero-glow {
        position: absolute; top: -60%; right: -5%; width: 380px; height: 380px;
        background: radial-gradient(circle, rgba(255,255,255,.18) 0%, transparent 68%);
        z-index: 1; pointer-events: none;
    }

    .dash-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .kpi-card {
        position: relative;
        background: var(--gr-surface);
        border-radius: 14px;
        padding: 1.5rem;
        border: 1px solid var(--gr-border);
        display: flex;
        align-items: center;
        gap: 1rem;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
    }
    .kpi-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 5px;
        background: var(--accent, var(--gr-green));
    }
    .kpi-card:hover { box-shadow: 0 12px 26px -6px rgba(15,23,42,.16); transform: translateY(-3px); }

    .kpi-card.accent-green  { --accent: #10b981; background: linear-gradient(135deg, #ffffff 60%, #ecfdf5 100%); }
    .kpi-card.accent-blue   { --accent: #2563eb; background: linear-gradient(135deg, #ffffff 60%, #eff6ff 100%); }
    .kpi-card.accent-amber  { --accent: #f59e0b; background: linear-gradient(135deg, #ffffff 60%, #fffbeb 100%); }
    .kpi-card.accent-violet { --accent: #7c3aed; background: linear-gradient(135deg, #ffffff 60%, #f5f3ff 100%); }

    .kpi-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
        color: #fff;
    }
    .kpi-icon.green  { background: linear-gradient(135deg, #10b981, #059669); }
    .kpi-icon.amber  { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
    .kpi-icon.red    { background: linear-gradient(135deg, #f87171, #ef4444); }
    .kpi-icon.blue   { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .kpi-icon.violet { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

    .kpi-value { font-size: 1.9rem; font-weight: 800; color: var(--gr-text); line-height: 1; }
    .kpi-label { font-size: .82rem; color: var(--gr-muted); font-weight: 500; margin-top: .25rem; }

    /* alert kpi */
    .kpi-card.alert-card {
        border-color: #fbbf24;
        background: #fffbeb;
    }

    /* two-column layout */
    .main-row { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; }

    .panel {
        background: var(--gr-surface);
        border-radius: 14px;
        border: 1px solid var(--gr-border);
        overflow: hidden;
    }
    .panel-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.1rem 1.4rem;
        border-bottom: 1px solid var(--gr-border);
    }
    .panel-title {
        font-size: 1rem; font-weight: 700; color: var(--gr-text);
        display: flex; align-items: center; gap: .5rem;
    }
    .panel-title i { color: var(--gr-green); }
    .panel-body { padding: 1.25rem 1.4rem; }

    /* tables */
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table th {
        font-size: .78rem; font-weight: 600; color: var(--gr-muted);
        text-transform: uppercase; letter-spacing: .04em;
        padding: .5rem .75rem; border-bottom: 2px solid var(--gr-border);
        background: #f8fafc;
    }
    .mini-table td {
        padding: .75rem; font-size: .875rem; color: var(--gr-text);
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .mini-table tr:last-child td { border-bottom: none; }
    .mini-table tr:hover td { background: #f8fafc; }

    /* badges */
    .badge-gr {
        display: inline-block; padding: .25rem .7rem;
        border-radius: 20px; font-size: .75rem; font-weight: 600;
    }
    .badge-gr.paid     { background: #d1fae5; color: #065f46; }
    .badge-gr.pending  { background: #fef3c7; color: #92400e; }
    .badge-gr.overdue  { background: #fee2e2; color: #991b1b; }
    .badge-gr.sent     { background: #dbeafe; color: #1d4ed8; }
    .badge-gr.draft    { background: #f1f5f9; color: #475569; }
    .badge-gr.cancelled{ background: #f1f5f9; color: #64748b; }

    /* quick actions */
    .qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .qa-btn {
        display: flex; flex-direction: column; align-items: center; gap: .4rem;
        padding: 1.1rem .75rem; border-radius: 12px;
        border: 1.5px solid var(--gr-border);
        background: #f8fafc; text-decoration: none; color: var(--gr-text);
        font-size: .82rem; font-weight: 600; text-align: center;
        transition: all .2s;
    }
    .qa-btn:hover { border-color: var(--qa, var(--gr-green)); background: #fff; color: var(--gr-text); transform: translateY(-2px); box-shadow: 0 8px 18px -6px rgba(15,23,42,.15); }
    .qa-btn i { font-size: 1.5rem; color: var(--qa, var(--gr-green)); }
    .qa-btn.qa-approve { --qa: #ef4444; }
    .qa-btn.qa-invoice { --qa: #2563eb; }
    .qa-btn.qa-schedule{ --qa: #f59e0b; }
    .qa-btn.qa-map     { --qa: #0d9488; }
    .qa-btn.qa-clients { --qa: #10b981; }
    .qa-btn.qa-routes  { --qa: #7c3aed; }
    .qa-btn.qa-reports { --qa: #db2777; }
    .qa-btn.qa-equip   { --qa: #ea580c; }
    .qa-btn.qa-sms     { --qa: #0891b2; }

    /* payment submissions */
    .pay-item {
        display: flex; align-items: center; gap: .85rem;
        padding: .8rem 0; border-bottom: 1px solid #f1f5f9;
    }
    .pay-item:last-child { border-bottom: none; }
    .pay-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: var(--gr-green-light); color: var(--gr-green);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .9rem; flex-shrink: 0;
    }
    .pay-name { font-weight: 600; font-size: .875rem; }
    .pay-sub  { font-size: .78rem; color: var(--gr-muted); }
    .pay-amount { margin-left: auto; font-weight: 700; font-size: .9rem; color: var(--gr-green); white-space: nowrap; }

    /* schedules */
    .sched-item {
        display: flex; align-items: flex-start; gap: .85rem;
        padding: .8rem 0; border-bottom: 1px solid #f1f5f9;
    }
    .sched-item:last-child { border-bottom: none; }
    .sched-date {
        background: var(--gr-green); color: white;
        border-radius: 10px; padding: .3rem .6rem;
        font-size: .75rem; font-weight: 700; white-space: nowrap;
        flex-shrink: 0;
    }

    /* empty state */
    .empty-state { text-align: center; padding: 2rem; color: var(--gr-muted); }
    .empty-state i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .4; }

    /* responsive */
    @media (max-width: 1200px) {
        .dash-grid { grid-template-columns: repeat(2, 1fr); }
        .main-row  { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .dash-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- Welcome banner --}}
<div class="welcome-hero mb-4">
    <div class="welcome-hero-content">
        <span class="welcome-eyebrow"><i class="bi bi-sun-fill"></i> {{ now()->format('l, F j, Y') }}</span>
        <h2 class="welcome-title">Welcome back, {{ Auth::user()->name }}</h2>
        <p class="welcome-sub">Here's what's happening across your collections today.</p>
    </div>
    <div class="welcome-hero-glow"></div>
</div>

{{-- KPI cards --}}
<div class="dash-grid">
    <div class="kpi-card accent-green">
        <div class="kpi-icon green"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="kpi-value">{{ $stats['active_clients'] }}</div>
            <div class="kpi-label">Active Clients</div>
        </div>
    </div>

    <div class="kpi-card accent-blue">
        <div class="kpi-icon blue"><i class="bi bi-receipt-cutoff"></i></div>
        <div>
            <div class="kpi-value">{{ $stats['total_invoices'] }}</div>
            <div class="kpi-label">Total Invoices</div>
        </div>
    </div>

    <div class="kpi-card accent-amber">
        <div class="kpi-icon amber"><i class="bi bi-wallet2"></i></div>
        <div>
            <div class="kpi-value">TZS {{ number_format($stats['pending_payments'], 0) }}</div>
            <div class="kpi-label">Pending Payments</div>
        </div>
    </div>

    <div class="kpi-card accent-violet">
        <div class="kpi-icon violet"><i class="bi bi-calendar3-week"></i></div>
        <div>
            <div class="kpi-value">{{ $stats['active_routes'] }}</div>
            <div class="kpi-label">Upcoming Collections</div>
        </div>
    </div>

    @if($stats['pending_clients'] > 0)
    <div class="kpi-card alert-card" style="grid-column: span 2;">
        <div class="kpi-icon amber"><i class="bi bi-person-exclamation"></i></div>
        <div class="flex-grow-1">
            <div class="kpi-value">{{ $stats['pending_clients'] }}</div>
            <div class="kpi-label">Clients waiting for approval</div>
        </div>
        <a href="{{ route('contractor.clients.pending') }}" class="btn btn-sm" style="background:#f59e0b;color:white;border-radius:8px;white-space:nowrap;">
            Review <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    @endif

    @if($stats['pending_approvals'] > 0)
    <div class="kpi-card alert-card" style="grid-column: span 2;">
        <div class="kpi-icon red"><i class="bi bi-bell-fill"></i></div>
        <div class="flex-grow-1">
            <div class="kpi-value">{{ $stats['pending_approvals'] }}</div>
            <div class="kpi-label">Payment submissions awaiting approval</div>
        </div>
        <a href="{{ route('contractor.pending-payments') }}" class="btn btn-sm" style="background:#ef4444;color:white;border-radius:8px;white-space:nowrap;">
            Approve <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    @endif
</div>

{{-- Main two-column layout --}}
<div class="main-row">

    {{-- Left column --}}
    <div class="d-flex flex-column gap-3">

        {{-- Recent Invoices --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-receipt"></i> Recent Invoices</span>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-size:.8rem;">View All</a>
            </div>
            <div class="panel-body p-0">
                @if($recentInvoices->isEmpty())
                    <div class="empty-state"><i class="bi bi-receipt"></i>No invoices yet</div>
                @else
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $inv)
                        <tr>
                            <td><span class="fw-semibold">{{ $inv->invoice_number ?? 'INV-'.$inv->id }}</span></td>
                            <td>{{ $inv->client->name ?? '—' }}</td>
                            <td class="fw-semibold">TZS {{ number_format($inv->total_amount, 0) }}</td>
                            <td><span class="badge-gr {{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                            <td><a href="{{ route('invoices.show', $inv->id) }}" class="text-muted"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- Upcoming Collections --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-truck"></i> Upcoming Collections</span>
                <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-size:.8rem;">View All</a>
            </div>
            <div class="panel-body">
                @if($upcomingSchedules->isEmpty())
                    <div class="empty-state"><i class="bi bi-calendar-x"></i>No upcoming collections</div>
                @else
                    @foreach($upcomingSchedules as $sched)
                    <div class="sched-item">
                        <div class="sched-date">
                            {{ \Carbon\Carbon::parse($sched->pickup_date)->format('M d') }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem;">{{ $sched->pickup_location }}</div>
                            <div style="font-size:.78rem;color:#64748b;">
                                {{ $sched->client->name ?? '—' }}
                                @if($sched->pickup_time)
                                 · {{ $sched->pickup_time }}
                                @endif
                            </div>
                        </div>
                        @if($sched->displayed_price)
                        <div class="ms-auto fw-bold" style="font-size:.85rem;color:#047857;">
                            TZS {{ number_format($sched->displayed_price, 0) }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Pending Payment Submissions --}}
        @if($pendingPayments->isNotEmpty())
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-clock-history"></i> Pending Payment Submissions</span>
                <a href="{{ route('contractor.pending-payments') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;font-size:.8rem;">View All</a>
            </div>
            <div class="panel-body">
                @foreach($pendingPayments as $pay)
                <div class="pay-item">
                    <div class="pay-avatar">{{ strtoupper(substr($pay->client->name ?? 'U', 0, 2)) }}</div>
                    <div>
                        <div class="pay-name">{{ $pay->client->name ?? 'Unknown' }}</div>
                        <div class="pay-sub">INV {{ $pay->invoice->invoice_number ?? '#'.$pay->invoice_id }} · {{ $pay->submitted_at ? $pay->submitted_at->diffForHumans() : '' }}</div>
                    </div>
                    <div class="pay-amount">TZS {{ number_format($pay->amount_submitted, 0) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Right sidebar --}}
    <div class="d-flex flex-column gap-3">

        {{-- Quick Actions --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</span>
            </div>
            <div class="panel-body">
                <div class="qa-grid">
                    <a href="{{ route('contractor.pending-payments') }}" class="qa-btn qa-approve" style="position:relative;">
                        <i class="bi bi-cash-coin"></i>
                        Payment Approvals
                        @if($stats['pending_approvals'] > 0)
                        <span class="badge rounded-pill bg-danger" style="position:absolute;top:.5rem;right:.5rem;font-size:.7rem;">{{ $stats['pending_approvals'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('billing.create') }}" class="qa-btn qa-invoice">
                        <i class="bi bi-receipt-cutoff"></i>
                        Create Invoice
                    </a>
                    <a href="{{ route('schedules.create') }}" class="qa-btn qa-schedule">
                        <i class="bi bi-calendar-plus"></i>
                        Schedule Pickup
                    </a>
                    <a href="{{ route('contractor.clients.map') }}" class="qa-btn qa-map">
                        <i class="bi bi-geo-alt-fill"></i>
                        Client Map
                    </a>
                    <a href="{{ route('contractor.clients.index') }}" class="qa-btn qa-clients">
                        <i class="bi bi-people-fill"></i>
                        Clients
                    </a>
                    <a href="{{ route('route-management.index') }}" class="qa-btn qa-routes">
                        <i class="bi bi-signpost-split-fill"></i>
                        Routes
                    </a>
                    <a href="{{ route('reports.index') }}" class="qa-btn qa-reports">
                        <i class="bi bi-graph-up-arrow"></i>
                        Reports
                    </a>
                    <a href="{{ route('contractor.equipment.index') }}" class="qa-btn qa-equip">
                        <i class="bi bi-tools"></i>
                        Equipment
                    </a>
                    <a href="{{ route('contractor.sms.campaign') }}" class="qa-btn qa-sms">
                        <i class="bi bi-megaphone-fill"></i>
                        SMS Campaign
                    </a>
                </div>
            </div>
        </div>

        {{-- Business Snapshot --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title"><i class="bi bi-bar-chart-line-fill"></i> Business Snapshot</span>
            </div>
            <div class="panel-body">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                            <span class="text-muted">Total Clients</span>
                            <strong>{{ $stats['total_clients'] }}</strong>
                        </div>
                        <div class="progress" style="height:7px;">
                            @php $pct = $stats['total_clients'] > 0 ? min(100, round($stats['active_clients']/$stats['total_clients']*100)) : 0; @endphp
                            <div class="progress-bar bg-success" style="width:{{ $pct }}%;border-radius:10px;"></div>
                        </div>
                        <div style="font-size:.72rem;color:#64748b;margin-top:.25rem;">{{ $pct }}% active</div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                            <span class="text-muted">Jobs Completed</span>
                            <strong>{{ $stats['completed_jobs'] }}</strong>
                        </div>
                        <div class="progress" style="height:7px;">
                            @php $total = max(1, $stats['completed_jobs'] + $stats['active_routes']); $cpct = min(100, round($stats['completed_jobs']/$total*100)); @endphp
                            <div class="progress-bar" style="width:{{ $cpct }}%;border-radius:10px;background:#047857;"></div>
                        </div>
                        <div style="font-size:.72rem;color:#64748b;margin-top:.25rem;">{{ $cpct }}% completion rate</div>
                    </div>

                    <div class="mt-2 pt-2" style="border-top:1px solid #f1f5f9;">
                        <div class="d-flex justify-content-between" style="font-size:.85rem;margin-bottom:.5rem;">
                            <span class="text-muted">Pending Payments</span>
                            <strong class="text-warning">{{ $stats['pending_approvals'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:.85rem;">
                            <span class="text-muted">Pending Clients</span>
                            <strong class="text-warning">{{ $stats['pending_clients'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Map shortcut --}}
        <a href="{{ route('contractor.clients.map') }}" class="panel text-decoration-none" style="overflow:hidden;position:relative;min-height:130px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#047857,#065f46);">
            <div style="text-align:center;color:white;z-index:2;">
                <i class="bi bi-map-fill" style="font-size:2.2rem;display:block;margin-bottom:.5rem;"></i>
                <strong style="font-size:1rem;">View Client Map</strong>
                <div style="font-size:.8rem;opacity:.85;margin-top:.2rem;">{{ $stats['total_clients'] }} clients plotted</div>
            </div>
        </a>

    </div>
</div>

@endsection
