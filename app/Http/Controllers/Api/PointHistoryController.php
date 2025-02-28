<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Point_history;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;

class PointHistoryController extends Controller
{
    use ApiResponseTrait;

    
    public function index()
    {
        try {
            $pointHistories = Point_history::with('user')->paginate(10); 

            return $this->successResponse(
                $pointHistories,
                'Danh sách lịch sử điểm',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    
    public function show(string $id)
    {
        try {
            // Tìm lịch sử điểm theo ID
            $pointHistory = Point_history::query()->findOrFail($id);

            return $this->successResponse(
                $pointHistory,
                'Thao tác thành công !!!',
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
