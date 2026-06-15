<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Meal;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Actions\Cart\AddCartItemAction;
use App\Http\Requests\AddCartItemRequest;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Requests\UpdateCartItemRequest;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\ClearCartAction;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(
    Request $request,
    GetCartAction $action
): JsonResponse
{
    $result = $action->execute(
        $request->user(),
        $request->query('delivery_type')
    );

    return response()->json([
        'success' => true,
        'message' => 'Cart retrieved successfully',
        'data' => $this->formatCart(
            $result['cart'],
            $result['shipping_fee'],
            $result['total_with_shipping']
        ),
    ]);
}




    /**
     * Add item to cart
     */
    public function addItem(
    AddCartItemRequest $request,
    AddCartItemAction $action
): JsonResponse
{
    $cart = $action->execute(
        $request->user(),
        $request->validated()
    );

    return response()->json([
        'success' => true,
        'message' => 'Item added to cart successfully',
        'data' => new CartResource($cart),
    ]);
}

    /**
     * Update cart item quantity
     */
 public function updateItem(
    UpdateCartItemRequest $request,
    string $itemId,
    UpdateCartItemAction $action
): JsonResponse
{
    $cart = $action->execute(
        $request->user(),
        $itemId,
        $request->validated()['quantity']
    );

    return response()->json([
        'success' => true,
        'message' => 'Cart item updated successfully',
        'data' => new CartResource($cart),
    ]);
}

    /**
     * Remove item from cart
     */
 public function removeItem(
    Request $request,
    string $itemId,
    RemoveCartItemAction $action
): JsonResponse
{
    $cart = $action->execute(
        $request->user(),
        $itemId
    );

    return response()->json([
        'success' => true,
        'message' => 'Item removed from cart successfully',
        'data' => new CartResource($cart),
    ]);
}

    /**
     * Clear cart
     */
   public function clear(
    Request $request,
    ClearCartAction $action
): JsonResponse
{
    $cart = $action->execute(
        $request->user()
    );

    return response()->json([
        'success' => true,
        'message' => 'Cart cleared successfully',
        'data' => new CartResource($cart),
    ]);
}

}