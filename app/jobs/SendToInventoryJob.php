<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendToInventoryJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $backoff = 10;
    public int $timeout = 60;

    public function __construct()
    {
    }


    public function failed(Exception $exception)
    {
    Log::error('Email sending failed: ' . $exception->getMessage());
        }



    public function handle(): void
    {
        sleep(10);

        Log::info('Sending to inventory');
    }


     public function backoff(): array
    {
        return [10, 30, 60];
    }
}