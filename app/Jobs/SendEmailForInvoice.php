<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SendEmailForInvoice implements ShouldQueue
{
    use Queueable, SerializesModels;

    // بنعرف متغير واحد بس لليوزر
    public $user;

    /**
     * الـ Construct بيستقبل حاجة واحدة بس: موديل اليوزر
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * تشغيل الـ Job في الخلفية
     */
    public function handle()
    {
        $user = $this->user;

        if (!$user) {
            return;
        }

        // 🚀 هنا السحر: بنباصي موديل اليوزر كامل لصفحة الـ Blade عشان نطبع معلوماته جوه الـ PDF
        $pdf = Pdf::loadView('emails.user_info_pdf', ['user' => $user]);

        // إرسال الإيميل وإرفاق ملف معلومات اليوزر
        Mail::send('emails.invoice_message', ['user' => $user], function($message) use ($user, $pdf) {
            $message->to($user->email)
                    ->subject('ملف معلومات الحساب الخاص بك 💾');
            
            // إرفاق الـ PDF باسم يخص اليوزر
            $message->attachData($pdf->output(), 'user-profile-' . $user->name . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        });

        Log::info("تم إرسال ملف البيانات بنجاح إلى: {$user->email}");
    }
}