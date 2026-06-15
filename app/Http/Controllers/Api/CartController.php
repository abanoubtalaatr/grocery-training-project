<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\Meal;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $cart->load(['items.meal.category', 'items.meal.subcategory']);

        $deliveryType = $request->query('delivery_type');
        $shippingFee = null;
        $totalWithShipping = null;

        if ($deliveryType && in_array($deliveryType, ['delivery', 'pickup'], true)) {
            $shippingService = app(ShippingService::class);
            $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
            $totalWithShipping = (float) $cart->total + $shippingFee;
        }

        $resource = (new CartResource($cart))->withShipping($shippingFee, $totalWithShipping);

        return response()->json([
            'success' => true,
            'message' => 'Cart retrieved successfully',
            'data' => $resource,
        ]);
    }

    /**
     * Add item to cart
     */
    public function addItem(AddToCartRequest $request): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $meal = Meal::findOrFail($request->validated('meal_id'));

        $cart = AddToCartAction::run($cart, $meal, (int) $request->validated('quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(UpdateCartItemRequest $request, string $itemId): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();

        $cart = UpdateCartItemAction::run($cart, $itemId, (int) $request->validated('quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, string $itemId): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();

        $cart = RemoveCartItemAction::run($cart, $itemId);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();

        $cart = ClearCartAction::run($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'data' => new CartResource($cart),
        ]);
    }
}
