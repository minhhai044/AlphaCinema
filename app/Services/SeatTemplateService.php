<?php

namespace App\Services;

use App\Models\Seat_template;

class SeatTemplateService
{
    public function getAll()
    {
        return Seat_template::query()->latest('id')->paginate(10);
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
