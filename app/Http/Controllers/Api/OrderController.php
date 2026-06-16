<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Actions\Order\GetOrderAction;
use App\Actions\Order\GetOrdersAction;
use App\Actions\Order\TrackOrderAction;
use App\Actions\Order\CreateOrderAction;

class OrderController extends Controller
{
    public function show(Request $request, Order $order, GetOrderAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => $action($order),
        ]);
    }
    
    public function store(StoreOrderRequest $request, CreateOrderAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function index(Request $request, GetOrdersAction $action): JsonResponse
    {
        try {
            $orders = $action($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => $orders,
                'total_count' => $orders->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function track(Request $request, TrackOrderAction $action): JsonResponse
    {
        try {
            $result = $action($request->user());

            if ($result['status'] === 404) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track order',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
