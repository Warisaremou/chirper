<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ChirpSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // Create chirps for random users
        for ($i = 0; $i < 3; $i++) 
        {
            $users->random()->chirps()->create([
                'message' => fake()->text(),
            ]);
        }
    }
}