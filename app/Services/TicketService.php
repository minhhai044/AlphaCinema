<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Movie;
use App\Models\Ticket;
use App\Repositories\Modules\TicketRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getService($request)
    {
        $date = $request->query('date', '');
        $branch_id = $request->query('branch_id', '');
        $cinema_id = $request->query('cinema_id', '');
        $movie_id = $request->query("movie_id", "");
        $status = $request->query("status", "");

        // Lấy tất cả tickets nếu không có filter, nếu có filter thì lọc theo điều kiện
        if (empty($date) && empty($branch_id) && empty($cinema_id) && empty($movie_id) && empty($status)) {
            $tickets = Ticket::with('movie', 'branch', 'cinema', 'user')->get();
        } else {
            $tickets = Ticket::with('movie', 'branch', 'cinema', 'user', 'showtime')
                ->when($date, function ($query) use ($date) {
                    $query->whereHas('showtime', function ($q) use ($date) {
                        $q->where('date', $date);
                    });
                })
                ->when($branch_id, fn($query) => $query->where('branch_id', $branch_id))
                ->when($cinema_id, fn($query) => $query->where('cinema_id', $cinema_id))
                ->when($movie_id, fn($query) => $query->where('movie_id', $movie_id))
                ->when($status, fn($query) => $query->where('status', $status))
                ->get();
        }

        $branches = Branch::with('cinemas.rooms')->where('is_active', 1)->get();
        $branchesRelation = [];
        foreach ($branches as $branch) {
            $branchesRelation[$branch->id] = $branch->cinemas->where('is_active', 1)->pluck('name', 'id')->all();
        }

        $movies = Movie::query()->where('is_active', 1)->get();

        return [$tickets, $branches, $branchesRelation, $movies];
    }
    public function getTicketDetail($id)
    {
        $ticketDetail = $this->ticketRepository->findTicket($id);

        return [
            'id' => $ticketDetail->id,
            'code' => $ticketDetail->code,
            'movie' => [
                'name' => $ticketDetail->movie->name ?? 'N/A',
                'thumbnail' => $ticketDetail->movie->img_thumbnail ? asset('storage/' . $ticketDetail->movie->img_thumbnail) : asset('path/to/default/image.jpg'),
            ],
            'cinema' => [
                'name' => $ticketDetail->cinema->name ?? 'N/A',
                'room' => $ticketDetail->room->name ?? 'N/A',
            ],
            'showtime' => [
                'start_time' => $ticketDetail->showtime->start_time ? Carbon::parse($ticketDetail->showtime->start_time)->format('H:i') : 'N/A',
                'end_time' => $ticketDetail->showtime->end_time ? Carbon::parse($ticketDetail->showtime->end_time)->format('H:i') : 'N/A',
                'date' => $ticketDetail->showtime->date ? Carbon::parse($ticketDetail->showtime->date)->format('d/m/Y') : 'N/A',
            ],
            'total_price' => $ticketDetail->total_price ? number_format($ticketDetail->total_price, 0, ',', '.') . ' VND' : 'N/A',
            'status' => $ticketDetail->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận',
            'user' => [
                'name' => $ticketDetail->user->name ?? 'N/A',
                'role' => $ticketDetail->user->role ?? 'member',
                'email' => $ticketDetail->user->email ?? 'N/A',
                'phone' => $ticketDetail->user->phone ?? 'N/A',
            ],
            'payment_name' => $ticketDetail->payment_name ?? 'N/A',
            'payment_time' => $ticketDetail->created_at ? Carbon::parse($ticketDetail->created_at)->format('H:i - d/m/Y') : 'N/A',
            'customer_name' => $ticketDetail->user->name ?? 'N/A',
            'total_amount' => $ticketDetail->total_price ? number_format($ticketDetail->total_price, 0, ',', '.') . ' VND' : 'N/A',
            'voucher_code' => $ticketDetail->voucher_code ?? 'N/A',
            'voucher_discount' => $ticketDetail->voucher_discount ? number_format($ticketDetail->voucher_discount, 0, ',', '.') . ' VND' : 'N/A',
            'point_use' => $ticketDetail->point_use ? number_format($ticketDetail->point_use, 0, ',', '.') : 'N/A',
            'point_discount' => $ticketDetail->point_discount ? number_format($ticketDetail->point_discount, 0, ',', '.') . ' VND' : 'N/A',
            'expiry' => $ticketDetail->expiry ? Carbon::parse($ticketDetail->expiry)->format('d/m/Y H:i') : 'N/A',
            'seats' => $this->getSeatList($ticketDetail->ticket_seats),
            'combos' => $this->getComboList($ticketDetail->ticket_combos),

        ];
    }

    private function getSeatList($ticketSeats)
    {
        // Kiểm tra nếu ticketSeats rỗng hoặc không phải mảng
        if (empty($ticketSeats) || !is_array($ticketSeats)) {
            return 'N/A';
        }

        // Nếu ticket_seats nằm trong key "seats" (tùy cách dữ liệu được lưu)
        $seats = isset($ticketSeats['seats']) && is_array($ticketSeats['seats'])
            ? $ticketSeats['seats']
            : $ticketSeats;

        // Kiểm tra xem mảng có dữ liệu hợp lệ không
        if (empty($seats)) {
            return 'N/A';
        }

        // Lấy danh sách tên ghế
        $seatNames = array_map(function ($seat) {
            // Đảm bảo seat là mảng và có key 'name'
            return is_array($seat) && isset($seat['name']) ? $seat['name'] : 'N/A';
        }, $seats);

        // Nối các tên ghế bằng dấu phẩy
        return implode(', ', $seatNames) ?: 'N/A';
    }
    private function getComboList($ticketCombos)
    {
        // Nếu dữ liệu không phải mảng hoặc rỗng, trả về mảng rỗng
        if (!is_array($ticketCombos) || empty($ticketCombos)) {
            return [];
        }

        // Xử lý danh sách combo
        return array_filter(array_map(function ($combo) {
            // Kiểm tra combo có phải mảng và có key 'name'
            if (!is_array($combo) || !isset($combo['name'])) {
                return null; // Trả về null nếu không hợp lệ, sẽ bị lọc bởi array_filter
            }

            $comboName = $combo['name'] ?? 'N/A';
            $quantity = $combo['quantity'] ?? 1;
            $price = isset($combo['price_sale']) ? $combo['price_sale'] : ($combo['price'] ?? 0);
            $totalPrice = number_format($price * $quantity, 0, ',', '.') . ' VND';
            $imgThumbnail = $combo['img_thumbnail'] ? asset('storage/' . $combo['img_thumbnail']) : asset('path/to/default_combo.jpg');

            // Xử lý danh sách món trong combo
            $foods = $combo['foods'] ?? [];
            $foodList = array_map(function ($food) {
                $foodName = $food['name'] ?? 'N/A';
                $quantity = $food['quantity'] ?? 1;
                return "$foodName (SL: $quantity)";
            }, $foods);

            return [
                'name' => $comboName,
                'image' => $imgThumbnail,
                'foods' => $foodList,
                'quantity' => $quantity,
                'price' => number_format($price, 0, ',', '.') . ' VND',
                'total_price' => $totalPrice,
            ];
        }, $ticketCombos), fn($item) => $item !== null); // Lọc bỏ các giá trị null
    }
}


