<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;

class OrderController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getOrders($request->user());
        $formattedOrders = $orders->map(fn($order) => $this->orderService->formatOrder($order));

        return self::collectionResponse('Orders retrieved successfully', $formattedOrders);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['items.meal', 'address']);
        return self::successResponse('Order retrieved successfully', $this->orderService->formatOrder($order));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $result = $this->orderService->createOrder($request->user(), $request->validated());

        if (!$result['success']) {
            return self::errorResponse($result['message'], null, $result['code'] ?? 400);
        }

        return self::successResponse('Order created successfully', $this->orderService->formatOrder($result['order']), 201);
    }

    public function track(Request $request): JsonResponse
    {
        $order = $this->orderService->getLatestActiveOrder($request->user());

        if (!$order) {
            return self::errorResponse('No active order found', null, 404);
        }

        if ($order->status === 'awaiting_payment') {
            return self::successResponse('Order is waiting for payment. Complete checkout to continue.', [
                'order' => $this->orderService->formatOrder($order),
                'awaiting_payment' => true,
                'tracking' => null,
            ]);
        }

        return self::successResponse('Order tracking retrieved successfully', [
            'order' => $this->orderService->formatOrder($order),
            'tracking' => [
                'position' => $order->status_position,
                'status' => $order->status,
                'status_description' => $order->status_description,
                'positions' => $this->formatTrackingPositions($order),
            ],
        ]);
    }

    private function formatTrackingPositions(Order $order): array
    {
        return [
            [
                'position' => 1,
                'status' => 'placed',
                'label' => 'Order Placed',
                'description' => 'Your order has been placed',
                'completed' => in_array($order->status, ['placed', 'processing', 'shipping', 'out_for_delivery', 'delivered']),
                'timestamp' => $order->placed_at,
            ],
            [
                'position' => 2,
                'status' => 'processing',
                'label' => 'Processing',
                'description' => 'Your order is being processed',
                'completed' => in_array($order->status, ['processing', 'shipping', 'out_for_delivery', 'delivered']),
                'timestamp' => $order->processing_at,
            ],
            [
                'position' => 3,
                'status' => 'shipping',
                'label' => 'Shipping',
                'description' => 'Your order is being shipped',
                'completed' => in_array($order->status, ['shipping', 'out_for_delivery', 'delivered']),
                'timestamp' => $order->shipping_at,
            ],
            [
                'position' => 4,
                'status' => 'out_for_delivery',
                'label' => 'Out for Delivery',
                'description' => 'Your order is on the way',
                'completed' => in_array($order->status, ['out_for_delivery', 'delivered']),
                'timestamp' => $order->out_for_delivery_at,
            ],
            [
                'position' => 5,
                'status' => 'delivered',
                'label' => 'Delivered',
                'description' => 'Your order has been delivered',
                'completed' => $order->status === 'delivered',
                'timestamp' => $order->delivered_at,
            ],
        ];
    }
}
