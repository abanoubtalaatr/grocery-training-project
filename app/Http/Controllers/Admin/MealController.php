<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MealRequest;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealController extends Controller
{
    public function index(Request $request): View
    {
        $meals = Meal::query()
            ->with(['category', 'subcategory'])
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->integer('category_id'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.meals.index', [
            'meals' => $meals,
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.meals.create', [
            'meal' => new Meal(),
            'categories' => $this->categoryOptions(),
            'subcategories' => $this->subcategoryOptions(),
        ]);
    }

    public function store(MealRequest $request): RedirectResponse
    {
        Meal::create($request->validated());

        return redirect()->route('admin.meals.index')->with('success', 'Meal created successfully.');
    }

    public function show(Meal $meal): View
    {
        $meal->load(['category', 'subcategory']);

        return view('admin.meals.show', compact('meal'));
    }

    public function edit(Meal $meal): View
    {
        return view('admin.meals.edit', [
            'meal' => $meal,
            'categories' => $this->categoryOptions(),
            'subcategories' => $this->subcategoryOptions(),
        ]);
    }

    public function update(MealRequest $request, Meal $meal): RedirectResponse
    {
        $meal->update($request->validated());

        return redirect()->route('admin.meals.index')->with('success', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal): RedirectResponse
    {
        $meal->delete();

        return redirect()->route('admin.meals.index')->with('success', 'Meal deleted successfully.');
    }

    /**
     * @return array<int, string>
     */
    private function categoryOptions(): array
    {
        return Category::ordered()->pluck('name', 'id')->all();
    }

    /**
     * @return array<int, string>
     */
    private function subcategoryOptions(): array
    {
        return Subcategory::ordered()->pluck('name', 'id')->all();
    }
}
