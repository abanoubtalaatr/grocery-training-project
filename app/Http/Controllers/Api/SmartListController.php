<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\AddMealToSmartListAction;
use App\Actions\Api\CreateSmartListAction;
use App\Actions\Api\DeleteSmartListAction;
use App\Actions\Api\FindSmartListAction;
use App\Actions\Api\ListSmartListsAction;
use App\Actions\Api\RemoveMealFromSmartListAction;
use App\Actions\Api\UpdateSmartListAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddMealToSmartListRequest;
use App\Http\Requests\Api\SmartListRequest;
use App\Http\Resources\Api\SmartListResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SmartListController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request, ListSmartListsAction $action): JsonResponse
    {
        return $this->successResponse(
            SmartListResource::collection($action->execute($request->user())),
            'Smart lists retrieved successfully',
        );
    }

    public function store(SmartListRequest $request, CreateSmartListAction $action): JsonResponse
    {
        return $this->successResponse(
            new SmartListResource($action->execute($request->user(), $request->validated(), $request->file('image'))),
            'Wish list created successfully',
        );
    }

    public function show(Request $request, $id, FindSmartListAction $action): JsonResponse
    {
        return $this->successResponse(
            new SmartListResource($action->execute($request->user(), $id)),
            'Smart list retrieved successfully',
        );
    }

    public function update(SmartListRequest $request, $id, UpdateSmartListAction $action): JsonResponse
    {
        return $this->successResponse(
            new SmartListResource($action->execute($request->user(), $id, $request->validated(), $request->file('image'))),
            'Wish list updated successfully',
        );
    }

    public function destroy(Request $request, $id, DeleteSmartListAction $action): JsonResponse
    {
        $action->execute($request->user(), $id);

        return $this->successResponse(message: 'Wish list deleted successfully');
    }

    /**
     * Add a meal to a wish list.
     */
    public function addMeal(AddMealToSmartListRequest $request, string $id, AddMealToSmartListAction $action): JsonResponse
    {
        return $this->successResponse(
            new SmartListResource($action->execute($request->user(), $id, $request->validated('meal_id'))),
            'Item added to wish list successfully',
        );
    }

    /**
     * Remove a meal from a wish list.
     */
    public function removeMeal(Request $request, string $id, string $mealId, RemoveMealFromSmartListAction $action): JsonResponse
    {
        return $this->successResponse(
            new SmartListResource($action->execute($request->user(), $id, $mealId)),
            'Item removed from wish list successfully',
        );
    }
}
