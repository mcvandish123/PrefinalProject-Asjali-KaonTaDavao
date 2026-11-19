<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Benjamin Asjali', 'email' => 'asjali@kaonta.test']);
        User::factory()->create(['name' => 'Tita Asjali', 'email' => 'tita.asjali@kaonta.test']);

        $this->call([
            DistrictSeeder::class,
            FeatureSeeder::class,
            EaterySeeder::class,
        ]);
    }
}
