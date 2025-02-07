<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $foodItems = [
            ['name' => 'Bắp rang bơ', 'price' => 50000],
            ['name' => 'Khoai tây chiên', 'price' => 40000],
            ['name' => 'Gà rán', 'price' => 60000],
            ['name' => 'Hotdog', 'price' => 35000],
            ['name' => 'Burger phô mai', 'price' => 70000],
            ['name' => 'Nước ngọt Coca-Cola', 'price' => 25000],
            ['name' => 'Nước cam tươi', 'price' => 30000],
            ['name' => 'Trà sữa trân châu', 'price' => 45000],
            ['name' => 'Cà phê sữa đá', 'price' => 40000],
            ['name' => 'Bánh ngọt', 'price' => 30000]
        ];

        static $usedNames = []; // Đảm bảo không bị trùng lặp tên món ăn

        do {
            $food = fake()->randomElement($foodItems);
        } while (in_array($food['name'], $usedNames));

        $usedNames[] = $food['name'];

        return [
            'name' => $food['name'],
            'img_thumbnail' => fake()->imageUrl(200, 200, 'food', true, $food['name']),
            'price' => $food['price'],
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90), // 90% món ăn sẽ có trạng thái hoạt động
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
