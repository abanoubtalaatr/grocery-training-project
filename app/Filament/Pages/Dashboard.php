<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Redirect to the custom admin-dashboard page.
     */
    public function mount(): void
    {
        redirect()->to('/admin-dashboard');
    }
}
