<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\AddItemToCartAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Meal;
use App\Services\GetCartWithShippingService;
use App\Services\ShippingService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Get user's cart
     */

    use ApiResponse;

    public function index(Request $request, GetCartWithShippingService $service): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        [$cart, $shippingFee, $totalWithShipping] = $service->execute($cart, $request->query('delivery_type'));

        return $this->successResponse(new CartResource($cart, $shippingFee, $totalWithShipping), 'Cart retrieved successfully');
    }
    
   
    public function addItem(AddToCartRequest $request, AddItemToCartAction $action): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $updatedCart = $action->execute($cart, $request->validated('meal_id'), $request->validated('quantity'));

        return $this->successResponse(new CartResource($updatedCart), 'Item added to cart successfully');
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(UpdateCartItemRequest $request, string $itemId, UpdateCartItemAction $action): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $updatedCart = $action->execute($cart, $itemId, $request->validated('quantity'));

        return $this->successResponse(new CartResource($updatedCart), 'Cart item updated successfully');
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, string $itemId, RemoveCartItemAction $action): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $updatedCart = $action->execute($cart, $itemId);

        return $this->successResponse(new CartResource($updatedCart), 'Item removed from cart successfully');
    }

    /**
     * Clear cart
     */
    public function clear(Request $request, ClearCartAction $action): JsonResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $updatedCart = $action->execute($cart);

        return $this->successResponse(new CartResource($updatedCart), 'Cart cleared successfully');
    }
}

   