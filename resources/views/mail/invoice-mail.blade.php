<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoiceNumber }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 40px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .message {
            line-height: 1.6;
            margin-bottom: 32px;
            color: #475569;
        }
        .invoice-details {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 32px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }
        .value {
            font-weight: 500;
            color: #1e293b;
        }
        .action-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .footer {
            padding: 24px;
            text-align: center;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
        .attachment-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #2563eb;
            font-weight: 500;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Grocery Store</h1>
            <p>Invoice #{{ $invoiceNumber }}</p>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $customerName }},</div>
            <div class="message">
                Thank you for your recent purchase from Grocery! We appreciate your business and hope you enjoy your items. Your order has been processed successfully.
            </div>
            
            <div class="invoice-details">
                <div class="detail-row">
                    <span class="label">Date:</span>
                    <span class="value">{{ $date }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Invoice No:</span>
                    <span class="value">INV-{{ $invoiceNumber }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Customer:</span>
                    <span class="value">{{ $customerName }}</span>
                </div>
            </div>

            <div class="message">
                For your convenience, we have attached the full PDF invoice to this email. Please keep it for your records.
            </div>

            <div class="attachment-note">
                📎 You will find the PDF invoice attached below.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Grocery Store. All rights reserved.<br>
            If you have any questions, please contact our support team.
        </div>
    </div>
</body>
</html>
