<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Ilham Nuruddin Al Huda',
            'email' => 'inurhuda00@gmail.com',
        ]);

        Movie::factory(5)->create([
            'user_id' => $user->id,
        ]);
    }
}
