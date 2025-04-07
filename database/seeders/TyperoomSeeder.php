<?php

namespace Database\Seeders;

use App\Models\Type_room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TyperoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_rooms')->insert([
            [
                'name' => 'Phòng 2D',
                'surcharge' => 50000, // Giá mặc định 50k50k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phòng 3D ',
                'surcharge' => 100000, // Giá mặc định 100k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phòng 4D ',
                'surcharge' => 150000, // Giá mặc định 150k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
