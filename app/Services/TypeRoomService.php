<?php

namespace App\Services;

use App\Models\Type_room;

class TypeRoomService
{
    public function storeService(array $data)
    {
        return Type_room::create($data);
    }
    public function updateService(array $data,string $id)
    {
        $Type_room=Type_room::findOrFail($id);
        $Type_room->update($data);
        return $Type_room;
    }
    public function deleteService(array $data,string $id){
        $Type_room=Type_room::findOrFail($id);
        $Type_room->delete();
        return $Type_room;
    }
    
}
