<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function index(Request $request): View
    {
        $subcategories = Subcategory::query()
            ->with('category')
            ->withCount('meals')
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create(): View
    {
        return view('admin.subcategories.create', [
            'subcategory' => new Subcategory(),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(SubcategoryRequest $request): RedirectResponse
    {
        Subcategory::create($request->validated());

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function show(Subcategory $subcategory): View
    {
        $subcategory->load('category')->loadCount('meals');

        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory): View
    {
        return view('admin.subcategories.edit', [
            'subcategory' => $subcategory,
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $subcategory->update($request->validated());

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }

    /**
     * @return array<int, string>
     */
    private function categoryOptions(): array
    {
        return Category::ordered()->pluck('name', 'id')->all();
    }
}
