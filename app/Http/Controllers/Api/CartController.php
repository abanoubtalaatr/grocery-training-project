<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get user's cart
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->cartService->getCart($request->user(), $request->query('delivery_type'));
        return self::successResponse('Cart retrieved successfully', $data);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request): JsonResponse
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);
        $request->validate([
            'meal_id' => ['required', 'exists:meals,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxPerProduct],
        ]);

        try {
            $cart = $this->cartService->addItem($request->user(), $request->meal_id, $request->quantity);
            return self::successResponse('Item added to cart successfully', $this->cartService->formatCart($cart));
        } catch (\Exception $e) {
            return self::errorResponse($e->getMessage(), null, 400);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, string $itemId): JsonResponse
    {
        $maxPerProduct = config('cart.max_quantity_per_product', 10);
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxPerProduct],
        ]);

        try {
            $cart = $this->cartService->updateItem($request->user()->getOrCreateCart(), (int) $itemId, $request->quantity);
            return self::successResponse('Cart item updated successfully', $this->cartService->formatCart($cart));
        } catch (\Exception $e) {
            return self::errorResponse($e->getMessage(), null, 400);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, string $itemId): JsonResponse
    {
        $cart = $this->cartService->removeItem($request->user()->getOrCreateCart(), (int) $itemId);
        return self::successResponse('Item removed from cart successfully', $this->cartService->formatCart($cart));
    }
}
