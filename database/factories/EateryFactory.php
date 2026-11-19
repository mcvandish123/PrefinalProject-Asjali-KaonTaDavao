<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\Eatery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Eatery>
 */
class EateryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company().' Carinderia';

        return [
            'district_id' => District::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => fake()->streetAddress().', Davao City',
            'price_range' => fake()->randomElement(['budget', 'mid', 'splurge']),
            'specialty' => fake()->optional()->randomElement(['Tinola', 'Kinilaw', 'Sisig', 'Durian Shake']),
            'is_open' => true,
        ];
    }

    /**
     * Indicate that the eatery is currently closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => ['is_open' => false]);
    }
}
