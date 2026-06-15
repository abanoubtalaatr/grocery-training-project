<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param string      $subject_title  عنوان الميل
     * @param string      $body           نص الميل
     * @param string|null $invoicePath    المسار الكامل للفاتورة (اختياري)
     * @param string      $invoiceName    اسم الملف اللي هيظهر في الميل
     */
    public function __construct(
        public string  $subject_title,
        public string  $body,
        public ?string $invoicePath = null,
        public string  $invoiceName = 'invoice.pdf',
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject_title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.general',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if (! $this->invoicePath) {
            return [];
        }

        return [
            Attachment::fromPath($this->invoicePath)
                      ->as($this->invoiceName)
                      ->withMime('application/pdf'),
        ];
    }
}
