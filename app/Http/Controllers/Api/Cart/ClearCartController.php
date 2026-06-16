<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Api\Actions\Cart\CartClearAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClearCartController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, CartClearAction $action): JsonResponse
    {
        try {
            $cart = $action->handle($request->user());

            return $this->successResponse(
                'Cart cleared successfully',
                new CartResource($cart),
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to clear cart', $e->getMessage(), 500);
        }
    }
}
