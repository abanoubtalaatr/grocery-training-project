<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use App\Services\FrequencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Get meals the authenticated user orders most often.
     */
    public function frequency(Request $request): JsonResponse
    {
        $frequencyType = $request->input('frequency_type', FrequencyService::FREQUENCY_WEEKLY);
        if (!in_array($frequencyType, FrequencyService::VALID_TYPES, true)) {
            $frequencyType = FrequencyService::FREQUENCY_WEEKLY;
        }

        $user = $request->user();
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required to view frequency meals.',
            ], 401);
        }

        $subcategoryId = $request->input('subcategory_id');
        $subcategoryId = is_numeric($subcategoryId) ? (int) $subcategoryId : null;

        $service = app(FrequencyService::class);
        $meals = $service->getFrequentlyOrderedMeals($user, $frequencyType, 50, $subcategoryId);

        $meals->each(function ($meal) {
            $meal->order_count = (int) $meal->getAttribute('order_count');
        });

        $payload = [
            'success' => true,
            'message' => 'Frequency meals retrieved successfully',
            'frequency_type' => $frequencyType,
            'data' => MealResource::collection($meals),
        ];
        if ($subcategoryId !== null) {
            $payload['subcategory_id'] = $subcategoryId;
        }

        return response()->json($payload);
    }

    /**
     * More to explore meals
     */
    public function moreToExplore(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'More to explore retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * List distinct brands
     */
    public function brands(Request $request): JsonResponse
    {
        $brands = Meal::distinct()->pluck('brand');

        return response()->json([
            'success' => true,
            'message' => 'Brands retrieved successfully',
            'data' => $brands,
        ]);
    }

    /**
     * Slider meals
     */
    public function slider(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Today\'s meals retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * Best selling meals
     */
    public function bestSells(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Best sells retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * New products/meals
     */
    public function newProducts(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'New products retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * Get hot / Ready-to-eat meals only.
     */
    public function hot(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->hot()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Hot meals retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * Get today's deals (meals with active discounts)
     */
    public function today(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->withActiveDiscount()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Today\'s deals retrieved successfully',
            'data' => MealResource::collection($meals),
        ]);
    }

    /**
     * Get all meals (paginated/filtered)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Meal::with(['category', 'subcategory'])->available();

        // Search
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->has('subcategory_id')) {
            $query->where('subcategory_id', $request->input('subcategory_id'));
        }
        if ($request->has('featured')) {
            $request->boolean('featured') ? $query->featured() : $query->where('is_featured', false);
        }
        if ($request->has('in_stock')) {
            $request->boolean('in_stock') ? $query->inStock() : $query->outOfStock();
        }
        if ($request->has('min_price')) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [$request->input('min_price')]);
        }
        if ($request->has('max_price')) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [$request->input('max_price')]);
        }
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->input('min_rating'));
        }
        if ($request->has('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = strtolower($request->input('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }
        $allowedSortFields = ['created_at', 'price', 'rating', 'title', 'sold_count'];
        if (in_array($sortBy, $allowedSortFields, true)) {
            if ($sortBy === 'price') {
                $query->orderByRaw('COALESCE(discount_price, price) ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $favoriteMealIds = $user ? $user->favorites()->pluck('meal_id')->toArray() : [];

        $meals = $query->get()->each(function ($meal) use ($favoriteMealIds) {
            $meal->is_favorited = in_array($meal->id, $favoriteMealIds, true);
        });

        $totalCount = $meals->count();
        $isEmpty = $totalCount === 0;

        return response()->json(array_merge([
            'success' => true,
            'message' => $isEmpty ? 'No products match your filters.' : 'Meals retrieved successfully',
            'data' => MealResource::collection($meals),
            'total_count' => $totalCount,
            'filters_applied' => [
                'search' => $request->input('search'),
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'min_rating' => $request->input('min_rating'),
                'brand' => $request->input('brand'),
                'featured' => $request->boolean('featured'),
                'in_stock' => $request->boolean('in_stock'),
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ], $isEmpty ? ['empty_message' => 'No products match the applied filters. Try adjusting your search or filters.'] : []));
    }

    /**
     * Get recommended meals
     */
    public function recommendations(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        // Get featured meals with offers
        $featuredMeals = Meal::with('category')
            ->available()
            ->featured()
            ->whereNotNull('discount_price')
            ->inRandomOrder()
            ->limit(ceil($limit / 2))
            ->get();

        // Get random meals from different categories
        $randomMeals = Meal::with('category')
            ->available()
            ->whereNotIn('id', $featuredMeals->pluck('id'))
            ->inRandomOrder()
            ->limit($limit - $featuredMeals->count())
            ->get();

        // Combine and shuffle
        $recommendations = $featuredMeals->merge($randomMeals)->shuffle()->take($limit);

        $recommendations->each(function ($meal) {
            $meal->recommendation_reason = $this->getRecommendationReason($meal);
        });

        return response()->json([
            'success' => true,
            'message' => 'Meal recommendations retrieved successfully',
            'data' => MealResource::collection($recommendations),
        ]);
    }

    /**
     * Get recommendation reason for a meal
     */
    private function getRecommendationReason(Meal $meal): string
    {
        if ($meal->is_featured && $meal->discount_price) {
            return 'Featured with special offer';
        }

        if ($meal->is_featured) {
            return 'Featured meal';
        }

        if ($meal->discount_price) {
            return 'Special offer';
        }

        return 'Popular choice';
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

        return response()->json([
            'success' => true,
            'message' => 'Meal retrieved successfully',
            'data' => new MealResource($meal),
        ]);
    }
}
