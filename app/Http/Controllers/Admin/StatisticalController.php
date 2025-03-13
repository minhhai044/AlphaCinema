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
        $cinemas = array_reduce($branchesRelation ?? [], fn($carry, $branchCinemas) => array_merge($carry, array_values($branchCinemas)), []);

        // Lấy các tham số lọc từ request
        $branchId = $request->input('branch_id');
        $cinemaId = $request->input('cinema_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $defaultStartDate = Carbon::now()->subDays(30);

        // Xây dựng truy vấn duy nhất lấy cả revenue và ticket_count
        $query = DB::table('tickets')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('SUM(tickets.total_price) as revenue'), // Tổng doanh thu
                DB::raw('SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) as ticket_count') // Tổng số ghế (vé)
            )
            ->groupBy('movies.name');

        // Áp dụng các điều kiện lọc
        $query->when($branchId, fn($q) => $q->where('branches.id', $branchId))
            ->when($startDate || $endDate, function ($q) use ($startDate, $endDate, $defaultStartDate) {
                // Trường hợp start_date và end_date trùng nhau
                if ($startDate && $endDate && $startDate === $endDate) {
                    $q->whereDate('tickets.created_at', $startDate);
                } else {
                    // Áp dụng khoảng thời gian
                    $q->when($startDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->whereDate('tickets.created_at', '<=', $endDate));
                }
            }, fn($q) => $q->whereDate('tickets.created_at', '>=', $defaultStartDate));
        // Lấy dữ liệu từ database
        $revenues = $query->orderBy('revenue', 'desc')->get()->toArray();

        // Kiểm tra nếu không có dữ liệu
        $message = empty($revenues) ? 'Không có dữ liệu để hiển thị.' : null;

        // Trả về view với dữ liệu
        return view('admin.statistical.cinema_revenue', compact('branches', 'cinemas', 'revenues','message'));
    }
}
