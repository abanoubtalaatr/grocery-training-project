<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderId;
    public $orderNumber;
    public $customerName;
    public $customerEmail;
    public $total;
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct($orderId, $orderNumber, $customerName, $customerEmail, $total, $items = [])
    {
        $this->orderId = $orderId;
        $this->orderNumber = $orderNumber;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->total = $total;
        $this->items = $items;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice #' . $this->orderNumber,
            from: 'noreply@grocery.app'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'orderId' => $this->orderId,
                'orderNumber' => $this->orderNumber,
                'customerName' => $this->customerName,
                'total' => $this->total,
                'items' => $this->items,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
