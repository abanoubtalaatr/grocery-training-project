<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(Request $request)
    {
        $categories = $this->categoryService->paginate(
            search: $request->search
        );

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }
}