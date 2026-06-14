<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvoiceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $invoice = $this->demoInvoice();
        Log::info('Sending invoice to ' . $this->email);
        Log::info($invoice);
        Mail::to($this->email)->send(new InvoiceMail($invoice, $this->email));
    }

    private function demoInvoice() {
        $invoice = [
            'invoice_number' => 'INV-00000001',
            'date' => now(),
            'customer' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'items' => [
                ['name' => 'Organic Milk', 'qty' => 2, 'price' => 4.99],
                ['name' => 'Whole Wheat Bread', 'qty' => 1, 'price' => 3.49],
            ],
            'subtotal' => 13.47,
            'tax' => 1.08,
            'total' => 14.55,
        ];

        return $invoice;
    }
}
