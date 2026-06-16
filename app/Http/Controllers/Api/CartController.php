<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\AddCartItemAction;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\IndexCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(IndexCartRequest $request, GetCartAction $action): JsonResponse
    {
        $cart = $action->execute($request->user(), $request->validated('delivery_type'));

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully',
            'data' => CartResource::make($cart),
        ]);
    }

    public function addItem(AddCartItemRequest $request, AddCartItemAction $action): JsonResponse
    {
        $cart = $action->execute($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => CartResource::make($cart),
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => CartResource::make($cart),
        ]);
    }

    public function removeItem(
        Request $request,
        string $itemId,
        RemoveCartItemAction $action
    ): JsonResponse {
        $cart = $action->execute($request->user(), $itemId);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'data' => CartResource::make($cart),
        ]);
    }
    public function clear(Request $request): JsonResponse
        {
    $cart = $request->user()->getOrCreateCart();

    $cart->items()->delete();
    $cart->calculateTotals();

    return response()->json([
        'success' => true,
        'message' => 'Cart cleared successfully',
        'data' => CartResource::make(
            $cart->load([
                'items.meal.category',
                'items.meal.subcategory',
            ])
         ),
            ]);
        }
}
