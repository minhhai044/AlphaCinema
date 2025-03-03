<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;

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
        // Sử dụng TicketService để lấy dữ liệu
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        // Lấy danh sách cinema từ $branchesRelation (nếu cần)
        $cinemas = [];
        if (!empty($branchesRelation)) {
            foreach ($branchesRelation as $branchCinemas) {
                $cinemas = array_merge($cinemas, array_values($branchCinemas));
            }
        }

        // Truyền tất cả các biến vào view
        return view(self::PATH_VIEW . __FUNCTION__, compact('tickets', 'branches', 'cinemas', 'movies'));
    }
}
