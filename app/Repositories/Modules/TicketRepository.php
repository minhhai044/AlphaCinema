<?php

namespace App\Repositories\Modules;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

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
        $query = $this->ticket->with('user', 'cinema', 'room', 'movie', 'showtime');
        $user = Auth::user();

        if ($user->hasRole('Quản lý chi nhánh')) {
            $query->whereHas('cinema', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        } elseif ($user->hasRole('Quản lý rạp')) {
            $query->where('cinema_id', $user->cinema_id);
        }

        return $query->get();
    }

    /**
     * Tìm ticket theo ID
     *
     * @param mixed $id
     * @return Ticket
     */
    public function findTicket($code)
    {
        $query = $this->ticket->with('movie', 'cinema', 'room', 'user', 'showtime', 'branch','notification')->where('code',$code);
        $user = Auth::user();

        if ($user->hasRole('Quản lý chi nhánh')) {
            $query->whereHas('cinema', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        } elseif ($user->hasRole('Quản lý rạp')) {
            $query->where('cinema_id', $user->cinema_id);
        }

        return $query->first();
    }

    /**
     * Lấy danh sách ticket với các bộ lọc
     *
     * @param array $filters
     * @return Collection
     */
    public function getTickets(array $filters = []): Collection
    {
        $query = $this->ticket->with('user', 'cinema', 'room', 'movie', 'showtime');
        $user = Auth::user();

        // Áp dụng phân quyền
        if ($user->hasRole('Quản lý chi nhánh')) {
            $query->whereHas('cinema', function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        } elseif ($user->hasRole('Quản lý rạp')) {
            $query->where('cinema_id', $user->cinema_id);
        }

        // Áp dụng các bộ lọc
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
