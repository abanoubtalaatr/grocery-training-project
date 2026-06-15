<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\GetOrdersAction;
use App\Actions\Order\TrackOrderAction;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponse;

class OrderController extends Controller
{
    use ApiResponse;

    public function show(Request $request, Order $order)
    {
        $order = $order->load(['items.meal', 'address']);

        return $this->success('Order retrieved successfully', new OrderResource($order));
    }
    
    /**
     * Create a new order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = (new CreateOrderAction())->execute($request->user(), $request->validated());

        $order->load(['items.meal', 'address']);

        return $this->created('Order created successfully', new OrderResource($order));
    }

    /**
     * Validate and process order items from cart.
     */
    private function validateAndProcessCartItems($cartItems): array
    {
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $meal = $cartItem->meal;

            if (!$meal) {
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => 'One or more items in your cart are no longer available.',
                    ],
                ];
            }

            if (!$meal->is_available) {
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => "Meal '{$meal->title}' is currently unavailable",
                    ],
                ];
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => "Only {$meal->stock_quantity} items available for '{$meal->title}'",
                    ],
                ];
            }

            $maxPerProduct = config('cart.max_quantity_per_product', 10);
            if ($cartItem->quantity > $maxPerProduct) {
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => "Maximum {$maxPerProduct} units per product allowed. Please reduce quantity for '{$meal->title}'.",
                    ],
                ];
            }

            // Use cart item pricing (already calculated)
            $items[] = [
                'meal' => $meal,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal' => $cartItem->subtotal,
            ];

            $subtotal += $cartItem->subtotal;
        }

        return [
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
        ];
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

        if (!$user->stripe_customer_id) {
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
                'amount' => (int)($total * 100),
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
                        'message' => 'Payment failed: ' . $paymentIntent->status,
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
                    'message' => 'Payment processing failed: ' . $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Create order record.
     */
    private function createOrder($user, array $validated, float $subtotal, array $totals, ?string $stripePaymentIntentId = null): Order
    {
        $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

        return Order::create([
            'user_id' => $user->id,
            'address_id' => $validated['delivery_type'] === 'delivery' ? $validated['address_id'] : null,
            'payment_method' => $validated['payment_method'],
            'payment_method_id' => null,
            'stripe_payment_intent_id' => $stripePaymentIntentId,
            'delivery_type' => $validated['delivery_type'],
            'status' => $isHostedStripe ? 'awaiting_payment' : 'placed',
            'subtotal' => $subtotal,
            'tax' => $totals['tax'],
            'discount' => $totals['discount'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'notes' => $validated['notes'] ?? null,
            'placed_at' => $isHostedStripe ? null : now(),
        ]);
    }

    /**
     * Create order items and update stock.
     */
    private function createOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $item['meal']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_amount' => $item['discount_amount'],
                'subtotal' => $item['subtotal'],
            ]);

            $item['meal']->decrement('stock_quantity', $item['quantity']);
        }
    }

    /**
     * Clear user's active cart.
     */
    private function clearUserCart($user): void
    {
        $cart = $user->activeCart()->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);
        }
    }

    /**
     * Get all user orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = (new GetOrdersAction())->execute($request->user());

        return response()->json(['success' => true, 'message' => 'Orders retrieved successfully', 'data' => OrderResource::collection($orders), 'total_count' => $orders->count()]);
    }

    /**
     * Track the last order with status positions.
     */
    public function track(Request $request): JsonResponse
    {
        $order = (new TrackOrderAction())->execute($request->user());

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'No active order found'], 404);
        }

        if ($order->status === 'awaiting_payment') {
            return response()->json(['success' => true, 'message' => 'Order is waiting for payment. Complete checkout to continue.', 'data' => ['order' => new OrderResource($order), 'awaiting_payment' => true, 'tracking' => null]]);
        }

        return response()->json(['success' => true, 'message' => 'Order tracking retrieved successfully', 'data' => ['order' => new OrderResource($order), 'tracking' => [
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
        ]]]);
    }
}
