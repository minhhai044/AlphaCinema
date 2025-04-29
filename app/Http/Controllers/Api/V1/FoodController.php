<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::query()->where('is_active', 1)->latest('id')->get();
        foreach ($foods as $food) {
            if (!Storage::exists($food->img_thumbnail)) {
                $food['img_thumbnail'] = 'images/foods/foods.png';
            }
        }
        return $this->successResponse($foods, 'Danh sách food');
    }
}
