<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\RoomService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    use ApiResponseTrait;
    private $roomService;
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }
    public function activeRoom(Request $request,string $id){
        try {
            $data = $this->roomService->updateService($request->all(),$id);
            return $this->successResponse(
                $data,
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
