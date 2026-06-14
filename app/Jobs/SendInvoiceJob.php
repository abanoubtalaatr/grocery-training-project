<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $email,
        public string $name
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoiceNumber' => 'INV-' . now()->timestamp,
            'name' => $this->name,
        ]);
        Mail::raw('Please find your invoice attached.', function ($message) use ($pdf) {
            $message->to($this->email)
                ->subject('Test Invoice')
                ->attachData($pdf->output(), 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}
