<?php

namespace App\Services\Admin;

use App\Models\Subcategory;
use Illuminate\Support\Str;

class SubcategoryService
{
    public function getAllPaginated(int $perPage = 15)
    {
        return Subcategory::with('category')
            ->orderBy('order')
            ->paginate($perPage);
    }

    public function create(array $data): Subcategory
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);
        $data['is_active'] ??= true;

        return Subcategory::create($data);
    }

    public function update(Subcategory $subcategory, array $data): Subcategory
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);

        $subcategory->update($data);

        return $subcategory->fresh();
    }

    public function delete(Subcategory $subcategory): bool
    {
        return $subcategory->delete();
    }
}