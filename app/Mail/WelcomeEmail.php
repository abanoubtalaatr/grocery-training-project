<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    protected $pdfFileName;


    public function __construct(User $user, string $pdfFileName)
    {
        $this->user = $user;
        $this->pdfFileName = $pdfFileName;
    }

  
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'مرحباً بك في Grocery - تم إنشاء حسابك بنجاح',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', 
            with: [
                'name' => $this->user->name, 
            ]
        );
    }

  
    public function attachments(): array
    {
        $filePath = Storage::disk('public')->path($this->pdfFileName);

        return [
            Attachment::fromPath($filePath)
                ->as('Welcome_Document.pdf') 
                ->withMime('application/pdf'), 
        ];
    }
}
