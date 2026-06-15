<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Cart\AddCartItemAction;
use App\Http\Actions\Api\Cart\ClearCartAction;
use App\Http\Actions\Api\Cart\GetCartAction;
use App\Http\Actions\Api\Cart\RemoveCartItemAction;
use App\Http\Actions\Api\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddCartItemRequest;
use App\Http\Requests\Api\Cart\UpdateCartItemRequest;
use App\Http\Resources\Api\CartResource;
use App\Models\Cart;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponse;

    public function index(
    Request $request,
    GetCartAction $action
): JsonResponse {

    $cart = $action->execute(
        $request->user(),
        $request->query('delivery_type')
    );

    return $this->successResponse(
        'Cart retrieved successfully',
        new CartResource($cart)
    );
}

    public function addItem(
        AddCartItemRequest $request,
        AddCartItemAction $action
    ): JsonResponse {

        $cart = $action->execute(
            $request->user(),
            $request->validated('meal_id'),
            $request->validated('quantity')
        );

        return $this->successResponse(
            'Item added to cart successfully',
            new CartResource($cart)
        );
    }

    public function updateItem(
        UpdateCartItemRequest $request,
        string $itemId,
        UpdateCartItemAction $action
    ): JsonResponse {

        $cart = $action->execute(
            $request->user(),
            $itemId,
            $request->validated('quantity')
        );

        return $this->successResponse(
            'Cart item updated successfully',
            new CartResource($cart)
        );
    }

    public function removeItem(
        Request $request,
        string $itemId,
        RemoveCartItemAction $action
    ): JsonResponse {

        $cart = $action->execute(
            $request->user(),
            $itemId
        );

        return $this->successResponse(
            'Cart item removed successfully',
            new CartResource($cart)
        );
    }

    public function clear(
        Request $request,
        ClearCartAction $action
    ): JsonResponse {

        $cart = $action->execute(
            $request->user()
        );

        return $this->successResponse(
            'Cart cleared successfully',
            new CartResource($cart)
        );
    }
}