<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvoiceMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
{
    Log::info('📄 Invoice Job Started');

    $invoice = [
        'invoice_number' => rand(1000, 9999),
        'customer' => 'Test User',
        'total' => 250,
    ];

    Log::info('Invoice Generated', $invoice);

    Log::info('📄 Invoice Job Finished');
}
}