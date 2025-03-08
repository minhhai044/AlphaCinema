<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        // $user =  User::find(1);

        // if($user->hasRole("System Admin")){
        //     dd(1);
        // }

        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        $cinemas = [];
        if (!empty($branchesRelation)) {
            foreach ($branchesRelation as $branchCinemas) {
                $cinemas = array_merge($cinemas, array_values($branchCinemas));
            }
        }

        // Truyền tất cả các biến vào view
        return view(self::PATH_VIEW . __FUNCTION__, compact('tickets', 'branches', 'cinemas', 'movies'));
    }

    public function print(){
        return view(self::PATH_VIEW.'test');
    }
}
