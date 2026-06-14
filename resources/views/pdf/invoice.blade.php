<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; margin: 8px 0; }
        .total { border-top: 2px solid #333; margin-top: 20px; padding-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice {{ $invoiceNumber }}</h1>
        <p>Date: {{ now()->format('Y-m-d') }}</p>
    </div>

    <div class="row"><span>Customer:</span> <span>{{ $name }}</span></div>
    <div class="row"><span>Description:</span> <span>Test invoice</span></div>
    <div class="row total"><span>Total:</span> <span>$1200</span></div>
</body>
</html>