<?php

namespace App\Services\Admin;

use App\Models\Meal;

class MealService
{
    public function paginate(?string $search = null, int $perPage = 10)
    {
        return Meal::query()
            ->with('category')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");

                });

            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}