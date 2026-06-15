<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    /**
     * Get all subcategories
     */
    public function index(Request $request): JsonResponse
    {
        $subcategories = $this->subcategoryService->getSubcategories($request->all());
        $formatted = $subcategories->map(fn($sub) => $this->subcategoryService->formatSubcategory($sub));
        
        return self::collectionResponse('Subcategories retrieved successfully', $formatted);
    }

    /**
     * Get single subcategory
     */
    public function show(Subcategory $subcategory): JsonResponse
    {
        return self::successResponse(
            'Subcategory retrieved successfully',
            $this->subcategoryService->formatSubcategory($subcategory)
        );
    }
}
