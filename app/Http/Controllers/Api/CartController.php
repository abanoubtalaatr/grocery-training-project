<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Actions\Cart\LoadUserCartAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexCartRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Services\ShippingService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    use ApiResponse;

    public function index(IndexCartRequest $request, LoadUserCartAction $loadUserCart): JsonResponse
    {
        try {
            $cart = $loadUserCart->handle($request->user());
            $cartResource = new CartResource($cart);

            $deliveryType = $request->validated('delivery_type');
            if ($deliveryType) {
                $shippingService = app(ShippingService::class);
                $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $deliveryType);
                $totalWithShipping = (float) $cart->total + $shippingFee;
                $cartResource->withShipping($shippingFee, $totalWithShipping);
            }

            return $this->successResponse(
                'Cart retrieved successfully',
                $cartResource,
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve cart', $e->getMessage(), 500);
        }
    }
}
