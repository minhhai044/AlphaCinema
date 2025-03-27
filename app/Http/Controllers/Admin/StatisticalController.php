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
        $user = Auth::user();

        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);
        $cinemas = array_reduce($branchesRelation ?? [], fn($carry, $branchCinemas) => array_merge($carry, array_values($branchCinemas)), []);

        $branchId = $user->hasRole('System Admin') ? $request->input('branch_id') : $user->branch_id;
        $cinemaId = $user->hasRole('System Admin') ? $request->input('cinema_id') : $user->cinema_id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $defaultStartDate = Carbon::now()->subDays(30);

        $revenueQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('SUM(tickets.total_price) as revenue'),
                DB::raw('SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) as ticket_count')
            )
            ->groupBy('movies.name');

        $showtimeQuery = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('COUNT(*) as showtime_count')
            )
            ->groupBy('movies.name');

        $filterClosure = function ($q) use ($startDate, $endDate, $defaultStartDate) {
            $q->when($startDate || $endDate, function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate && $startDate === $endDate) {
                    $q->whereDate('tickets.created_at', $startDate);
                } else {
                    $q->when($startDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->whereDate('tickets.created_at', '<=', $endDate));
                }
            }, fn($q) => $q->whereDate('tickets.created_at', '>=', $defaultStartDate));
        };

        $showtimeFilterClosure = function ($q) use ($startDate, $endDate, $defaultStartDate) {
            $q->when($startDate || $endDate, function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate && $startDate === $endDate) {
                    $q->where('showtimes.date', $startDate);
                } else {
                    $q->when($startDate, fn($q) => $q->where('showtimes.date', '>=', $startDate))
                        ->when($endDate, fn($q) => $q->where('showtimes.date', '<=', $endDate));
                }
            }, fn($q) => $q->where('showtimes.date', '>=', $defaultStartDate));
        };

        $revenueQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))->tap($filterClosure);
        $showtimeQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))->tap($showtimeFilterClosure);

        $revenues = $revenueQuery->orderBy('revenue', 'desc')->get()->toArray();
        $showtimes = $showtimeQuery->orderBy('movie_name')->get()->toArray();

        $message = null;
        if (empty($revenues) && empty($showtimes)) {
            if ($user->hasRole('System Admin')) {
                $message = 'Không có dữ liệu phù hợp với bộ lọc.';
            } elseif ($user->branch_id) {
                $message = 'Không có dữ liệu cho chi nhánh này.';
            } elseif ($user->cinema_id) {
                $message = 'Không có dữ liệu cho rạp này.';
            }
        }

        return view('admin.statistical.cinema_revenue', compact(
            'branches',
            'cinemas',
            'revenues',
            'showtimes',
            'message',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'movies'
        ));
    }

    private function applyPermission($query, $user, $branchId = null, $cinemaId = null)
    {
        if ($user->hasRole('System Admin')) {
            $query->when($branchId, fn($q) => $q->where('branches.id', $branchId))
                ->when($cinemaId, fn($q) => $q->where('cinemas.id', $cinemaId));
        } elseif ($user->branch_id) {
            $query->where('branches.id', $user->branch_id);
        } elseif ($user->cinema_id) {
            $query->where('cinemas.id', $user->cinema_id);
        } else {
            $query->where('tickets.id', 0);
        }
        return $query;
    }

    public function getCinemasByBranch(Request $request)
    {
        $branchId = $request->input('branch_id');
        $branchesRelation = $this->ticketService->getService($request)[2];

        $cinemas = $branchId && isset($branchesRelation[$branchId])
            ? $branchesRelation[$branchId]
            : [];

        return response()->json(array_map(fn($id, $name) => ['id' => $id, 'name' => $name], array_keys($cinemas), $cinemas));
    }
    public function comboRevenue(Request $request)
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

        // Truy vấn thống kê top 5 combo theo doanh thu
        $comboQuery = "
            SELECT
                JSON_UNQUOTE(JSON_EXTRACT(tc.combo_item, '$.name')) AS combo_name,
                CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED) AS total_quantity,
                SUM(JSON_EXTRACT(tc.combo_item, '$.price') * JSON_EXTRACT(tc.combo_item, '$.quantity')) AS total_price,
                CONCAT(
                    CAST(FLOOR(SUM(JSON_EXTRACT(tc.combo_item, '$.quantity'))) AS UNSIGNED), ' lượt - ',
                    FORMAT(SUM(JSON_EXTRACT(tc.combo_item, '$.price') * JSON_EXTRACT(tc.combo_item, '$.quantity')), 0), ' VND'
                ) AS summary
            FROM tickets
            JOIN JSON_TABLE(
                ticket_combos,
                '$[*]' COLUMNS (
                    combo_item JSON PATH '$'
                )
            ) AS tc ON 1=1
            WHERE ticket_combos IS NOT NULL
            AND created_at BETWEEN ? AND ?
            $whereClause
            GROUP BY combo_name
            ORDER BY total_price DESC
            LIMIT 5
        ";

        // Thực thi truy vấn
        $comboStatistics = DB::select($comboQuery, [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        // dd($comboStatistics);

        // Chuẩn bị dữ liệu cho view
        $comboQuantities = array_column($comboStatistics, 'total_quantity');
        $comboNames = array_column($comboStatistics, 'combo_name');
        $comboSummaries = array_column($comboStatistics, 'summary');
        $comboRevenue = array_column($comboStatistics, 'total_price');

        // Trả về view
        return view('admin.statistical.ComboStatistical', compact(
            'branches',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'comboStatistics',
            'comboQuantities',
            'comboNames',
            'comboSummaries',
            'comboRevenue'
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
