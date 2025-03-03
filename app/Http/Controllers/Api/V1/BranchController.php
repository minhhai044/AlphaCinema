<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $branchs = Branch::with(['cinemas' => function ($query) {
            $query->where('is_active', '>=', 1);
        }])->where('is_active', 1)->get();
        return $this->successResponse($branchs, 'Thao tác thành công !!!');
    }
}
