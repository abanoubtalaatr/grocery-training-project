<?php

namespace App\Jobs;

use App\Mail\SendInvoiceMail;
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

        $path = storage_path(
            'app/private/invoices/invoice-' . time() . '.pdf'
        );

        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $pdf->output());

        Mail::raw(
            'Please find your invoice attached.',
            function ($message) use ($path) {
                $message->to($this->email)
                    ->subject('Test Invoice')
                    ->attach($path);
            }
        );
    }
}
