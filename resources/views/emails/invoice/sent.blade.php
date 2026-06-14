<x-mail::message>
# Your Invoice {{ $invoice['invoice_number'] }}

Hello,

Please find your invoice attached to this email.

**Invoice Number:** {{ $invoice['invoice_number'] }}  
**Date:** {{ $invoice['date']->format('Y-m-d') }}  
**Total:** ${{ number_format($invoice['total'], 2) }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
