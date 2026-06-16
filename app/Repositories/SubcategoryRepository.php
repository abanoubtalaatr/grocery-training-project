<?php

namespace App\Repositories;

use App\Models\Subcategory;

class SubcategoryRepository
{
    public function getAllActive(?string $categoryId = null)
    {
        $query = Subcategory::with('category')->active();
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query->inRandomOrder()->get();
    }

    public function findByIdWithMeals(string $id): Subcategory
    {
        return Subcategory::with(['category', 'meals' => function ($query) {
            $query->available()->limit(10);
        }])->findOrFail($id);
    }

    public function findById(string $id): Subcategory
    {
        return Subcategory::findOrFail($id);
    }
}
