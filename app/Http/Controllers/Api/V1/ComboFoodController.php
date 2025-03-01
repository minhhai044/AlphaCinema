<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ComboFood;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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

        return $this->successResponse(array_values($Combo), 'Thao tác thành công !!!');
    }
}
