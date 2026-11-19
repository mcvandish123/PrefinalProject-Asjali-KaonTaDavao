<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\Permit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EaterySeeder extends Seeder
{
    public function run(): void
    {
        $districts = District::all();
        $features = Feature::all();

        $this->createMyEatery($districts);
        $this->createFactoryEateries($districts, $features);
    }

    private function createMyEatery($districts): void
    {
        $name = 'Carinderia ni Asjali';

        $eatery = Eatery::create([
            'district_id' => $districts->firstWhere('name', 'Poblacion')->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => 'San Pedro St., Poblacion, Davao City',
            'price_range' => 'budget',
            'specialty' => 'Tinolang Manok',
            'is_open' => true,
        ]);

        $eatery->features()->sync(
            Feature::whereIn('name', ['Suki ni Asjali', 'Budget Meal'])->pluck('id')
        );

        $eatery->permit()->create([
            'permit_no' => 'DVO-2026-0523',
            'expires_on' => '2027-05-23',
        ]);

        $eatery->dishes()->createMany([
            ['name' => 'Tinolang Manok', 'price' => 85.00, 'is_bestseller' => true],
            ['name' => 'Kinilaw na Tuna', 'price' => 120.00, 'is_bestseller' => false],
            ['name' => 'Durian Shake', 'price' => 75.00, 'is_bestseller' => false],
        ]);
    }

    private function createFactoryEateries($districts, $features): void
    {
        $eateries = Eatery::factory()
            ->count(11)
            ->recycle($districts)
            ->create()
            ->concat(
                Eatery::factory()->count(3)->closed()->recycle($districts)->create()
            );

        foreach ($eateries->values() as $index => $eatery) {
            $permit = Permit::factory()->for($eatery);

            // The first two factory eateries get an already expired permit.
            if ($index < 2) {
                $permit = $permit->expired();
            }

            $permit->create(['permit_no' => sprintf('DVO-2026-%04d', 1000 + $index)]);

            Dish::factory()->count(fake()->numberBetween(2, 5))->for($eatery)->create();

            $eatery->features()->attach(
                $features->random(fake()->numberBetween(1, 3))->pluck('id')
            );
        }
    }
}
