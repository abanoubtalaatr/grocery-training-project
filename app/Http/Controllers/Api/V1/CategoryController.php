<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\ListCategoriesAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request, ListCategoriesAction $action): JsonResponse
    {
        return $this->jsonResponse($action->execute($request));
    }
}
