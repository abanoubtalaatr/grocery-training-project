<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SmartListResource;
use App\Models\SmartList;
use App\Services\SmartListService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmartListMealController extends Controller
{
    use ApiResponse;

    protected $smartListService;

    public function __construct(SmartListService $smartListService)
    {
        $this->smartListService = $smartListService;
    }

    /**
     * Add a meal to a smart list.
     */
    public function store(Request $request, SmartList $smartList): JsonResponse
    {
        if ($smartList->user_id !== $request->user()->id) {
            return self::errorResponse('Unauthorized', null, 403);
        }

        $request->validate(['meal_id' => ['required', 'exists:meals,id']]);

        $updatedSmartList = $this->smartListService->addMeal($smartList, $request->meal_id);

        return self::successResponse(
            'Item added to wish list successfully',
            new SmartListResource($updatedSmartList)
        );
    }

    /**
     * Remove a meal from a smart list.
     */
    public function destroy(Request $request, SmartList $smartList, string $mealId): JsonResponse
    {
        if ($smartList->user_id !== $request->user()->id) {
            return self::errorResponse('Unauthorized', null, 403);
        }

        $updatedSmartList = $this->smartListService->removeMeal($smartList, (int) $mealId);

        return self::successResponse(
            'Item removed from wish list successfully',
            new SmartListResource($updatedSmartList)
        );
    }
}
