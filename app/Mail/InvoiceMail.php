<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        private string $pdfContent
    ) {}

    public function build()
    {
        return $this->subject('Your Invoice for Order #'.$this->order->id)
                    ->markdown('emails.invoice')
                    ->with([
                        'order' => $this->order,
                    ]);
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                'invoice-'.$this->order->id.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
