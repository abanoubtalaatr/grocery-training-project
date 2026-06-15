<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>

<h1>Invoice #{{ $invoice->id }}</h1>

<p>User: {{ $invoice->user?->name ?? 'Unknown User' }}</p>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
    </tr>

    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->qty }}</td>
        </tr>
    @endforeach
</table>

<h3>Total: {{ $invoice->total }}</h3>

</body>
</html>