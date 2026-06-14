<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendInventoryJob implements ShouldQueue
{
    use Queueable;

    private $mealId;
    private $quantity;
    private $status;

    /**
     * Create a new job instance.
     */
    public function __construct($mealId = null, $quantity = 0, $status = 'pending')
    {
        $this->mealId = $mealId;
        $this->quantity = $quantity;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Sending inventory update', [
            'meal_id' => $this->mealId,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'timestamp' => now()
        ]);

        // Add your inventory update logic here
        // Example: Update meal stock, notify warehouse, etc.
    }
}
