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
        $user = Auth::user();

        // Validation input
        $branchId = $request->input('branch_id', $user->branch_id ?? '');
        $cinemaId = $request->input('cinema_id', $user->cinema_id ?? '');
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $selectedMonth = $request->input('month', Carbon::now()->month) ?: Carbon::now()->month;
        $selectedYear = $request->input('year', Carbon::now()->year) ?: Carbon::now()->year;

        // Validate month/year
        $selectedMonth = $selectedMonth ? max(1, min(12, (int) $selectedMonth)) : null;
        $selectedYear = max(2020, min(Carbon::now()->year, (int) $selectedYear));

        // Date handling
        $today = $date ? Carbon::parse($date) : Carbon::today();
        $todayStart = $today->startOfDay()->toDateTimeString();
        $todayEnd = $today->endOfDay()->toDateTimeString();
        $thisMonthStart = $selectedMonth ? Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth()->toDateTimeString() : Carbon::now()->startOfMonth()->toDateTimeString();
        $thisMonthEnd = $selectedMonth ? Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()->toDateTimeString() : Carbon::now()->endOfMonth()->toDateTimeString();

        // Phân quyền mặc định
        if (!$user->hasRole('System Admin')) {
            $branchId = $user->branch_id ?: null;
            $cinemaId = $user->cinema_id ?: null;
        }

        // Lấy danh sách chi nhánh, rạp, và phim
        $branchesQuery = DB::table('branches')->select('id', 'name')->where('is_active', 1);
        $cinemasQuery = DB::table('cinemas')->select('id', 'name')->where('is_active', 1);

        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchesQuery->where('id', $user->branch_id);
                $cinemasQuery->where('branch_id', $user->branch_id);
            } elseif ($user->cinema_id) {
                $cinemasQuery->where('id', $user->cinema_id);
                $branchesQuery->whereIn('id', DB::table('cinemas')->where('id', $user->cinema_id)->pluck('branch_id'));
            } else {
                $branchesQuery->where('id', 0);
                $cinemasQuery->where('id', 0);
            }
        }

        $branches = $branchesQuery->get();
        $cinemas = $cinemasQuery->get();
        $movies = DB::table('movies')->select('id', 'name')->where('is_active', 1)->get();

        // Quan hệ chi nhánh - rạp
        $branchesRelationQuery = DB::table('cinemas')
            ->select('branch_id', 'id', 'name')
            ->where('is_active', 1);

        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchesRelationQuery->where('branch_id', $user->branch_id);
            } elseif ($user->cinema_id) {
                $branchesRelationQuery->where('id', $user->cinema_id);
            } else {
                $branchesRelationQuery->where('id', 0);
            }
        }

        $branchesRelation = $branchesRelationQuery->get()
            ->groupBy('branch_id')
            ->map(fn($group) => $group->pluck('name', 'id')->toArray())
            ->toArray();

        // Điều kiện lọc chi nhánh/rạp
        $whereClause = '';
        if ($branchId) {
            $whereClause .= " AND cinemas.branch_id = " . (int)$branchId;
        }
        if ($cinemaId) {
            $whereClause .= " AND tickets.cinema_id = " . (int)$cinemaId;
        }
        if ($movieId) {
            $whereClause .= " AND tickets.movie_id = " . (int)$movieId;
        }

        // Truy vấn thống kê combo
        $comboQuery = "
            SELECT
                JSON_UNQUOTE(JSON_EXTRACT(tc.combo_item, '$.name')) AS combo_name,
                CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED) AS total_quantity,
                SUM(JSON_EXTRACT(tc.combo_item, '$.price_sale') * JSON_EXTRACT(tc.combo_item, '$.quantity')) AS total_price,
                CONCAT(
                    CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED), ' lượt - ',
                    FORMAT(SUM(JSON_EXTRACT(tc.combo_item, '$.price_sale') * JSON_EXTRACT(tc.combo_item, '$.quantity')), 0), ' VND'
                ) AS summary
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            JOIN JSON_TABLE(
                ticket_combos,
                '$[*]' COLUMNS (
                    combo_item JSON PATH '$'
                )
            ) AS tc ON 1=1
            WHERE ticket_combos IS NOT NULL
            AND tickets.created_at BETWEEN ? AND ?
            $whereClause
            GROUP BY combo_name
            ORDER BY total_price DESC
        ";

        $startDate = $date ? $todayStart : $thisMonthStart;
        $endDate = $date ? $todayEnd : $thisMonthEnd;
        $comboStatistics = DB::select($comboQuery, [$startDate, $endDate]);

        // Chuẩn bị dữ liệu cho biểu đồ
        $comboNames = array_column($comboStatistics, 'combo_name');
        $comboQuantities = array_column($comboStatistics, 'total_quantity');
        $comboRevenues = array_column($comboStatistics, 'total_price');
        $comboSummaries = array_column($comboStatistics, 'summary');

        // Tỷ lệ đơn hàng có combo
        $totalTicketsQuery = "
            SELECT COUNT(*) as total
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            WHERE tickets.created_at BETWEEN ? AND ?
            $whereClause
        ";
        $totalTicketsResult = DB::selectOne($totalTicketsQuery, [$startDate, $endDate]);
        $totalTickets = $totalTicketsResult->total;

        $comboTicketsQuery = "
            SELECT COUNT(*) as total
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            WHERE ticket_combos IS NOT NULL
            AND tickets.created_at BETWEEN ? AND ?
            $whereClause
        ";
        $comboTicketsResult = DB::selectOne($comboTicketsQuery, [$startDate, $endDate]);
        $comboTickets = $comboTicketsResult->total;

        $comboUsage = $totalTickets > 0 ? round(($comboTickets / $totalTickets) * 100, 2) : 0;

        // Doanh thu combo theo khung giờ
        $timeFrameQuery = "
            SELECT
                DATE_FORMAT(showtimes.start_time, '%H:%i') AS time_frame,
                SUM(JSON_EXTRACT(tc.combo_item, '$.price_sale') * JSON_EXTRACT(tc.combo_item, '$.quantity')) AS revenue
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            JOIN JSON_TABLE(
                ticket_combos,
                '$[*]' COLUMNS (
                    combo_item JSON PATH '$'
                )
            ) AS tc ON 1=1
            WHERE ticket_combos IS NOT NULL
            AND tickets.created_at BETWEEN ? AND ?
            $whereClause
            GROUP BY time_frame
            ORDER BY time_frame ASC
        ";
        $timeFrames = DB::select($timeFrameQuery, [$startDate, $endDate]);
        $trendDates = array_column($timeFrames, 'time_frame');
        $trendRevenues = array_column($timeFrames, 'revenue');

        // Tổng doanh thu combo
        $comboRevenue = array_sum($comboRevenues);

        // Trả về view với dữ liệu
        return view('admin.statistical.ComboStatistical', compact(
            'branches',
            'cinemas',
            'branchesRelation',
            'movies',
            'movieId',
            'selectedMonth',
            'selectedYear',
            'branchId',
            'cinemaId',
            'date',
            'comboRevenue',
            'comboNames',
            'comboQuantities',
            'comboRevenues',
            'trendDates',
            'trendRevenues',
            'comboUsage',
            'comboSummaries',
            'comboStatistics'
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
            $conditions[] = "cinema_id = " . (int) $cinemaId;
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
