<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Halal', 'Budget Meal', 'Open 24 Hours', 'Suki ni Asjali'] as $name) {
            Feature::firstOrCreate(['name' => $name]);
        }
    }
}
