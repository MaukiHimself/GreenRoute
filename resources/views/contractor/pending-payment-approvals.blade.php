@extends('layouts.app')

@section('title', 'Pending Payment Approvals')

@push('styles')
<style>
:root { --gr: #047857; --gr-dark: #064e3b; --gr-mid: #059669; --gr-light: #d1fae5; --gr-pale: #ecfdf5; }

/* tabs */
.ppa-tab { position:relative; padding:1rem 2rem; font-weight:700; font-size:.9rem; color:#6b7280;
           border-bottom:3px solid transparent; margin-bottom:-1px; transition:all .3s ease; border-radius:.75rem .75rem 0 0; }
.ppa-tab:hover { color:var(--gr); background:var(--gr-pale); transform:translateY(-2px); }
.ppa-tab.active { color:var(--gr); border-bottom-color:var(--gr); background:linear-gradient(180deg,var(--gr-pale) 0%,#fff 100%); }
.ppa-tab .badge { display:inline-flex; align-items:center; justify-content:center;
                  min-width:1.75rem; height:1.75rem; padding:0 .5rem; border-radius:999px;
                  font-size:.75rem; font-weight:800; margin-left:.6rem; box-shadow:0 2px 8px rgba(0,0,0,.1); }

/* card hover lift */
.pay-card { transition: all .35s cubic-bezier(0.4, 0, 0.2, 1); border:1px solid #e5e7eb; }
.pay-card:hover { box-shadow:0 25px 60px rgba(4,120,87,.15); transform:translateY(-5px); border-color:var(--gr-mid); }

/* status badges */
.badge-pending  { background:linear-gradient(135deg,#fef9c3 0%,#fef08a 100%); color:#854d0e; }
.badge-approved { background:linear-gradient(135deg,var(--gr-light) 0%,#a7f3d0 100%); color:var(--gr-dark); }
.badge-rejected { background:linear-gradient(135deg,#fee2e2 0%,#fecaca 100%); color:#991b1b; }

/* stat cards */
.stat-card { border-radius:1.25rem; padding:1.5rem 1.75rem; background:#fff;
             box-shadow:0 4px 20px rgba(0,0,0,.06); position:relative; overflow:hidden;
             transition:all .3s ease; }
.stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 35px rgba(0,0,0,.12); }
.stat-card::before { content:''; position:absolute; inset:0 auto 0 0; width:5px; border-radius:5px 0 0 5px; }
.stat-card.green::before  { background:linear-gradient(180deg,var(--gr) 0%,var(--gr-mid) 100%); }
.stat-card.amber::before  { background:linear-gradient(180deg,#d97706 0%,#f59e0b 100%); }
.stat-card.blue::before   { background:linear-gradient(180deg,#2563eb 0%,#3b82f6 100%); }
.stat-icon { width:2.75rem; height:2.75rem; border-radius:.875rem; display:flex; align-items:center;
             justify-content:center; font-size:1.25rem; transition:transform .25s; }
.stat-card:hover .stat-icon { transform:scale(1.1) rotate(5deg); }

/* search */
.search-bar { transition:all .3s ease; }
.search-bar:hover { box-shadow:0 6px 25px rgba(0,0,0,.08); }
.search-bar input:focus { outline:none; border-color:var(--gr); box-shadow:0 0 0 4px rgba(4,120,87,.12); background:#fff; }

/* approve/reject buttons */
.btn-approve { background:linear-gradient(135deg,var(--gr) 0%,var(--gr-mid) 100%); color:#fff; border-radius:1rem; font-weight:700;
               padding:1rem 2rem; transition:all .3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow:0 8px 25px rgba(4,120,87,.35); border:2px solid transparent; }
.btn-approve:hover { background:linear-gradient(135deg,var(--gr-dark) 0%,var(--gr) 100%); transform:translateY(-4px) scale(1.02); box-shadow:0 12px 40px rgba(4,120,87,.5); border-color:var(--gr-light); }
.btn-approve:active { transform:translateY(-2px) scale(1); }
.btn-approve:disabled { opacity:.6; cursor:not-allowed; transform:none; box-shadow:none; }
.btn-reject  { background:linear-gradient(135deg,#fee2e2 0%,#fecaca 100%); color:#991b1b; border-radius:1rem; font-weight:700;
               padding:1rem 2rem; transition:all .3s cubic-bezier(0.4, 0, 0.2, 1); border:2px solid #fecaca; }
.btn-reject:hover { background:linear-gradient(135deg,#fecaca 0%,#fca5a5 100%); transform:translateY(-4px) scale(1.02); box-shadow:0 8px 25px rgba(153,27,27,.25); border-color:#fca5a5; }
.btn-reject:active { transform:translateY(-2px) scale(1); }

/* modal backdrop */
.modal-backdrop { background:rgba(0,0,0,.5); backdrop-filter:blur(5px); }

/* progress bar */
.prog-track { height:8px; border-radius:4px; background:#e5e7eb; overflow:hidden; box-shadow:inset 0 2px 4px rgba(0,0,0,.08); }
.prog-fill   { height:100%; border-radius:4px; background:linear-gradient(90deg,var(--gr) 0%,var(--gr-mid) 100%); transition:width .5s cubic-bezier(0.4, 0, 0.2, 1); position:relative; }
.prog-fill::after { content:''; position:absolute; top:0; left:0; right:0; bottom:0; background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.4) 50%,transparent 100%); animation:shimmer 2s infinite; }

@keyframes shimmer { 0% { transform:translateX(-100%); } 100% { transform:translateX(100%); } }

/* receipt link */
.receipt-link { display:inline-flex; align-items:center; gap:.4rem; color:var(--gr);
                font-weight:600; font-size:.8rem; padding:.4rem .8rem; border-radius:.5rem;
                background:linear-gradient(135deg,var(--gr-pale) 0%,var(--gr-light) 100%); transition:all .2s; border:1px solid transparent; }
.receipt-link:hover { background:linear-gradient(135deg,var(--gr-light) 0%,#a7f3d0 100%); border-color:var(--gr-mid); transform:translateY(-2px); box-shadow:0 4px 12px rgba(4,120,87,.15); }

/* table */
.data-table { border-radius:1rem; overflow:hidden; }
.data-table th { background:linear-gradient(180deg,#f9fafb 0%,#f3f4f6 100%); font-size:.7rem; font-weight:700; text-transform:uppercase;
                 letter-spacing:.06em; color:#6b7280; padding:1rem 1.25rem; border-bottom:2px solid #e5e7eb; }
.data-table td { padding:1rem 1.25rem; font-size:.875rem; border-bottom:1px solid #f3f4f6; }
.data-table tr:last-child td { border-bottom:none; }
.data-table tr:hover td { background:linear-gradient(90deg,var(--gr-pale) 0%,#fff 100%); }

/* enhanced back button */
.btn-home { display:inline-flex; align-items:center; gap:.5rem; padding:.875rem 1.5rem;
            background:linear-gradient(135deg,#fff 0%,#f9fafb 100%);
            border:3px solid var(--gr); border-radius:1rem; font-weight:700;
            color:var(--gr); transition:all .3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow:0 4px 15px rgba(4,120,87,.2); }
.btn-home:hover { background:linear-gradient(135deg,var(--gr) 0%,var(--gr-mid) 100%); color:#fff; transform:translateX(-5px) scale(1.05);
                   box-shadow:0 10px 35px rgba(4,120,87,.4); }
.btn-home svg { transition:transform .3s; }
.btn-home:hover svg { transform:translateX(-5px); }

/* hero header animation */
.hero-gradient { animation:gradientShift 8s ease infinite; background-size:200% 200%; }
@keyframes gradientShift { 0%, 100% { background-position:0% 50%; } 50% { background-position:100% 50%; } }

/* stat card animations */
@keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
.stat-card { animation:fadeInUp 0.6s ease forwards; opacity:0; }
.stat-card:nth-child(1) { animation-delay:0.1s; }
.stat-card:nth-child(2) { animation-delay:0.2s; }
.stat-card:nth-child(3) { animation-delay:0.3s; }

/* empty state pulse */
.empty-icon { animation:pulse 3s ease-in-out infinite; }
@keyframes pulse { 0%, 100% { transform:scale(1); } 50% { transform:scale(1.08); } }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">

  {{-- ── BACK TO HOME BUTTON ─────────────────────────────────────────── --}}
  <div class="mb-6">
    <a href="{{ route('dashboard.contractor') }}" class="btn-home">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12l7-7m-7 7l7 7"/>
      </svg>
      Back to Home
    </a>
  </div>

  {{-- ── HERO HEADER ───────────────────────────────────────────────── --}}
  <div class="rounded-2xl shadow-xl overflow-hidden mb-8 hero-gradient"
       style="background:linear-gradient(135deg,#047857 0%,#059669 55%,#0d9488 100%);">
    <div class="p-8 sm:p-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
      <div>
        <div class="flex items-center gap-4 mb-3">
          <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">Payment Approvals</h1>
        </div>
        <p class="text-white/80 text-base font-medium">Verify client payment submissions and issue receipts instantly.</p>
      </div>
      <div class="flex gap-4">
        <div class="text-center bg-white rounded-2xl px-6 py-4 shadow-xl border-2 border-amber-400 transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:border-amber-500">
          <div class="text-4xl font-extrabold text-amber-600 leading-none">{{ $pendingCount }}</div>
          <p class="text-gray-600 text-xs mt-1.5 uppercase tracking-widest font-bold">Pending</p>
        </div>
        <div class="text-center bg-white rounded-2xl px-6 py-4 shadow-xl border-2 border-green-500 transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:border-green-600">
          <div class="text-4xl font-extrabold text-green-600 leading-none">{{ $approved->count() }}</div>
          <p class="text-gray-600 text-xs mt-1.5 uppercase tracking-widest font-bold">Approved</p>
        </div>
        <div class="text-center bg-white rounded-2xl px-6 py-4 shadow-xl border-2 border-red-400 transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:border-red-500">
          <div class="text-4xl font-extrabold text-red-600 leading-none">{{ $rejected->count() }}</div>
          <p class="text-gray-600 text-xs mt-1.5 uppercase tracking-widest font-bold">Rejected</p>
        </div>
      </div>
    </div>
  </div>


  @if ($submissions->isEmpty() && $approved->isEmpty() && $rejected->isEmpty())
  {{-- ── ALL-CLEAR EMPTY STATE ─────────────────────────────────────── --}}
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 empty-icon"
         style="background:var(--gr-light);">
      <svg class="w-10 h-10" style="color:var(--gr)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-2">All Caught Up!</h3>
    <p class="text-gray-500 mb-8 max-w-sm mx-auto text-sm">
      No payment submissions to review right now. New submissions from clients will appear here.
    </p>
    <a href="{{ route('dashboard.contractor') }}" class="btn-home inline-flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12l7-7m-7 7l7 7"/>
      </svg>
      Back to Home
    </a>
  </div>

  @else
  {{-- ── TABS ──────────────────────────────────────────────────────── --}}
  <div class="mb-8">
    {{-- pill-style tab strip --}}
    <div class="inline-flex gap-2 p-1.5 rounded-2xl shadow-inner w-full sm:w-auto"
         style="background:rgba(4,120,87,.08); border:1.5px solid rgba(4,120,87,.15);">

      {{-- PENDING --}}
      <button type="button" id="tab-btn-pending" data-tab="pending" onclick="switchTab('pending')"
              class="ppa-tab-btn group relative flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm
                     cursor-pointer select-none transition-all duration-200 active:scale-95
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1"
              style="background:linear-gradient(135deg,#047857 0%,#059669 100%);
                     color:#fff;
                     box-shadow:0 4px 14px rgba(4,120,87,.40);">
        <svg class="w-4 h-4 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Pending</span>
        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-black leading-none
                     bg-white/25 text-white min-w-[1.5rem]">
          {{ $submissions->count() }}
        </span>
      </button>

      {{-- APPROVED --}}
      <button type="button" id="tab-btn-approved" data-tab="approved" onclick="switchTab('approved')"
              class="ppa-tab-btn group relative flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm
                     cursor-pointer select-none transition-all duration-200 active:scale-95
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-green-400
                     text-gray-600 hover:text-gray-900 hover:bg-white hover:shadow-md"
              style="background:transparent;">
        <svg class="w-4 h-4 flex-shrink-0 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Approved</span>
        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-black leading-none
                     bg-emerald-100 text-emerald-700 min-w-[1.5rem]">
          {{ $approved->count() }}
        </span>
      </button>

      {{-- REJECTED --}}
      <button type="button" id="tab-btn-rejected" data-tab="rejected" onclick="switchTab('rejected')"
              class="ppa-tab-btn group relative flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm
                     cursor-pointer select-none transition-all duration-200 active:scale-95
                     focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400
                     text-gray-600 hover:text-gray-900 hover:bg-white hover:shadow-md"
              style="background:transparent;">
        <svg class="w-4 h-4 flex-shrink-0 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Rejected</span>
        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-black leading-none
                     bg-red-100 text-red-700 min-w-[1.5rem]">
          {{ $rejected->count() }}
        </span>
      </button>

    </div>{{-- /tab strip --}}
  </div>{{-- /mb-8 --}}


  {{-- ════════════════════════════════════════════════════════════════
       TAB: PENDING
  ════════════════════════════════════════════════════════════════ --}}
  <div id="tab-pending" class="tab-panel">
  @if ($submissions->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-500">
      <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 empty-icon" style="background:var(--gr-pale);">
        <i class="bi bi-inbox text-3xl" style="color:var(--gr)"></i>
      </div>
      <p class="font-medium text-gray-700">No pending submissions right now.</p>
    </div>
  @else

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="stat-card green">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Reviews</span>
          <div class="stat-icon" style="background:var(--gr-pale)">
            <i class="bi bi-hourglass-split" style="color:var(--gr)"></i>
          </div>
        </div>
        <div class="text-3xl font-extrabold" style="color:var(--gr)">{{ $pendingCount }}</div>
      </div>
      <div class="stat-card amber">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Amount</span>
          <div class="stat-icon bg-amber-50">
            <i class="bi bi-cash-stack text-amber-600"></i>
          </div>
        </div>
        <div class="text-2xl font-extrabold text-amber-700">
          TZS {{ number_format($submissions->sum('amount_submitted'), 0) }}
        </div>
      </div>
      <div class="stat-card blue">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Average</span>
          <div class="stat-icon bg-blue-50">
            <i class="bi bi-bar-chart-line text-blue-600"></i>
          </div>
        </div>
        <div class="text-2xl font-extrabold text-blue-700">
          TZS {{ number_format($submissions->avg('amount_submitted'), 0) }}
        </div>
      </div>
    </div>

    {{-- Search --}}
    <div class="search-bar bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-5 flex gap-3">
      <div class="flex-1 relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Search by client or payer name…"
               class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white transition"
               oninput="filterSubmissions()">
      </div>
      <button onclick="filterSubmissions()" class="btn-approve px-5 py-2 text-sm flex items-center gap-1.5">
        <i class="bi bi-search"></i> Search
      </button>
    </div>

    {{-- Submission cards --}}
    <div class="space-y-4" id="submissions-list">
    @foreach ($submissions as $submission)
      @php
        $invoice = $submission->invoice;
        $paid    = $invoice->amount_paid ?? 0;
        $total   = $invoice->total_amount ?? 0;
        $balance = $total - $paid;
        $afterApproval = $paid + $submission->amount_submitted;
        $pct     = $total > 0 ? min(100, round(($afterApproval / $total) * 100)) : 0;
        $fullyPaid = $afterApproval >= $total;
        $methodNames = [
          'vodacom_mpesa' => 'Vodacom M-Pesa',
          'airtel_money'  => 'Airtel Money',
          'halopesa'      => 'Halopesa',
          'mixx_by_yas'   => 'Mixx by Yas',
          'crdb_bank'     => 'CRDB Bank',
          'nmb_bank'      => 'NMB Bank',
          'nbc_bank'      => 'NBC Bank',
        ];
        $methodLabel = $methodNames[$submission->payment_method] ?? ucwords(str_replace('_',' ',$submission->payment_method));
      @endphp

      <div class="pay-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden submission-card"
           data-client="{{ strtolower($submission->client->name ?? '') }}"
           data-payer="{{ strtolower($submission->payer_name) }}">

        {{-- Card top bar --}}
        <div class="h-1 w-full" style="background:linear-gradient(90deg,var(--gr),var(--gr-mid))"></div>

        <div class="p-6">
          {{-- Row 1: client + status --}}
          <div class="flex flex-wrap justify-between items-start gap-3 mb-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                   style="background:var(--gr)">
                {{ strtoupper(substr($submission->client->name ?? 'C', 0, 1)) }}
              </div>
              <div>
                <div class="font-bold text-gray-900 leading-tight">{{ $submission->client->name ?? 'Unknown Client' }}</div>
                <div class="text-xs text-gray-500 mt-0.5">Invoice <span class="font-semibold text-gray-700">{{ $invoice->invoice_number }}</span></div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs text-gray-400">{{ $submission->submitted_at->diffForHumans() }}</span>
              <span class="badge badge-pending text-xs px-3 py-1 rounded-full font-semibold">⏳ Pending</span>
            </div>
          </div>

          {{-- Details grid --}}
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4 p-4 rounded-xl" style="background:var(--gr-pale); border:1px solid #a7f3d0;">
            <div>
              <p class="text-xs text-gray-500 mb-0.5">Payer</p>
              <p class="text-sm font-semibold text-gray-800">{{ $submission->payer_name }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-0.5">Method</p>
              <p class="text-sm font-semibold text-gray-800">{{ $methodLabel }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-0.5">Submitted</p>
              <p class="text-lg font-extrabold" style="color:var(--gr)">
                TZS {{ number_format($submission->amount_submitted, 0) }}
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-0.5">Balance Due</p>
              <p class="text-sm font-semibold text-gray-800">TZS {{ number_format($balance, 0) }}</p>
            </div>
          </div>

          {{-- Progress towards full payment --}}
          <div class="mb-5">
            <div class="flex justify-between text-xs text-gray-500 mb-1.5">
              <span>Payment progress after approval</span>
              <span class="font-semibold {{ $fullyPaid ? 'text-green-700' : 'text-amber-600' }}">
                {{ $pct }}% — {{ $fullyPaid ? 'Fully Paid' : 'Partially Paid' }}
              </span>
            </div>
            <div class="prog-track">
              <div class="prog-fill" style="width:{{ $pct }}%; {{ !$fullyPaid ? 'background:#d97706' : '' }}"></div>
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex flex-wrap gap-3">
            <button type="button" onclick="approvePayment({{ $submission->id }}, event)"
                    class="btn-approve flex-1 flex items-center justify-center gap-2 text-sm py-2.5">
              <i class="bi bi-check-circle"></i> Approve & Issue Receipt
            </button>
            <button type="button" onclick="showRejectModal({{ $submission->id }})"
                    class="btn-reject flex items-center justify-center gap-2 text-sm py-2.5 px-5">
              <i class="bi bi-x-circle"></i> Reject
            </button>
          </div>

          {{-- Collapsible client details --}}
          <details class="mt-4 pt-4 border-t border-gray-100">
            <summary class="cursor-pointer text-xs font-semibold text-gray-500 hover:text-gray-800 select-none flex items-center gap-1">
              <i class="bi bi-person-lines-fill"></i> Client Details
            </summary>
            <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-600 p-3 bg-gray-50 rounded-lg">
              <div><span class="font-medium text-gray-700">Reg #</span><br>{{ $submission->client->registration_number ?? '—' }}</div>
              <div><span class="font-medium text-gray-700">Phone</span><br>{{ $submission->client->phone ?? '—' }}</div>
              <div><span class="font-medium text-gray-700">Email</span><br>{{ $submission->client->email ?? '—' }}</div>
              <div><span class="font-medium text-gray-700">Address</span><br>{{ $submission->client->address ?? '—' }}</div>
            </div>
          </details>
        </div>
      </div>

      {{-- Reject modal --}}
      <div id="reject-modal-{{ $submission->id }}"
           class="hidden fixed inset-0 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-7 animate-fade-in">
          <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
              <i class="bi bi-x-circle text-red-600 text-lg"></i>
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Reject Payment</h3>
              <p class="text-xs text-gray-500">This will notify the client</p>
            </div>
          </div>
          <div class="mb-4 p-4 bg-red-50 rounded-xl text-sm text-red-800 space-y-1 border border-red-100">
            <p><span class="font-semibold">Client:</span> {{ $submission->client->name ?? '—' }}</p>
            <p><span class="font-semibold">Amount:</span> TZS {{ number_format($submission->amount_submitted, 0) }}</p>
            <p><span class="font-semibold">Invoice:</span> {{ $invoice->invoice_number }}</p>
          </div>
          <form id="reject-form-{{ $submission->id }}" onsubmit="submitReject(event, {{ $submission->id }})">
            @csrf
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Reason for Rejection <span class="text-red-500">*</span>
            </label>
            <textarea id="reason-{{ $submission->id }}" name="reason" rows="4" required
                      placeholder="e.g., Amount doesn't match, wrong payer name, transaction not found…"
                      class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent resize-none mb-4"
                      style="--tw-ring-color:var(--gr)"></textarea>
            <div class="flex gap-3">
              <button type="button" onclick="closeRejectModal({{ $submission->id }})"
                      class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
                Cancel
              </button>
              <button type="submit"
                      class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition">
                Confirm Rejection
              </button>
            </div>
          </form>
        </div>
      </div>
    @endforeach
    </div>{{-- /submissions-list --}}
  @endif
  </div>{{-- /tab-pending --}}


  {{-- ════════════════════════════════════════════════════════════════
       TAB: APPROVED
  ════════════════════════════════════════════════════════════════ --}}
  <div id="tab-approved" class="tab-panel hidden">
    @if ($approved->isEmpty())
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 empty-icon" style="background:var(--gr-pale);">
          <i class="bi bi-check2-circle text-3xl" style="color:var(--gr)"></i>
        </div>
        <p class="font-medium text-gray-700">No approved payments yet.</p>
      </div>
    @else
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
          <i class="bi bi-check-circle-fill" style="color:var(--gr)"></i>
          <span class="font-semibold text-gray-800 text-sm">{{ $approved->count() }} approved payment{{ $approved->count() !== 1 ? 's' : '' }}</span>
        </div>
        <div class="overflow-x-auto">
          <table class="data-table min-w-full divide-y divide-gray-100">
            <thead>
              <tr>
                <th>Client</th>
                <th>Invoice #</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Approved On</th>
                <th>Receipt</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @foreach ($approved as $s)
              @php $mn = ['vodacom_mpesa'=>'M-Pesa','airtel_money'=>'Airtel','halopesa'=>'Halopesa','mixx_by_yas'=>'Mixx by Yas','crdb_bank'=>'CRDB','nmb_bank'=>'NMB','nbc_bank'=>'NBC']; @endphp
              <tr>
                <td class="font-medium text-gray-900">
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:var(--gr)">{{ strtoupper(substr($s->client->name ?? 'C',0,1)) }}</div>
                    {{ $s->client->name ?? 'Unknown' }}
                  </div>
                </td>
                <td class="text-gray-600 font-mono text-xs">{{ $s->invoice->invoice_number ?? '—' }}</td>
                <td>
                  <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                    {{ $mn[$s->payment_method] ?? ucfirst($s->payment_method) }}
                  </span>
                </td>
                <td class="font-bold" style="color:var(--gr)">TZS {{ number_format($s->amount_submitted, 0) }}</td>
                <td class="text-gray-500 text-xs">{{ optional($s->verified_at)->format('M d, Y · H:i') ?? '—' }}</td>
                <td>
                  @if ($s->receipt_path)
                    <a href="{{ route('payment-submissions.receipt.download', $s) }}" target="_blank" class="receipt-link">
                      <i class="bi bi-download"></i> #{{ $s->receipt_number }}
                    </a>
                  @else
                    <span class="text-gray-300 text-xs">—</span>
                  @endif
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>{{-- /tab-approved --}}

  {{-- ════════════════════════════════════════════════════════════════
       TAB: REJECTED
  ════════════════════════════════════════════════════════════════ --}}
  <div id="tab-rejected" class="tab-panel hidden">
    @if ($rejected->isEmpty())
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 empty-icon" style="background:#fee2e2;">
          <i class="bi bi-x-circle text-3xl text-red-400"></i>
        </div>
        <p class="font-medium text-gray-700">No rejected payments.</p>
      </div>
    @else
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
          <i class="bi bi-x-circle-fill text-red-500"></i>
          <span class="font-semibold text-gray-800 text-sm">{{ $rejected->count() }} rejected payment{{ $rejected->count() !== 1 ? 's' : '' }}</span>
        </div>
        <div class="overflow-x-auto">
          <table class="data-table min-w-full divide-y divide-gray-100">
            <thead>
              <tr>
                <th>Client</th>
                <th>Invoice #</th>
                <th>Amount</th>
                <th>Rejected On</th>
                <th>Reason</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @foreach ($rejected as $s)
              <tr>
                <td class="font-medium text-gray-900">
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 bg-red-400">
                      {{ strtoupper(substr($s->client->name ?? 'C',0,1)) }}</div>
                    {{ $s->client->name ?? 'Unknown' }}
                  </div>
                </td>
                <td class="text-gray-600 font-mono text-xs">{{ $s->invoice->invoice_number ?? '—' }}</td>
                <td class="font-semibold text-gray-700">TZS {{ number_format($s->amount_submitted, 0) }}</td>
                <td class="text-gray-500 text-xs">{{ optional($s->rejected_at)->format('M d, Y · H:i') ?? '—' }}</td>
                <td>
                  <span class="text-red-700 text-xs bg-red-50 px-2 py-1 rounded-lg">
                    {{ $s->rejection_reason ?? 'No reason provided' }}
                  </span>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>{{-- /tab-rejected --}}

  @endif {{-- end if any submissions exist --}}
</div>{{-- /container --}}


@push('scripts')
<script>
/* ─── Tab switching ─── */
const TAB_BADGE_COLOURS = {
  pending:  { inactive: ['bg-gray-200','text-gray-600'],    active: ['bg-white/25','text-white'] },
  approved: { inactive: ['bg-emerald-100','text-emerald-700'], active: ['bg-white/25','text-white'] },
  rejected: { inactive: ['bg-red-100','text-red-700'],      active: ['bg-white/25','text-white'] },
};

function switchTab(tab) {
  // hide all panels
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
  const panel = document.getElementById('tab-' + tab);
  if (panel) panel.classList.remove('hidden');

  // reset all buttons to inactive
  document.querySelectorAll('.ppa-tab-btn').forEach(btn => {
    const t = btn.dataset.tab;
    btn.style.background = 'transparent';
    btn.style.boxShadow  = 'none';
    btn.style.color      = '#4b5563';
    const badge = btn.querySelector('span:last-child');
    if (badge && TAB_BADGE_COLOURS[t]) {
      TAB_BADGE_COLOURS[t].active.forEach(c => badge.classList.remove(c));
      TAB_BADGE_COLOURS[t].inactive.forEach(c => badge.classList.add(c));
    }
  });

  // activate selected button
  const active = document.getElementById('tab-btn-' + tab);
  if (active) {
    active.style.background = 'linear-gradient(135deg,#047857 0%,#059669 100%)';
    active.style.boxShadow  = '0 4px 14px rgba(4,120,87,.40)';
    active.style.color      = '#fff';
    const badge = active.querySelector('span:last-child');
    if (badge && TAB_BADGE_COLOURS[tab]) {
      TAB_BADGE_COLOURS[tab].inactive.forEach(c => badge.classList.remove(c));
      TAB_BADGE_COLOURS[tab].active.forEach(c => badge.classList.add(c));
    }
  }
}

/* ─── Search / filter ─── */
function filterSubmissions() {
  const q = (document.getElementById('searchInput').value || '').toLowerCase();
  document.querySelectorAll('.submission-card').forEach(card => {
    const match = card.dataset.client.includes(q) || card.dataset.payer.includes(q);
    card.style.display = match ? '' : 'none';
  });
}
document.getElementById('searchInput')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') filterSubmissions();
});

/* ─── Approve ─── */
function approvePayment(id, event) {
  const btn = event.target.closest('button');
  if (!confirm('Approve this payment and generate a receipt?')) return;

  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Processing…';

  fetch(`/payment-submissions/${id}/approve`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      if (data.receipt_url) window.open(data.receipt_url, '_blank');
      showToast('Payment approved! Receipt generated.', 'success');
      setTimeout(() => window.location.reload(), 1200);
    } else {
      showToast(data.error || 'Approval failed.', 'error');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve & Issue Receipt';
    }
  })
  .catch(() => {
    showToast('Network error. Please try again.', 'error');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve & Issue Receipt';
  });
}

/* ─── Reject modal ─── */
function showRejectModal(id)  { document.getElementById('reject-modal-' + id).classList.remove('hidden'); }
function closeRejectModal(id) { document.getElementById('reject-modal-' + id).classList.add('hidden'); }

function submitReject(event, id) {
  event.preventDefault();
  const reason = document.getElementById('reason-' + id).value.trim();
  if (!reason) { showToast('Please provide a rejection reason.', 'error'); return; }

  fetch(`/payment-submissions/${id}/reject`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    body: JSON.stringify({ reason })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showToast('Payment rejected.', 'error');
      setTimeout(() => window.location.reload(), 1000);
    } else {
      showToast(data.error || 'Failed to reject.', 'error');
    }
  })
  .catch(() => showToast('Network error.', 'error'));
}

/* ─── Close modal on backdrop click ─── */
document.querySelectorAll('[id^="reject-modal-"]').forEach(el => {
  el.addEventListener('click', e => { if (e.target === el) closeRejectModal(el.id.replace('reject-modal-','')) });
});

/* ─── Toast ─── */
function showToast(msg, type) {
  const el = document.createElement('div');
  const bg = type === 'success' ? '#047857' : '#dc2626';
  el.style.cssText = `position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
    background:${bg};color:#fff;padding:.85rem 1.4rem;border-radius:.9rem;
    font-size:.875rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.18);
    display:flex;align-items:center;gap:.5rem;max-width:22rem;
    animation:slideInRight .25s ease;`;
  const icon = type === 'success' ? '✓' : '✕';
  el.innerHTML = `<span style="font-size:1rem">${icon}</span> ${msg}`;
  document.body.appendChild(el);
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(() => el.remove(), 400); }, 3500);
}

const style = document.createElement('style');
style.textContent = `@keyframes slideInRight{from{opacity:0;transform:translateX(1.5rem)}to{opacity:1;transform:translateX(0)}}`;
document.head.appendChild(style);
</script>
@endpush

@endsection
