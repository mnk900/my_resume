<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.4in;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 9pt;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .invoice-container {
            width: 100%;
        }
        /* Top Header */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-title {
            font-size: 20pt;
            font-weight: bold;
            color: #3b82f6;
            text-transform: uppercase;
        }
        .header-meta {
            text-align: right;
            font-size: 8.5pt;
            color: #64748b;
        }
        .header-meta-value {
            font-weight: bold;
            color: #1e293b;
        }
        .header-meta-due {
            font-weight: bold;
            color: #ef4444;
        }
        /* Address Grid */
        .address-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .address-col {
            width: 50%;
            vertical-align: top;
        }
        .address-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .address-name {
            font-size: 10pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 3px;
        }
        .address-text {
            font-size: 8.5pt;
            color: #475569;
            line-height: 1.35;
        }
        /* Project Banner */
        .project-banner {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 15px;
        }
        .project-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
        }
        .project-label {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 8.5pt;
        }
        .items-table .text-center {
            text-align: center;
        }
        .items-table .text-right {
            text-align: right;
        }
        /* Totals Block */
        .totals-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .totals-col-left {
            width: 60%;
        }
        .totals-col-right {
            width: 40%;
        }
        .totals-sub-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-sub-table td {
            padding: 4px 8px;
            font-size: 8.5pt;
        }
        .totals-sub-table .label {
            color: #64748b;
            text-align: left;
        }
        .totals-sub-table .value {
            text-align: right;
            font-weight: bold;
        }
        .totals-sub-table .grand-total-row td {
            border-top: 1px solid #cbd5e1;
            font-size: 10.5pt;
            font-weight: bold;
            color: #3b82f6;
            padding-top: 6px;
        }
        /* Terms and Bank details */
        .details-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-bottom: 20px;
        }
        .details-col {
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .details-col-last {
            width: 50%;
            vertical-align: top;
            padding-left: 15px;
            border-left: 1px solid #e2e8f0;
        }
        .details-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .bank-info-item {
            margin-bottom: 4px;
            font-size: 8.5pt;
        }
        .bank-info-label {
            font-size: 7.5pt;
            color: #64748b;
            display: block;
        }
        .bank-info-value {
            font-weight: bold;
            color: #1e293b;
        }
        .terms-list {
            padding-left: 12px;
            margin: 0;
            font-size: 8pt;
            color: #475569;
            line-height: 1.3;
        }
        .terms-list li {
            margin-bottom: 3px;
        }
        /* Footer */
        .footer-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
        }
        .thank-you {
            font-size: 10.5pt;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .company-name {
            font-size: 9pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 1px;
        }
        .company-tagline {
            font-size: 8pt;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="header-title">Invoice</td>
                <td class="header-meta">
                    <div style="margin-bottom: 3px;">INVOICE NO.: <span class="header-meta-value">{{ $invoice->invoice_no }}</span></div>
                    <div style="margin-bottom: 3px;">INVOICE DATE: <span class="header-meta-value">{{ $invoice->invoice_date->format('F d, Y') }}</span></div>
                    <div>PAYMENT DUE: <span class="header-meta-due">{{ $invoice->due_date->format('F d, Y') }}</span></div>
                </td>
            </tr>
        </table>

        <!-- Address details -->
        <table class="address-table">
            <tr>
                <td class="address-col">
                    <div class="address-title">From</div>
                    <table style="width: 100%; border-collapse: collapse; border: 0;">
                        <tr>
                            @if($logoBase64)
                                <td style="width: 80px; vertical-align: top; padding-right: 15px;">
                                    <img src="{{ $logoBase64 }}" alt="ITechGB Logo" style="width: 65px; height: auto; border: 1px solid #e2e8f0; padding: 2px; background-color: #f8fafc;">
                                </td>
                            @endif
                            <td style="vertical-align: top;">
                                <div class="address-name">Innovative Technologies Gilgit-Baltistan</div>
                                <div class="address-text">
                                    Website Design & Development Services<br>
                                    Gilgit-Baltistan, Pakistan<br>
                                    Email: info@itechgb.com<br>
                                    Phone: +92 346 9236762<br>
                                    Website: https://www.itechgb.com/
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="address-col" style="text-align: right;">
                    <div class="address-title">Bill To</div>
                    <div class="address-name">{{ $invoice->client_name }}</div>
                    <div class="address-text">
                        @if($invoice->organization)
                            {{ $invoice->organization }}<br>
                        @endif
                        @if($invoice->address)
                            {{ $invoice->address }}<br>
                        @endif
                        Email: {{ $invoice->email }}
                        @if($invoice->phone)
                            <br>Phone: {{ $invoice->phone }}
                        @endif
                        @if($invoice->user && $invoice->user->username)
                            <br>Portfolio: {{ url('/' . $invoice->user->username) }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Project banner -->
        <div class="project-banner">
            <div class="project-label">My Resume Cloud</div>
            <div class="project-title">Portfolio Website Design & Development</div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 55%;">Description</th>
                    <th style="width: 10%; text-align: center;">Qty.</th>
                    <th style="width: 15%; text-align: right;">Rate</th>
                    <th style="width: 15%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="text-center" style="color: #64748b;">{{ $index + 1 }}</td>
                        <td style="font-weight: 500;">{{ $item->description }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rs. {{ number_format($item->rate, 2) }}</td>
                        <td class="text-right" style="font-weight: bold;">Rs. {{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Block -->
        <table class="totals-table">
            <tr>
                <td class="totals-col-left"></td>
                <td class="totals-col-right">
                    <table class="totals-sub-table">
                        <tr>
                            <td class="label">Subtotal:</td>
                            <td class="value">Rs. {{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Discount:</td>
                            <td class="value">Rs. {{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tax:</td>
                            <td class="value">Rs. {{ number_format($invoice->tax, 2) }}</td>
                        </tr>
                        <tr class="grand-total-row">
                            <td>TOTAL DUE:</td>
                            <td style="text-align: right;">Rs. {{ number_format($invoice->total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Payment Details & Terms -->
        <table class="details-table">
            <tr>
                <td class="details-col">
                    <div class="details-title">Payment Details</div>
                    <div class="bank-info-item">
                        <span class="bank-info-label">Account/Bank</span>
                        <span class="bank-info-value">{{ $invoice->payment_bank }}</span>
                    </div>
                    <div class="bank-info-item">
                        <span class="bank-info-label">Account Title</span>
                        <span class="bank-info-value">{{ $invoice->payment_account_title }}</span>
                    </div>
                    <div class="bank-info-item">
                        <span class="bank-info-label">IBAN/Account No.</span>
                        <span class="bank-info-value">{{ $invoice->payment_iban }}</span>
                    </div>
                </td>
                <td class="details-col-last">
                    <div class="details-title">Terms & Conditions</div>
                    <ol class="terms-list">
                        @foreach(explode("\n", $invoice->terms) as $term)
                            @if(trim($term))
                                <li>{{ preg_replace('/^\d+\.\s*/', '', trim($term)) }}</li>
                            @endif
                        @endforeach
                    </ol>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer-section">
            <div class="thank-you">THANK YOU FOR YOUR BUSINESS</div>
            <div class="company-name">ITechGB – Innovative Technologies Gilgit-Baltistan</div>
            <div class="company-tagline">Website Design • Web Development • Software Solutions</div>
        </div>

    </div>
</body>
</html>
