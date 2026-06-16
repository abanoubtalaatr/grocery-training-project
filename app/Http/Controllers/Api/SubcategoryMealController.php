<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Api\MealResource;
use App\Http\Resources\Api\SubcategoryResource;

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
      MealResource::collection($meals),
      [
        'subcategory' => new SubcategoryResource($subcategory)
      ]
    );
  }


}
