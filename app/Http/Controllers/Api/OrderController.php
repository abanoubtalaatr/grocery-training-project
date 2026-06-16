<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderTrackingResource;
use App\Http\Actions\Api\Order\GetOrderAction;
use App\Http\Actions\Api\Order\TrackOrderAction;
use App\Http\Actions\Api\Order\CreateOrderAction;
use App\Http\Actions\Api\Order\GetOrdersAction;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(
        Request $request,
        GetOrdersAction $action
    ): JsonResponse {

        $orders = $action->execute(
            $request->user()
        );

        return $this->successResponse(
            'Orders retrieved successfully',
            OrderResource::collection($orders)
        );
    }

    public function show(
        Order $order,
        GetOrderAction $action
    ): JsonResponse {

        return $this->successResponse(
            'Order retrieved successfully',
            new OrderResource(
                $action->execute($order)
            )
        );
    }

    public function store(
        StoreOrderRequest $request,
        CreateOrderAction $action
    ): JsonResponse {

        $order = $action->execute(
            $request->user(),
            $request->validated()
        );

        return $this->successResponse(
            'Order created successfully',
            new OrderResource($order),
            201
        );
    }

    public function track(
        Request $request,
        TrackOrderAction $action
    ): JsonResponse {

        $order = $action->execute(
            $request->user()
        );

        if (!$order) {
            return $this->errorResponse(
                'No active order found',
                [],
                404
            );
        }

        return $this->successResponse(
            'Order tracking retrieved successfully',
            new OrderTrackingResource($order)
        );
    }
}