<?php

namespace Database\Seeders;

use App\Models\Eatery;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tita = User::where('email', 'tita.asjali@kaonta.test')->firstOrFail();

        $mine = Eatery::where('slug', 'carinderia-ni-asjali')->firstOrFail();

        Review::factory()->for($mine)->for($tita)->create([
            'rating' => 5,
            'body' => 'Lami kaayo! Balik-balikan ni Tita Asjali.',
        ]);

        foreach ($users->where('id', '!=', $tita->id)->random(2) as $reviewer) {
            Review::factory()->for($mine)->for($reviewer)->create();
        }

        Eatery::where('is_open', true)
            ->where('id', '!=', $mine->id)
            ->get()
            ->each(function (Eatery $eatery) use ($users) {
                for ($count = fake()->numberBetween(0, 6); $count > 0; $count--) {
                    Review::factory()->for($eatery)->for($users->random())->create();
                }
            });
    }
}
