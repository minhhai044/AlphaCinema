<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_seats')->insert([
            [
                'name' => 'Ghế thường',
                'price' => 5000, // Giá mặc định 50k50k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế VIP',
                'price' => 10000, // Giá mặc định 100k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế đôi',
                'price' => 15000, // Giá mặc định 150k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
