<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAllActiveWithMealsCount()
    {
        return Category::active()->ordered()->withCount('meals')->get();
    }

    public function findByIdWithMeals(string $id): Category
    {
        return Category::with(['meals' => function ($query) {
            $query->available()->orderBy('created_at', 'desc');
        }])->findOrFail($id);
    }

    public function findById(string $id): Category
    {
        return Category::findOrFail($id);
    }

    public function searchActiveWithMealsCount($request)
    {
        return Category::search($request)->active()->ordered()->withCount('meals')->get();
    }
}
