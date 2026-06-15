<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  public string $customerName;
  public string $invoiceNumber;

  /**
   * Create a new message instance.
   */
  public function __construct(string $customerName, string $invoiceNumber)
  {
    $this->customerName = $customerName;
    $this->invoiceNumber = $invoiceNumber;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Invoice Mail',

    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'mail.invoice-mail',
      with: [
        'customerName' => $this->customerName,
        'invoiceNumber' => $this->invoiceNumber,
        'date' => now()->format('M d, Y'),
      ],
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, Attachment>
   */
  public function attachments(): array
  {
    return [
      Attachment::fromPath(public_path('InvoicePDfs/invoice_Aaron Hawkins_37425.pdf'))
    ];
  }
}
