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

        // Lấy tất cả tickets nếu không có filter, nếu có filter thì lọc theo điều kiện
        if (empty($date) && empty($branch_id) && empty($cinema_id) && empty($movie_id) && empty($status)) {
            $tickets = Ticket::with('movie', 'branch', 'cinema', 'user')->get();
        } else {
            $tickets = Ticket::with('movie', 'branch', 'cinema', 'user')
                ->when($date, fn($query) => $query->where('date', $date))
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
