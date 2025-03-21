<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodRequest;
use App\Models\Food;
use App\Services\FoodService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FoodController extends Controller
{
    use ApiResponseTrait;

    protected $FoodService;

    public function __construct(FoodService $FoodService)
    {
        $this->FoodService = $FoodService;
    }

    public function index(Request $request)
    {
        try {
            // Tổng số bản ghi (không lọc)
            $totalRecords = Food::count();

            // Tạo query gốc
            $query = Food::query()->withCount('combos')->latest();

            // Lọc theo tên phim
            if ($request->filled('id')) {
                $query->where('id', 'LIKE', '%' . $request->id . '%');
            }

            // Tìm kiếm từ DataTables (nếu có)
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }

            // Số bản ghi sau khi lọc
            $filteredRecords = $query->count();

            // Phân trang
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $Foods = $query->skip($start)->take($length)->get();

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,     // Tổng số bản ghi trước lọc
                "recordsFiltered" => $filteredRecords,  // Số bản ghi sau khi lọc
                "data" => $Foods,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        try {
            $food = Food::query()->findOrFail($id);
            return $this->successResponse(
                $food,
                'Food retrieved successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
