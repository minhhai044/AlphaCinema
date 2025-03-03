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
     */
    public function all(): Collection
    {
        return $this->ticket
            ->with('user', 'cinema', 'room', 'movie', 'showtime')
            ->get();
    }

    /**
     * Tìm ticket theo ID
     */
    public function find($id)
    {
        return $this->ticket->findOrFail($id);
    }
}
