<?php

namespace App\Http\Controllers\Api\V1;

use App\Filament\Resources\CategoryResource;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()->filter($request->all())->get();
        return ApiResponse::success(CategoryResource::collection($categories));
    }
}
