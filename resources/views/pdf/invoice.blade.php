<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            background: #fff;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        table td {
            padding: 8px;
            vertical-align: top;
        }
        .header-table td {
            padding-bottom: 20px;
        }
        .header-table .title {
            font-size: 28px;
            line-height: 32px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .info-table {
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        .info-header {
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .items-table {
            margin-top: 20px;
        }
        .items-table th {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            font-weight: bold;
            color: #374151;
            padding: 10px 8px;
            text-align: left;
        }
        .items-table td {
            border-bottom: 1px solid #f3f4f6;
            padding: 12px 8px;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            float: right;
            margin-top: 20px;
        }
        .totals-table td {
            padding: 6px 8px;
        }
        .totals-table tr.total-row td {
            border-top: 2px solid #e5e7eb;
            font-weight: bold;
            font-size: 16px;
            color: #1e3a8a;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            clear: both;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: capitalize;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td class="title">
                    {{ $settings->site_name ?? 'Grocery Store' }}
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 20px; font-weight: bold; color: #4b5563;">INVOICE</div>
                    Invoice #: <strong>{{ $order->order_number }}</strong><br>
                    Date: {{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}<br>
                    Status: <span class="badge badge-success">{{ $order->status }}</span>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <div class="info-header">From:</div>
                    <strong>{{ $settings->site_name ?? 'Grocery Store' }}</strong><br>
                    {{ $settings->store_address ?? $settings->address ?? '123 Grocery Lane' }}<br>
                    Phone: {{ $settings->phone ?? $settings->support_phone ?? 'N/A' }}<br>
                    Email: {{ $settings->email ?? $settings->support_email ?? 'N/A' }}
                </td>
                <td style="width: 50%;">
                    <div class="info-header">Bill To:</div>
                    <strong>{{ $order->user->full_name ?? $order->user->username }}</strong><br>
                    @if($order->address)
                        {{ $order->address->full_address }}<br>
                        Phone: {{ $order->address->phone ?? $order->user->phone ?? 'N/A' }}
                    @else
                        Delivery Type: {{ ucfirst($order->delivery_type) }}<br>
                        Phone: {{ $order->user->phone ?? 'N/A' }}
                    @endif
                    Email: {{ $order->user->email }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="width: 15%; text-align: center;">Quantity</th>
                    <th style="width: 20%; text-align: right;">Unit Price</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->meal->title ?? 'Meal Item' }}</strong>
                        </td>
                        <td style="text-align: center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="text-align: right;">
                            {{ $currencySymbol }}{{ number_format($item->unit_price, 2) }}
                        </td>
                        <td style="text-align: right;">
                            {{ $currencySymbol }}{{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if((float)$order->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td style="text-align: right; color: #b91c1c;">-{{ $currencySymbol }}{{ number_format($order->discount, 2) }}</td>
                </tr>
            @endif
            @if((float)$order->tax > 0)
                <tr>
                    <td>Tax:</td>
                    <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</td>
                </tr>
            @endif
            @if((float)$order->shipping_fee > 0)
                <tr>
                    <td>Shipping Fee:</td>
                    <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            Thank you for shopping with {{ $settings->site_name ?? 'Grocery Store' }}!<br>
            If you have any questions about this invoice, please contact our support at {{ $settings->support_email ?? $settings->email ?? 'support@grocery.app' }}
        </div>
    </div>
</body>
</html>
