@php
    $customer = $order->user;
    $customerName = $customer?->full_name ?? $customer?->username;
    $orderLabel = $order->order_number ?? $order->id;
@endphp

<x-mail::message>
# Your Invoice

Hello {{ $customerName ?? 'there' }},

Your invoice for order #{{ $orderLabel }} is attached to this email as a PDF.

Thank you for shopping with {{ config('app.name') }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
