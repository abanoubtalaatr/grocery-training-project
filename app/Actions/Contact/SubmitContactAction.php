<?php
namespace App\Actions\Contact;

use App\Mail\ContactMessageReceived;
use App\Mail\ContactAutoReply;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SubmitContactAction
{
    public function execute(array $data): ContactMessage
    {
        // 1. فحص السبام (Spam Detection)
        if ($this->isSpam($data['message'])) {
            throw ValidationException::withMessages([
                'message' => ['Your message appears to be spam.'],
            ]);
        }

        // 2. تخزين الرسالة في قاعدة البيانات
        $contactMessage = ContactMessage::create($data);

        // 3. إرسال رسائل البريد الإلكتروني في الخلفية مع تجنب توقف العملية
        try {
            Mail::to(config('mail.admin_email', 'admin@example.com'))->send(new ContactMessageReceived($contactMessage));
            Mail::to($data['email'])->send(new ContactAutoReply($contactMessage));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return $contactMessage;
    }

    private function isSpam(string $message): bool
    {
        $spamKeywords = ['viagra', 'casino', 'loan', 'debt', 'free money', 'work from home', 'make money fast', 'click here'];
        $message = strtolower($message);

        foreach ($spamKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }
}