// [
//     {
//         "id": 1,
//         "name": "Combooooo",
//         "foods": [
//             {
//                 "id": 1,
//                 "name": "Trần Minh Hải",
//                 "type": "Đồ ăn",
//                 "price": "12345",
//                 "quantity": 1,
//                 "is_active": 1,
//                 "created_at": "2025-03-01T02:36:50.000000Z",
//                 "updated_at": "2025-03-01T02:36:50.000000Z",
//                 "description": "aa",
//                 "img_thumbnail": "foodImages/gGLNgJcakpDNDRzgtMlS83gdcTKbDMgBxWfPE3Mj.png"
//             },
//             {
//                 "id": 3,
//                 "name": "Phân tích thống kê website",
//                 "type": "Đồ uống",
//                 "price": "12345",
//                 "quantity": 1,
//                 "is_active": 1,
//                 "created_at": "2025-03-01T02:37:05.000000Z",
//                 "updated_at": "2025-03-01T02:37:05.000000Z",
//                 "description": "a",
//                 "img_thumbnail": "foodImages/WnEzhUjXtZVTNK0Qwg67D5ueVBadQ5gUp6Th9Xnd.png"
//             }
//         ],
//         "price": "24690",
//         "quantity": 2,
//         "is_active": 1,
//         "created_at": "2025-03-01T02:37:54.000000Z",
//         "price_sale": "10000",
//         "updated_at": "2025-03-01T02:37:54.000000Z",
//         "description": "aaaaaaaaa",
//         "img_thumbnail": "comboImages/NYaC6YDrGg3BYg5fIkwWdBBUYsibmJ7ZNNeuSVvZ.jpg"
//     },
//     {
//
//         "foods": [
//             {
//                 "id": 1,
//                 "name": "Trần Minh Hải",
//                 "type": "Đồ ăn",
//                 "price": "12345",
//                 "quantity": 1,
//                 "is_active": 1,
//                 "created_at": "2025-03-01T02:36:50.000000Z",
//                 "updated_at": "2025-03-01T02:36:50.000000Z",
//                 "description": "aa",
//                 "img_thumbnail": "foodImages/gGLNgJcakpDNDRzgtMlS83gdcTKbDMgBxWfPE3Mj.png"
//             },
//            
// ]
