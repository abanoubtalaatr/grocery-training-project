<?php
namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendInvoiceMailJob implements ShouldQueue
{
    use Queueable;

    protected string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function handle(): void
    {
        // بيانات الفاتورة
        $invoice = [
            'invoice_no' => 'INV-1001',
            'customer' => 'Ahmed',
            'amount' => 1500,
        ];

        // إنشاء PDF
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        $fileName = 'invoice_' . time() . '.pdf';
        $filePath = storage_path('app/public/' . $fileName);

        $pdf->save($filePath);

        // إرسال الإيميل مع المرفق
        Mail::to($this->email)
            ->send(new InvoiceMail($filePath));
    }

    public function failed(?\Throwable $exception): void
    {
        \Log::error('Failed sending invoice: ' . $exception?->getMessage());
    }
}