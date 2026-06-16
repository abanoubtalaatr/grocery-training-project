<?php

namespace App\Http\Controllers\Api\Cart;

use App\Exceptions\Cart\CartOperationException;
use App\Http\Controllers\Api\Actions\Cart\CartUpdateItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UpdateCartItemController extends Controller
{
    use ApiResponse;

    public function __invoke(UpdateCartItemRequest $request, string $itemId, CartUpdateItemAction $action): JsonResponse
    {
        try {
            $cart = $action->handle($request->user(), $itemId, $request->validated());

            return $this->successResponse(
                'Cart item updated successfully',
                new CartResource($cart),
            );
        } catch (CartOperationException $e) {
            return $this->errorResponse($e->getMessage(), [], $e->status);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Cart item not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update cart item', $e->getMessage(), 500);
        }
    }
}
