<?php

namespace App\Http\Controllers\Api\V1;

use App\Filament\Resources\SubcategoryResource;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $subcategories = Subcategory::query()->filter($request->all())->get();
        return ApiResponse::success(SubcategoryResource::collection($subcategories));
    }
}
