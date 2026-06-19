<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.6;
            background: #ffffff;
        }
        .page-wrapper {
            max-width: 780px;
            margin: 0 auto;
            padding: 32px;
        }
        .brand-banner {
            background: linear-gradient(135deg, #055c5c 0%, #0d9488 40%, #0891b2 100%);
            border-radius: 16px 16px 0 0;
            padding: 28px 36px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            position: relative;
        }
        .brand-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="1"/><path d="M40 140 L80 70 L120 130 L160 60" stroke="rgba(255,255,255,0.08)" stroke-width="1.5" fill="none"/></svg>') center/280px no-repeat;
            opacity: 1;
            pointer-events: none;
        }
        .brand-info {
            position: relative;
            z-index: 1;
        }
        .brand-logo {
            width: 110px;
            height: auto;
            max-height: 56px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        .brand-name {
            font-size: 20px;
            font-weight: 700;
            margin-top: 6px;
            letter-spacing: 0.3px;
        }
        .brand-tagline {
            font-size: 11px;
            opacity: 0.85;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .invoice-title-block {
            position: relative;
            z-index: 1;
            text-align: right;
        }
        .invoice-title-block h1 {
            font-size: 38px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1.5px;
            line-height: 1;
        }
        .invoice-title-block .invoice-number {
            font-size: 15px;
            font-weight: 600;
            margin-top: 6px;
            opacity: 0.9;
            letter-spacing: 0.5px;
        }
        .content-body {
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 16px 16px;
            background: #ffffff;
            padding: 28px 36px 24px;
        }
        .meta-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }
        .meta-block {
            flex: 1;
        }
        .meta-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            margin-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .meta-block .company-name {
            font-size: 16px;
            font-weight: 700;
            color: #055c5c;
            margin-bottom: 2px;
        }
        .meta-block .company-detail {
            font-size: 12px;
            color: #475569;
            line-height: 1.7;
        }
        .meta-block .client-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .meta-block .invoice-meta-line {
            font-size: 13px;
            color: #475569;
            padding: 3px 0;
        }
        .status-pill {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-draft { background: #f1f5f9; color: #475569; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #fee2e2; color: #7f1d1d; }
        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, #055c5c, #0891b2);
            border-radius: 2px;
            margin: 22px 0 18px;
            width: 60px;
        }
        .section-heading {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #055c5c;
            margin: 0 0 12px;
        }
        table.service-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .service-table thead th {
            background: linear-gradient(135deg, #055c5c, #0d9488);
            color: #ffffff;
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .service-table thead th:last-child,
        .service-table tbody td:last-child {
            text-align: right;
        }
        .service-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #334155;
            background: #ffffff;
        }
        .service-table tbody tr:last-child td {
            border-bottom: none;
        }
        .service-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }
        .service-table tbody tr:hover td {
            background: #f0fdfa;
        }
        .service-table .col-desc { width: 55%; }
        .service-table .col-type { width: 25%; }
        .service-table .col-amount { width: 20%; }
        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        table.totals-table {
            width: 320px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #475569;
        }
        .totals-table .label-col { text-align: right; font-weight: 500; }
        .totals-table .amount-col { text-align: right; font-weight: 500; }
        .totals-table .grand-total td {
            border-top: 2px solid #055c5c;
            border-bottom: 3px solid #055c5c;
            padding: 12px 14px;
            font-size: 16px;
            font-weight: 700;
            color: #055c5c;
        }
        .info-card {
            background: #f0fdfa;
            border-left: 4px solid #0d9488;
            border-radius: 0 10px 10px 0;
            padding: 16px 20px;
            margin-top: 22px;
        }
        .info-card .info-heading {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #055c5c;
            margin-bottom: 8px;
        }
        .info-card p {
            font-size: 13px;
            color: #475569;
            margin-bottom: 3px;
        }
        .overdue-warning {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            border-radius: 0 10px 10px 0;
            padding: 14px 20px;
            margin-top: 14px;
            color: #991b1b;
            font-weight: 600;
            font-size: 13px;
        }
        .notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-top: 16px;
        }
        .notes-box .notes-heading {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .notes-box .notes-text {
            font-size: 13px;
            color: #475569;
            white-space: pre-line;
        }
        .pdf-footer {
            text-align: center;
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .pdf-footer p {
            font-size: 11px;
            color: #94a3b8;
            margin: 2px 0;
        }
        .pdf-footer .footer-brand {
            font-weight: 700;
            color: #055c5c;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        @php
            $logoPath = public_path('result.png');
            $logoSrc = file_exists($logoPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                : null;
        @endphp

        <div class="brand-banner">
            <div class="brand-info">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" class="brand-logo" alt="GreenRoute">
                @endif
                <div class="brand-name">GREENROUTE</div>
                <div class="brand-tagline">Professional Waste Management</div>
            </div>
            <div class="invoice-title-block">
                <h1>INVOICE</h1>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
            </div>
        </div>

        <div class="content-body">
            <div class="meta-grid">
                <div class="meta-block">
                    <div class="meta-label">From</div>
                    <div class="company-name">{{ config('app.name', 'GreenRoute') }}</div>
                    <div class="company-detail">
                        123 Business Street<br>
                        City, State 12345<br>
                        Phone: (255) 123-4567<br>
                        Email: info@greenroute.co.tz
                    </div>
                </div>
                <div class="meta-block">
                    <div class="meta-label">Bill To</div>
                    <div class="client-name">{{ $invoice->client->name }}</div>
                    <div class="invoice-meta-line">{{ $invoice->client->email }}</div>
                    @if($invoice->client->phone)
                        <div class="invoice-meta-line">{{ $invoice->client->phone }}</div>
                    @endif
                    @if($invoice->client->address)
                        <div class="invoice-meta-line">{{ $invoice->client->address }}</div>
                    @endif
                    @if($invoice->client->city)
                        <div class="invoice-meta-line">{{ $invoice->client->city }}</div>
                    @endif
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="meta-grid">
                <div class="meta-block">
                    <div class="meta-label">Invoice Details</div>
                    <div class="invoice-meta-line"><strong>Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</div>
                    <div class="invoice-meta-line"><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</div>
                    <div class="invoice-meta-line"><strong>Status:</strong><br>
                        <span class="status-pill status-{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
                <div class="meta-block">
                    <div class="meta-label">Service Information</div>
                    @if($invoice->schedule)
                        <div class="invoice-meta-line"><strong>Pickup Date:</strong> {{ $invoice->schedule->pickup_date->format('M d, Y') }}</div>
                        <div class="invoice-meta-line"><strong>Location:</strong> {{ $invoice->schedule->pickup_location }}</div>
                    @endif
                    <div class="invoice-meta-line"><strong>Type:</strong> {{ str_replace('_', ' ', ucfirst($invoice->service_type)) }}</div>
                </div>
            </div>

            <div class="section-heading">Service Line Items</div>
            <div class="section-divider" style="margin-top: 0;"></div>
            <table class="service-table">
                <thead>
                    <tr>
                        <th class="col-desc">Description</th>
                        <th class="col-type">Service Type</th>
                        <th class="col-amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $invoice->service_type }} Service</strong>
                            @if($invoice->description)
                                <br><small style="color: #64748b;">{{ $invoice->description }}</small>
                            @endif
                            @if($invoice->schedule)
                                <br><small style="color: #64748b;">
                                    Pickup: {{ $invoice->schedule->pickup_date->format('M d, Y') }}<br>
                                    Location: {{ $invoice->schedule->pickup_location }}
                                    @if($invoice->schedule->displayed_price !== null)
                                        <br>Schedule price: TZS {{ number_format($invoice->schedule->displayed_price, 2) }}
                                    @endif
                                </small>
                            @endif
                        </td>
                        <td>{{ ucwords(str_replace('_', ' ', $invoice->service_type)) }}</td>
                        <td>TZS {{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="totals-wrapper">
                <table class="totals-table">
                    <tr>
                        <td class="label-col">Subtotal</td>
                        <td class="amount-col">TZS {{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Tax ({{ number_format($invoice->tax_rate, 2) }}%)</td>
                        <td class="amount-col">TZS {{ number_format($invoice->tax_amount, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label-col">Total Amount</td>
                        <td class="amount-col">TZS {{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                    @if($invoice->amount_paid > 0)
                        <tr>
                            <td class="label-col" style="color: #059669;">Paid</td>
                            <td class="amount-col" style="color: #059669;">TZS {{ number_format($invoice->amount_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="label-col" style="color: #dc2626; font-weight: 700;">Balance Due</td>
                            <td class="amount-col" style="color: #dc2626; font-weight: 700;">TZS {{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="info-card">
                <div class="info-heading"><i class="bi bi-info-circle me-1"></i> Payment Information</div>
                <p><strong>Payment Terms:</strong> Net 30 days</p>
                <p><strong>Payment Methods:</strong> Mobile Money, Bank Transfer, Cheque</p>
                <p><strong>Late Fee:</strong> 1.5% per month on overdue balances</p>
            </div>

            @if($invoice->status === 'overdue')
                <div class="overdue-warning">
                    &#9888; This invoice is overdue. Please arrange payment immediately to avoid additional fees.
                </div>
            @endif

            @if($invoice->notes)
                <div class="notes-box">
                    <div class="notes-heading">Additional Notes</div>
                    <div class="notes-text">{{ $invoice->notes }}</div>
                </div>
            @endif

            <div class="pdf-footer">
                <p class="footer-brand">GREENROUTE &mdash; Professional Waste Management Services</p>
                <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }} &bull; Invoice #{{ $invoice->invoice_number }}</p>
            </div>
        </div>
    </div>
</body>
</html>