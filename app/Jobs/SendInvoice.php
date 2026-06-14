<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;

class SendInvoice implements ShouldQueue
{
    use Queueable;

    protected $timeout = 60;

    protected $tries = 2;

    protected $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = Blade::render('pdf.invoice');

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        Mail::to('test@app.com')->send(new \App\Mail\Invoice($pdfContent));
    }
}
