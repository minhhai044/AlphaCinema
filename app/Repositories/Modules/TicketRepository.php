<?php

namespace App\Repositories\Modules;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Lấy tất cả các ticket với các mối quan hệ
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->ticket
            ->with('user', 'cinema', 'room', 'movie', 'showtime')
            ->get();
    }

    /**
     * Tìm ticket theo ID
     *
     * @param mixed $id
     * @return Ticket
     */
    public function findTicket($id)
    {
        return $this->ticket
            ->with('movie', 'cinema', 'room', 'user', 'showtime', 'branch')
            ->findOrFail($id);
    }

    /**
     * Lấy danh sách ticket với các bộ lọc
     *
     * @param array $filters
     * @return Collection
     */
    public function getTickets(array $filters = []): Collection
    {
        $query = $this->ticket
            ->with('user', 'cinema', 'room', 'movie', 'showtime');

        if (isset($filters['date'])) {
            $query->whereHas('showtime', function ($q) use ($filters) {
                $q->where('date', $filters['date']);
            });
        }

        if (isset($filters['cinema_id'])) {
            $query->where('cinema_id', $filters['cinema_id']);
        }

        if (isset($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        if (isset($filters['movie_id'])) {
            $query->where('movie_id', $filters['movie_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }
}
