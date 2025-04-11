<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;


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
            // $barcode = DNS1D::getBarcodeHTML($data['code'], 'C128', 1.5, 50);

            if (!$ticket) {
                return $this->errorResponse('Không thể tạo ticket', 500);
            }

            return $this->successResponse([
                'message' => 'Tạo ticket thành công',
                'data' => $ticket,
                // 'barcode' => $barcode,
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

    public function getTicketByID($id)
    {
        try {
            $ticket = $this->ticketService->getTicketID($id);

            // Kiểm tra nếu `cinema` tồn tại để tránh lỗi
            $branch = $ticket->cinema ? $ticket->cinema->branch->name : 'Không xác định';
            $cinema = $ticket->cinema ? $ticket->cinema->name : 'Không xác định';
            $user = $ticket->user->name;
            $ticket_combos = $ticket->ticket_combos;
            $ticket_seats = $ticket->ticket_seats;
            $room = $ticket->room->name;
            $showtime = $ticket->showtime->date;
            $start_time = $ticket->showtime->start_time;
            $end_time = $ticket->showtime->end_time;
            $address = $ticket->cinema->address;
            $movie = $ticket->movie->name;
            $type_movie = $ticket->room->type_room->name;
            $category_movie = $ticket->movie->category;
            $duration = $ticket->movie->duration;
            $barcode = DNS1D::getBarcodeHTML($ticket->code, 'C128', 1.5, 50);
            $code = $ticket->code;

            return $this->successResponse([
                'ticket' => $ticket,
                'branch' => $branch,
                'cinema' => $cinema,
                'user' => $user,
                'ticket_combos' => $ticket_combos,
                'ticket_seats' => $ticket_seats,
                'room' => $room,
                'showtime' => $showtime,
                'start_time' => date("H:i", strtotime($start_time)),
                'end_time' => date("H:i", strtotime($end_time)),
                'address' => $address,
                'movie' => $movie,
                'vat'   => $ticket->vat,
                'type_movie' => $type_movie,
                'category_movie' => $category_movie,
                'duration' => $duration,
                'barcode' => $barcode,
                'code' => $code
            ], 'Thành công rồi nè');
        } catch (\Throwable $th) {
            // Xử lý lỗi và trả về thông báo lỗi
            Log::error($th->getMessage());
            return $this->errorResponse('Có lỗi xảy ra, vui lòng thử lại!', 500);
        }
    }

    public function getComboFoodById($id)
    {
        try {
            $ticket = $this->ticketService->getTicketID($id);

            // Kiểm tra nếu `cinema` tồn tại để tránh lỗi
            $branch = $ticket->cinema ? $ticket->cinema->branch->name : 'Không xác định';
            $cinema = $ticket->cinema ? $ticket->cinema->name : 'Không xác định';
            $user = $ticket->user->name;
            $ticket_combos = $ticket->ticket_combos;
            $ticket_seats = $ticket->ticket_seats;
            $room = $ticket->room->name;
            $showtime = $ticket->showtime->date;
            $start_time = $ticket->showtime->start_time;
            $end_time = $ticket->showtime->end_time;
            $address = $ticket->cinema->address;
            $movie = $ticket->movie->name;
            $type_movie = $ticket->room->type_room->name;
            $category_movie = $ticket->movie->category;
            $duration = $ticket->movie->duration;
            $barcode = DNS1D::getBarcodeHTML($ticket->code, 'C128', 1.5, 50);
            $code = $ticket->code;

            return $this->successResponse([
                'ticket' => $ticket,
                'branch' => $branch,
                'cinema' => $cinema,
                'user' => $user,
                'ticket_combos' => $ticket_combos,
                'ticket_seats' => $ticket_seats,
                'ticket_foods' => $ticket->ticket_foods,
                'created_at' => Carbon::parse($ticket->created_at)->format("H:i d-m-Y"),
                'room' => $room,
                'showtime' => $showtime,
                'start_time' => date("H:i", strtotime($start_time)),
                'end_time' => date("H:i", strtotime($end_time)),
                'address' => $address,
                'movie' => $movie,
                'vat'   => $ticket->vat,
                'type_movie' => $type_movie,
                'category_movie' => $category_movie,
                'duration' => $duration,
                'barcode' => $barcode,
                'code' => $code
            ], 'Thành công rồi nè');
        } catch (\Throwable $th) {
            // Xử lý lỗi và trả về thông báo lỗi
            Log::error($th->getMessage());
            return $this->errorResponse('Có lỗi xảy ra, vui lòng thử lại!', 500);
        }
    }

    public function getTicketByUser()
    {
        try {
            // Lấy ID của user đang đăng nhập
            // $userId = Auth::id();
            $user = Auth::user();

            // Lấy danh sách vé của người dùng
            $tickets = Ticket::where('user_id', $user['id'])
                ->with(['showtime', 'movie', 'room', 'cinema', 'branch'])
                ->latest('id')
                ->get();

            return response()->json(['status' => 'success', 'data' => $tickets], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
