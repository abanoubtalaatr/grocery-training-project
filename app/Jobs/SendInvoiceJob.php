<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $orderData
    ) {}

    public function handle(): void
    {
        $pdf = Pdf::loadView('invoices.invoice', $this->orderData);
        
        Mail::send([], [], function ($message) use ($pdf) {
            $message->to($this->orderData['email'])
                ->subject('Your Invoice')
                ->attachData($pdf->output(), 'invoice.pdf');
        });
    }
}