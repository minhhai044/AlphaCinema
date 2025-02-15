<?php

namespace App\Services;

use DB;
use Storage;
use App\Models\Combo;


class ComboService
{
    public function getAllComboService($perPage = 10, string $latest = 'id')
    {
        return Combo::orderByDesc('updated_at')->orderByDesc('created_at')->paginate($perPage);
    }

    public function getComboByIdService($id)
    {
        return Combo::findOrFail($id);
    }

    public function createComboService($data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['img_thumbnail'])) {
                $data['img_thumbnail'] = Storage::put('comboImages', $data['img_thumbnail']);
            }

            $data['is_active'] ??= 0;

            return Combo::create($data);
        });
    }

    public function updateComboService($id, $data)
    {

        return DB::transaction(function () use ($id, $data) {
            $combo = Combo::findOrFail($id);
            if (isset($data['img_thumbnail'])) {
                if (Storage::exists($combo->img_thumbnail)) {
                    Storage::delete($combo->img_thumbnail);
                }

                $data['img_thumbnail'] = Storage::put('comboImages', $data['img_thumbnail']);
            }

            $combo->update($data);

            return $combo->update($data);
        });
    }

    // Xóa vĩnh viễn
    public function forceDeleteComboService($id)
    {
        $combo = Combo::findOrFail($id);

        if (Storage::exists($combo->img_thumbnail)) {
            Storage::delete($combo->img_thumbnail);
        }

        $combo->forceDelete();

        return true;
    }

}
