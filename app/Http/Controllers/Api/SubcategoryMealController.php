<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubcategoryMealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    /**
     * Get meals by subcategory (paginated)
     */
    public function index(Request $request, Subcategory $subcategory): JsonResponse
    {
        $meals = $this->subcategoryService->getSubcategoryMeals($subcategory, $request->all(), $request->input('per_page', 15));

        return self::collectionResponse(
            'Meals retrieved successfully',
            $meals->getCollection()->map(fn($meal) => $this->formatMeal($meal)),
            [
                'subcategory' => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'slug' => $subcategory->slug,
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
            'rating' => (float) $meal->rating,
            'rating_count' => (int) $meal->rating_count,
            'has_offer' => $meal->hasOffer(),
            'is_featured' => $meal->is_featured,
            'in_stock' => $meal->isInStock(),
            'features' => $meal->features,
        ];
    }
}
