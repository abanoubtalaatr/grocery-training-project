<?php

namespace App\Mail;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PDFMail extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PDF Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pdf',
        );
    }
}
