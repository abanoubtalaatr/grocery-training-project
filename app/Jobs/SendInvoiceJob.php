<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $customerName,
        public float $amount,
    ) {
    }

    public function handle(): void
    {
        $html = "
            <h1>Invoice</h1>
            <hr>
            <p>Customer: {$this->customerName}</p>
            <p>Amount: {$this->amount} EGP</p>
        ";

        $pdf = Pdf::loadHTML($html);

        $directory = storage_path('app/invoice');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory . '/invoice-' . time() . '.pdf';

        $pdf->save($filePath);

        Log::info('Invoice generated successfully', [
            'path' => $filePath,
        ]);

        Mail::to($this->email)
            ->send(new InvoiceMail($filePath));

        Log::info('Invoice email sent successfully');
    }
}