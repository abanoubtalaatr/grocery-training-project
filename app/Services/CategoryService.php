<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     description: string|null,
     *     image_url: string,
     *     meals_count: int,
     *     sort_order: int,
     *     created_at: \Illuminate\Support\Carbon|null
     * }>
     */
    public function getCategories(): Collection
    {
        return Category::active()
            ->ordered()
            ->withCount('meals')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image_url' => $category->image_url,
                'meals_count' => $category->meals_count,
                'sort_order' => $category->sort_order,
                'created_at' => $category->created_at,
            ]);
    }
}
