<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Mail\Mailable;

class InvoiceMail extends Mailable
{
    public string $pdfPath;
    public Invoice $invoice;

    public function __construct(
        string $pdfPath,
        Invoice $invoice
    ) {
        $this->pdfPath = $pdfPath;
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject('Invoice')
            ->view('emails.invoice')
            ->with([
                'invoice' => $this->invoice
            ])
            ->attach($this->pdfPath, [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
