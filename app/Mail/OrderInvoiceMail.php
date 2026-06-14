<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public Setting $settings;
    public string $currencySymbol;
    protected string $pdfData;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $pdfData)
    {
        $this->order = $order;
        $this->pdfData = $pdfData;
        $this->settings = Setting::getSettings();
        $this->currencySymbol = $this->settings->currency_symbol ?? '$';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Invoice for Order #' . $this->order->order_number)
            ->markdown('emails.orders.invoice')
            ->with([
                'order' => $this->order,
                'settings' => $this->settings,
                'currencySymbol' => $this->currencySymbol,
            ])
            ->attachData($this->pdfData, 'invoice-' . $this->order->order_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
