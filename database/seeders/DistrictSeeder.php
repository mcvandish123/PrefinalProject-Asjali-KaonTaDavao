<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Poblacion', 'Agdao', 'Buhangin', 'Toril', 'Talomo'] as $name) {
            District::firstOrCreate(['name' => $name]);
        }
    }
}
