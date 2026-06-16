<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddItemRequest;
use App\Http\Requests\Cart\UpdateItemRequest;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\AddItemToCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Actions\Cart\RemoveItemFromCartAction;
use App\Actions\Cart\ClearCartAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, GetCartAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->query('delivery_type'));

            return response()->json([
                'success' => true,
                'message' => 'Cart retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function addItem(AddItemRequest $request, AddItemToCartAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateItem(UpdateItemRequest $request, string $itemId, UpdateCartItemAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $itemId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        } catch (\Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeItem(Request $request, string $itemId, RemoveItemFromCartAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $itemId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function clear(Request $request, ClearCartAction $action): JsonResponse
    {
        try {
            $data = $action($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
