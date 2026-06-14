<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>

<h1>Invoice</h1>

<p>Invoice No: {{ $invoice['invoice_no'] }}</p>
<p>Customer: {{ $invoice['customer'] }}</p>
<p>Amount: ${{ $invoice['amount'] }}</p>

</body>
</html>