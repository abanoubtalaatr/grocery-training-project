<?php

namespace App\Actions\Subcategory;

use App\Repositories\SubcategoryRepository;
use Illuminate\Support\Collection;

class GetSubcategoriesAction
{
    public function __construct(private readonly SubcategoryRepository $subcategoryRepository) {}

    public function __invoke(?string $categoryId): Collection
    {
        return $this->subcategoryRepository->getAllActive($categoryId)->map(function ($subcategory) {
            return [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'slug' => $subcategory->slug,
                'description' => $subcategory->description,
                'image_url' => $subcategory->image_url,
                'order' => $subcategory->order,
                'category' => [
                    'id' => $subcategory->category->id,
                    'name' => $subcategory->category->name,
                    'slug' => $subcategory->category->slug,
                ],
                'meals_count' => $subcategory->meals()->available()->count(),
                'created_at' => $subcategory->created_at,
            ];
        });
    }
}
