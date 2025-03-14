<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // Validation input
        $branch = $request->input('branch', 'all');
        $selectedMonth = $request->input('month', Carbon::now()->month) ?: Carbon::now()->month;
        $selectedYear = $request->input('year', Carbon::now()->year) ?: Carbon::now()->year;

        // Validate month/year
        $selectedMonth = max(1, min(12, (int) $selectedMonth));
        $selectedYear = max(2020, min(Carbon::now()->year, (int) $selectedYear));

        // Date handling
        $today = Carbon::today();
        $thisMonthStart = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $lastMonthStart = $thisMonthStart->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $thisMonthStart->copy()->subMonth()->endOfMonth();

        // Lấy danh sách rạp (cacheable nếu ít thay đổi)
        $cinemas = DB::table('tickets')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->select('cinemas.id', 'cinemas.name')
            ->distinct()
            ->get();

        // Tổng doanh thu
        $totalRevenue = $this->getTotalRevenue($selectedMonth, $selectedYear, $branch);
        $formattedRevenue = number_format($totalRevenue, 0, ',', '.');

        // Thống kê vé và loại ghế
        [$ticketCount, $seatSeries, $seatLabels] = $this->getTicketStats($selectedMonth, $selectedYear, $branch);

        // Doanh thu tháng này
        $revenueThisMonth = $this->getRevenue($thisMonthStart, $today, $branch);

        // Doanh thu tháng trước
        $revenueLastMonth = $this->getRevenue($lastMonthStart, $lastMonthEnd, $branch);
        $revenueChange = $revenueLastMonth > 0
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100)
            : 0;

        // Số vé bán ra tháng này
        $ticketsThisMonth = $this->getTicketCount($thisMonthStart, $today, $branch);

        // Doanh thu theo năm
        $revenueDataJson = $this->getYearlyRevenue($selectedYear, $branch, $selectedMonth);

        // Doanh thu theo rạp
        [$revenueSeriesJson, $cinemaLabelsJson] = $this->getRevenueByCinema($selectedMonth, $selectedYear);

        return view(self::PATH_VIEW . __FUNCTION__, compact(
            'revenueThisMonth',
            'revenueChange',
            'ticketsThisMonth',
            'selectedYear',
            'branch',
            'cinemas',
            'totalRevenue',
            'formattedRevenue',
            'selectedMonth',
            'ticketCount',
            'seatSeries',
            'seatLabels',
            'revenueDataJson',
            'revenueSeriesJson',
            'cinemaLabelsJson'
        ));
    }

    private function getTotalRevenue($month, $year, $branch): float
    {
        return DB::table('tickets')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->when($branch !== 'all', fn($q) => $q->where('cinema_id', $branch))
            ->sum('total_price') ?: 0;
    }

    private function getTicketStats($month, $year, $branch): array
    {
        $tickets = DB::table('tickets')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->when($branch !== 'all', fn($q) => $q->where('cinema_id', $branch))
            ->select('ticket_seats')
            ->get();

        $ticketCount = 0;
        $seatTypes = [];
        foreach ($tickets as $ticket) {
            $seats = json_decode($ticket->ticket_seats, true);
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

    private function getRevenue($startDate, $endDate, $branch): float
    {
        return DB::table('tickets')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($branch !== 'all', fn($q) => $q->where('cinema_id', $branch))
            ->sum(DB::raw('COALESCE(total_price, 0) ')) ?: 0;
    }

    private function getTicketCount($startDate, $endDate, $branch): int
    {
        return DB::table('tickets')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($branch !== 'all', fn($q) => $q->where('cinema_id', $branch))
            ->count();
    }

    private function getYearlyRevenue($year, $branch, $month): string
    {
        $monthlyRevenues = [];
        $query = DB::table('tickets')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->when($branch !== 'all', fn($q) => $q->where('cinema_id', $branch));
        $revenue = $query->sum(DB::raw('COALESCE(total_price, 0)')) ?: 0;
        $monthlyRevenues[] = round($revenue / 1000000, 2);

        return json_encode([$year => $monthlyRevenues]);
    }

    private function getRevenueByCinema($month, $year): array
    {
        $revenueByCinema = DB::table('tickets')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->whereMonth('tickets.created_at', $month)
            ->whereYear('tickets.created_at', $year)
            ->select(
                'cinemas.name as cinema_name',
                DB::raw('SUM(COALESCE(tickets.total_price, 0)) as revenue')
            )
            ->groupBy('cinemas.id', 'cinemas.name')
            ->orderBy('revenue', 'desc')
            ->get();

        $revenueSeries = $revenueByCinema->pluck('revenue')->map(fn($value) => round((float) $value / 1000000, 2))->toArray();
        $cinemaLabels = $revenueByCinema->pluck('cinema_name')->toArray();

        return [json_encode($revenueSeries), json_encode($cinemaLabels)];
    }
}
