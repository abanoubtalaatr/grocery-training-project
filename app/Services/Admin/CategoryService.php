<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAllPaginated(int $perPage = 15)
    {
        return Category::withCount(['meals', 'subcategories'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data, ?UploadedFile $image = null): Category
    {
        if ($image) {
            $data['image'] = $image->store('categories', 'public');
        }

        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);
        $data['is_active'] ??= true;

        return Category::create($data);
    }

    public function update(Category $category, array $data, ?UploadedFile $image = null): Category
    {
        if ($image) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $image->store('categories', 'public');
        }

        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);

        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return $category->delete();
    }
}