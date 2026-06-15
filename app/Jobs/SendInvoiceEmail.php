<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendInvoiceEmail implements ShouldQueue
{
    use Queueable;

    protected int $invoiceId;

    public function __construct(int $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function handle(): void
    {
        $invoice = Invoice::findOrFail($this->invoiceId);

        $pdf = Pdf::loadView(
            'pdf.invoice',
            compact('invoice')
        );

        $fileName = 'invoice_' . $invoice->id . '.pdf';

        $filePath = storage_path(
            'app/public/' . $fileName
        );

        $pdf->save($filePath);

        Mail::to($invoice->email)
            ->send(
                new InvoiceMail(
                    $filePath,
                    $invoice
                )
            );
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error(
            'Failed sending invoice: ' .
                $exception?->getMessage()
        );
    }
}
