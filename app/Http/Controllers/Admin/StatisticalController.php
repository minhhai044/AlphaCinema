<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        // Truy vấn doanh thu và số vé theo phim
        $revenueQuery = DB::table('tickets')
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

        // Truy vấn số lượng suất chiếu của từng phim theo tháng
        $showtimeQuery = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('DATE_FORMAT(showtimes.date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as showtime_count')
            )
            ->groupBy('movies.name', DB::raw('DATE_FORMAT(showtimes.date, "%Y-%m")'));

        // Áp dụng các điều kiện lọc cho cả hai truy vấn
        $filterClosure = function ($q) use ($startDate, $endDate, $defaultStartDate) {
            $q->when($startDate || $endDate, function ($q) use ($startDate, $endDate, $defaultStartDate) {
                if ($startDate && $endDate && $startDate === $endDate) {
                    $q->whereDate('tickets.created_at', $startDate);
                } else {
                    $q->when($startDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->whereDate('tickets.created_at', '<=', $endDate));
                }
            }, fn($q) => $q->whereDate('tickets.created_at', '>=', $defaultStartDate));
        };

        $showtimeFilterClosure = function ($q) use ($startDate, $endDate, $defaultStartDate) {
            $q->when($startDate || $endDate, function ($q) use ($startDate, $endDate, $defaultStartDate) {
                if ($startDate && $endDate && $startDate === $endDate) {
                    $q->where('showtimes.date', $startDate);
                } else {
                    $q->when($startDate, fn($q) => $q->where('showtimes.date', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('showtimes.date', '<=', $endDate));
                }
            }, fn($q) => $q->where('showtimes.date', '>=', $defaultStartDate));
        };

        $revenueQuery->when($branchId, fn($q) => $q->where('branches.id', $branchId))
            ->tap($filterClosure);

        $showtimeQuery->when($branchId, fn($q) => $q->where('branches.id', $branchId))
            ->tap($showtimeFilterClosure);

        // Lấy dữ liệu từ database
        $revenues = $revenueQuery->orderBy('revenue', 'desc')->get()->toArray();
        $showtimes = $showtimeQuery->orderBy('month', 'asc')->get()->toArray();

        // Kiểm tra nếu không có dữ liệu
        $message = empty($revenues) && empty($showtimes) ? 'Không có dữ liệu để hiển thị.' : null;

        // Trả về view với dữ liệu
        return view('admin.statistical.cinema_revenue', compact('branches', 'cinemas', 'revenues', 'showtimes', 'message'));
    }
    public function comboRevenue(Request $request)
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
        

        // Xây dựng query cơ bản với Eloquent
        $query = Ticket::whereNotNull('ticket_combos')
            ->where('ticket_combos', '!=', '[]')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Áp dụng bộ lọc chi nhánh và rạp
        if ($branchId) {
            $query->whereIn('cinema_id', Cinema::where('branch_id', $branchId)->pluck('id'));
        }
        if ($cinemaId) {
            $query->where('cinema_id', $cinemaId);
        }

        // Lấy dữ liệu tickets
        $tickets = $query->get();

        // 1. Tổng doanh thu combo
        $comboRevenue = $tickets->sum(function ($ticket) {
            $combos = is_string($ticket->ticket_combos) ? json_decode($ticket->ticket_combos, true) : $ticket->ticket_combos;
            return collect($combos)->sum(fn($combo) => $combo['price_sale'] * $combo['quantity']);
        });

        // 2. Top 5 combo bán chạy (theo doanh thu)
        $comboStats = $tickets->flatMap(function ($ticket) {
            $combos = is_string($ticket->ticket_combos) ? json_decode($ticket->ticket_combos, true) : $ticket->ticket_combos;
            return collect($combos)->map(fn($combo) => [
                'combo_name' => $combo['name'],
                'total_quantity' => (int) $combo['quantity'],
                'total_price' => $combo['price_sale'] * $combo['quantity'],
            ]);
        })->groupBy('combo_name')->map(function ($group) {
            return (object) [
                'combo_name' => $group->first()['combo_name'],
                'total_quantity' => $group->sum('total_quantity'),
                'total_price' => $group->sum('total_price'),
            ];
        })->sortByDesc('total_price')->take(5)->values();

        $comboStatistics = $comboStats->all();
        $comboNames = $comboStats->pluck('combo_name')->all();
        $comboQuantities = $comboStats->pluck('total_quantity')->all();
        $comboRevenues = $comboStats->pluck('total_price')->all();

        // 3. Xu hướng doanh thu combo theo ngày
        $trendData = $tickets->groupBy(fn($ticket) => Carbon::parse($ticket->created_at)->toDateString())
            ->map(function ($group) {
                return $group->sum(function ($ticket) {
                    $combos = is_string($ticket->ticket_combos) ? json_decode($ticket->ticket_combos, true) : $ticket->ticket_combos;
                    return collect($combos)->sum(fn($combo) => $combo['price_sale'] * $combo['quantity']);
                });
            })->sortKeys();

        $trendDates = $trendData->keys()->all();
        $trendRevenues = $trendData->values()->all();

        // 4. Tỷ lệ đơn hàng có combo
        $totalTickets = Ticket::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($branchId, fn($q) => $q->whereIn('cinema_id', Cinema::where('branch_id', $branchId)->pluck('id')))
            ->when($cinemaId, fn($q) => $q->where('cinema_id', $cinemaId))
            ->count();

        $comboUsage = $totalTickets > 0 ? round(($tickets->count() / $totalTickets) * 100, 2) : 0;

        $comboSummaries = null;
        // Trả về view với dữ liệu
        return view('admin.statistical.ComboStatistical', compact(
            'branches',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'comboRevenue',
            'comboNames',
            'comboQuantities',
            'comboRevenues',
            'trendDates',
            'trendRevenues',
            'comboUsage',
            'comboStatistics',
            'comboSummaries'  
        ));
    }
    public function foodRevenue(Request $request)
    {
        $user = Auth::user();
        $branches = Branch::where('is_active', 1)->get();

        // Lấy khoảng thời gian và bộ lọc từ request hoặc session
        $startDate = $request->input('start_date', session('statistical.start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = $request->input('end_date', session('statistical.end_date', Carbon::now()->format('Y-m-d')));
        $branchId = $request->input('branch_id', session('statistical.branch_id'));
        $cinemaId = $request->input('cinema_id', session('statistical.cinema_id'));

        // Lưu vào session
        session([
            'statistical.start_date' => $startDate,
            'statistical.end_date' => $endDate,
            'statistical.branch_id' => $branchId,
            'statistical.cinema_id' => $cinemaId,
        ]);

        // Điều kiện lọc chi nhánh/rạp
        $conditions = [];
        if ($branchId) {
            $cinemaIds = Cinema::where('branch_id', $branchId)->pluck('id')->toArray();
            if (!empty($cinemaIds)) {
                $conditions[] = "cinema_id IN (" . implode(',', $cinemaIds) . ")";
            }
        }
        if ($cinemaId) {
            $conditions[] = "cinema_id = " . (int)$cinemaId;
        }
        $whereClause = !empty($conditions) ? " AND " . implode(' AND ', $conditions) : "";

        // Truy vấn thống kê top 5 món ăn bán chạy
        $foodQuery = "
        SELECT
            JSON_UNQUOTE(JSON_EXTRACT(tf.food_item, '$.name')) AS food_name,
            CAST(FLOOR(SUM(JSON_EXTRACT(tf.food_item, '$.quantity'))) AS UNSIGNED) AS total_quantity,
            SUM(JSON_EXTRACT(tf.food_item, '$.price') * JSON_EXTRACT(tf.food_item, '$.quantity')) AS total_price,
            CONCAT(
                CAST(FLOOR(SUM(JSON_EXTRACT(tf.food_item, '$.quantity'))) AS UNSIGNED), ' lượt - ',
                FORMAT(SUM(JSON_EXTRACT(tf.food_item, '$.price') * JSON_EXTRACT(tf.food_item, '$.quantity')), 0), ' VND'
            ) AS summary
        FROM tickets
        JOIN JSON_TABLE(
            ticket_foods,
            '$[*]' COLUMNS (
                food_item JSON PATH '$'
            )
        ) AS tf ON 1=1
        WHERE ticket_foods IS NOT NULL
        AND created_at BETWEEN ? AND ?
        $whereClause
        GROUP BY food_name
        ORDER BY total_price DESC
            LIMIT 5
    ";

        // Thực thi truy vấn
        $foodStatistics = DB::select($foodQuery, [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        // dd($foodStatistics);
        // Chuẩn bị dữ liệu cho view
        $foodQuantities = array_column($foodStatistics, 'total_quantity');
        $foodNames = array_column($foodStatistics, 'food_name');
        $foodSummaries = array_column($foodStatistics, 'summary');
        $foodRevenue = array_column($foodStatistics, 'total_price');

        // Trả về view
        return view('admin.statistical.FoodStatistical', compact(
            'branches',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'foodStatistics',
            'foodQuantities',
            'foodNames',
            'foodSummaries',
            'foodRevenue'
        ));
    }
}
