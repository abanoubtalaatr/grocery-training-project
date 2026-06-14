<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order['id'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            margin: 0;
        }
        .customer-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #eee;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <h2>Invoice</h2>
            </div>
            <div>
                Order #: {{ $order['id'] }}<br>
                Date: {{ date('Y-m-d') }}
            </div>
        </div>

        <div class="customer-details">
            <strong>Bill To:</strong><br>
            {{ $order['customer_name'] }}<br>
            {{ $order['customer_email'] ?? 'N/A' }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: ${{ number_format($order['total_price'], 2) }}
        </div>
    </div>
</body>
</html>
