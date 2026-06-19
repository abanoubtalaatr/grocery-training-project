<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username'            => fake()->unique()->userName(),
            'firstname'           => fake()->firstName(),
            'lastname'            => fake()->lastName(),
            'email'               => fake()->unique()->safeEmail(),
            'phone'               => fake()->numerify('010########'),
            'password'            => bcrypt('password'),
            'email_verified'      => true,
            'phone_verified'      => true,
            'agree_terms'         => true,
            'is_active'           => true,
            'is_admin'            => false,
            'loyalty_points'      => fake()->numberBetween(0, 1000),
            'store_credits'       => fake()->randomFloat(2, 0, 500),
            'preferred_languages' => ['en', 'ar'],
            'app_language'        => 'en',
            'app_theme'           => 'light',
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }
}
