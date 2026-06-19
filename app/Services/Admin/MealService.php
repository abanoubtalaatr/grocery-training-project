<?php

namespace App\Services\Admin;

use App\Models\Meal;
use Illuminate\Http\Request;


class MealService
{
    public function paginate(
    Request $request,
    int $perPage = 10
    )
    {
        return Meal::query()
            ->with([
                'category',
                'subcategory',
            ])
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Meal
    {
        return Meal::create($data);
    }

    public function update(Meal $meal, array $data): bool
    {
        return $meal->update($data);
    }

    public function delete(Meal $meal): bool
    {
        return $meal->delete();
    }
}