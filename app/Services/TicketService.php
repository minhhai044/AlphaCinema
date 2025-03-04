<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Movie;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class TicketService
{
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        $ticket = $this->ticket->findTicket($id);

        return [
            'id' => $ticket->id,
            'code' => $ticket->code,
            'movie' => [
                'name' => $ticket->movie->name ?? 'N/A',
                'thumbnail' => $ticket->movie->img_thumbnail ? asset('storage/' . $ticket->movie->img_thumbnail) : asset('path/to/default/image.jpg'),
            ],
            'branch' => ['name' => $ticket->branch->name ?? 'N/A'],
            'cinema' => [
                'name' => $ticket->cinema->name ?? 'N/A',
                'room' => $ticket->cinema->room ?? 'N/A',
            ],
            'showtime' => [
                'start_time' => $ticket->showtime->start_time ? Carbon::parse($ticket->showtime->start_time)->format('H:i') : 'N/A',
                'end_time' => $ticket->showtime->end_time ? Carbon::parse($ticket->showtime->end_time)->format('H:i') : 'N/A',
                'date' => $ticket->showtime->date ? Carbon::parse($ticket->showtime->date)->format('d/m/Y') : 'N/A',
                'seats' => $ticket->showtime->seats ? implode(', ', json_decode($ticket->showtime->seats, true) ?? []) : 'N/A',
            ],
            'total_price' => $ticket->total_price ?? 'N/A',
            'status' => $ticket->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận',
            'user' => [
                'name' => $ticket->user->name ?? 'N/A',
                'role' => $ticket->user->role ?? 'member',
                'email' => $ticket->user->email ?? 'N/A',
                'phone' => $ticket->user->phone ?? 'N/A',
            ],
            'payment_name' => $ticket->payment_name ?? 'N/A',
            'payment_time' => $ticket->created_at ? Carbon::parse($ticket->created_at)->format('H:i - d/m/Y') : 'N/A',
            'customer_name' => $ticket->user->name ?? 'N/A',
            'total_amount' => $ticket->total_price ?? 'N/A',
        ];
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
}
