<?php

namespace App\Actions\Category;

use App\Repositories\CategoryRepository;

class GetCategoryAction
{
    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    public function __invoke(string $id): array
    {
        $category = $this->categoryRepository->findByIdWithMeals($id);

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image_url' => $category->image_url,
            'sort_order' => $category->sort_order,
            'meals' => $category->meals->map(function ($meal) {
                return [
                    'id' => $meal->id,
                    'title' => $meal->title,
                    'slug' => $meal->slug,
                    'description' => $meal->description,
                    'image_url' => $meal->image_url,
                    'offer_title' => $meal->offer_title,
                    ...$meal->getApiPriceAttributes(),
                    'rating' => (float) $meal->rating,
                    'rating_count' => (int) $meal->rating_count,
                    'has_offer' => $meal->hasOffer(),
                    'is_featured' => $meal->is_featured,
                    'features' => $meal->features,
                ];
            }),
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ];
    }
}
