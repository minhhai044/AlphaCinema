<?php

namespace App\Services;

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

