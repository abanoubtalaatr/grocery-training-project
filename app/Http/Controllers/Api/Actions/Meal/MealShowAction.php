<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Models\Meal;

class MealShowAction
{
    public function handle(string $id): Meal
    {
        return Meal::with([
            'category',
            'subcategory',
            'reviews' => fn ($q) => $q->approved()->with('user:id,username,firstname,lastname')->orderBy('created_at', 'desc'),
        ])->findOrFail($id);
    }
}
