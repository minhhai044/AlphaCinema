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
    protected $ticket;

    public function __construct(TicketRepository $ticketRepository, Ticket $ticket)
    {
        $this->ticketRepository = $ticketRepository;
        $this->ticket = $ticket;
    }

    public function getService($request)
    {
        $user = auth()->user();
        $date = $request->query('date', '');
        $branch_id = $request->query('branch_id', $user->branch_id ?? ''); // Mặc định dùng branch_id của user nếu có
        $cinema_id = $request->query('cinema_id', $user->cinema_id ?? ''); // Mặc định dùng cinema_id của user nếu có
        $movie_id = $request->query('movie_id', '');
        $status = $request->query('status_id', '');

        $ticketsQuery = Ticket::with('movie', 'branch', 'cinema', 'user', 'showtime');

        // Phân quyền mặc định dựa trên vai trò user
        if ($user->hasRole('System Admin')) {
            // System Admin: Lấy tất cả vé nếu không có filter cụ thể
        } elseif ($user->branch_id) {
            // Quản lý chi nhánh: Lấy vé của chi nhánh ngay từ đầu
            $ticketsQuery->whereHas('cinema', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        } elseif ($user->cinema_id) {
            // Quản lý rạp: Lấy vé của rạp ngay từ đầu
            $ticketsQuery->where('cinema_id', $user->cinema_id);
        } else {
            // Không có quyền: Trả về rỗng
            $ticketsQuery->where('id', 0);
        }

        // Áp dụng các filter từ query params (nếu có)
        $tickets = $ticketsQuery
            ->when($date, function ($query) use ($date) {
                $query->whereHas('showtime', function ($q) use ($date) {
                    $q->where('date', $date);
                });
            })
            ->when($branch_id, function ($query) use ($branch_id) {
                $query->whereHas('cinema', function ($q) use ($branch_id) {
                    $q->where('branch_id', $branch_id);
                });
            })
            ->when($cinema_id, fn($query) => $query->where('cinema_id', $cinema_id))
            ->when($movie_id, fn($query) => $query->where('movie_id', $movie_id))
            ->when($status, fn($query) => $query->where('status', $status))
            ->get();

        // Lấy danh sách chi nhánh và rạp
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


        $seatData = $this->getSeatList($ticketDetail->ticket_seats);
        $totalSeatPrice = $seatData['total_price'] ?? 0;

        $combos = $this->getComboList($ticketDetail->ticket_combos);
        $totalComboPrice = array_reduce($combos, function ($carry, $combo) {
            return $carry + (isset($combo['total_price']) ? str_replace([' VND', ','], '', $combo['total_price']) : 0);
        }, 0);

        return [
            'id' => $ticketDetail->id,
            'code' => $ticketDetail->code,
            'movie' => [
                'name' => $ticketDetail->movie->name ?? 'N/A',
                'thumbnail' => $ticketDetail->movie->img_thumbnail ? asset('storage/' . $ticketDetail->movie->img_thumbnail) : asset('path/to/default/image.jpg'),
                'age_rating' => $ticketDetail->movie->age_rating ?? 'P',
                'duration' => $ticketDetail->movie->duration ?? '160 phút',
            ],
            'cinema' => [
                'name' => optional($ticketDetail->cinema)->name ?? 'N/A',
                'room' => optional($ticketDetail->room)->name ?? 'N/A',
            ],
            'branch' => [
                'name' => optional($ticketDetail->branch)->name ?? 'N/A',
            ],
            'showtime' => [
                'start_time' => $ticketDetail->showtime->start_time ? Carbon::parse($ticketDetail->showtime->start_time)->format('H:i') : 'N/A',
                'end_time' => $ticketDetail->showtime->end_time ? Carbon::parse($ticketDetail->showtime->end_time)->format('H:i') : 'N/A',
                'date' => $ticketDetail->showtime->date ? Carbon::parse($ticketDetail->showtime->date)->format('d/m/Y') : 'N/A',
            ],
            'seats' => $seatData,
            'ticket_price' => $this->formatPrice($totalSeatPrice),
            'combos' => $combos,
            'total_combo_price' => $this->formatPrice($totalComboPrice),
            'total_amount' => $this->formatPrice($ticketDetail->total_price),
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
            'voucher_code' => $ticketDetail->voucher_code ?? 'N/A',
            'voucher_discount' => $this->formatPrice($ticketDetail->voucher_discount),
            'point_use' => $ticketDetail->point_use ? number_format($ticketDetail->point_use, 0, ',', '.') : 'N/A',
            'point_discount' => $this->formatPrice($ticketDetail->point_discount),
            'expiry' => $ticketDetail->expiry ? Carbon::parse($ticketDetail->expiry)->format('d/m/Y H:i') : 'N/A',
        ];
    }

    private function getSeatList($ticketSeats)
    {
        $result = [
            'names' => 'N/A',
            'details' => [],
            'total_price' => 0,
        ];

        if (empty($ticketSeats) || !is_array($ticketSeats)) {
            return $result;
        }

        $seats = isset($ticketSeats['seats']) && is_array($ticketSeats['seats'])
            ? $ticketSeats['seats']
            : $ticketSeats;

        if (empty($seats)) {
            return $result;
        }

        $seatDetails = array_map(function ($seat) {
            return [
                'name' => $seat['seat_name'] ?? 'N/A',
                'price' => $seat['price'] ? number_format($seat['price'], 0, ',', '.') . ' VND' : '0 VND',
            ];
        }, $seats);

        $seatNames = array_column($seatDetails, 'name');
        $totalPrice = array_reduce($seatDetails, function ($carry, $seat) {
            return $carry + str_replace([' VND', ','], '', $seat['price']);
        }, 0);

        return [
            'names' => implode(', ', $seatNames) ?: 'N/A',
            'details' => $seatDetails,
            'total_price' => $totalPrice,
        ];
    }

    private function getComboList($ticketCombos)
    {
        if (!is_array($ticketCombos) || empty($ticketCombos)) {
            return [];
        }

        return array_filter(array_map(function ($combo) {
            if (!is_array($combo) || !isset($combo['name'])) {
                return null;
            }

            $comboName = $combo['name'] ?? 'N/A';
            $quantity = $combo['quantity'] ?? 1;
            $price = isset($combo['price_sale']) ? $combo['price_sale'] : ($combo['price'] ?? 0);
            $totalPrice = $price * $quantity;
            $imgThumbnail = $combo['img_thumbnail'] ? asset('storage/' . $combo['img_thumbnail']) : asset('path/to/default_combo.jpg');

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
                'price' => $this->formatPrice($price),
                'total_price' => $totalPrice,
            ];
        }, $ticketCombos), fn($item) => $item !== null);
    }

    private function formatPrice($price, $currency = 'VND'): string
    {
        return $price ? number_format($price, 0, ',', '.') . ' ' . $currency : '0 ' . $currency;
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->ticket->create($data);
            });
        } catch (Exception $e) {
            Log::error("LỖI KHI TẠO MỚI: " . $e->getMessage());
            return false;
        }
    }

    public function getTicketID($id)
    {
        return $this->ticket->findOrFail($id);
    }
}
