<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Admin\Subcategory\UpdateSubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use App\Services\Admin\SubcategoryService;

class SubcategoryController extends Controller
{
    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

     /**
     * لا يوجد view مستقل للـ subcategories index.
     * الـ listing بيظهر داخل تاب "subcategories" في صفحة categories.index.
     */
    public function index()
    {
        return redirect()->route('admin.categories.index', ['tab' => 'subcategories']);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('pages.admin.subcategories.create', compact('categories'));
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $this->subcategoryService->create($request->validated());

        return redirect()->route('admin.subcategories.index')
            ->with('success', __('Subcategory created successfully.'));
    }

    public function show(Subcategory $subcategory)
    {
        $subcategory->load('category')->loadCount('meals');

        return view('pages.admin.subcategories.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('pages.admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $this->subcategoryService->update($subcategory, $request->validated());

        return redirect()->route('admin.subcategories.index')
            ->with('success', __('Subcategory updated successfully.'));
    }

    public function destroy(Subcategory $subcategory)
    {
        $this->subcategoryService->delete($subcategory);

        return redirect()->route('admin.subcategories.index')
            ->with('success', __('Subcategory deleted successfully.'));
    }
}