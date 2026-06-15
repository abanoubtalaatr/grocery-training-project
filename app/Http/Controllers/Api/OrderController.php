<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderTrackingResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Get single order.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $this->authorizeOwner($request, $order);

        $order->load(['items.meal', 'address']);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Create a new order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = CreateOrderAction::run($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * Get all user orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => OrderResource::collection($orders),
            'total_count' => $orders->count(),
        ]);
    }

    /**
     * Track the last order with status positions.
     */
    public function track(Request $request): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No active order found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $order->status === 'awaiting_payment'
                ? 'Order is waiting for payment. Complete checkout to continue.'
                : 'Order tracking retrieved successfully',
            'data' => new OrderTrackingResource($order),
        ]);
    }

    /**
     * Authorize that the order belongs to the user.
     */
    private function authorizeOwner(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) {
            abort(404, 'Order not found');
        }
    }
}
