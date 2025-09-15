<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .company-info {
            flex: 1;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .company-details {
            font-size: 12px;
            color: #666;
        }
        .invoice-title {
            text-align: right;
            flex: 1;
        }
        .invoice-title h1 {
            font-size: 36px;
            margin: 0;
            color: #2563eb;
        }
        .invoice-number {
            font-size: 18px;
            color: #666;
            margin-top: 5px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bill-to, .invoice-info {
            flex: 1;
        }
        .bill-to {
            margin-right: 40px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .client-info, .invoice-meta {
            font-size: 14px;
            line-height: 1.8;
        }
        .client-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .service-details {
            margin: 30px 0;
        }
        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .service-table th {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #374151;
        }
        .service-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            vertical-align: top;
        }
        .service-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .totals {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .totals-table {
            width: 300px;
        }
        .totals-table td {
            padding: 8px 12px;
            border: none;
        }
        .totals-table .label {
            text-align: right;
            font-weight: bold;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-table .amount {
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-table .total-row .label,
        .totals-table .total-row .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            border-top: 2px solid #2563eb;
        }
        .payment-info {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
        }
        .payment-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-draft {
            background-color: #f3f4f6;
            color: #374151;
        }
        .status-cancelled {
            background-color: #fecaca;
            color: #7f1d1d;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 5px;
        }
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #374151;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ config('app.name', 'Waste Management') }}</div>
            <div class="company-tagline">Professional Waste Management Services</div>
            <div class="company-details">
                123 Business Street<br>
                City, State 12345<br>
                Phone: (555) 123-4567<br>
                Email: info@wastemanagement.com
            </div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="bill-to">
            <div class="section-title">Bill To:</div>
            <div class="client-info">
                <div class="client-name">{{ $invoice->client->name }}</div>
                <div>{{ $invoice->client->email }}</div>
                @if($invoice->client->phone)
                    <div>{{ $invoice->client->phone }}</div>
                @endif
                @if($invoice->client->address)
                    <div>{{ $invoice->client->address }}</div>
                @endif
            </div>
        </div>
        
        <div class="invoice-info">
            <div class="section-title">Invoice Information:</div>
            <div class="invoice-meta">
                <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</div>
                <div><strong>Status:</strong> 
                    <span class="payment-status status-{{ $invoice->status }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
                @if($invoice->schedule)
                    <div><strong>Related Schedule:</strong> {{ $invoice->schedule->pickup_date->format('M d, Y') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Service Details -->
    <div class="service-details">
        <div class="section-title">Service Details:</div>
        <table class="service-table">
            <thead>
                <tr>
                    <th style="width: 60%;">Description</th>
                    <th style="width: 20%;">Service Type</th>
                    <th style="width: 20%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $invoice->service_type }} Service</strong>
                        @if($invoice->description)
                            <br><small style="color: #666;">{{ $invoice->description }}</small>
                        @endif
                        @if($invoice->schedule)
                            <br><small style="color: #666;">
                                Pickup Date: {{ $invoice->schedule->pickup_date->format('M d, Y') }}<br>
                                Location: {{ $invoice->schedule->pickup_location }}
                            </small>
                        @endif
                    </td>
                    <td>{{ $invoice->service_type }}</td>
                    <td style="text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="totals">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="amount">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Tax ({{ number_format($invoice->tax_rate, 2) }}%):</td>
                <td class="amount">${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Total Amount:</td>
                <td class="amount">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @if($invoice->amount_paid > 0)
                <tr>
                    <td class="label">Amount Paid:</td>
                    <td class="amount" style="color: #059669;">${{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Balance Due:</td>
                    <td class="amount" style="color: #dc2626; font-weight: bold;">${{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Payment Information -->
    <div class="payment-info">
        <div class="section-title">Payment Information:</div>
        <p><strong>Payment Terms:</strong> Net 30 days</p>
        <p><strong>Payment Methods:</strong> Check, Bank Transfer, Credit Card</p>
        <p><strong>Late Fee:</strong> 1.5% per month on overdue amounts</p>
        @if($invoice->status === 'overdue')
            <p style="color: #dc2626; font-weight: bold;">⚠️ This invoice is overdue. Please remit payment immediately to avoid additional fees.</p>
        @endif
    </div>

    <!-- Notes -->
    @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">Additional Notes:</div>
            <div>{{ $invoice->notes }}</div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for choosing our waste management services!</p>
        <p>For questions about this invoice, please contact us at (555) 123-4567 or billing@wastemanagement.com</p>
        <p style="margin-top: 20px; font-size: 10px;">Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>