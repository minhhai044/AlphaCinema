<?php

namespace App\Services;

use App\Models\Food;
use App\Models\Combo;
use App\Models\ComboFood;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ComboService
{


    public function getAllComboService($perPage = 10, string $latest = 'id')
    {
        return Combo::query()->with('comboFood')->latest($latest)->paginate($perPage);
    }

    public function getComboByIdService($id)
    {
        return Combo::findOrFail($id);
    }

    public function createComboService($data)
    {
        // dd($data);
        return DB::transaction(function () use ($data) {
            if (isset($data['img_thumbnail'])) {
                $data['img_thumbnail'] = Storage::put('comboImages', $data['img_thumbnail']);
            }
            // Thiết lập trạng thái mặc định cho is_active nếu không có
            $data['is_active'] ??= 0;
            if (empty($data['price_sale'])) {
                $data['price_sale'] = 0;
            };
            // Tính tổng combo

            $foodIds = $data['combo_food'];            // Lấy ID combo
            $quantities = $data['combo_quantity'];      // Lấy số lượng combo
            $totalPrice = 0;

            foreach ($foodIds as $key => $foodId) {
                // Lấy thông tin món ăn từ bảng food
                $food = Food::findOrFail($foodId); // lấy món ăn
                $quantity = $quantities[$key];         // lấy số lượng của món ăn tương ứng

                // Tính giá: giá món ăn * số lượng
                $totalPrice += $food->price * $quantity;
            }
            // Lưu combo mới với tổng giá đã tính
            $combo = Combo::create([
                'name' => $data['name'],
                'price_sale' => $data['price_sale'],
                'price' => $totalPrice,  // Lưu tổng giá của combo
                'description' => $data['description'],
                'img_thumbnail' => $data['img_thumbnail'] ?? null,
                'is_active' => $data['is_active'],
            ]);

            // Lưu các món ăn vào combo
            foreach ($foodIds as $key => $foodId) {
                ComboFood::create([
                    'combo_id' => $combo->id,
                    'food_id' => $foodId,
                    'quantity' => $quantities[$key],
                ]);
            }
            return  $combo;
        });
    }

    public function updateComboService($id, $data)
    {

        return DB::transaction(function () use ($id, $data) {
            $combo = Combo::findOrFail($id);
            // dd($data->toArray());
            if (isset($data['img_thumbnail'])) {
                if (Storage::exists($combo->img_thumbnail)) {
                    Storage::delete($combo->img_thumbnail);
                }

                $data['img_thumbnail'] = Storage::put('comboImages', $data['img_thumbnail']);
            }

            // Tính toán lại tổng giá của combo
            $foodIds = $data['combo_food'];           // Lấy ID combo
            $quantities = $data['combo_quantity'];    // Lấy số lượng combo
            $totalPrice = 0;

            foreach ($foodIds as $key => $foodId) {
                $food = Food::findOrFail($foodId);
                $quantity = $quantities[$key];
                $totalPrice += $food->price * $quantity;
            }

            // Cập nhật thông tin combo
            $combo->update([
                'name' => $data['name'],
                'price_sale' => $data['price_sale'],
                'price' => $totalPrice,  // Cập nhật tổng giá của combo
                'description' => $data['description'],
                'img_thumbnail' => $data['img_thumbnail'] ?? $combo->img_thumbnail,
                'is_active' => $data['is_active'],
            ]);

            // Xóa tất cả món ăn cũ của combo để cập nhật lại
            ComboFood::where('combo_id', $combo->id)->delete();

            // Thêm món ăn mới vào combo
            foreach ($foodIds as $key => $foodId) {
                ComboFood::create([
                    'combo_id' => $combo->id,
                    'food_id' => $foodId,
                    'quantity' => $quantities[$key],
                ]);
            }

            return $combo;
        });
    }

    // Xóa vĩnh viễn
    public function forceDeleteComboService($id)
    {

        return DB::transaction(function () use ($id) {
            $combo = Combo::with('comboFood')->findOrFail($id);

            foreach ($combo->comboFood ?? [] as $food) {
                $food->delete();
            }
            if (Storage::exists($combo->img_thumbnail)) {
                Storage::delete($combo->img_thumbnail);
            }


            $combo->delete();
            return true;
        });
    }
}
