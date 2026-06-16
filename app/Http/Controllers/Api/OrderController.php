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

    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getOrders($request->user(), $request);
        $formattedOrders = $orders->map(fn($order) => $this->orderService->formatOrder($order));

        return self::collectionResponse('Orders retrieved successfully', $formattedOrders);
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $result = $this->orderService->createOrder($request->user(), $request->validated());

        if (!$result['success']) {
            return self::errorResponse($result['message'], null, $result['code'] ?? 400);
        }

        return self::successResponse('Order created successfully', $this->orderService->formatOrder($result['order']), 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['items.meal', 'address']);
        return self::successResponse('Order retrieved successfully', $this->orderService->formatOrder($order));
    }
}
