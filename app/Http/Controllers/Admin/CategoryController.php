<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display the categories/subcategories listing page (tabbed view).
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'categories');
        $categories = Category::withCount('meals')->orderBy('sort_order')->paginate(15, ['*'], 'cat_page');
        $subcategories = Subcategory::with('category')->orderBy('order')->paginate(15, ['*'], 'sub_page');

        return view('pages.admin.categories.index', compact('categories', 'subcategories', 'tab'));
    }

    public function create()
    {
        return view('pages.admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->create(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.categories.index')->with('success', __('Category created successfully.'));
    }

    public function show(Category $category)
    {
        $category->loadCount(['meals', 'subcategories']);

        return view('pages.admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('pages.admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->update(  $category,  $request->validated(),  $request->file('image') );

        return redirect()->route('admin.categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()->route('admin.categories.index')->with('success', __('Category deleted successfully.'));
    }
}