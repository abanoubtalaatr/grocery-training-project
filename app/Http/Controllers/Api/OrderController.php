<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\OrderTrackingResource;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Services\StripeCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private CartService $cartService,
        private ShippingService $shippingService,
        private StripeCheckoutService $stripeService,
    ) {}

    public function show(Order $order)
    {
        $order = $order->load(['items.meal', 'address']);

        return ApiResponse::success(new OrderResource($order), 'Order details retrieved successfully');
    }

    /**
     * Create a new order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Get user's active cart
            $cart = $user->activeCart()->with('items.meal')->first();

            if (! $cart || $cart->isEmpty()) {
                return ApiResponse::error('Your cart is empty. Please add items to your cart before placing an order.', 400);
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Your cart is empty. Please add items to your cart before placing an order.',
                // ], 400);
            }

            // Validate and process items from cart
            $itemsResult = $this->cartService->validateAndProcessCartItems($cart->items);

            // if (! $itemsResult['success']) {
            //     return ApiResponse::error($itemsResult['response']['message'], 400);
            // }

            $items = $itemsResult['items'];

            // Calculate totals and shipping (use cart totals; add shipping for delivery)
            $cart->calculateTotals();

            $shippingFee = $this->shippingService->calculateShippingFee((float) $cart->subtotal, $validated['delivery_type']);
            $totals = $this->shippingService->calculateOrderTotals($cart, $shippingFee);

            $total = $totals['total'];

            // Validate amount matches cart total

            // if (abs($total - $validated['amount']) > 0.01) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Amount mismatch. Please recalculate your order.',
            //         'calculated_total' => $total,
            //         'provided_amount' => $validated['amount'],
            //     ], 400);
            // }

            $this->stripeService->validateTotal($total, $validated['amount']);

            DB::beginTransaction();

            $paymentResult = match ($validated['payment_method']) {
                'stripe_checkout' => ['success' => true],
                default => $this->stripeService->processPayment($user, $validated, $total),
            };

            if (! $paymentResult['success']) {
                DB::rollBack();

                return ApiResponse::error($paymentResult['response'], 400);
            }

            $stripePaymentIntentId = $paymentResult['stripe_payment_intent_id'] ?? null;

            // Create order
            $order = $this->orderService->createOrder($user, $validated, $totals['subtotal'], $totals, $stripePaymentIntentId);

            // Create order items and update stock

            $this->orderService->createOrderItems($order, $items);

            // Clear user's active cart
            $this->cartService->clearUserCart($user);

            // if (isset($validated['special_note_id'])) {
            //     OrderNote::create([
            //         'order_id' => $order->id,
            //         'special_note_id' => $validated['special_note_id'],
            //         'notes' => $validated['notes'] ?? null,
            //     ]);
            // }
            // if (isset($validated['notes'])) {
            //     OrderNote::create([
            //         'order_id' => $order->id,
            //         'special_note_id' => null,
            //         'notes' => $validated['notes'],
            //     ]);
            // }
            $this->orderService->handleOrderNotes($order, $validated);

            // Commit transaction
            DB::commit();

            $order->load(['items.meal', 'address']);

            return ApiResponse::success(new OrderResource($order), 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::error('Failed to create order: '.$e->getMessage(), 500);
        }
    }

    /**
     * Validate and process order items from cart.
     */
    // private function validateAndProcessCartItems($cartItems): array
    // {
    //     $items = [];
    //     $subtotal = 0;

    //     foreach ($cartItems as $cartItem) {
    //         $meal = $cartItem->meal;

    //         if (! $meal) {
    //             return [
    //                 'success' => false,
    //                 'response' => [
    //                     'success' => false,
    //                     'message' => 'One or more items in your cart are no longer available.',
    //                 ],
    //             ];
    //         }

    //         if (! $meal->is_available) {
    //             return [
    //                 'success' => false,
    //                 'response' => [
    //                     'success' => false,
    //                     'message' => "Meal '{$meal->title}' is currently unavailable",
    //                 ],
    //             ];
    //         }

    //         if ($meal->stock_quantity < $cartItem->quantity) {
    //             return [
    //                 'success' => false,
    //                 'response' => [
    //                     'success' => false,
    //                     'message' => "Only {$meal->stock_quantity} items available for '{$meal->title}'",
    //                 ],
    //             ];
    //         }

    //         $maxPerProduct = config('cart.max_quantity_per_product', 10);
    //         if ($cartItem->quantity > $maxPerProduct) {
    //             return [
    //                 'success' => false,
    //                 'response' => [
    //                     'success' => false,
    //                     'message' => "Maximum {$maxPerProduct} units per product allowed. Please reduce quantity for '{$meal->title}'.",
    //                 ],
    //             ];
    //         }

    //         // Use cart item pricing (already calculated)
    //         $items[] = [
    //             'meal' => $meal,
    //             'quantity' => $cartItem->quantity,
    //             'unit_price' => $cartItem->unit_price,
    //             'discount_amount' => $cartItem->discount_amount,
    //             'subtotal' => $cartItem->subtotal,
    //         ];

    //         $subtotal += $cartItem->subtotal;
    //     }

    //     return [
    //         'success' => true,
    //         'items' => $items,
    //         'subtotal' => $subtotal,
    //     ];
    // }
    // private function validateAndProcessCartItems($cartItems): array
    // {
    //     $items = [];
    //     $subtotal = 0;

    //     foreach ($cartItems as $cartItem) {
    //         $meal = $cartItem->meal;

    //         if (! $meal) {
    //             throw new CartValidationException('One or more items are no longer available.');
    //         }

    //         if (! $meal->is_available) {
    //             throw new CartValidationException("Meal '{$meal->title}' is currently unavailable");
    //         }

    //         if ($meal->stock_quantity < $cartItem->quantity) {
    //             throw new CartValidationException("Only {$meal->stock_quantity} items available for '{$meal->title}'");
    //         }

    //         $max = config('cart.max_quantity_per_product', 10);
    //         if ($cartItem->quantity > $max) {
    //             throw new CartValidationException("Max {$max} units allowed for '{$meal->title}'");
    //         }

    //         $items[] = [
    //             'meal' => $meal,
    //             'quantity' => $cartItem->quantity,
    //             'unit_price' => $cartItem->unit_price,
    //             'discount_amount' => $cartItem->discount_amount,
    //             'subtotal' => $cartItem->subtotal,
    //         ];

    //         $subtotal += $cartItem->subtotal;
    //     }

    //     return compact('items', 'subtotal');
    // }

    /**
     * Calculate order totals.
     */
    // private function calculateTotals(float $subtotal): array
    // {
    //     $tax = $subtotal * 0.1; // 10% tax
    //     $discount = 0;
    //     $total = $subtotal + $tax - $discount;

    //     return [
    //         'subtotal' => $subtotal,
    //         'tax' => $tax,
    //         'discount' => $discount,
    //         'total' => $total,
    //     ];
    // }

    /**
     * Create order record.
     */
    // private function createOrder($user, array $validated, float $subtotal, array $totals, ?string $stripePaymentIntentId = null): Order
    // {
    //     $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

    //     return Order::create([
    //         'user_id' => $user->id,
    //         'address_id' => $validated['delivery_type'] === 'delivery' ? $validated['address_id'] : null,
    //         'payment_method' => $validated['payment_method'],
    //         'payment_method_id' => null,
    //         'stripe_payment_intent_id' => $stripePaymentIntentId,
    //         'delivery_type' => $validated['delivery_type'],
    //         'status' => $isHostedStripe ? 'awaiting_payment' : 'placed',
    //         'subtotal' => $subtotal,
    //         'tax' => $totals['tax'],
    //         'discount' => $totals['discount'],
    //         'shipping_fee' => $totals['shipping_fee'],
    //         'total' => $totals['total'],
    //         'notes' => $validated['notes'] ?? null,
    //         'placed_at' => $isHostedStripe ? null : now(),
    //     ]);
    // }

    // /**
    //  * Create order items and update stock.
    //  */
    // private function createOrderItems(Order $order, array $items): void
    // {
    //     foreach ($items as $item) {
    //         OrderItem::create([
    //             'order_id' => $order->id,
    //             'meal_id' => $item['meal']->id,
    //             'quantity' => $item['quantity'],
    //             'unit_price' => $item['unit_price'],
    //             'discount_amount' => $item['discount_amount'],
    //             'subtotal' => $item['subtotal'],
    //         ]);

    //         $item['meal']->decrement('stock_quantity', $item['quantity']);
    //     }
    // }

    /**
     * Clear user's active cart.
     */
    // private function clearUserCart($user): void
    // {
    //     $cart = $user->activeCart()->first();
    //     if ($cart) {
    //         $cart->items()->delete();
    //         $cart->update(['status' => 'completed']);
    //     }
    // }

    /**
     * Get all user orders.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return ApiResponse::success(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

    /**
     * Track the last order with status positions.
     */
    public function track(Request $request): JsonResponse
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $order) {
            return ApiResponse::error('No active order found for tracking.', 404);
        }

        if ($order->status === 'awaiting_payment') {
            return ApiResponse::success([
                'order' => new OrderResource($order),
                'awaiting_payment' => true,
                'tracking' => null,
            ],
                'Order is waiting for payment. Complete checkout to continue.');
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Order is waiting for payment. Complete checkout to continue.',
            //     'data' => [
            //         'order' => $this->formatOrder($order),
            //         'awaiting_payment' => true,
            //         'tracking' => null,
            //     ],
            // ]);
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Order tracking retrieved successfully',
        //     'data' => [
        //         'order' => $this->formatOrder($order),
        //         'tracking' => [
        //             'position' => $order->status_position,
        //             'status' => $order->status,
        //             'status_description' => $order->status_description,
        //             'positions' => [
        //                 [
        //                     'position' => 1,
        //                     'status' => 'placed',
        //                     'label' => 'Order Placed',
        //                     'description' => 'Your order has been placed',
        //                     'completed' => in_array($order->status, ['placed', 'processing', 'shipping', 'out_for_delivery', 'delivered']),
        //                     'timestamp' => $order->placed_at,
        //                 ],
        //                 [
        //                     'position' => 2,
        //                     'status' => 'processing',
        //                     'label' => 'Processing',
        //                     'description' => 'Your order is being processed',
        //                     'completed' => in_array($order->status, ['processing', 'shipping', 'out_for_delivery', 'delivered']),
        //                     'timestamp' => $order->processing_at,
        //                 ],
        //                 [
        //                     'position' => 3,
        //                     'status' => 'shipping',
        //                     'label' => 'Shipping',
        //                     'description' => 'Your order is being shipped',
        //                     'completed' => in_array($order->status, ['shipping', 'out_for_delivery', 'delivered']),
        //                     'timestamp' => $order->shipping_at,
        //                 ],
        //                 [
        //                     'position' => 4,
        //                     'status' => 'out_for_delivery',
        //                     'label' => 'Out for Delivery',
        //                     'description' => 'Your order is on the way',
        //                     'completed' => in_array($order->status, ['out_for_delivery', 'delivered']),
        //                     'timestamp' => $order->out_for_delivery_at,
        //                 ],
        //                 [
        //                     'position' => 5,
        //                     'status' => 'delivered',
        //                     'label' => 'Delivered',
        //                     'description' => 'Your order has been delivered',
        //                     'completed' => $order->status === 'delivered',
        //                     'timestamp' => $order->delivered_at,
        //                 ],
        //             ],
        //         ],
        //     ],
        // ]);
        return ApiResponse::success(new OrderTrackingResource($order), 'Order tracking retrieved successfully');

    }

    /**
     * Format order data for response.
     */
    // private function formatOrder(Order $order): array
    // {
    //     return [
    //         'id' => $order->id,
    //         'order_number' => $order->order_number,
    //         'payment_method' => $order->payment_method,
    //         'stripe_payment_intent_id' => $order->stripe_payment_intent_id,
    //         'delivery_type' => $order->delivery_type,
    //         'status' => $order->status,
    //         'status_position' => $order->status_position,
    //         'status_description' => $order->status_description,
    //         'items' => $order->items->map(function ($item) {
    //             return [
    //                 'id' => $item->id,
    //                 'meal' => [
    //                     'id' => $item->meal->id,
    //                     'title' => $item->meal->title,
    //                     'slug' => $item->meal->slug,
    //                     'image_url' => $item->meal->image_url,
    //                     ...$item->meal->getApiPriceAttributes(),
    //                     'category' => $item->meal->category ? [
    //                         'id' => $item->meal->category->id,
    //                         'name' => $item->meal->category->name,
    //                     ] : null,
    //                     'subcategory' => $item->meal->subcategory ? [
    //                         'id' => $item->meal->subcategory->id,
    //                         'name' => $item->meal->subcategory->name,
    //                     ] : null,
    //                 ],
    //                 'quantity' => $item->quantity,
    //                 'unit_price' => (float) $item->unit_price,
    //                 'discount_amount' => (float) $item->discount_amount,
    //                 'subtotal' => (float) $item->subtotal,
    //             ];
    //         }),
    //         'address' => $order->address ? [
    //             'id' => $order->address->id,
    //             'label' => $order->address->label,
    //             'full_name' => $order->address->full_name,
    //             'phone' => $order->address->phone,
    //             'country_code' => $order->address->country_code,
    //             'street_address' => $order->address->street_address,
    //             'building_number' => $order->address->building_number,
    //             'floor' => $order->address->floor,
    //             'apartment' => $order->address->apartment,
    //             'landmark' => $order->address->landmark,
    //             'city' => $order->address->city,
    //             'state' => $order->address->state,
    //             'postal_code' => $order->address->postal_code,
    //             'country' => $order->address->country,
    //             'full_address' => $order->address->full_address,
    //             'latitude' => $order->address->latitude,
    //             'longitude' => $order->address->longitude,
    //         ] : null,
    //         'subtotal' => $order->subtotal,
    //         'tax' => $order->tax,
    //         'discount' => $order->discount,
    //         'shipping_fee' => (float) ($order->shipping_fee ?? 0),
    //         'total' => $order->total,
    //         'notes' => $order->notes,
    //         'created_at' => $order->created_at,
    //         'updated_at' => $order->updated_at,
    //         'placed_at' => $order->placed_at,
    //         'processing_at' => $order->processing_at,
    //         'shipping_at' => $order->shipping_at,
    //         'out_for_delivery_at' => $order->out_for_delivery_at,
    //         'delivered_at' => $order->delivered_at,
    //         'estimated_delivery_time' => $order->estimated_delivery_time,
    //         'special_note' => $order->special_note,
    //         'schedule_delivery' => $order->schedule_delivery,
    //         'delivery_speed' => $order->delivery_speed,
    //     ];
    // }
}
