<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MealService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Subcategory;
use App\Http\Requests\Admin\StoreMealRequest;
use App\Http\Requests\Admin\UpdateMealRequest;


class MealController extends Controller
{
    public function __construct(
        private MealService $mealService
    ) {}

    public function index(Request $request)
    {
        $meals = $this->mealService->paginate($request);

        return view(
            'admin.meals.index',
            compact('meals')
        );
    }

    public function create()
    {
        return view('admin.meals.create', [
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
        ]);
    }

    public function edit(Meal $meal)
    {
        return view('admin.meals.edit', [
            'meal' => $meal,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
        ]);
    }


    public function update(
    UpdateMealRequest $request,
    Meal $meal
    ) {
        $this->mealService->update(
            $meal,
            $request->validated()
        );

        return redirect()
            ->route('admin.meals.index')
            ->with(
                'success',
                'Meal updated successfully.'
            );
    }


    public function destroy(Meal $meal)
    {
        $this->mealService->delete($meal);

        return redirect()
            ->route('admin.meals.index')
            ->with(
                'success',
                'Meal deleted successfully.'
            );
    }
}