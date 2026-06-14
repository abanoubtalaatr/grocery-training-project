<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendToEmailJob implements ShouldQueue
{
  use Queueable;

  public int $tries = 2;
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
    Log::info('Sending to Email');

    // Send to inventory and assign an email
    $user_email = 'ahmadosama.2011@gmail.com';
    Mail::to($user_email)->send(new InvoiceMail('Ahmed', '37425'));
  }

  /**
   * Handle a job failure.
   */
  // public function failed(): void
  // {
  //   Log::error('Failed to send to email');
  // }

  public function failed(Throwable $exception): void
  {
    Log::error('Failed to send email: ' . $exception->getMessage());
  }
}
