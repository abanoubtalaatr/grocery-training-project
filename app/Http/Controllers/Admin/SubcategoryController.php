<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')
            ->latest()
            ->paginate(10);

        return view(
            'admin.subcategories.index',
            compact('subcategories')
        );
    }
}