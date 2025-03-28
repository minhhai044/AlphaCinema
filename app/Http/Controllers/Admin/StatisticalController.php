<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Ticket;
use App\Models\Movie;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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


    private function applyPermission($query, $user, $branchId = null, $cinemaId = null)
    {
        if ($user->hasRole('System Admin')) {
            $query->when($branchId, fn($q) => $q->where('branches.id', $branchId))
                ->when($cinemaId, fn($q) => $q->where('cinemas.id', $cinemaId));
        } elseif ($user->branch_id) {
            $query->where('branches.id', $user->branch_id)
                ->when($cinemaId, fn($q) => $q->where('cinemas.id', $cinemaId));
        } elseif ($user->cinema_id) {
            $query->where('cinemas.id', $user->cinema_id);
        } else {
            $query->where('tickets.id', 0); // Không có quyền
        }
        return $query;
    }

    public function getCinemasByBranch(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!$branchId) {
            return response()->json([]);
        }

        try {
            $cinemas = DB::table('cinemas')
                ->select('id', 'name')
                ->where('branch_id', $branchId)
                ->where('is_active', 1)
                ->get();

            if ($cinemas->isEmpty()) {
                \Log::info("No cinemas found for branch_id: {$branchId}");
            } else {
                \Log::info("Cinemas found for branch_id: {$branchId}", $cinemas->toArray());
            }

            return response()->json($cinemas);
        } catch (\Exception $e) {
            \Log::error('Error in getCinemasByBranch: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function comboRevenue(Request $request)
    {
        $user = Auth::user();

        // Validation input
        $validated = $request->validate([
            'branch_id' => 'nullable|integer|exists:branches,id',
            'cinema_id' => 'nullable|integer|exists:cinemas,id',
            'date' => 'nullable|date',
            'movie_id' => 'nullable|integer|exists:movies,id',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:' . Carbon::now()->year,
        ]);

        // Lấy input với giá trị mặc định
        $branchId = $validated['branch_id'] ?? $user->branch_id ?? null;
        $cinemaId = $validated['cinema_id'] ?? $user->cinema_id ?? null;
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : null;
        $movieId = $validated['movie_id'] ?? null;
        $selectedMonth = $validated['month'] ?? Carbon::now()->month;
        $selectedYear = $validated['year'] ?? Carbon::now()->year;

        // Xử lý ngày tháng
        $startDate = $date ? $date->startOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $date ? $date->endOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Phân quyền
        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchId = $user->branch_id; // Khóa branch_id cho User Chi Nhánh
                // Kiểm tra cinema_id thuộc branch của user
                if ($cinemaId && !Cinema::where('id', $cinemaId)->where('branch_id', $user->branch_id)->exists()) {
                    $cinemaId = null; // Reset nếu cinema không hợp lệ
                }
            } elseif ($user->cinema_id) {
                $cinemaId = $user->cinema_id;
                $branchId = Cinema::where('id', $user->cinema_id)->value('branch_id');
            }
        }

        // Lấy danh sách chi nhánh, rạp, và phim
        $branchesQuery = Branch::query()->select('id', 'name')->where('is_active', 1);
        $cinemasQuery = Cinema::query()->select('id', 'name')->where('is_active', 1);

        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchesQuery->where('id', $user->branch_id);
                $cinemasQuery->where('branch_id', $user->branch_id);
            } elseif ($user->cinema_id) {
                $cinemasQuery->where('id', $user->cinema_id);
                $branchesQuery->whereIn('id', Cinema::where('id', $user->cinema_id)->pluck('branch_id'));
            } else {
                $branchesQuery->where('id', 0);
                $cinemasQuery->where('id', 0);
            }
        }

        $branches = $branchesQuery->get();
        $cinemas = $cinemasQuery->get();
        $movies = Movie::select('id', 'name')->where('is_active', 1)->get();

        // Quan hệ chi nhánh - rạp
        $branchesRelationQuery = Cinema::query()->select('branch_id', 'id', 'name')->where('is_active', 1);
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

        // Truy vấn cơ bản cho tickets
        $ticketQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate]);

        if ($branchId)
            $ticketQuery->where('cinemas.branch_id', $branchId);
        if ($cinemaId)
            $ticketQuery->where('tickets.cinema_id', $cinemaId);
        if ($movieId)
            $ticketQuery->where('tickets.movie_id', $movieId);

        // Thống kê combo
        $comboStatistics = DB::select("
            SELECT
                JSON_UNQUOTE(JSON_EXTRACT(tc.combo_item, '$.name')) AS combo_name,
                CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED) AS total_quantity,
                SUM(CAST(JSON_EXTRACT(tc.combo_item, '$.price_sale') AS DECIMAL(15,2)) * JSON_EXTRACT(tc.combo_item, '$.quantity')) AS total_price,
                CONCAT(
                    CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED), ' lượt - ',
                    FORMAT(SUM(CAST(JSON_EXTRACT(tc.combo_item, '$.price_sale') AS DECIMAL(15,2)) * JSON_EXTRACT(tc.combo_item, '$.quantity')), 0), ' VND'
                ) AS summary,
                MAX(JSON_UNQUOTE(JSON_EXTRACT(tc.combo_item, '$.img_thumbnail'))) AS img_thumbnail
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
            AND (? IS NULL OR cinemas.branch_id = ?)
            AND (? IS NULL OR tickets.cinema_id = ?)
            AND (? IS NULL OR tickets.movie_id = ?)
            GROUP BY combo_name
            ORDER BY total_price DESC
        ", [$startDate, $endDate, $branchId, $branchId, $cinemaId, $cinemaId, $movieId, $movieId]);

        $comboNames = array_column($comboStatistics, 'combo_name');
        $comboQuantities = array_column($comboStatistics, 'total_quantity');
        $comboRevenues = array_column($comboStatistics, 'total_price');
        $comboSummaries = array_column($comboStatistics, 'summary');

        // Top 6 combo doanh thu cao nhất
        $top6Combos = array_slice($comboStatistics, 0, 3);

        // Tỷ lệ đơn hàng có combo
        $ticketStats = $ticketQuery->selectRaw("
            COUNT(*) as total_tickets,
            SUM(CASE WHEN ticket_combos IS NOT NULL THEN 1 ELSE 0 END) as combo_tickets
        ")->first();

        $comboUsage = $ticketStats->total_tickets > 0
            ? round(($ticketStats->combo_tickets / $ticketStats->total_tickets) * 100, 2)
            : 0;

        // Doanh thu combo theo khung giờ
        $timeFrames = DB::select("
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
            " . ($branchId ? "AND cinemas.branch_id = ?" : "") . "
            " . ($cinemaId ? "AND tickets.cinema_id = ?" : "") . "
            " . ($movieId ? "AND tickets.movie_id = ?" : "") . "
            GROUP BY time_frame
            ORDER BY time_frame ASC
        ", array_filter([$startDate, $endDate, $branchId, $cinemaId, $movieId]));

        $trendDates = array_column($timeFrames, 'time_frame');
        $trendRevenues = array_column($timeFrames, 'revenue');

        // Tổng doanh thu combo
        $comboRevenue = array_sum($comboRevenues);

        // Debug log
        Log::debug("Branch ID: $branchId, Cinema ID: $cinemaId, User Branch: {$user->branch_id}");

        // Trả về view
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
            'comboStatistics',
            'top6Combos'
        ));
    }

    public function foodRevenue(Request $request)
    {
        $user = Auth::user();

        // Validation input (giống comboRevenue)
        $validated = $request->validate([
            'branch_id' => 'nullable|integer|exists:branches,id',
            'cinema_id' => 'nullable|integer|exists:cinemas,id',
            'date' => 'nullable|date',
            'movie_id' => 'nullable|integer|exists:movies,id',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:' . Carbon::now()->year,
        ]);

        // Lấy input với giá trị mặc định (giống comboRevenue)
        $branchId = $validated['branch_id'] ?? $user->branch_id ?? null;
        $cinemaId = $validated['cinema_id'] ?? $user->cinema_id ?? null;
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : null;
        $movieId = $validated['movie_id'] ?? null;
        $selectedMonth = $validated['month'] ?? Carbon::now()->month;
        $selectedYear = $validated['year'] ?? Carbon::now()->year;

        // Xử lý ngày tháng (giống comboRevenue)
        $startDate = $date ? $date->startOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $date ? $date->endOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Phân quyền (giống comboRevenue)
        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchId = $user->branch_id;
                if ($cinemaId && !Cinema::where('id', $cinemaId)->where('branch_id', $user->branch_id)->exists()) {
                    $cinemaId = null;
                }
            } elseif ($user->cinema_id) {
                $cinemaId = $user->cinema_id;
                $branchId = Cinema::where('id', $user->cinema_id)->value('branch_id');
            }
        }

        // Lấy danh sách chi nhánh, rạp, và phim (giống comboRevenue)
        $branchesQuery = Branch::query()->select('id', 'name')->where('is_active', 1);
        $cinemasQuery = Cinema::query()->select('id', 'name')->where('is_active', 1);

        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $branchesQuery->where('id', $user->branch_id);
                $cinemasQuery->where('branch_id', $user->branch_id);
            } elseif ($user->cinema_id) {
                $cinemasQuery->where('id', $user->cinema_id);
                $branchesQuery->whereIn('id', Cinema::where('id', $user->cinema_id)->pluck('branch_id'));
            } else {
                $branchesQuery->where('id', 0);
                $cinemasQuery->where('id', 0);
            }
        }

        $branches = $branchesQuery->get();
        $cinemas = $cinemasQuery->get();
        $movies = Movie::select('id', 'name')->where('is_active', 1)->get();

        // Quan hệ chi nhánh - rạp (giống comboRevenue)
        $branchesRelationQuery = Cinema::query()->select('branch_id', 'id', 'name')->where('is_active', 1);
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

        // Truy vấn cơ bản cho tickets (giống comboRevenue)
        $ticketQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate]);

        if ($branchId)
            $ticketQuery->where('cinemas.branch_id', $branchId);
        if ($cinemaId)
            $ticketQuery->where('tickets.cinema_id', $cinemaId);
        if ($movieId)
            $ticketQuery->where('tickets.movie_id', $movieId);

        // Thống kê food (tương tự combo nhưng dùng ticket_foods)
        $foodStatistics = DB::select("
            SELECT
                JSON_UNQUOTE(JSON_EXTRACT(tf.food_item, '$.name')) AS food_name,
                CAST(FLOOR(SUM(JSON_EXTRACT(tf.food_item, '$.quantity'))) AS UNSIGNED) AS total_quantity,
                SUM(CAST(JSON_EXTRACT(tf.food_item, '$.price') AS DECIMAL(15,2)) * JSON_EXTRACT(tf.food_item, '$.quantity')) AS total_price,
                CONCAT(
                    CAST(FLOOR(SUM(JSON_EXTRACT(tf.food_item, '$.quantity'))) AS UNSIGNED), ' lượt - ',
                    FORMAT(SUM(CAST(JSON_EXTRACT(tf.food_item, '$.price') AS DECIMAL(15,2)) * JSON_EXTRACT(tf.food_item, '$.quantity')), 0), ' VND'
                ) AS summary,
                MAX(JSON_UNQUOTE(JSON_EXTRACT(tf.food_item, '$.img_thumbnail'))) AS img_thumbnail
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            JOIN JSON_TABLE(
                ticket_foods,
                '$[*]' COLUMNS (
                    food_item JSON PATH '$'
                )
            ) AS tf ON 1=1
            WHERE ticket_foods IS NOT NULL
            AND tickets.created_at BETWEEN ? AND ?
            AND (? IS NULL OR cinemas.branch_id = ?)
            AND (? IS NULL OR tickets.cinema_id = ?)
            AND (? IS NULL OR tickets.movie_id = ?)
            GROUP BY food_name
            ORDER BY total_price DESC
        ", [$startDate, $endDate, $branchId, $branchId, $cinemaId, $cinemaId, $movieId, $movieId]);

        $foodNames = array_column($foodStatistics, 'food_name');
        $foodQuantities = array_column($foodStatistics, 'total_quantity');
        $foodRevenues = array_column($foodStatistics, 'total_price');
        $foodSummaries = array_column($foodStatistics, 'summary');

        // Top 6 food doanh thu cao nhất
        $top6Foods = array_slice($foodStatistics, 0, 3);

        // Tỷ lệ đơn hàng có food
        $ticketStats = $ticketQuery->selectRaw("
            COUNT(*) as total_tickets,
            SUM(CASE WHEN ticket_foods IS NOT NULL THEN 1 ELSE 0 END) as food_tickets
        ")->first();

        $foodUsage = $ticketStats->total_tickets > 0
            ? round(($ticketStats->food_tickets / $ticketStats->total_tickets) * 100, 2)
            : 0;

        // Doanh thu food theo khung giờ
        $timeFrames = DB::select("
            SELECT
                DATE_FORMAT(showtimes.start_time, '%H:%i') AS time_frame,
                SUM(CAST(JSON_EXTRACT(tf.food_item, '$.price') AS DECIMAL(15,2)) * JSON_EXTRACT(tf.food_item, '$.quantity')) AS revenue
            FROM tickets
            JOIN showtimes ON tickets.showtime_id = showtimes.id
            JOIN cinemas ON tickets.cinema_id = cinemas.id
            JOIN branches ON cinemas.branch_id = branches.id
            JOIN JSON_TABLE(
                ticket_foods,
                '$[*]' COLUMNS (
                    food_item JSON PATH '$'
                )
            ) AS tf ON 1=1
            WHERE ticket_foods IS NOT NULL
            AND tickets.created_at BETWEEN ? AND ?
            " . ($branchId ? "AND cinemas.branch_id = ?" : "") . "
            " . ($cinemaId ? "AND tickets.cinema_id = ?" : "") . "
            " . ($movieId ? "AND tickets.movie_id = ?" : "") . "
            GROUP BY time_frame
            ORDER BY time_frame ASC
        ", array_filter([$startDate, $endDate, $branchId, $cinemaId, $movieId]));

        $trendDates = array_column($timeFrames, 'time_frame');
        $trendRevenues = array_column($timeFrames, 'revenue');

        // Tổng doanh thu food
        $foodRevenue = array_sum($foodRevenues);

        // Debug log
        Log::debug("Branch ID: $branchId, Cinema ID: $cinemaId, User Branch: {$user->branch_id}");

        // Trả về view
        return view('admin.statistical.FoodStatistical', compact(
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
            'foodRevenue',
            'foodNames',
            'foodQuantities',
            'foodRevenues',
            'trendDates',
            'trendRevenues',
            'foodUsage',
            'foodSummaries',
            'foodStatistics',
            'top6Foods'
        ));
    }
}
