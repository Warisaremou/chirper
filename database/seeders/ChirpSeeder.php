<?php

namespace Database\Seeders;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChirpSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        Chirp::factory()
            ->count(fake()->numberBetween(40, 50))
            ->create(['user_id' => fn() => $users->random()->id]);
    }
}