<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // 👈 اتأكد إنك ضفت السطر ده عشان نستخدم كلاس الإرسال
use Barryvdh\DomPDF\Facade\Pdf;      // 👈 وضفنا السطر ده عشان نولد الـ PDF

class SendEmail implements ShouldQueue
{
    use Queueable;

    // 1. التعريف هنا بنفس حالة الأحرف
    public $Order_invoice;
    public $userid;

    /**
     * Create a new job instance.
     */
    public function __construct($Order_invoice, $userid)
    {
        // 2. التخزين هنا بنفس حالة الأحرف
        $this->Order_invoice = $Order_invoice;
        $this->userid = $userid;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $user = User::find($this->userid);
        // 3. النداء هنا بنفس حالة الأحرف
        $Order_invoice = $this->Order_invoice;
        
    }
}