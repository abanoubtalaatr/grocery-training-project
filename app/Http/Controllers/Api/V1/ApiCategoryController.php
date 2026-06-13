<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
  public function index(Request $request)
  {

    $categories = Category::query()->filter($request)->paginate(3);

    return response()->json($categories);
  }
}
