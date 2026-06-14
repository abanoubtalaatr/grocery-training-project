<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendToInventoryJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

    }

    public function handle(): void
    {
    
        \Log::info('Sending order to inventory system', [
            'order_id' => $this->order->id,
            'items' => $this->order->items->toArray(),
            'total' => $this->order->total,
        ]);

        // Here you would implement the actual logic to send the order data to the inventory system.
    }
}
