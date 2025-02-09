<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat_template extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'matrix',
        'seat_structure',
        'row_regular',
        'row_vip',
        'row_double',
        'description',
        'is_active',
        'is_publish'
    ];
    protected $casts = [
        'is_publish' => 'boolean',
        'is_active' => 'boolean',
        'seat_structure' => 'array'
    ];
    const ROW_REGULAR = 4;
    const ROW_DOUBLE = 2;
    const MATRIXS = [
        ['id' => 1, 'name' => '12x12', 'max_row' => 12, 'max_col' => 12, 'description' => 'Sức chứa tối đa 144 chỗ ngồi.', 'row_default' => ['regular' => 4, 'vip' => 6, 'double' => 2]],
        ['id' => 2, 'name' => '13x13', 'max_row' => 13, 'max_col' => 13, 'description' => 'Sức chứa tối đa 169 chỗ ngồi.', 'row_default' => ['regular' => 4, 'vip' => 7, 'double' => 2]],
        ['id' => 3, 'name' => '14x14', 'max_row' => 14, 'max_col' => 14, 'description' => 'Sức chứa tối đa 196 chỗ ngồi.', 'row_default' => ['regular' => 4, 'vip' => 8, 'double' => 2]],
        ['id' => 4, 'name' => '15x15', 'max_row' => 15, 'max_col' => 15, 'description' => 'Sức chứa tối đa 225 chỗ ngồi.', 'row_default' => ['regular' => 4, 'vip' => 9, 'double' => 2]]
    ];

    public static function getMatrixById($id)
    {
        return collect(self::MATRIXS)->firstWhere('id', $id);
    }
}









