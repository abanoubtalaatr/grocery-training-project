<?php

namespace App\Jobs;

use App\Mail\SendInvoiceMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvoiceJob implements ShouldQueue
{
    use Queueable;

    private $orderId;
    private $orderNumber;
    private $customerName;
    private $customerEmail;
    private $total;
    private $items;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId = null, $orderNumber = null, $customerName = null, $customerEmail = null, $total = 0, $items = [])
    {
        $this->orderId = $orderId;
        $this->orderNumber = $orderNumber;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->total = $total;
        $this->items = $items;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->customerEmail)->send(
                new SendInvoiceMail(
                    $this->orderId,
                    $this->orderNumber,
                    $this->customerName,
                    $this->customerEmail,
                    $this->total,
                    $this->items
                )
            );

            Log::info('Invoice email sent', [
                'order_id' => $this->orderId,
                'order_number' => $this->orderNumber,
                'customer_email' => $this->customerEmail,
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send invoice email', [
                'order_id' => $this->orderId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
