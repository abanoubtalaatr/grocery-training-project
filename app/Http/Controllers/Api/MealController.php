<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    /**
     * Get all meals
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $favoriteMealIds = $user ? $user->favorites()->pluck('meal_id')->toArray() : [];
        
        $meals = $this->mealService->getMeals($request->all(), $user);
        $formatted = $meals->map(fn($meal) => $this->mealService->formatMeal($meal, $favoriteMealIds));

        return self::collectionResponse('Meals retrieved successfully', $formatted, [
            'total_count' => $meals->count(),
            'filters_applied' => $request->only([
                'search', 'category_id', 'subcategory_id', 'min_price', 'max_price', 
                'min_rating', 'brand', 'featured', 'in_stock', 'sort_by', 'sort_order'
            ]),
        ]);
    }

    /**
     * Get single meal
     */
    public function show(Meal $meal): JsonResponse
    {
        $meal->load([
            'category',
            'subcategory',
            'reviews' => fn ($q) => $q->approved()->with('user:id,username,firstname,lastname')->orderBy('created_at', 'desc'),
        ]);

        return self::successResponse('Meal retrieved successfully', [
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
            'includes' => $meal->includes,
            'how_to_use' => $meal->how_to_use,
            'features' => $meal->features,
            'expiry_date' => $meal->expiry_date,
            'days_until_expiry' => $meal->daysUntilExpiry(),
            'is_expired' => $meal->isExpired(),
            'stock_quantity' => $meal->stock_quantity,
            'in_stock' => $meal->isInStock(),
            'sold_count' => $meal->sold_count,
            'is_featured' => $meal->is_featured,
            'is_available' => $meal->is_available,
            'available_date' => $meal->available_date,
            'category' => [
                'id' => $meal->category->id,
                'name' => $meal->category->name,
                'slug' => $meal->category->slug,
            ],
            'reviews' => $meal->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user ? [
                        'id' => $review->user->id,
                        'name' => $review->user->full_name ?? $review->user->username ?? 'User',
                    ] : null,
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                    'images' => $review->images ?? [],
                    'created_at' => $review->created_at?->toIso8601String(),
                ];
            })->values(),
            'subcategory' => $meal->subcategory ? [
                'id' => $meal->subcategory->id,
                'name' => $meal->subcategory->name,
                'slug' => $meal->subcategory->slug,
            ] : null,
            'created_at' => $meal->created_at,
            'updated_at' => $meal->updated_at,
        ]);
    }
}
