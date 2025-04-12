<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Voucher;
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
        $this->middleware('can:Danh sách hóa đơn')->only('index');
        $this->middleware('can:Quét hóa đơn')->only('scan', 'processScan');
        $this->middleware('can:Xem chi tiết hóa đơn')->only('show');
    }

    public function index(Request $request)
    {
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        $cinemas = Cinema::pluck('id', 'name');

        return view(self::PATH_VIEW . 'index', compact('tickets', 'branches', 'branchesRelation', 'movies', 'cinemas'));
    }

    public function show(string $code)
    {
        $ticketData = $this->ticketService->getTicketDetail($code);
        $ticketData['total_combo'] = $this->ticketService->extractNumber($ticketData['total_combo_price']);
        $ticketData['total_food'] = $this->ticketService->extractNumber($ticketData['total_food_price']);
        $ticketData['barcode'] = DNS1D::getBarcodeHTML($ticketData['code'], 'C128', 1.5, 50);

        // dd($ticketData);
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
            $ticketType = Voucher::where("code", $ticket['voucher_code'])->value('type_voucher');

            return $this->successResponse([
                'ticket'                => $ticket,
                'branch'                => $branch,
                'cinema'                => $cinema,
                'user'                  => $user,
                'ticket_combos'         => $ticket_combos,
                'ticket_seats'          => $ticket_seats,
                'room'                  => $room,
                'showtime'              => $showtime,
                'start_time'            => date("H:i", strtotime($start_time)),
                'end_time'              => date("H:i", strtotime($end_time)),
                'address'               => $address,
                'movie'                 => $movie,
                'type_movie'            => $type_movie,
                'category_movie'        => $category_movie,
                'duration'              => $duration,
                'barcode'               => $barcode,
                'code'                  => $code,
                'vat'                   => $ticket->vat,
                'userPrintTicket'       => $userPrintTicket,
                'voucher_type'          => $ticketType,
                'voucher_discount'      => $ticket->voucher_discount,
                'point_discount'        => $ticket->point_discount ?? 0,
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
            $ticketType = Voucher::where("code", $ticket['voucher_code'])->value('type_voucher');

            $userPrintTicket = Auth::user()->name;

            return $this->successResponse([
                'ticket'                => $ticket,
                'branch'                => $branch,
                'cinema'                => $cinema,
                'user'                  => $user,
                'ticket_combos'         => $ticket_combos,
                'ticket_seats'          => $ticket_seats,
                'ticket_foods'          => $ticket->ticket_foods,
                'created_at'            => Carbon::parse($ticket->created_at)->format("H:i d-m-Y"),
                'room'                  => $room,
                'showtime'              => $showtime,
                'start_time'            => date("H:i", strtotime($start_time)),
                'end_time'              => date("H:i", strtotime($end_time)),
                'address'               => $address,
                'movie'                 => $movie,
                'type_movie'            => $type_movie,
                'category_movie'        => $category_movie,
                'duration'              => $duration,
                'vat'                   => $ticket->vat,
                'barcode'               => $barcode,
                'userPrintTicket'       => $userPrintTicket,
                'code'                  => $code,
                'voucher_type'          => $ticketType,
                'voucher_discount'      => $ticket->voucher_discount,
            ], 'Thành công rồi nè');
        } catch (\Throwable $th) {
            // Xử lý lỗi và trả về thông báo lỗi
            Log::error($th->getMessage());
            return $this->errorResponse('Có lỗi xảy ra, vui lòng thử lại!', 500);
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'status' => 'required|in:confirmed,pending',
            'staff' => 'required|string',
        ]);

        $ticket = Ticket::find($request->ticket_id);
        if (!$ticket) {
            return response()->json(['message' => 'Vé không tồn tại'], 404);
        }

        $ticket->status = $request->status;
        $ticket->save();

        return response()->json(['message' => 'Cập nhật trạng thái thành công']);
    }
    public function checkExists(string $code)
    {
        $exists = Ticket::where('code', $code)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}
