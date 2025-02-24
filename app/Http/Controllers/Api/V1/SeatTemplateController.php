<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SeatTemplateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeatTemplateController extends Controller
{
    use ApiResponseTrait;
    private $seatTemplateService;
    public function __construct(SeatTemplateService $seatTemplateService)
    {
        $this->seatTemplateService = $seatTemplateService;
    }
    public function activeSeatTemplate(Request $request, string $id)
    {
        try {
            $data = $this->seatTemplateService->updateSevice($id, $request->all());
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
