@php
    $customer = $order->user;
    $customerName = $customer?->full_name ?? $customer?->username ?? 'Customer';
    $orderDate = $order->placed_at ?? $order->created_at;
    $money = fn ($value) => '$'.number_format((float) $value, 2);
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number ?? $order->id }}</title>
    <style>
        body {
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 32px;
        }

        h1 {
            font-size: 26px;
            margin: 0 0 8px;
        }

        h2 {
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            margin: 28px 0 12px;
            padding-bottom: 6px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .summary td {
            border-bottom: none;
            padding: 4px 8px;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <h1>Invoice</h1>
    <div class="muted">Order #{{ $order->order_number ?? $order->id }}</div>

    <h2>Order Information</h2>
    <table>
        <tr>
            <td><strong>Order ID</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        @if ($order->order_number)
            <tr>
                <td><strong>Order Number</strong></td>
                <td>{{ $order->order_number }}</td>
            </tr>
        @endif
        <tr>
            <td><strong>Customer</strong></td>
            <td>{{ $customerName }}</td>
        </tr>
        @if ($customer?->email)
            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $customer->email }}</td>
            </tr>
        @endif
        @if ($orderDate)
            <tr>
                <td><strong>Order Date</strong></td>
                <td>{{ $orderDate->format('F j, Y g:i A') }}</td>
            </tr>
        @endif
        <tr>
            <td><strong>Status</strong></td>
            <td>{{ $order->status_description ?? ucfirst(str_replace('_', ' ', $order->status)) }}</td>
        </tr>
        @if ($order->payment_method)
            <tr>
                <td><strong>Payment Method</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
            </tr>
        @endif
    </table>

    @if ($order->items->isNotEmpty())
        <h2>Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Quantity</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->meal?->title ?? 'Item #'.$item->id }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">{{ $money($item->unit_price) }}</td>
                        <td class="right">{{ $money($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Totals</h2>
    <table class="summary">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ $money($order->subtotal) }}</td>
        </tr>
        <tr>
            <td>Tax</td>
            <td class="right">{{ $money($order->tax) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td class="right">{{ $money($order->discount) }}</td>
        </tr>
        <tr>
            <td>Shipping Fee</td>
            <td class="right">{{ $money($order->shipping_fee ?? 0) }}</td>
        </tr>
        <tr>
            <td><strong>Order Total</strong></td>
            <td class="right"><strong>{{ $money($order->total) }}</strong></td>
        </tr>
    </table>
</body>
</html>
