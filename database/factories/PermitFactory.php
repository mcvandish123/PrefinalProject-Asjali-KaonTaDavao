<?php

namespace Database\Factories;

use App\Models\Eatery;
use App\Models\Permit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permit>
 */
class PermitFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'eatery_id' => Eatery::factory(),
            'permit_no' => 'DVO-2026-'.fake()->unique()->numerify('####'),
            'expires_on' => fake()->dateTimeBetween('+1 month', '+2 years'),
        ];
    }

    /**
     * Indicate that the permit has already expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_on' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}
