<?php
namespace App\Actions\Order;

use App\Models\{Order, User};
use App\Services\{CartService, ShippingService};
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function __construct(
        protected CartService $cartService,
        protected ShippingService $shippingService
    ) {}

    public function execute(User $user, array $data): Order
    {
        $cart = $user->activeCart()->with('items.meal')->first();

        if (!$cart || $cart->isEmpty()) {
            throw new \InvalidArgumentException('Your cart is empty.');
        }

       
        $processedCart = $this->cartService->validateAndProcessItems($cart);

        return DB::transaction(function () use ($user, $cart, $data, $processedCart) {
            $cart->calculateTotals();
            $shippingFee = $this->shippingService->calculateShippingFee((float) $processedCart['subtotal'], $data['delivery_type']);
            
            $totals = [
                'tax'          => $cart->tax,
                'discount'     => $cart->discount,
                'shipping_fee' => $shippingFee,
                'total'        => (float) $processedCart['subtotal'] + (float) $cart->tax + $shippingFee,
            ];

         
            $order = Order::create([
                'user_id'           => $user->id,
                'address_id'        => $data['delivery_type'] === 'delivery' ? $data['address_id'] : null,
                'payment_method'    => $data['payment_method'],
                'delivery_type'     => $data['delivery_type'],
                'status'            => $data['payment_method'] === 'stripe_checkout' ? 'awaiting_payment' : 'placed',
                'subtotal'          => $processedCart['subtotal'],
                'tax'               => $totals['tax'],
                'discount'          => $totals['discount'],
                'shipping_fee'      => $totals['shipping_fee'],
                'total'             => $totals['total'],
                'notes'             => $data['notes'] ?? null,
                'placed_at'         => $data['payment_method'] === 'stripe_checkout' ? null : now(),
            ]);

          
            foreach ($processedCart['items'] as $item) {
                $order->items()->create([
                    'meal_id'         => $item['meal']->id,
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'],
                    'subtotal'        => $item['subtotal'],
                ]);
                $item['meal']->decrement('stock_quantity', $item['quantity']);
            }

           
            if (!empty($data['special_note_id'])) {
                $order->notes()->create(['special_note_id' => $data['special_note_id'], 'notes' => $data['notes'] ?? null]);
            } elseif (!empty($data['notes'])) {
                $order->notes()->create(['notes' => $data['notes']]);
            }

           
            $this->cartService->clearCart($cart);

            return $order;
        });
    }
}