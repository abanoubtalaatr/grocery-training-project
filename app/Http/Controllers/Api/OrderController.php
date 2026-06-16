<?php

namespace App\Http\Controllers\Api;

use App\Actions\StoreOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all user orders.
     */
    public function index(Request $request)
    {
        $orders = Order::query()->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        $order->load(['items.meal', 'address']);

        return new OrderResource($order);
    }

    /**
     * Create a new order.
     */
    public function store(StoreOrderRequest $request, StoreOrder $action): JsonResponse
    {
        return $action->handle($request->validated(), auth()->user());
    }

    /**
     * Calculate order totals.
     */
    private function calculateTotals(float $subtotal): array
    {
        $tax = $subtotal * 0.1; // 10% tax
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
        ];
    }

    /**
     * Process payment for card orders.
     */
    private function processPayment($user, array $validated, float $total): array
    {
        if ($validated['payment_method'] !== 'card') {
            return ['success' => true];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (! $user->stripe_customer_id) {
            return [
                'success' => false,
                'response' => [
                    'success' => false,
                    'message' => 'Stripe customer not found. Please add a payment method first.',
                ],
            ];
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($total * 100),
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $validated['payment_method_id'],
                'off_session' => true,
                'confirm' => true,
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => 'Payment failed: '.$paymentIntent->status,
                    ],
                ];
            }

            return [
                'success' => true,
                'stripe_payment_intent_id' => $paymentIntent->id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => [
                    'success' => false,
                    'message' => 'Payment processing failed: '.$e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Track the last order with status positions.
     */
    public function track(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('user_id', $user->id)
                ->whereNotIn('status', ['cancelled', 'delivered'])
                ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active order found',
                ], 404);
            }

            if ($order->status === 'awaiting_payment') {
                return response()->json([
                    'success' => true,
                    'message' => 'Order is waiting for payment. Complete checkout to continue.',
                    'data' => [
                        'order' => new OrderResource($order),
                        'awaiting_payment' => true,
                        'tracking' => null,
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order tracking retrieved successfully',
                'data' => [
                    'order' => new OrderResource($order),
                    'tracking' => [
                        'position' => $order->status_position,
                        'status' => $order->status,
                        'status_description' => $order->status_description,
                        'positions' => [
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
                        ],
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
