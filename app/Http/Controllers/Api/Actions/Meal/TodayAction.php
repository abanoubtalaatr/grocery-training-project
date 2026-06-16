<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class TodayAction
{
    public function handle()
    {
        return Meal::with('category')
            ->available()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
