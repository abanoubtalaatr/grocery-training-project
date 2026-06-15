<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateInvoicePdfAndSendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public int $invoiceId) {}

    public function handle(): void
    {
        $invoice = Invoice::with(['user', 'items'])
            ->findOrFail($this->invoiceId);

        // 🚨 حماية أساسية
        if (!$invoice->user || !$invoice->user->email) {
            $invoice->update(['status' => 'failed']);
            throw new \Exception("Missing user or email for invoice {$invoice->id}");
        }

        try {
            // 📄 Generate PDF
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('pdf.invoice', [
                'invoice' => $invoice
            ]);

            // 💾 Save PDF
            $path = "invoices/invoice-{$invoice->id}.pdf";
            Storage::put($path, $pdf->output());

            // 📧 Send Email
            Mail::to($invoice->user->email)
                ->send(new InvoiceMail(
                    $invoice,
                    storage_path("app/$path")
                ));

            // ✅ Update DB
            $invoice->update([
                'status' => 'sent',
                'pdf_path' => $path
            ]);

        } catch (\Throwable $e) {

            // ❌ Fail state
            $invoice->update(['status' => 'failed']);

            // rethrow so queue marks it failed
            throw $e;
        }
    }
}