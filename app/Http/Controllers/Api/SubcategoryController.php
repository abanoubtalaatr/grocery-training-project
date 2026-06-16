<?php

namespace App\Http\Controllers\Api;

use App\Actions\Subcategory\GetSubcategoriesAction;
use App\Actions\Subcategory\GetSubcategoryAction;
use App\Actions\Subcategory\GetSubcategoryMealsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexSubcategoriesRequest;
use App\Http\Requests\SubcategoryMealsRequest;
use App\Http\Resources\SubcategoryDetailsResource;
use App\Http\Resources\SubcategoryMealsResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;

class SubcategoryController extends Controller
{
    public function index(IndexSubcategoriesRequest $request, GetSubcategoriesAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Subcategories retrieved successfully',
            'data' => SubcategoryResource::collection($action->execute($request->validated())),
        ]);
    }

    public function show(Subcategory $subcategory, GetSubcategoryAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Subcategory retrieved successfully',
            'data' => SubcategoryDetailsResource::make($action->execute($subcategory)),
        ]);
    }

    public function meals(
        SubcategoryMealsRequest $request,
        Subcategory $subcategory,
        GetSubcategoryMealsAction $action
    ): JsonResponse {
        $paginator = $action->execute($subcategory, $request->validated());
        $message = $paginator->total() === 0
            ? 'No products match your filters.'
            : 'Meals retrieved successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => SubcategoryMealsResource::make([
                'subcategory' => $subcategory,
                'paginator' => $paginator,
            ]),
        ] + ($paginator->total() === 0 ? [
            'empty_message' => 'No products match the applied filters. Try adjusting your filters.',
        ] : []));
    }
}
