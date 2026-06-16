<?php

namespace App\Http\Actions\Api\Contact;

use App\Mail\ContactAutoReply;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SubmitContactMessageAction
{
    public function __construct(
        private DetectSpamAction $spamAction
    ) {}

    public function execute(
        array $data,
        string $ip,
        ?string $userAgent
    ): ContactMessage {

        if (
            $this->spamAction->execute(
                $data['message']
            )
        ) {
            throw ValidationException::withMessages([
                'message' => [
                    'Your message appears to be spam.'
                ]
            ]);
        }

        $contactMessage =
            ContactMessage::create([
                ...$data,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);

        try {

            Mail::to(
                config(
                    'mail.admin_email'
                )
            )->send(
                new ContactMessageReceived(
                    $contactMessage
                )
            );

            Mail::to(
                $contactMessage->email
            )->send(
                new ContactAutoReply(
                    $contactMessage
                )
            );

        } catch (\Throwable $e) {

            Log::error(
                'Contact email failed',
                [
                    'message'
                        => $e->getMessage()
                ]
            );
        }

        return $contactMessage;
    }
}