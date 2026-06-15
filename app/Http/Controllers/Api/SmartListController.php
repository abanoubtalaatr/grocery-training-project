<?php

namespace App\Http\Controllers\Api;

use App\Actions\SmartList\AddMealToSmartListAction;
use App\Actions\SmartList\RemoveMealFromSmartListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmartListRequest;
use App\Http\Resources\SmartListResource;
use App\Models\Meal;
use App\Models\SmartList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmartListController extends Controller
{
    /**
     * Get all smart lists for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $smartLists = SmartList::where('user_id', $request->user()->id)
            ->with('meals')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Smart lists retrieved successfully',
            'data' => SmartListResource::collection($smartLists),
        ]);
    }

    /**
     * Store a new smart list.
     */
    public function store(StoreSmartListRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['description'] = $data['description'] ?? '';
        $mealIds = $data['meal_ids'] ?? [];
        unset($data['meal_ids']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList = SmartList::create($data);
        if (!empty($mealIds)) {
            $smartList->meals()->attach($mealIds);
        }
        $smartList->load('meals');

        return response()->json([
            'success' => true,
            'message' => 'Wish list created successfully',
            'data' => new SmartListResource($smartList),
        ]);
    }

    /**
     * Show a single smart list.
     */
    public function show(Request $request, SmartList $smartList): JsonResponse
    {
        $this->authorizeOwner($request, $smartList);
        $smartList->load('meals');

        return response()->json([
            'success' => true,
            'message' => 'Smart list retrieved successfully',
            'data' => new SmartListResource($smartList),
        ]);
    }

    /**
     * Update a smart list.
     */
    public function update(StoreSmartListRequest $request, SmartList $smartList): JsonResponse
    {
        $this->authorizeOwner($request, $smartList);
        $data = $request->validated();

        if (array_key_exists('description', $data) && $data['description'] === null) {
            $data['description'] = '';
        }
        $mealIds = $data['meal_ids'] ?? null;
        unset($data['meal_ids']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/smart-lists'), $imageName);
            $data['image'] = $imageName;
        }

        $smartList->update($data);
        if ($mealIds !== null) {
            $smartList->meals()->sync($mealIds);
        }
        $smartList->load('meals');

        return response()->json([
            'success' => true,
            'message' => 'Wish list updated successfully',
            'data' => new SmartListResource($smartList),
        ]);
    }

    /**
     * Delete a smart list.
     */
    public function destroy(Request $request, SmartList $smartList): JsonResponse
    {
        $this->authorizeOwner($request, $smartList);
        $smartList->meals()->detach();
        $smartList->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wish list deleted successfully',
        ]);
    }

    /**
     * Add a meal to a smart list.
     */
    public function addMeal(Request $request, SmartList $smartList): JsonResponse
    {
        $request->validate(['meal_id' => ['required', 'exists:meals,id']]);
        $this->authorizeOwner($request, $smartList);

        $meal = Meal::findOrFail($request->meal_id);
        $smartList = AddMealToSmartListAction::run($smartList, $meal);

        return response()->json([
            'success' => true,
            'message' => 'Item added to wish list successfully',
            'data' => new SmartListResource($smartList),
        ]);
    }

    /**
     * Remove a meal from a smart list.
     */
    public function removeMeal(Request $request, SmartList $smartList, Meal $meal): JsonResponse
    {
        $this->authorizeOwner($request, $smartList);
        $smartList = RemoveMealFromSmartListAction::run($smartList, $meal);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wish list successfully',
            'data' => new SmartListResource($smartList),
        ]);
    }

    /**
     * Authorize that the user owns the smart list.
     */
    private function authorizeOwner(Request $request, SmartList $smartList): void
    {
        if ($smartList->user_id !== $request->user()->id) {
            abort(404, 'Smart list not found');
        }
    }
}

