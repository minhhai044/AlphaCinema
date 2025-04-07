<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Faker\Factory as Faker;

class MovieSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Movie::create([
                'name' => $faker->sentence(3),
                'slug' => $faker->slug,
                'category' => $faker->randomElement(['Hành động', 'Kinh dị', 'Hài hước', 'Tình cảm']),
                'img_thumbnail' => $faker->imageUrl(300, 400, 'movies', true),
                'description' => $faker->paragraph,
                'director' => $faker->name,
                'duration' => rand(90, 180) ,
                'rating' => $faker->numberBetween(5, 10),
                'release_date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'end_date' => $faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                'trailer_url' => $faker->url,
                'surcharge' => rand(0, 50000),
                'movie_versions' => $faker->randomElements(['2D', '3D', '4D'], rand(1, 3)),
                'movie_genres' => $faker->randomElements(['Horror', 'Action', 'Comedy'], rand(1, 3)),
                'is_active' => $faker->boolean,
                'is_hot' => $faker->boolean,
                'is_special' => $faker->boolean,
                'is_publish' => $faker->boolean,
            ]);
        }
    }
}
