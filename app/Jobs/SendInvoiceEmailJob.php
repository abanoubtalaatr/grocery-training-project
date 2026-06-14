<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @param mixed $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting SendInvoiceEmailJob for Order ID: {$this->order['id']}");

        // For testing purposes: simulate a failure if the flag is set
        if (!empty($this->order['simulate_failure'])) {
            throw new \Exception('Test failure from Postman: Simulated failure for testing the queue system.');
        }

        // Generate PDF using barryvdh/laravel-dompdf
        $pdf = Pdf::loadView('invoice', ['order' => $this->order]);
        
        // Output the PDF to a string (could also be saved to disk)
        $pdfContent = $pdf->output();

        // Simulate sending an email
        Log::info("Simulating email send for Order ID: {$this->order['id']}");
        Log::info("Customer: {$this->order['customer_name']}");
        Log::info("Total: \${$this->order['total_price']}");
        Log::info("PDF Attachment Size: " . strlen($pdfContent) . " bytes");

        Log::info("SendInvoiceEmailJob completed successfully.");
    }
}
