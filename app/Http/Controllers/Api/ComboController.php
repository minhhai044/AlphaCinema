<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComboRequest;
use App\Models\Combo;
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
            // Tổng số bản ghi (không lọc)
            $totalRecords = Combo::count();

            // Tạo query gốc
            $query = Combo::query();

            // Lọc theo tên 
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
            $combos = $query->skip($start)->take($length)->get();

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,     // Tổng số bản ghi trước lọc
                "recordsFiltered" => $filteredRecords,  // Số bản ghi sau khi lọc
                "data" => $combos,
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

