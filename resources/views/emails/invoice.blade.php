<!DOCTYPE html>
<html>

<body>

    <h2>Hello {{ $invoice->customer }}</h2>

    <p>Please find your invoice attached.</p>

    <p>Invoice No: {{ $invoice->invoice_no }}</p>

    <p>Amount: ${{ $invoice->amount }}</p>

</body>

</html>