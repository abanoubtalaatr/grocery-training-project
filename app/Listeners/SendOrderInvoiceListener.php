<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\OrderInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SendOrderInvoiceListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order->load(['user', 'items.meal', 'address']);

        if ($order->user && $order->user->email) {
            $pdf = Pdf::loadView('emails.invoice_pdf', compact('order'))->output();

            Mail::to($order->user->email)
                ->send(new OrderInvoiceMail($order, $pdf));
        }
    }
}
