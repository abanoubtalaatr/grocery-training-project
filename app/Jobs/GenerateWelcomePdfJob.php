<?php

namespace App\Jobs;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateWelcomePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $name = $this->user->name; 
        $email = $this->user->email; 
        $pdf = Pdf::loadView('pdf.welcome', [
            'name' => $name,
            'email' => $email,
        ]);

        $fileName = 'welcome_user_' . $this->user->id . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());
    }
}
