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
        $query = DB::table('tickets')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id') // Join với bảng movies
            ->select(
                'movies.name as movie_name', // Lấy tên phim
                DB::raw('SUM(tickets.total_price) as revenue') // Tổng doanh thu theo phim
            )
            ->where('tickets.status', 'confirmed') // Chỉ tính vé đã xác nhận
            ->groupBy('movies.name'); // Nhóm theo tên phim

        // Áp dụng các điều kiện lọc theo ngày, chi nhánh, rạp
        if ($branchId) {
            $query->where('branches.id', $branchId);
        }
        if ($cinemaId) {
            $query->where('cinemas.id', $cinemaId);
        }
        if ($startDate) {
            $query->whereDate('showtimes.start_time', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('showtimes.start_time', '<=', $endDate);
        }
        // Nếu không có ngày nào được chọn, mặc định lấy 7 ngày gần nhất
        if (!$startDate && !$endDate) {
            $query->whereDate('showtimes.start_time', '>=', Carbon::now()->subDays(7));
        }

        // Lấy dữ liệu động từ database
        $revenues = $query->orderBy('revenue', 'desc')->get()->toArray(); // Sắp xếp theo doanh thu giảm dần




        
        // Trả về view với dữ liệu động
        return view('admin.statistical.cinema_revenue', compact('branches', 'cinemas', 'revenues'));
    }
}
