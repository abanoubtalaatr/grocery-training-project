<?php

namespace App\Http\Controllers\Api;

use App\Filters\MealFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealDetailResource;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use App\Services\FrequencyService;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class MealController extends Controller
{
    use ApiResponse;

    public function index(Request $request, MealFilter $filter): JsonResponse
    {
        $query = Meal::query();
        $query = $filter->apply($query);

        if ($user = $request->user()) {
            $favoriteIds = $user->favorites()->pluck('meal_id')->toArray();
            $query->get()->each(fn($meal) => $meal->setAttribute('is_favorited', in_array($meal->id, $favoriteIds)));
        }

        $meals = $query->get();
        
        return $this->successResponse([
            'meals' => MealResource::collection($meals)->resolve(),
            'total_count' => $meals->count(),
            'filters_applied' => $request->all()
        ], $meals->isEmpty() ? 'No products match your filters.' : 'Meals retrieved successfully');
    }

    public function show(Meal $meal): JsonResponse
    {
        $meal->load([
            'category', 'subcategory',
            'reviews' => fn($q) => $q->approved()->with('user:id,username,firstname,lastname')->latest()
        ]);

        return $this->successResponse(
            data: (new MealDetailResource($meal))->resolve(),
            message: 'Meal retrieved successfully'
        );
    }

    public function frequency(Request $request, FrequencyService $service): JsonResponse
    {
        $frequencyType = $request->input('frequency_type', FrequencyService::FREQUENCY_WEEKLY);
        if (!in_array($frequencyType, FrequencyService::VALID_TYPES, true)) {
            $frequencyType = FrequencyService::FREQUENCY_WEEKLY;
        }

        if (!$user = $request->user()) {
            return $this->errorResponse('Authentication required to view frequency meals.', 401);
        }

        $subcategoryId = $request->integer('subcategory_id') ?: null;
        $meals = $service->getFrequentlyOrderedMeals($user, $frequencyType, 50, $subcategoryId);

        return $this->successResponse([
            'frequency_type' => $frequencyType,
            'subcategory_id' => $subcategoryId,
            'meals' => MealResource::collection($meals)->resolve(),
        ], 'Frequency meals retrieved successfully');
    }

    public function recommendations(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $featured = Meal::with('category')->available()->featured()->whereNotNull('discount_price')->inRandomOrder()->limit(ceil($limit / 2))->get();
        $random = Meal::with('category')->available()->whereNotIn('id', $featured->pluck('id'))->inRandomOrder()->limit($limit - $featured->count())->get();
        
        $recommendations = $featured->merge($random)->shuffle()->take($limit);
        
        $recommendations->each(fn($meal) => $meal->setAttribute('recommendation_reason', $this->getRecommendationReason($meal)));

        return $this->successResponse(MealResource::collection($recommendations)->resolve(), 'Meal recommendations retrieved successfully');
    }

    public function moreToExplore(): JsonResponse
    {
        $meals = Meal::with('category')->available()->latest()->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), 'More to explore retrieved successfully');
    }

    public function slider(): JsonResponse
    {
        $meals = Meal::with('category')->available()->latest()->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), "Today's meals retrieved successfully");
    }

    public function bestSells(): JsonResponse
    {
        $meals = Meal::with('category')->available()->take(10)->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), 'Best sells retrieved successfully');
    }

    public function newProducts(): JsonResponse
    {
        $meals = Meal::with('category')->available()->latest()->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), 'New products retrieved successfully');
    }

    public function hot(): JsonResponse
    {
        $meals = Meal::with('category')->available()->hot()->latest()->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), 'Hot meals retrieved successfully');
    }

    public function today(): JsonResponse
    {
        $meals = Meal::with('category')->available()->withActiveDiscount()->latest()->get();
        return $this->successResponse(MealResource::collection($meals)->resolve(), "Today's deals retrieved successfully");
    }

    public function brands(): JsonResponse
    {
        return $this->successResponse(Meal::distinct()->pluck('brand'), 'Brands retrieved successfully');
    }

    private function getRecommendationReason($meal): string
    {
        if ($meal->is_featured && $meal->discount_price) return 'Featured with special offer';
        if ($meal->is_featured) return 'Featured meal';
        return $meal->discount_price ? 'Special offer' : 'Popular choice';
    }
}