<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Api\Actions\Cart\CartRemoveItemAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RemoveCartItemController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, string $itemId, CartRemoveItemAction $action): JsonResponse
    {
        try {
            $cart = $action->handle($request->user(), $itemId);

            return $this->successResponse(
                'Item removed from cart successfully',
                new CartResource($cart),
            );
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Cart item not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to remove item from cart', $e->getMessage(), 500);
        }
    }
}
