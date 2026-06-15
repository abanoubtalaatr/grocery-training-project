<x-mail::message>
# Order Confirmed!

Dear {{ $order->user->full_name ?? $order->user->username }},

Thank you for shopping with us! We are pleased to confirm that your order **{{ $order->order_number }}** has been successfully placed and is now being processed.

We have attached your PDF invoice to this email for your records.

### Order Summary:
- **Order Number:** {{ $order->order_number }}
- **Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
- **Total Amount:** {{ $currencySymbol }}{{ number_format($order->total, 2) }}
- **Delivery Type:** {{ ucfirst($order->delivery_type) }}

@if($order->address)
**Delivery Address:**
{{ $order->address->full_address }}
@endif

If you have any questions or need to make changes to your order, please reach out to our support team immediately.

Thanks,<br>
The {{ $settings->site_name ?? 'Grocery Store' }} Team
</x-mail::message>
