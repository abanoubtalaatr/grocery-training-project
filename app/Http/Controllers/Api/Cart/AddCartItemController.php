<?php

namespace App\Http\Controllers\Api\Cart;

use App\Exceptions\Cart\CartOperationException;
use App\Http\Controllers\Api\Actions\Cart\CartAddItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddCartItemRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AddCartItemController extends Controller
{
    use ApiResponse;

    public function __invoke(AddCartItemRequest $request, CartAddItemAction $action): JsonResponse
    {
        try {
            $cart = $action->handle($request->user(), $request->validated());

            return $this->successResponse(
                'Item added to cart successfully',
                new CartResource($cart),
            );
        } catch (CartOperationException $e) {
            return $this->errorResponse($e->getMessage(), [], $e->status);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Meal not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add item to cart', $e->getMessage(), 500);
        }
    }
}
