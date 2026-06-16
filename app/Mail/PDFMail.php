<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PDFMail extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(
        public string $pdfPath
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PDF Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.pdf',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('invoice.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
