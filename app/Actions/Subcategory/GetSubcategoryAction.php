<?php

namespace App\Actions\Subcategory;

use App\Repositories\SubcategoryRepository;

class GetSubcategoryAction
{
    public function __construct(private readonly SubcategoryRepository $subcategoryRepository) {}

    public function __invoke(string $id): array
    {
        $subcategory = $this->subcategoryRepository->findByIdWithMeals($id);

        return [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'slug' => $subcategory->slug,
            'description' => $subcategory->description,
            'image_url' => $subcategory->image_url,
            'order' => $subcategory->order,
            'is_active' => $subcategory->is_active,
            'category' => [
                'id' => $subcategory->category->id,
                'name' => $subcategory->category->name,
                'slug' => $subcategory->category->slug,
            ],
            'meals' => $subcategory->meals->map(function ($meal) {
                return [
                    'id' => $meal->id,
                    'title' => $meal->title,
                    'slug' => $meal->slug,
                    'image_url' => $meal->image_url,
                    ...$meal->getApiPriceAttributes(),
                    'rating' => (float) $meal->rating,
                    'is_featured' => $meal->is_featured,
                    'features' => $meal->features,
                ];
            }),
            'meals_count' => $subcategory->meals()->available()->count(),
            'created_at' => $subcategory->created_at,
            'updated_at' => $subcategory->updated_at,
        ];
    }
}
