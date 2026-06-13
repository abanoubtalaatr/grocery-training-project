<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class ApiOffersController extends Controller
{
  public function index(Request $request)
  {
    $offers = Offer::query()->filter($request)->paginate(3);

    return response()->json($offers);
  }
}
