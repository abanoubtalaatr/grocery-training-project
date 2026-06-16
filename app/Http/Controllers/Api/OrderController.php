<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class OrderController extends Controller
{
    use ApiResponse;

   
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->latest()
            ->get();

        return $this->successResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

   
    public function show(Order $order): JsonResponse
    {
        return $this->successResponse(
            new OrderResource($order->load(['items.meal', 'address'])), 
            'Order retrieved successfully'
        );
    }

  
    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction): JsonResponse
    {
        // تنفيذ الـ Action مباشرة، وإذا حدث أي Exception ستصعد تلقائيًا للـ Global Exception Handler
        $order = $createOrderAction->execute($request->user(), $request->validated());
        
        return $this->successResponse(
            new OrderResource($order->load(['items.meal', 'address'])), 
            'Order created successfully', 
            201
        );
    }

 
    public function track(Request $request): JsonResponse
    {
        $order = $request->user()->orders()
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->latest()
            ->first();

        if (!$order) {
            return $this->errorResponse('No active order found', 404);
        }

        if ($order->status === 'awaiting_payment') {
            return $this->successResponse([
                'order'            => new OrderResource($order), 
                'awaiting_payment' => true, 
                'tracking'         => null
            ], 'Order is waiting for payment.');
        }

        return $this->successResponse([
            'order'    => new OrderResource($order),
            'tracking' => [
                'position'           => $order->status_position,
                'status'             => $order->status,
                'status_description' => $order->status_description,
                'positions'          => $order->getTrackingPositionsArray(),
            ]
        ], 'Order tracking retrieved successfully');
    }
}