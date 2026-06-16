<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; // أضفنا دي عشان الـ Slug
use Barryvdh\DomPDF\Facade\Pdf;

class SendEmailForInvoice implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            $user = $this->user;

            // 1. توليد الـ PDF
            $pdf = Pdf::loadView('emails.user_info_pdf', ['user' => $user]);

            // 2. استخدام Str::slug لتجنب مشاكل الأسماء العربية أو المسافات في اسم الملف
            $fileName = 'user-profile-' . Str::slug($user->name) . '.pdf';

            // 3. إرسال الإيميل
            Mail::send('emails.invoice_message', ['user' => $user], function($message) use ($user, $pdf, $fileName) {
                $message->to($user->email)
                        ->subject('ملف معلومات الحساب الخاص بك 💾')
                        ->attachData($pdf->output(), $fileName, [
                            'mime' => 'application/pdf',
                        ]);
            });

            Log::info("تم إرسال ملف البيانات بنجاح إلى: {$user->email}");

        } catch (\Exception $e) {
            // تسجيل الخطأ الحقيقي في اللوج عشان لو فشلت نعرف السبب فوراً
            Log::error("فشل إرسال الإيميل للمستخدم {$this->user->id}: " . $e->getMessage());
            
            // إعادة رمي الخطأ عشان الـ Job تدخل في قائمة Failed ونقدر نعملها retry
            throw $e; 
        }
    }
}