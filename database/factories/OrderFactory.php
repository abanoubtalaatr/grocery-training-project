<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 1000);

        $discount = fake()->randomFloat(2, 0, 50);

        $shipping = 30;

        $tax = round($subtotal * 0.14, 2);

        return [
            'user_id'           => User::inRandomOrder()->first()?->id ?? User::factory(),

            'payment_method'    => fake()->randomElement([
                'card',
                'cash_on_delivery',
                'stripe_checkout',
            ]),

            'delivery_type'     => fake()->randomElement([
                'delivery',
                'pickup',
            ]),

            'status'            => fake()->randomElement([
                'awaiting_payment',
                'placed',
                'processing',
                'shipping',
                'out_for_delivery',
                'delivered',
            ]),

            'subtotal'          => $subtotal,
            'tax'               => $tax,
            'discount'          => $discount,
            'shipping_fee'      => $shipping,
            'total'             => $subtotal + $tax + $shipping - $discount,

            'placed_at'         => now()->subDays(rand(1, 30)),

            'schedule_delivery' => null,
            'delivery_speed'    => fake()->randomElement([
                'normal',
                'express',
            ]),
        ];
    }
}
