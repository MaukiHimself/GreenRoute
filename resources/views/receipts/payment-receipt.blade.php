<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .receipt {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 40px;
            border: 2px solid #055c5c;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #055c5c;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #055c5c;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 12px;
            margin: 5px 0;
        }

        .receipt-number {
            background-color: #f0f0f0;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }

        .receipt-number .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }

        .receipt-number .value {
            font-size: 18px;
            font-weight: bold;
            color: #055c5c;
            font-family: 'Courier New', monospace;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #055c5c;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .info-row.highlight {
            background-color: #f9f9f9;
            padding: 8px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin: 5px 0;
        }

        .info-label {
            color: #666;
            font-weight: 600;
        }

        .info-value {
            color: #333;
            font-weight: 500;
            text-align: right;
        }

        .amount-section {
            background-color: #f0fdf4;
            border: 2px solid #2e7d32;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .amount-row.total {
            border-top: 2px solid #2e7d32;
            padding-top: 12px;
            margin-top: 12px;
            font-size: 18px;
            font-weight: bold;
            color: #2e7d32;
        }

        .status {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
            color: #1e40af;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            font-size: 14px;
        }

        .payment-method {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .payment-method-label {
            font-size: 11px;
            color: #92400e;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .payment-method-value {
            font-size: 18px;
            font-weight: bold;
            color: #b45309;
            font-family: 'Courier New', monospace;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .footer-message {
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 12px;
            line-height: 1.5;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }

        .signature {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #2e7d32;
            color: white;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>✓ PAYMENT RECEIPT</h1>
            <p><strong>{{ $contractor->name ?? $contractor->company_name }}</strong></p>
            <p>{{ $contractor->address ?? 'Tanzania' }}</p>
            <p>Phone: {{ $contractor->phone ?? 'N/A' }}</p>
        </div>

        <!-- Receipt Number -->
        <div class="receipt-number">
            <div class="label">Receipt Number</div>
            <div class="value">{{ $receiptNumber }}</div>
        </div>

        <!-- Invoice Details Section -->
        <div class="section">
            <div class="section-title">Invoice Information</div>
            <div class="info-row">
                <span class="info-label">Invoice Number:</span>
                <span class="info-value">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Invoice Date:</span>
                <span class="info-value">{{ $invoice->invoice_date->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Due Date:</span>
                <span class="info-value">{{ $invoice->due_date->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Client Details Section -->
        <div class="section">
            <div class="section-title">Client Information</div>
            <div class="info-row">
                <span class="info-label">Client Name:</span>
                <span class="info-value">{{ $client->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Registration #:</span>
                <span class="info-value">{{ $client->registration_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payer Name:</span>
                <span class="info-value">{{ $submission->payer_name }}</span>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="payment-method">
            <div class="payment-method-label">Payment Method</div>
            <div class="payment-method-value">
                @switch($submission->payment_method)
                    @case('vodacom_mpesa')
                        Vodacom M-Pesa
                    @break
                    @case('airtel_money')
                        Airtel Money
                    @break
                    @case('halopesa')
                        Halopesa
                    @break
                    @case('mixx_by_yas')
                        Mixx by Yas (Tigo Pesa)
                    @break
                    @case('crdb_bank')
                        CRDB Bank
                    @break
                    @case('nmb_bank')
                        NMB Bank
                    @break
                    @case('nbc_bank')
                        NBC Bank
                    @break
                    @default
                        {{ $submission->payment_method }}
                @endswitch
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="amount-section">
            <div class="amount-row">
                <span class="info-label">Invoice Total:</span>
                <span class="info-value">TZS {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span class="info-label">Previously Paid:</span>
                <span class="info-value">TZS {{ number_format(max(0, $invoice->amount_paid - $submission->amount_submitted), 2) }}</span>
            </div>
            <div class="amount-row">
                <span class="info-label">This Payment:</span>
                <span class="info-value" style="color: #2e7d32; font-weight: bold;">+ TZS {{ number_format($submission->amount_submitted, 2) }}</span>
            </div>
            <div class="amount-row total">
                <span>Total Paid:</span>
                <span style="color: #2e7d32;">TZS {{ number_format($invoice->amount_paid, 2) }}</span>
            </div>
            <div class="amount-row" style="margin-top: 10px;">
                <span class="info-label">Remaining Balance:</span>
                <span class="info-value">TZS {{ number_format(max(0, $invoice->total_amount - $invoice->amount_paid), 2) }}</span>
            </div>
        </div>

        <!-- Status -->
        <div class="status">
            <span class="badge">✓ APPROVED</span>
            Payment Verified and Approved
        </div>

        <!-- Dates -->
        <div class="section">
            <div class="section-title">Verification Details</div>
            <div class="info-row">
                <span class="info-label">Submitted:</span>
                <span class="info-value">{{ $submission->submitted_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Verified:</span>
                <span class="info-value">{{ $submission->verified_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Receipt Issued:</span>
                <span class="info-value">{{ $generatedAt->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-message">
                <strong>This is an official payment receipt.</strong><br>
                Thank you for your payment. Please keep this receipt for your records.<br>
                For inquiries, contact the contractor at {{ $contractor->phone ?? 'N/A' }}.
            </p>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p>Receipt ID: {{ $receiptNumber }}</p>
                <p>Generated: {{ $generatedAt->format('M d, Y') }}</p>
                <p style="font-size: 10px; margin-top: 10px; color: #999;">
                    This receipt was automatically generated by the GreenRoute Payment System
                </p>
            </div>
        </div>
    </div>
</body>
</html>
