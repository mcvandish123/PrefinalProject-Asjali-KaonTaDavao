<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\Permit;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EaterySeeder extends Seeder
{
    public function run(): void
    {
        $districts = District::all();
        $genericFeatures = Feature::where('name', '!=', 'Suki ni Asjali')->get();
        $users = User::all();

        Eatery::factory(8)
            ->recycle($districts)
            ->create()
            ->each(function (Eatery $eatery) use ($genericFeatures, $users) {
                Permit::factory()->for($eatery)->create();
                Dish::factory(3)->for($eatery)->create();
                Dish::factory()->bestseller()->for($eatery)->create();
                $eatery->features()->sync($genericFeatures->random(2)->pluck('id'));
                Review::factory(2)->for($eatery)->recycle($users)->create();
            });

        $name = 'Carinderia ni Asjali';
        $mine = Eatery::create([
            'district_id' => $districts->firstWhere('name', 'Poblacion')->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => 'Poblacion, Davao City',
            'price_range' => 'budget',
            'specialty' => 'Tinola',
            'is_open' => true,
        ]);

        Permit::create([
            'eatery_id' => $mine->id,
            'permit_no' => 'DVO-2026-0523',
            'expires_on' => '2027-05-23',
        ]);

        $mine->dishes()->createMany([
            ['name' => 'Tinola', 'price' => 120, 'is_bestseller' => true],
            ['name' => 'Kinilaw', 'price' => 150, 'is_bestseller' => false],
            ['name' => 'Durian Shake', 'price' => 90, 'is_bestseller' => false],
        ]);

        $mine->features()->sync(Feature::where('name', 'Suki ni Asjali')->pluck('id'));
    }
}
