<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;

class TicketController extends Controller
{
    use ApiResponseTrait;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function createTicket(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            // Gọi đúng phương thức của TicketService
            $ticket = $this->ticketService->create($data);

            if (!$ticket) {
                return $this->errorResponse('Không thể tạo ticket', 500);
            }

            return $this->successResponse([
                'message' => 'Tạo ticket thành công',
                'data' => $ticket,
            ], 201);

        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo ticket: " . $e->getMessage());

            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại sau.', 500);
        }
    }
}
