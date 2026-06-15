<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * مسار ملف الفاتورة
     */
    public function __construct(
        public string $filePath
    ) {
    }

    /**
     * عنوان الإيميل
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mail',
        );
    }

    /**
     * محتوى الإيميل
     */
    public function content(): Content
    {
        return new Content(
            view: 'invoice.invoice',
        );
    }

    /**
     * مرفقات الإيميل
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filePath)
                ->as('invoice.pdf')
                ->withMime('application/pdf'),
        ];
    }
}