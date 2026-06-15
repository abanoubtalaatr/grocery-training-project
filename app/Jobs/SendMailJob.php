<?php

namespace App\Jobs;

use App\Mail\GeneralMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    public int $tries = 3;

    
    public int $timeout = 60;

    /**
     * @param string      $email        إيميل المستقبل
     * @param string      $subject      عنوان الميل
     * @param string      $body         نص الميل
     * @param string|null $invoicePath  المسار الكامل للفاتورة  (null = بدون فاتورة)
     * @param string      $invoiceName  اسم الملف في الميل
     */
    public function __construct(
        private string  $email,
        private string  $subject,
        private string  $body,
        private ?string $invoicePath = null,
        private string  $invoiceName = 'invoice.pdf',
    ) {}

    /**
     * 
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new GeneralMail(
            subject_title: $this->subject,
            body:          $this->body,
            invoicePath:   $this->invoicePath,
            invoiceName:   $this->invoiceName,
        ));
    }

    /**
     * 
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendMailJob Failed', [
            'email'   => $this->email,
            'subject' => $this->subject,
            'error'   => $exception->getMessage(),
        ]);
    }
}