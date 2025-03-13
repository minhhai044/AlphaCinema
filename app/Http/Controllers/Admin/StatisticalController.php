<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticalController extends Controller
{
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function cinemaRevenue(Request $request)
    {
        // Tái sử dụng bộ lọc từ TicketService
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        // Lấy danh sách cinemas từ branchesRelation
        $cinemas = [];
        if (!empty($branchesRelation)) {
            foreach ($branchesRelation as $branchCinemas) {
                $cinemas = array_merge($cinemas, array_values($branchCinemas));
            }
        }

        // Lấy các tham số lọc từ request
        $branchId = $request->input('branch_id');
        $cinemaId = $request->input('cinema_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Xây dựng truy vấn doanh thu theo phim
        $query1 = DB::table('tickets')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('SUM(tickets.total_price) as revenue')
            )
            ->where('tickets.status', 'confirmed')
            ->groupBy('movies.name');


        $query2 = DB::table('tickets')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select(
                'tickets.movie_id',
                'movies.name as movie_name',
                DB::raw('SUM(JSON_LENGTH(tickets.ticket_seats)) as ticket_count')
            )
            ->whereNotNull('tickets.ticket_seats')
            ->groupBy('tickets.movie_id', 'movies.name');
        if ($branchId) {
            $query2->where('branches.id', $branchId);
        }
        if ($cinemaId) {
            $query2->where('cinemas.id', $cinemaId);
        }
        if ($startDate) {
            $query2->whereDate('showtimes.start_time', '>=', $startDate);
        }
        if ($endDate) {
            $query2->whereDate('showtimes.start_time', '<=', $endDate);
        }
        if (!$startDate && !$endDate) {
            $query2->whereDate('showtimes.start_time', '>=', Carbon::now()->subDays(7));
        }


        // Áp dụng các điều kiện lọc theo ngày, chi nhánh, rạp
        if ($branchId) {
            $query1->where('branches.id', $branchId);
        }
        if ($cinemaId) {
            $query1->where('cinemas.id', $cinemaId);
        }
        if ($startDate) {
            $query1->whereDate('showtimes.start_time', '>=', $startDate);
        }
        if ($endDate) {
            $query1->whereDate('showtimes.start_time', '<=', $endDate);
        }
        // Nếu không có ngày nào được chọn, mặc định lấy 7 ngày gần nhất
        if (!$startDate && !$endDate) {
            $query1->whereDate('showtimes.start_time', '>=', Carbon::now()->subDays(7));
        }

        // Lấy dữ liệu động từ database
        $revenues = $query1->orderBy('revenue', 'desc')->get()->toArray(); // Sắp xếp theo doanh thu giảm dần

        // Lấy dữ liệu động từ database
        $revenuesx = $query2->orderBy('ticket_count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'movie_name' => $item->movie_name,
                    'ticket_count' => $item->ticket_count
                ];
            })
            ->toArray();



        return view('admin.statistical.cinema_revenue', compact(
            'branches',
            'cinemas',
            'revenues',
            'revenuesx'
        ));
    }
}
