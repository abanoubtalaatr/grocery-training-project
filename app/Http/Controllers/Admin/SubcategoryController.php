<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubcategoryRequest;
use App\Http\Requests\Admin\UpdateSubcategoryRequest;
use App\Models\Subcategory;
use App\Services\Admin\SubcategoryService;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function __construct(
        private SubcategoryService $subcategoryService
    ) {}

    public function index(Request $request)
    {
        $subcategories =
            $this->subcategoryService->paginate($request);

        return view(
            'admin.subcategories.index',
            compact('subcategories')
        );
    }

    public function create()
    {
        $categories =
            $this->subcategoryService->getCategories();

        return view(
            'admin.subcategories.create',
            compact('categories')
        );
    }

    public function store(
        StoreSubcategoryRequest $request
    ) {
        $this->subcategoryService->store(
            $request->validated()
        );

        return redirect()
            ->route('admin.subcategories.index')
            ->with(
                'success',
                'Subcategory created successfully.'
            );
    }

    public function edit(
        Subcategory $subcategory
    ) {
        $categories =
            $this->subcategoryService->getCategories();

        return view(
            'admin.subcategories.edit',
            compact(
                'subcategory',
                'categories'
            )
        );
    }

    public function update(
        UpdateSubcategoryRequest $request,
        Subcategory $subcategory
    ) {
        $this->subcategoryService->update(
            $subcategory,
            $request->validated()
        );

        return redirect()
            ->route('admin.subcategories.index')
            ->with(
                'success',
                'Subcategory updated successfully.'
            );
    }

    public function destroy(
        Subcategory $subcategory
    ) {
        $this->subcategoryService->delete(
            $subcategory
        );

        return back()->with(
            'success',
            'Subcategory deleted successfully.'
        );
    }
}