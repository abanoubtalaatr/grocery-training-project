<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;

class SendInvoiceMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 10;

    public function __construct(
        public string $email = 'samiralsaied07@gmail.com'
    ) {}

    public function handle(): void
    {
        $invoice = [
            'invoice_no' => 100,
            'customer'   => 'Ahmed Hany',
            'amount'     => 8000,
        ];

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        $fileName = 'invoice_' . time() . '.pdf';
        $filePath = storage_path("app/public/$fileName");

        $pdf->save($filePath);

        Mail::to($this->email)
            ->send(new InvoiceMail($filePath));

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error('Failed sending invoice', [
            'error' => $exception?->getMessage(),
            'email' => $this->email,
        ]);
    }
}
