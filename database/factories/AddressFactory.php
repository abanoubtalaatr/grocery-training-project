<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->randomElement(['Home', 'Work', 'Other']),
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('1#########'),
            'country_code' => '+20',
            'street_address' => $this->faker->streetAddress(),
            'building_number' => (string) $this->faker->numberBetween(1, 200),
            'floor' => (string) $this->faker->numberBetween(1, 20),
            'apartment' => (string) $this->faker->numberBetween(1, 100),
            'landmark' => $this->faker->boolean() ? $this->faker->word() : null,
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'Egypt',
            'notes' => $this->faker->boolean() ? $this->faker->sentence() : null,
            'is_default' => false,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
