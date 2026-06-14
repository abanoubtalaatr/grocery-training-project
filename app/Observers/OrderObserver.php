<?php

namespace App\Observers;

use App\Jobs\SendOrderInvoiceJob;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if ($order->status === 'placed') {
            SendOrderInvoiceJob::dispatch($order);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === 'placed') {
            SendOrderInvoiceJob::dispatch($order);
        }
    }
}
