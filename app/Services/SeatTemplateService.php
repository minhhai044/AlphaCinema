<?php

namespace App\Services;

use App\Models\Seat_template;

class SeatTemplateService
{
    public function getAll($request)
    {
        $query = Seat_template::query()->latest('id');
        if ($request->has('name') && $request->query('name', '') != "") {
            $name = $request->query('name', '');
            $query->where('name', 'LIKE', "%$name%");
        }


        return  $query->paginate(10);
    }
    public function storeService(array $data)
    {
        return Seat_template::query()->create($data);
    }
    public function updateSevice(string $id, array $data)
    {
        $seatTemplate = Seat_template::query()->findOrFail($id);
        $seatTemplate->update($data);
        return $seatTemplate;
    }
}
