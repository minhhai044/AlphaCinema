<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessengerChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'messenge',
        'image',
        'user_id',
        'room_chat_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
