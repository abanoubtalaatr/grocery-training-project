<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
   protected $email;
    protected $invoiceData;

    public function __construct($email, $invoiceData)
    {
        $this->email = $email;
        $this->invoiceData = $invoiceData;
    }

    public function handle()
    {

    $data = $this->invoiceData;

      
        $pdf = Pdf::loadView('pdf.invoice', $data);

        
         Mail::send([], [], function ($message) use ($data, $pdf) {
        $message->to($this->email)
                ->subject('Your Grocery Invoice #' . $data['id'])
                ->html('<h3>Hello, please find your invoice attached as a PDF.</h3>')
                ->attachData($pdf->output(), "invoice_" . $data['id'] . ".pdf", [
                    'mime' => 'application/pdf',
                ]);
    });

  
    Log::info("successfully send to  {$this->email}");
       
     
    }
}
