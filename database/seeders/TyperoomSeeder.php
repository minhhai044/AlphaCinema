<?php

namespace Database\Seeders;

use App\Models\Type_room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TyperoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type_room::factory(10)->create();
    }
}
