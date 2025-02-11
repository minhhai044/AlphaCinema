<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cinema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(50)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // for ($i = 0; $i < 20; $i++) {
        //     Cinema::query()->create([
        //         'name' => Str::random(10),
        //         'address' => Str::random(10),
        //         'image' => Str::random(10)
        //     ]);
        // }
    }
}
