<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $pdfPath
    ) {}

    public function build()
    {
        return $this->subject('Welcome to '.config('app.name'))
            ->view('emails.welcome-user')
            ->with([
                'user' => $this->user,
            ])
            ->attach($this->pdfPath, [
                'as' => 'welcome-user-'.$this->user->id.'.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
