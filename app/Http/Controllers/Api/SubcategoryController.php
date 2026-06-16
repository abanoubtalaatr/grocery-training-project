<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Actions\Subcategory\SubcategoryIndexAction;
use App\Http\Controllers\Api\Actions\Subcategory\SubcategoryShowAction;
use App\Http\Resources\Api\V1\SubcategoryResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    use ApiResponse;

    /**
     * Get all subcategories
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $subcategories = (new SubcategoryIndexAction())->handle($request);

            return $this->successResponse(
                'Subcategories retrieved successfully',
                SubcategoryResource::collection($subcategories),
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve subcategories', $e->getMessage(), 500);
        }
    }

    /**
     * Get single subcategory
     */
    public function show(string $id): JsonResponse
    {
        try {
            $subcategory = (new SubcategoryShowAction())->handle($id);

            return $this->successResponse(
                'Subcategory retrieved successfully',
                new SubcategoryResource($subcategory),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Subcategory not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve subcategory', $e->getMessage(), 500);
        }
    }
}
