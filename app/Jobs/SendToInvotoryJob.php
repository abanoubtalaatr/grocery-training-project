<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendToInvotoryJob implements ShouldQueue
{
  use Queueable;

  public int $tries = 3;
  public int $backoff = 10;
  public int $timeout = 120;

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
    Log::info('Sending to inventory');

    // Send to inventory and assign an email
    Mail::to('example@example.com')->send(new InvoiceMail());
  }

  /**
   * Handle a job failure.
   */
  public function failed(): void
  {
    Log::error('Failed to send to inventory');
  }

  /**
   * Handle a job failure.
   */
  public function failedIn($seconds): void
  {
    Log::error('Failed to send to inventory in ' . $seconds . ' seconds');
  }
}
