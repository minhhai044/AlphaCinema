<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class DashBoardController extends Controller
{
    private const PATH_VIEW = 'admin.';
    private const SEAT_TYPES = [
        1 => 'Ghế Thường',
        2 => 'Ghế Vip',
        3 => 'Ghế Đôi',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        // Validation input
        $branchId = $request->input('branch_id', $user->branch_id ?? '');
        $cinemaId = $request->input('cinema_id', $user->cinema_id ?? '');
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $statusId = $request->input('status_id');
        $selectedMonth = $request->input('month', Carbon::now()->month) ?: Carbon::now()->month;
        $selectedYear = $request->input('year', Carbon::now()->year) ?: Carbon::now()->year;

        // Validate month/year
        $selectedMonth = $selectedMonth ? max(1, min(12, (int) $selectedMonth)) : null;
        $selectedYear = max(2020, min(Carbon::now()->year, (int) $selectedYear));

        // Validate date
        $today = $date && Carbon::hasFormat($date, 'Y-m-d') ? Carbon::parse($date) : Carbon::today();
        $todayEnd = $today->copy()->endOfDay();
        $yesterday = $today->copy()->subDay()->startOfDay();
        $yesterdayEnd = $today->copy()->subDay()->endOfDay();
        $thisMonthStart = $selectedMonth ? Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth() : Carbon::now()->startOfMonth();
        $lastMonthStart = $thisMonthStart->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $thisMonthStart->copy()->subMonth()->endOfMonth();
        $thisMonthEnd = $thisMonthStart->copy()->endOfMonth();

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

        // Tổng doanh thu
        $totalRevenue = $this->getTotalRevenue($selectedMonth, $selectedYear, $branchId, $cinemaId, $movieId, $statusId);
        $formattedRevenue = number_format($totalRevenue, 0, ',', '.');

        // Thống kê vé và loại ghế
        [$ticketCount, $seatSeries, $seatLabels] = $this->getTicketStats($selectedMonth, $selectedYear, $branchId, $cinemaId, $movieId, $statusId);

        // Doanh thu hôm nay và hôm qua
        $revenueToday = $this->getRevenue($today, $todayEnd, $branchId, $cinemaId, $movieId, $statusId);
        $revenueYesterday = $this->getRevenue($yesterday, $yesterdayEnd, $branchId, $cinemaId, $movieId, $statusId);
        $revenueTodayChange = $revenueYesterday > 0
            ? (($revenueToday - $revenueYesterday) / $revenueYesterday * 100)
            : ($revenueToday > 0 ? 100 : 0); // Nếu hôm qua = 0, hôm nay > 0 thì tăng 100%
        $formattedRevenueToday = number_format($revenueToday, 0, ',', '.');

        // Debug log để kiểm tra
        Log::debug('Revenue Debug', [
            'today' => $revenueToday,
            'yesterday' => $revenueYesterday,
            'change' => $revenueTodayChange
        ]);

        // Số vé hôm nay và hôm qua
        $ticketsToday = $this->getTicketCountToday($today, $todayEnd, $branchId, $cinemaId, $movieId, $statusId);
        $ticketsYesterday = $this->getTicketCountToday($yesterday, $yesterdayEnd, $branchId, $cinemaId, $movieId, $statusId);
        $ticketsTodayChange = $ticketsYesterday > 0
            ? (($ticketsToday - $ticketsYesterday) / $ticketsYesterday * 100)
            : ($ticketsToday > 0 ? 100 : 0); // Nếu hôm qua = 0, hôm nay > 0 thì tăng 100%

        // Debug log để kiểm tra
        Log::debug('Tickets Debug', [
            'today' => $ticketsToday,
            'yesterday' => $ticketsYesterday,
            'change' => $ticketsTodayChange
        ]);

        // Doanh thu tháng này và tháng trước
        $revenueThisMonth = $this->getRevenue($thisMonthStart, $thisMonthEnd, $branchId, $cinemaId, $movieId, $statusId);
        $revenueLastMonth = $this->getRevenue($lastMonthStart, $lastMonthEnd, $branchId, $cinemaId, $movieId, $statusId);
        $revenueChange = $revenueLastMonth > 0
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // Số vé bán ra tháng này
        $ticketsThisMonth = $this->getTicketCount($thisMonthStart, $today, $branchId, $cinemaId, $movieId, $statusId);

        // Doanh thu theo năm
        $revenueDataJson = $this->getYearlyRevenue($selectedYear, $branchId, $cinemaId, $movieId, $statusId);

        // Doanh thu theo rạp
        [$revenueSeriesJson, $cinemaLabelsJson] = $this->getRevenueByCinema($selectedMonth, $selectedYear, $branchId, $cinemaId, $movieId, $statusId);


        // PHIM HOẠT ĐỘNG HIỆN TẠI
        $activeMoviesCount = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->when($cinemaId, fn($q) => $q->where('showtimes.cinema_id', $cinemaId))
            ->when($branchId, function ($q) {
                return $q->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
                    ->whereColumn('cinemas.id', 'showtimes.cinema_id');
            })
            ->when($date, fn($q) => $q->whereDate('showtimes.date', $date))
            ->when(
                !$date && $selectedMonth && $selectedYear,
                fn($q) =>
                $q->whereMonth('showtimes.date', $selectedMonth)
                    ->whereYear('showtimes.date', $selectedYear)
            )
            ->where('showtimes.is_active', 1)
            ->where('movies.is_active', 1)
            ->distinct()
            ->count('showtimes.movie_id');

        // THÁNG TRƯỚC
        $activeMoviesLastMonth = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->when($cinemaId, fn($q) => $q->where('showtimes.cinema_id', $cinemaId))
            ->when($branchId, function ($q) {
                return $q->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
                    ->whereColumn('cinemas.id', 'showtimes.cinema_id');
            })
            ->whereBetween('showtimes.date', [$lastMonthStart, $lastMonthEnd])
            ->where('showtimes.is_active', 1)
            ->where('movies.is_active', 1)
            ->distinct()
            ->count('showtimes.movie_id');

        // TÍNH % THAY ĐỔI
        $activeMoviesChange = $activeMoviesLastMonth > 0
            ? (($activeMoviesCount - $activeMoviesLastMonth) / $activeMoviesLastMonth * 100)
            : ($activeMoviesCount > 0 ? 100 : 0);

        // LOG DEBUG
        Log::debug('Active Movies Debug', [
            'current' => $activeMoviesCount,
            'last_month' => $activeMoviesLastMonth,
            'change' => $activeMoviesChange
        ]);
        $movieCount = DB::table('movies')->count();

        return view(self::PATH_VIEW . __FUNCTION__, compact(
            'branches',
            'cinemas',
            'branchesRelation',
            'movies',
            'movieCount',
            'revenueThisMonth',
            'revenueChange',
            'ticketsThisMonth',
            'selectedYear',
            'branchId',
            'cinemaId',
            'date',
            'movieId',
            'statusId',
            'totalRevenue',
            'formattedRevenue',
            'selectedMonth',
            'ticketCount',
            'seatSeries',
            'seatLabels',
            'revenueDataJson',
            'revenueSeriesJson',
            'cinemaLabelsJson',
            'revenueToday',
            'revenueTodayChange',
            'formattedRevenueToday',
            'ticketsToday',
            'ticketsTodayChange',
            'activeMoviesCount',
            'activeMoviesChange'
        ));
    }

    private function applyPermission($query, $branchId = null, $cinemaId = null)
    {
        $user = Auth::user();
        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $query->whereHas('branch', fn($q) => $q->where('branches.id', $user->branch_id));
            } elseif ($user->cinema_id) {
                $query->where('cinema_id', $user->cinema_id);
            } else {
                $query->where('id', 0); // Không có quyền
            }
        } else {
            $query->when($branchId, fn($q) => $q->whereHas('branch', fn($subQ) => $subQ->where('branches.id', $branchId)))
                ->when($cinemaId, fn($q) => $q->where('cinema_id', $cinemaId));
        }
        return $query;
    }

    private function getTotalRevenue($month, $year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): float
    {
        $query = Ticket::query()
            ->whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        return $query->sum('total_price') ?: 0;
    }

    private function getTicketStats($month, $year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): array
    {
        $query = Ticket::query()
            ->whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        $tickets = $query->select('ticket_seats')->get();

        $ticketCount = 0;
        $seatTypes = [];
        foreach ($tickets as $ticket) {
            $seats = is_array($ticket->ticket_seats) ? $ticket->ticket_seats : json_decode($ticket->ticket_seats, true);
            if (is_array($seats)) {
                $ticketCount += count($seats);
                foreach ($seats as $seat) {
                    $typeId = $seat['type_seat_id'] ?? 0;
                    $seatTypes[$typeId] = ($seatTypes[$typeId] ?? 0) + 1;
                }
            }
        }

        if ($ticketCount > 0) {
            $series = [];
            $labels = [];
            foreach ($seatTypes as $typeId => $count) {
                $series[] = round(($count / $ticketCount) * 100, 1);
                $labels[] = self::SEAT_TYPES[$typeId] ?? 'Loại ghế ' . $typeId;
            }
            return [$ticketCount, json_encode($series), json_encode($labels)];
        }

        return [0, json_encode([100]), json_encode(['Không có dữ liệu'])];
    }

    private function getRevenue($startDate, $endDate, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): float
    {
        $query = Ticket::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        return $query->sum('total_price') ?: 0;
    }

    private function getTicketCount($startDate, $endDate, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): int
    {
        $query = Ticket::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        $tickets = $query->select('ticket_seats')->get();

        $ticketCount = 0;
        foreach ($tickets as $ticket) {
            $seats = is_array($ticket->ticket_seats) ? $ticket->ticket_seats : json_decode($ticket->ticket_seats, true);
            if (is_array($seats)) {
                $ticketCount += count($seats);
            }
        }

        return $ticketCount;
    }

    private function getTicketCountToday($startDate, $endDate, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): int
    {
        return $this->getTicketCount($startDate, $endDate, $branchId, $cinemaId, $movieId, $statusId);
    }

    private function getYearlyRevenue($year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): string
    {
        $monthlyRevenues = [];
        for ($month = 1; $month <= 12; $month++) {
            $query = Ticket::query()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->when($movieId, function ($q) use ($movieId) {
                    $q->where('movie_id', $movieId);
                })
                ->when($statusId, function ($q) use ($statusId) {
                    $q->where('status', $statusId);
                });

            $this->applyPermission($query, $branchId, $cinemaId);
            $revenue = $query->sum('total_price') ?: 0;
            $monthlyRevenues[] = round($revenue / 1000000, 2);
        }

        return json_encode($monthlyRevenues);
    }

    private function getRevenueByCinema($month, $year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): array
    {
        $query = Ticket::query()
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->whereYear('tickets.created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('tickets.created_at', $month))
            ->when($movieId, fn($q) => $q->where('tickets.movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('tickets.status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        $revenueByCinema = $query->select(
            'cinemas.name as cinema_name',
            DB::raw('SUM(COALESCE(tickets.total_price, 0)) as revenue')
        )
            ->groupBy('cinemas.id', 'cinemas.name')
            ->orderBy('revenue', 'desc')
            ->get();

        $revenueSeries = $revenueByCinema->pluck('revenue')->map(fn($value) => (float) $value / 1000000)->toArray();
        $cinemaLabels = $revenueByCinema->pluck('cinema_name')->toArray();

        return [json_encode($revenueSeries), json_encode($cinemaLabels)];
    }

    private function getOccupancyRate($month, $year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): float
    {
        $query = Ticket::query()
            ->whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);
        $tickets = $query->select('ticket_seats')->get();

        $soldSeats = 0;
        foreach ($tickets as $ticket) {
            $seats = is_array($ticket->ticket_seats) ? $ticket->ticket_seats : json_decode($ticket->ticket_seats, true);
            if (is_array($seats)) {
                $soldSeats += count($seats);
            }
        }

        $totalSeats = 1000; // Cần thay bằng dữ liệu thực tế
        return $soldSeats > 0 ? round(($soldSeats / $totalSeats) * 100, 2) : 0;
    }

    private function getRevenueByShowtime($month, $year, $branchId = null, $cinemaId = null, $movieId = null, $statusId = null): array
    {
        $query = Ticket::query()
            ->whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->when($movieId, fn($q) => $q->where('movie_id', $movieId))
            ->when($statusId, fn($q) => $q->where('status', $statusId));

        $this->applyPermission($query, $branchId, $cinemaId);

        $morning = $query->clone()->whereBetween(DB::raw('HOUR(created_at)'), [6, 11])->sum('total_price');
        $afternoon = $query->clone()->whereBetween(DB::raw('HOUR(created_at)'), [12, 17])->sum('total_price');
        $evening = $query->clone()->whereBetween(DB::raw('HOUR(created_at)'), [18, 23])->sum('total_price');

        $series = [
            round((float) $morning / 1000000, 2),
            round((float) $afternoon / 1000000, 2),
            round((float) $evening / 1000000, 2),
        ];
        $labels = ['Sáng (6h-12h)', 'Chiều (12h-18h)', 'Tối (18h-24h)'];

        return [json_encode($series), json_encode($labels)];
    }
}
