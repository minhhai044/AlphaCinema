<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Movie;
use App\Models\Ticket;
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

        if (empty($date) || empty($branch_id) || empty($cinema_id) || empty($status)) {
            $tickets = collect();
        } else {
            $tickets = Ticket::with('movie', 'branch', 'cinema')
                ->where('date', $date)
                ->where('branch_id', $branch_id)
                ->where('movie_id', $movie_id)
                ->where('cinema_id', $cinema_id)
                ->where("status", $status)
                ->get();
        }

        // $listTickets = $tickets->groupBy('movie_id')->map(function ($showtimes) {
        //     return [
        //         'movie' => $showtimes->first()->movie, // Lấy thông tin phim từ showtime đầu tiên
        //         'showtimes' => $showtimes->toArray(), // Danh sách suất chiếu của phim đó
        //     ];
        // });

        $branchs = Branch::with('cinemas.rooms')->where('is_active', 1)->get();
        $branchsRelation = [];
        foreach ($branchs as $branch) {
            $branchsRelation[$branch['id']] = $branch->cinemas->where('is_active', 1)->pluck('name', 'id')->all();
        }

        $movies = Movie::query()->where('is_active', 1)->get();

        return [$branchs, $branchsRelation, $movies];
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->ticket->create($data);
            });
        } catch (Exception $e) {
            // Ghi log lỗi nếu có vấn đề xảy ra
            Log::error("lÕI KHI Tạo MỚI: " . $e->getMessage());
            return false;
        }
    }
}
