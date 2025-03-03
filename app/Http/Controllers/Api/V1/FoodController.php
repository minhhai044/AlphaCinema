<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::query()->latest('id')->get();
        return $this->successResponse($foods, 'Danh sách food');
    }
}
