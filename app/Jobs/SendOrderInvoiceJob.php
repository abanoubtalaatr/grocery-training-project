<?php

namespace App\Jobs;

use App\Mail\OrderInvoiceMail;
use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load relationships needed for the invoice
        $this->order->load(['user', 'items.meal', 'address']);

        // Check if there is an email address to send to
        if (!$this->order->user || !$this->order->user->email) {
            return;
        }

        $settings = Setting::getSettings();
        $currencySymbol = $settings->currency_symbol ?? '$';

        // Generate PDF
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $this->order,
            'settings' => $settings,
            'currencySymbol' => $currencySymbol,
        ]);

        $pdfContent = $pdf->output();

        // Send Email
        Mail::to($this->order->user->email)->send(
            new OrderInvoiceMail($this->order, $pdfContent)
        );
    }
}
