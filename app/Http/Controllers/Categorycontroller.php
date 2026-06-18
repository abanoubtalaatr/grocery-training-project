<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories= Category::withCount('products')
            ->paginate(15);

        return view('admin.categorys.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categorys.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح.');
    }

    public function show(Category $category): View
    {
        $category->load('products');

        return view('admin.categorys.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categorys.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'لا يمكن حذف هذه الفئة لأنها تحتوي على منتجات مرتبطة بها.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}