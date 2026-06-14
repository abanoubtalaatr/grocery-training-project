<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $id }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .invoice-box {
            padding: 30px;
            border: 1px solid #eee;
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h2>GROCERY STORE INVOICE</h2>
        <p><strong>Invoice ID:</strong> {{ $id }}</p>
        <p><strong>Total Amount:</strong> ${{ $total }}</p>
        <p>Thank you for shopping with us!</p>
    </div>
</body>

</html>
