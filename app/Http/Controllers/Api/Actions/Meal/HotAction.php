<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class HotAction
{
    public function handle()
    {
        return Meal::with('category')
            ->available()
            ->hot()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
