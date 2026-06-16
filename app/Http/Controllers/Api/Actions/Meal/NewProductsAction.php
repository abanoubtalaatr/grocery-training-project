<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class NewProductsAction
{
    public function handle()
    {
        return Meal::with('category')
            ->available()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
