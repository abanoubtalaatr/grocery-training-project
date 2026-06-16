<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class BestSellsAction
{
    public function handle(int $limit = 10)
    {
        return Meal::with('category')
            ->available()
            ->take($limit)
            ->get();
    }
}
