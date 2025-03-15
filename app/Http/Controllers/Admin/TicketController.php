<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected const PATH_VIEW = "admin.tickets.";

    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        return view(self::PATH_VIEW . 'index', compact('tickets', 'branches', 'branchesRelation', 'movies'));
    }

    public function show(Ticket $ticket)
    {
        $ticketData = $this->ticketService->getTicketDetail($ticket->id);
        return view(self::PATH_VIEW . 'detail', compact('ticketData'));
    }

    public function print(Ticket $ticket)
    {
        $ticketData = $this->ticketService->getTicketDetail($ticket->id);
        return view(self::PATH_VIEW . 'print', compact('ticketData'));
    }
}
