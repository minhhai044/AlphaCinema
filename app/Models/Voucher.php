<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'type_discount',
        'discount',
        'quantity',
        'limit',
        'limit_by_user',
        'is_active',
    ];

    protected $dates = [
        'start_date_time',
        'end_date_time',
    ];
    public function userVouchers(){
        return $this->hasMany(User_voucher::class);
    }
}
