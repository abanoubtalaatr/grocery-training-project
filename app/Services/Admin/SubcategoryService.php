<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return Subcategory::query()
            ->with('category')
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getCategories()
    {
        return Category::orderBy('name')
            ->pluck('name', 'id');
    }

    public function store(array $data): Subcategory
    {
        return Subcategory::create($data);
    }

    public function update(
        Subcategory $subcategory,
        array $data
    ): bool {
        return $subcategory->update($data);
    }

    public function delete(
        Subcategory $subcategory
    ): bool {
        return $subcategory->delete();
    }
}