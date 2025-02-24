<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComboRequest;
use App\Models\Combo;
use App\Models\Food;
use App\Services\ComboService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComboController extends Controller
{
    use ApiResponseTrait;

    protected $ComboService;

    public function __construct(ComboService $ComboService)
    {
        $this->ComboService = $ComboService;
    }

    public function index(Request $request)
    {
        try {
            $foods = Food::query()->select('id', 'name', 'type')->get();

            // Tổng số bản ghi không lọc
            $totalRecords = Combo::count();

            // Query danh sách combo kèm theo món ăn
            $query = Combo::with(['comboFood.food']);

            // Lọc theo ID
            if ($request->filled('id')) {
                $query->where('id', 'LIKE', '%' . $request->id . '%');
            }


            // Lọc theo khoảng giá
            if ($request->filled('price_min')) {
                $query->where('price_sale', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $query->where('price_sale', '<=', $request->price_max);
            }

            // Lọc theo món ăn (ID)
            if ($request->filled('food_id')) {
                $query->whereHas('comboFood', function ($q) use ($request) {
                    $q->whereIn('food_id', $request->food_id);
                });
            }

            if ($request->filled('food_name')) {
                $query->whereHas('comboFood.food', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->food_name . '%');
                });
            }

            // Số bản ghi sau khi lọc
            $filteredRecords = $query->count();

            // Phân trang
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $combos = $query->skip($start)->take($length)->get();

            // Chuyển đổi dữ liệu
            $combos->transform(function ($combo) {
                $combo->info = $combo->comboFood->map(function ($item) {
                    return "Món: {$item->food->name} (SL: {$item->quantity})";
                })->implode('<br>');
                return $combo;
            });

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data" => $combos,
                "food" => $foods
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function show(string $id)
    {
        try {
            $combo = Combo::query()->findOrFail($id);
            return $this->successResponse(
                $combo,
                'combo retrieved successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function store(ComboRequest $request)
    {
        try {
            $data = $this->ComboService->createComboService($request->all());
            return $this->successResponse(
                $data,
                'combo created successfully!',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function update(ComboRequest $request, string $id)
    {
        try {
            $data = $this->ComboService->updateComboService($id, $request->all());
            return $this->successResponse(
                $data,
                'combo updated successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:combos,id',
        'is_active' => 'required|boolean',
    ]);

    $combo = Combo::findOrFail($request->id);
    $combo->is_active = $request->is_active;
    $combo->save();

    return response()->json([
        'success' => true,
        'message' => 'Trạng thái đã được cập nhật thành công.'
    ]);
}

    public function delete(string $id)
    {
        try {
            $this->ComboService->forceDeleteComboService($id);
            return $this->successResponse(
                null,
                'combo deleted successfully!',
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

