<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Meal;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::with(['category', 'subcategory'])
            ->paginate(15);

        return view('admins.meals.index', compact('meals'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view('admins.meals.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        Meal::create($data);

        return redirect()
            ->route('admins.meals.index')
            ->with('status', 'Meal created successfully.');
    }

    public function show(Meal $meal)
    {
        return view('admins.meals.show', compact('meal'));
    }

    public function edit(Meal $meal)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view('admins.meals.edit', compact('meal', 'categories', 'subcategories'));
    }

    public function update(Request $request, Meal $meal)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }

            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal->update($data);

        return redirect()
            ->route('admins.meals.show', $meal)
            ->with('status', 'Meal updated successfully.');
    }

    public function destroy(Meal $meal)
    {
        $meal->delete();

        return redirect()
            ->route('admins.meals.index')
            ->with('status', 'Meal deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:meals,id'],
        ]);

        Meal::whereIn('id', $data['ids'])->delete();

        return redirect()
            ->route('admins.meals.index')
            ->with('status', 'Selected meals deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'brand' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'discount_price' => ['nullable', 'numeric'],
            'offer_title' => ['nullable', 'string', 'max:255'],
            'stock_quantity' => ['required', 'integer'],
            'available_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'includes' => ['nullable', 'string'],
            'how_to_use' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_available' => ['boolean'],
            'is_hot' => ['boolean'],
        ]);
    }
}
