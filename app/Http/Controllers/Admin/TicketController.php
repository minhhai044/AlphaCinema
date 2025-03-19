<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class TicketController extends Controller
{
    use ApiResponseTrait;
    protected const PATH_VIEW = "admin.tickets.";

    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        $cinemas = Cinema::pluck('id', 'name');
// dd($cinemas);
// dd(auth()->user()->cinema_id, auth()->user());


        return view(self::PATH_VIEW . 'index', compact('tickets', 'branches', 'branchesRelation', 'movies', 'cinemas'));
    }

    public function show(Ticket $ticket)
    {
        $ticketData = $this->ticketService->getTicketDetail($ticket->id);
        return view(self::PATH_VIEW . 'detail', compact('ticketData'));
    }
    public function print()
    {
        return view(self::PATH_VIEW . 'test');
    }

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
            $userPrintTicket = Auth::user()->name;

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
                'type_movie' => $type_movie,
                'category_movie' => $category_movie,
                'duration' => $duration,
                'barcode' => $barcode,
                'code' => $code,
                'userPrintTicket' => $userPrintTicket
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

            $userPrintTicket = Auth::user()->name;

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
                'type_movie' => $type_movie,
                'category_movie' => $category_movie,
                'duration' => $duration,
                'barcode' => $barcode,
                'userPrintTicket' => $userPrintTicket,
                'code' => $code
            ], 'Thành công rồi nè');
        } catch (\Throwable $th) {
            // Xử lý lỗi và trả về thông báo lỗi
            Log::error($th->getMessage());
            return $this->errorResponse('Có lỗi xảy ra, vui lòng thử lại!', 500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            // Tìm ticket theo ID
            $ticket = Ticket::findOrFail($request->ticket_id);

            // Cập nhật trạng thái của ticket
            $ticket->status = $request->status;
            $ticket->staff = $request->staff;  // Có thể bỏ dòng này nếu không thay đổi `staff`
            $ticket->save(); // Lưu thay đổi

            // Trả về phản hồi thành công
            return response()->json(['message' => 'Trạng thái ticket đã được thay đổi!'], 200);
        } catch (\Exception $e) {
            // Bắt lỗi và trả về thông báo lỗi
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Đã có lỗi xảy ra khi thay đổi trạng thái ticket!',
                'error' => $e->getMessage(), // Lấy thông báo lỗi chi tiết
            ], 500);
        }
    }
}
