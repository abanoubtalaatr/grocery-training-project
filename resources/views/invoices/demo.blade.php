<h1>Invoice {{ $invoice['invoice_number'] }}</h1>
<p>Date: {{ $invoice['date']->format('Y-m-d') }}</p>
<p>Customer: {{ $invoice['customer']['name'] }}</p>

<table>
    @foreach ($invoice['items'] as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ $item['qty'] }}</td>
            <td>${{ number_format($item['price'], 2) }}</td>
        </tr>
    @endforeach
</table>

<p>Total: ${{ number_format($invoice['total'], 2) }}</p>
