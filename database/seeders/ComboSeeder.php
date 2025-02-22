<?php

namespace Database\Seeders;

use App\Models\Combo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $combos = [
            [
                'name' => 'Combo 1',
                'img_thumbnail' => 'comboImages/combo1.png',
                'price' => 500000,
                'price_sale' => 450000,
                'description' => 'Combo tiết kiệm 1',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 2],
                    ['food_id' => 2, 'quantity' => 3],
                ],
            ],
            [
                'name' => 'Combo 2',
                'img_thumbnail' => 'comboImages/combo2.png',
                'price' => 600000,
                'price_sale' => 550000,
                'description' => 'Combo tiết kiệm 2',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 2, 'quantity' => 2],
                    ['food_id' => 3, 'quantity' => 1],
                ],
            ],
            [
                'name' => 'Combo 3',
                'img_thumbnail' => 'comboImages/combo3.png',
                'price' => 700000,
                'price_sale' => 650000,
                'description' => 'Combo tiết kiệm 3',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 3, 'quantity' => 4],
                    ['food_id' => 4, 'quantity' => 2],
                ],
            ],
            [
                'name' => 'Combo 4',
                'img_thumbnail' => 'comboImages/combo4.png',
                'price' => 800000,
                'price_sale' => 750000,
                'description' => 'Combo tiết kiệm 4',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 4, 'quantity' => 3],
                    ['food_id' => 5, 'quantity' => 1],
                ],
            ],
            [
                'name' => 'Combo 5',
                'img_thumbnail' => 'comboImages/combo5.png',
                'price' => 900000,
                'price_sale' => 850000,
                'description' => 'Combo tiết kiệm 5',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 5],
                    ['food_id' => 5, 'quantity' => 2],
                ],
            ],
        ];

        foreach ($combos as $combo) {
            $comboId = DB::table('combos')->insertGetId([
                'name' => $combo['name'],
                'img_thumbnail' => $combo['img_thumbnail'],
                'price' => $combo['price'],
                'price_sale' => $combo['price_sale'],
                'description' => $combo['description'],
                'is_active' => $combo['is_active'],
                'created_at' => $combo['created_at'],
                'updated_at' => $combo['updated_at'],
            ]);

            foreach ($combo['combo_food'] as $food) {
                DB::table('combo_food')->insert([
                    'combo_id' => $comboId,
                    'food_id' => $food['food_id'],
                    'quantity' => $food['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
