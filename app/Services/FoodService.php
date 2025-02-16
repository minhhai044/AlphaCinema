<?php

namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FoodService
{
    const PATH_UPLOAD = 'foodImages';

    public function getAllFoodService()
    {
        return Food::latest('id')->paginate(10);
    }

    public function getFoodByIdService($id)
    {
        return Food::findOrFail($id);
    }

    public function createFoodService($data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['img_thumbnail'])) {
                $data['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['img_thumbnail']);
            }

            $data['is_active'] ??= 0;

            return Food::create($data);
        });
    }

    public function updateFoodService($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $food = Food::findOrFail($id);


            if (!empty($data['img_thumbnail'])) { // Nếu có ảnh mới
                if (!empty($food->img_thumbnail) && Storage::exists($food->img_thumbnail)) {
                    Storage::delete($food->img_thumbnail); // Xóa ảnh cũ
                }
                $data['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['img_thumbnail']);
            } elseif (!empty($data['old_img_thumbnail'])) {
                // Nếu không có ảnh mới, giữ nguyên ảnh cũ
                $data['img_thumbnail'] = $food->img_thumbnail;
            }

            $food->update($data);

            return $food;
        });
    }

    public function forceDeleteFoodService($id)
    {
        $food = Food::findOrFail($id);

        if (Storage::exists($food->img_thumbnail)) {
            Storage::delete($food->img_thumbnail);
        }

        $food->forceDelete();

        return true;
    }
}
