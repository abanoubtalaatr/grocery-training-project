<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceJob implements ShouldQueue
{
    use Queueable;

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle(): void
    {
        $html = "
            <h1>Invoice</h1>
            <hr>
            <p>Invoice No: INV-" . rand(1000, 9999) . "</p>
            <p>Date: " . now()->format('Y-m-d') . "</p>
            <p>Total: 500 EGP</p>
        ";

        $pdf = Pdf::loadHTML($html);

        $directory = storage_path('app/invoices');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory . '/invoice-' . time() . '.pdf';

        $pdf->save($filePath);

        Log::info('Invoice generated successfully', [
            'path' => $filePath,
        ]);

        Mail::to($this->email)
            ->send(new InvoiceMail($filePath));
    }
}