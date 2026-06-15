<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryMealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get meals by category (paginated)
     */
    public function index(Request $request, Category $category): JsonResponse
    {
        $meals = $this->categoryService->getCategoryMeals($category, $request->all(), $request->input('per_page', 15));

        return self::collectionResponse(
            'Meals retrieved successfully',
            $meals->map(fn($meal) => $this->formatMeal($meal)),
            [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]
        );
    }

    private function formatMeal($meal): array
    {
        return [
            'id' => $meal->id,
            'title' => $meal->title,
            'slug' => $meal->slug,
            'description' => $meal->description,
            'image_url' => $meal->image_url,
            'offer_title' => $meal->offer_title,
            ...$meal->getApiPriceAttributes(),
            'has_offer' => $meal->hasOffer(),
            'rating' => (float) $meal->rating,
            'rating_count' => (int) $meal->rating_count,
            'size' => $meal->size,
            'brand' => $meal->brand,
            'stock_quantity' => $meal->stock_quantity,
            'in_stock' => $meal->isInStock(),
            'is_featured' => $meal->is_featured,
            'expiry_date' => $meal->expiry_date,
            'is_expired' => $meal->isExpired(),
            'features' => $meal->features,
            'subcategory' => $meal->subcategory ? [
                'id' => $meal->subcategory->id,
                'name' => $meal->subcategory->name,
            ] : null,
        ];
    }
}
