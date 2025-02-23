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
            ['name' => 'Burger', 'img_thumbnail' => 'images/burger.jpg', 'type' => 'Đồ ăn', 'price' => 50000, 'description' => 'Burger bò thơm ngon', 'is_active' => 1],
            ['name' => 'Pizza', 'img_thumbnail' => 'images/pizza.jpg', 'type' => 'Đồ ăn', 'price' => 120000, 'description' => 'Pizza hải sản', 'is_active' => 1],
            ['name' => 'Coca Cola', 'img_thumbnail' => 'images/coca.jpg', 'type' => 'Đồ uống', 'price' => 15000, 'description' => 'Nước giải khát có ga', 'is_active' => 1],
            ['name' => 'Pepsi', 'img_thumbnail' => 'images/pepsi.jpg', 'type' => 'Đồ uống', 'price' => 15000, 'description' => 'Nước ngọt Pepsi', 'is_active' => 1],
            ['name' => 'Khoai tây chiên', 'img_thumbnail' => 'images/fries.jpg', 'type' => 'Đồ ăn', 'price' => 30000, 'description' => 'Khoai tây chiên giòn rụm', 'is_active' => 1],
            ['name' => 'Trà sữa', 'img_thumbnail' => 'images/milktea.jpg', 'type' => 'Đồ uống', 'price' => 40000, 'description' => 'Trà sữa trân châu', 'is_active' => 1],
            ['name' => 'Nước lọc', 'img_thumbnail' => 'images/water.jpg', 'type' => 'Đồ uống', 'price' => 10000, 'description' => 'Nước suối tinh khiết', 'is_active' => 1],
            ['name' => 'Xúc xích', 'img_thumbnail' => 'images/sausage.jpg', 'type' => 'Đồ ăn', 'price' => 20000, 'description' => 'Xúc xích chiên giòn', 'is_active' => 1],
            ['name' => 'Bánh ngọt', 'img_thumbnail' => 'images/cake.jpg', 'type' => 'Khác', 'price' => 25000, 'description' => 'Bánh kem ngọt ngào', 'is_active' => 1],
            ['name' => 'Sinh tố bơ', 'img_thumbnail' => 'images/avocado_smoothie.jpg', 'type' => 'Đồ uống', 'price' => 50000, 'description' => 'Sinh tố bơ béo ngậy', 'is_active' => 1],
        ];

        DB::table('food')->insert($foods);
    }
}
