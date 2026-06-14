<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $orderId
    ) {}

    public function handle(): void
    {
        $order = Order::with(['user', 'items.meal'])->find($this->orderId);

        if (! $order) {
            Log::warning('Invoice email job skipped: order not found', [
                'order_id' => $this->orderId,
            ]);

            return;
        }

        $email = $order->user?->email;

        if (! $email) {
            Log::warning('Invoice email job skipped: customer email missing', [
                'order_id' => $order->id,
            ]);

            return;
        }

        $pdf = Pdf::loadView('pdfs.invoice', [
            'order' => $order,
        ]);

        Mail::to($email)->send(new InvoiceMail($order, $pdf->output()));
    }
}
