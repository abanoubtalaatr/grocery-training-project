<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryMealsController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer|min:1|max:50',
            'featured' => 'sometimes|boolean',
            'subcategory_id' => 'sometimes|integer',
            'in_stock' => 'sometimes|boolean',
            'sort_by' => 'sometimes|string',
            'sort_order' => 'sometimes|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid parameters', $validator->errors(), 422);
        }

        try {
            $category = Category::findOrFail($id);

            $query = $category->meals()->with(['subcategory'])->available();

            if ($request->has('featured')) {
                $request->boolean('featured') ? $query->featured() : $query->where('is_featured', false);
            }

            if ($request->has('subcategory_id')) {
                $query->where('subcategory_id', $request->input('subcategory_id'));
            }

            if ($request->has('in_stock')) {
                $request->boolean('in_stock') ? $query->inStock() : $query->outOfStock();
            }

            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = strtolower($request->input('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
            if ($sortBy === 'newest') {
                $sortBy = 'created_at';
                $sortOrder = 'desc';
            }
            $allowedSortFields = ['created_at', 'price', 'rating', 'title', 'sold_count'];
            if (in_array($sortBy, $allowedSortFields)) {
                if ($sortBy === 'price') {
                    $query->orderByRaw('COALESCE(discount_price, price) ' . $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $perPage = min(max((int) $request->input('per_page', 15), 1), 50);
            $paginator = $query->paginate($perPage);

            $total = $paginator->total();
            return $this->successResponse(
                $total === 0 ? 'No products match your filters.' : 'Meals retrieved successfully',
                [
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Category not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve meals', $e->getMessage(), 500);
        }
    }
}
