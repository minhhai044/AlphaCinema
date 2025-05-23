<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];
    public function messenges(){
        return $this->hasMany(MessengerChat::class);
    }
}
