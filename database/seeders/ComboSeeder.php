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
                'name' => 'Combo ngọt ngào',
                'img_thumbnail' => 'comboImages/QpaY6X34CWEZuhn1danLRsxUe0wgIDHExU2OHvqb.png',
                'price' => 140000,
                'price_sale' => 90000,
                'description' => 'TIẾT KIỆM 50K!!! Gồm: 1 Bắp + 2 Nước có gaz',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 1],
                    ['food_id' => 2, 'quantity' => 2],
                ],
            ],
            [
                'name' => 'Combo Gia đình',
                'img_thumbnail' => 'comboImages/RQ8UPqOcPraPy4F4fSG3T4kD6GmXSan2cR6V9Ety.png',
                'price' => 310000,
                'price_sale' => 210000,
                'description' => 'TIẾT KIỆM 100K!!! Gồm: 2 Bắp  + 4 Nước có gaz  + 1 khoai tây chiên',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 2],
                    ['food_id' => 2, 'quantity' => 4],
                    ['food_id' => 5, 'quantity' => 1],
                ],
            ],
            [
                'name' => 'Combo See Mê - Cầu Vồng',
                'img_thumbnail' => 'comboImages/27VjNN6H1jLK5xmU9Km7fl7IUj0xLI579M8Q8YH1.png',
                'price' => 170000,
                'price_sale' => 114000,
                'description' => 'TIẾT KIỆM 56K!!! Sỡ hữu ngay: 1 Ly Cầu Vồng kèm nước + 1 Bắp (69oz)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 1],
                    ['food_id' => 4, 'quantity' => 1],
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
