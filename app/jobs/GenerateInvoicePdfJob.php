<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class GenerateInvoicePdfJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $name
    ) {}

    public function handle(): void
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoiceNumber' => 'INV-' . now()->timestamp,
            'name' => $this->name,
        ]);

        $path = storage_path(
            'app/invoice-' . time() . '.pdf'
        );

        file_put_contents(
            $path,
            $pdf->output()
        );
        \Log::info('PDF saved at: ' . $path);

        Mail::raw(
            'Please find your invoice attached.',
            function ($message) use ($path) {
                $message->to($this->email)
                    ->subject('Invoice')
                    ->attach($path);
            }
        );
    }
}