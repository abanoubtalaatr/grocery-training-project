<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartClearController extends Controller
{
    use ApiResponse;

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Clear the whole cart.
     */
    public function destroy(Request $request): JsonResponse
    {
        $cart = $this->cartService->clearCart($request->user()->getOrCreateCart());
        return self::successResponse('Cart cleared successfully', $this->cartService->formatCart($cart));
    }
}
