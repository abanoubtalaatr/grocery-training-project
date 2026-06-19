<?php
namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 50, 300);
        $qty   = fake()->numberBetween(1, 5);

        return [
            'quantity'        => $qty,
            'unit_price'      => $price,
            'discount_amount' => 0,
            'subtotal'        => $price * $qty,
        ];
    }
}
