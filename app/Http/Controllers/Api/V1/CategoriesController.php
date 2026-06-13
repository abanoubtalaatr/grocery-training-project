<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()->filter($request)->get();
        
        return response()->json($categories);
    }

}
