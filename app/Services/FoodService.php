<?php

    namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

    class FoodService
    {
        public function getAllFoodService()
        {
            return Food::latest()->paginate(10);
        }

        public function getFoodByIdService($id)
        {
            return Food::findOrFail($id);
        }

        public function createFoodService($data)
        {
            return DB::transaction(function () use ($data) {
                if (isset($data['img_thumbnail'])) {
                    $data['img_thumbnail'] = Storage::put('foodImages', $data['img_thumbnail']);
                }

                $data['is_active'] ??= 0;

                return Food::create($data);
            });
        }

        public function updateFoodService($id, $data)
        {
            return DB::transaction(function () use ($id, $data) {
                $food = Food::findOrFail($id);

                if (isset($data['img_thumbnail'])) {
                    if (Storage::exists($food->img_thumbnail)) {
                        Storage::delete($food->img_thumbnail);
                    }

                    $data['img_thumbnail'] = Storage::put('foodImages', $data['img_thumbnail']);
                }

                $food->update($data);

                return $food->update($data);
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
