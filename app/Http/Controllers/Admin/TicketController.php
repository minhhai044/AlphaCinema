<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Movie;
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

        $branchs = Branch::query()->get();
        $cinemas = Cinema::query()->get();
        $movies  = Movie::query()->get();

        return view(self::PATH_VIEW .__FUNCTION__, compact(['branchs', 'cinemas', 'movies']));
    }

   
}
