<?php

namespace App\Services;

use App\Models\Type_seat;

class TypeSeatService
{
    public function storeService(array $data)
    {
        return Type_seat::create($data);
    }
    public function updateService(array $data,string $id)
    {
        $Type_seat=Type_seat::findOrFail($id);
        $Type_seat->update($data);
        return $Type_seat;
    }
    public function deleteService(array $data,string $id){
        $Type_seat=Type_seat::findOrFail($id);
        $Type_seat->delete();
        return $Type_seat;
    }
    
}
