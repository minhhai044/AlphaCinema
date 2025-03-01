<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected const PATH_VIEW = "admin.tickets.";

    private $ticketService;

    public function __construct(TicketService $ticketService) {
        $this->ticketService = $ticketService;
    }

    public function index(){
        return view(self::PATH_VIEW .__FUNCTION__);
    }
}
