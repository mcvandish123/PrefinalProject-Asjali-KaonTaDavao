<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\Eatery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'eatery_id' => Eatery::factory(),
            'name' => fake()->randomElement(['Tinola', 'Kinilaw', 'Sinigang', 'Adobo', 'Durian Shake', 'Grilled Tuna Panga', 'Sisig', 'Halo-halo']),
            'price' => fake()->randomFloat(2, 40, 350),
            'is_bestseller' => false,
        ];
    }

    /**
     * Indicate that the dish is a bestseller.
     */
    public function bestseller(): static
    {
        return $this->state(fn (array $attributes) => ['is_bestseller' => true]);
    }
}
