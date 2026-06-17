<?php

namespace App\Services\Admin;

use App\Models\Category;

class CategoryService
{
    public function paginate(?string $search = null, int $perPage = 10)
    {
        return Category::query()
            ->withCount('meals')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}