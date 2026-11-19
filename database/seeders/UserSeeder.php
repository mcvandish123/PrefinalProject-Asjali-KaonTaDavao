<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Benjamin Asjali',
            'email' => 'asjali@kaonta.test',
            'password' => 'kaonta2026',
        ]);

        User::factory()->create([
            'name' => 'Tita Asjali',
            'email' => 'tita.asjali@kaonta.test',
            'password' => 'kaonta2026',
        ]);

        User::factory(10)->create();
    }
}
