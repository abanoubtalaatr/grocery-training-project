<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $subcategories = Subcategory::query()->filter($request);

        return response()->json($subcategories->paginate($request->input('per_page', 5)));
    }
}
