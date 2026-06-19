<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(Request $request)
    {
        $categories = $this->categoryService->paginate($request);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ) {
        $this->categoryService->update(
            $category,
            $request->validated()
        );

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }
}