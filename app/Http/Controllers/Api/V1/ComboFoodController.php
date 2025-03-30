<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ComboFood;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboFoodController extends Controller
{
    use ApiResponseTrait;

    public function list_combo()
    {
        $ComboFoods = ComboFood::with(['combo', 'food'])->get();
        $Combo = [];

        foreach ($ComboFoods as $ComboFood) {
            $combo_id = $ComboFood->combo_id;

            if (!isset($Combo[$combo_id])) {
                $Combo[$combo_id] = array_merge(
                    $ComboFood->combo->toArray(),
                    ['foods' => []]
                );
            }

            $foodData = array_merge(
                $ComboFood->food->toArray(),
                ['quantity' => $ComboFood->quantity]
            );

            $Combo[$combo_id]['foods'][] = $foodData;
        }
        foreach ($Combo as $value) {
            if (!Storage::exists($value['img_thumbnail'])) {
                $value['img_thumbnail'] = 'images/foods/foods.png';
            }
        }
        
        return $this->successResponse(array_values($Combo), 'Thao tác thành công !!!');
    }
}
