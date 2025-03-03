<?php

namespace Database\Factories;

use App\Models\Point_history;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Point_history>
 */
class Point_historyFactory extends Factory
{
    

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id, 
            'point' => fake()->numberBetween(10, 1000), 
            'type' => fake()->word, 
            'description' => fake()->sentence, 
            'expiry_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'), 
            'processed' => fake()->boolean, 
        ];
    }
}
