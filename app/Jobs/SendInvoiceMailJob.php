<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvoiceMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $invoiceData;

    public function __construct($email, $invoiceData)
    {
        $this->email = $email;
        $this->invoiceData = $invoiceData;
    }

    public function handle(): void
{
    Log::info('📧 Invoice Job Started');

    $html = view('emails.invoice', $this->invoiceData)->render();

    $pdf = Pdf::loadHTML($html);
    $pdfContent = $pdf->output();

    Log::info('PDF Generated');

    Mail::raw('Your invoice is attached.', function ($message) use ($pdfContent) {
        $message->to($this->email)
                ->subject('Invoice PDF')
                ->attachData($pdfContent, 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);
    });

    Log::info('Email Sent with PDF');

    Log::info('📧 Invoice Job Finished');
}
}