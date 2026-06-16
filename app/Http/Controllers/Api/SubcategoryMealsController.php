<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Subcategory\SubcategoryMealsAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryMealsController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer|min:1|max:50',
            'featured' => 'sometimes|boolean',
            'in_stock' => 'sometimes|boolean',
            'sort_by' => 'sometimes|string',
            'sort_order' => 'sometimes|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid parameters', $validator->errors(), 422);
        }

        try {
            $paginator = (new SubcategoryMealsAction())->handle($id, $request);
            
            $meals = $paginator->getCollection()->map(function ($meal) {
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
            });

            $total = $paginator->total();
            return $this->successResponse(
                $total === 0 ? 'No products match your filters.' : 'Meals retrieved successfully',
                [
                    'subcategory' => [
                        'id' => $paginator->getCollection()->first()?->category?->id ?? $id,
                        'name' => $paginator->getCollection()->first()?->category?->name,
                        'slug' => $paginator->getCollection()->first()?->category?->slug,
                    ],
                    'meals' => MealResource::collection($paginator->items()),
                    'pagination' => [
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'per_page' => $paginator->perPage(),
                        'total' => $total,
                        'from' => $paginator->firstItem(),
                        'to' => $paginator->lastItem(),
                    ],
                ] + ($total === 0 ? ['empty_message' => 'No products match the applied filters. Try adjusting your filters.'] : []),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Subcategory not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve meals', $e->getMessage(), 500);
        }
    }
}
