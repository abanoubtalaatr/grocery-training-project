<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ApiReviewController extends Controller
{
  public function index(Request $request)
  {
    $reviews = Review::query()->filter($request)->paginate(3);

    return response()->json($reviews);
  }
}
