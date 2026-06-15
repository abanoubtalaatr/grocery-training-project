<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>

<h1>Invoice #{{ $invoice->id }}</h1>

<p>Customer: {{ $invoice->customer_name }}</p>

<table width="100%" border="1">
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
    </tr>

    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ $item->price }}</td>
        </tr>
    @endforeach
</table>

<h3>Total: {{ $invoice->total }}</h3>

</body>
</html>