<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function paginate(
    Request $request,
    int $perPage = 10
    )
    {
        return Category::query()
            ->withCount('meals')
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}