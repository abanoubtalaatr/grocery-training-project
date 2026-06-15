<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #eee; }
        .header { border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; }
        .order-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .totals { text-align: right; }
        .totals div { margin-bottom: 5px; }
        .footer { margin-top: 30px; font-size: 0.9em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Invoice</h1>
            <p>Thank you for your order, {{ $order->user->name ?? 'Customer' }}!</p>
        </div>

        <div class="order-info">
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->meal->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</div>
            <div><strong>Tax:</strong> ${{ number_format($order->tax, 2) }}</div>
            <div><strong>Shipping Fee:</strong> ${{ number_format($order->shipping_fee, 2) }}</div>
            <div><strong>Discount:</strong> -${{ number_format($order->discount, 2) }}</div>
            <div style="font-size: 1.2em; color: #28a745;"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</div>
        </div>

        @if ($order->address)
            <div style="margin-top: 20px;">
                <h3>Delivery Address</h3>
                <p>
                    {{ $order->address->full_name }}<br>
                    {{ $order->address->full_address }}<br>
                    {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}
                </p>
            </div>
        @endif

        <div class="footer">
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
