<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $foods = [
            ['name' => 'Bỏng ngô thường', 'img_thumbnail' => 'foodImages/ExZZzbTWCffHEbC9hreNjbxgMJlc0WS1zPZXkDsK.png', 'type' => 'Đồ ăn', 'price' => 80000, 'description' => 'Bắp rang bơ thường', 'is_active' => 1],
            ['name' => 'Nước Cocacola', 'img_thumbnail' => 'foodImages/0DDHnitdJcLWaqn30DggqUYQkZZtrjGoWmnFioP7.png', 'type' => 'Đồ uống', 'price' => 30000, 'description' => 'Nước giải khát có ga', 'is_active' => 1],
            ['name' => 'Snack vị bí đỏ ', 'img_thumbnail' => 'foodImages/eBbuAnLlHAeqrlORz6nWUe1eNAplcaVMjDXvliFo.png', 'type' => 'Đồ ăn', 'price' => 15000, 'description' => 'Đồ ăn vặt', 'is_active' => 1],
            ['name' => 'Cốc uống nước', 'img_thumbnail' => 'foodImages/XeLClFZXZzzxHVel8OphU2wI8uXeKyI4XSXkN8Ng.png', 'type' => 'Khác', 'price' => 90000, 'description' => 'Ly Cầu vồng', 'is_active' => 1],
            ['name' => 'Khoai tây chiên', 'img_thumbnail' => 'foodImages/4cc6B1xM2cR0cPcdRZPbNODeHpENe1zJka26ODbU.png', 'type' => 'Đồ ăn', 'price' => 30000, 'description' => 'Khoai tây chiên giòn rụm', 'is_active' => 1],
            ['name' => 'Trà sữa', 'img_thumbnail' => 'foodImages/w3J9NRrcc0HSGrEwXigQ0vyK3LzmdBpF9YO6Q3Em.png', 'type' => 'Đồ uống', 'price' => 40000, 'description' => 'Trà sữa trân châu', 'is_active' => 1],
            ['name' => 'Nước lọc', 'img_thumbnail' => 'foodImages/3Tyn0ao42FNo4op6VyyDWaZzyI8JwizwiG1kKlAc.png', 'type' => 'Đồ uống', 'price' => 10000, 'description' => 'Nước suối tinh khiết', 'is_active' => 1],
            ['name' => 'Xúc xích', 'img_thumbnail' => 'foodImages/9VSxWJuIkw0YGNqdY8qpdUaGs4PAHs5AlaOGadpG.png', 'type' => 'Đồ ăn', 'price' => 20000, 'description' => 'Xúc xích chiên giòn', 'is_active' => 1],
            ['name' => 'Bánh ngọt', 'img_thumbnail' => 'foodImages/Hpy6jIoYMX6pFVL41N5L5e20y081dmx1fQyZJhuY.png', 'type' => 'Khác', 'price' => 25000, 'description' => 'Bánh kem ngọt ngào', 'is_active' => 1],
            ['name' => 'Sinh tố bơ', 'img_thumbnail' => 'foodImages/9rx7OY31Z4rXHkeBRxnvDEaoco9sGZtOLtG45a6L.png', 'type' => 'Đồ uống', 'price' => 50000, 'description' => 'Sinh tố bơ béo ngậy', 'is_active' => 1],
        ];

        DB::table('food')->insert($foods);
    }
}
