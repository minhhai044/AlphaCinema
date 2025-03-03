<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Models\Cinema;
use App\Models\Movie;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Str;



class TicketController extends Controller
{
    use ApiResponseTrait;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function createTicket(TicketRequest $ticketRequest): JsonResponse
    {
        try {
            $data = $ticketRequest->validated();
            $data['code'] = strtoupper(Str::random(8));
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

    public function getCinemas(Request $request)
    {

        $branchId = $request->branch_id;
        // Lọc các rạp phim theo chi nhánh
        $cinemas = Cinema::where('branch_id', $branchId)->get();

        return response()->json(['cinemas' => $cinemas]);
    }

    // // Lấy danh sách phim theo rạp phim
    // public function getMovies(Request $request)
    // {
    //     $cinemaId = $request->cinema_id;

    //     // Lọc các phim theo rạp phim
    //     $movies = Movie::where('cinema_id', $cinemaId)->get();

    //     return response()->json(['movies' => $movies]);
    // }

}
