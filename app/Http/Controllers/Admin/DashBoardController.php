<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashBoardController extends Controller
{
    private const PATH_VIEW = 'admin.';

    public function index(Request $request)
    {
        $branch = $request->input('branch', 'all'); // Lọc theo chi nhánh
        $today = Carbon::today();
        $thisMonthStart = $today->startOfMonth();
        $lastMonthStart = $today->subMonth()->startOfMonth();
        $lastMonthEnd = $today->subMonth()->endOfMonth();
        $monthnow = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Lấy danh sách rạp
        $cinemas = DB::table('tickets')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->select('cinemas.id', 'cinemas.name')
            ->distinct()
            ->get();

        // Tổng doanh thu theo tháng
        $totalRevenue = DB::table('tickets')
            ->whereMonth('created_at', $monthnow)
            ->whereYear('created_at', $year)
            ->sum('total_price');
        $totalRevenue = $totalRevenue !== null ? (float) $totalRevenue : 0;
        $formattedRevenue = number_format($totalRevenue, 0, ',', '.');

        $tickets = DB::table('tickets')
            ->whereMonth('created_at', $monthnow)
            ->whereYear('created_at', $year)
            ->select('ticket_seats')
            ->get();

        $ticketCount = 0;
        $seatTypes = [];
        foreach ($tickets as $ticket) {
            $seats = json_decode($ticket->ticket_seats, true);
            if (is_array($seats)) {
                $ticketCount += count($seats);
                foreach ($seats as $seat) {
                    $typeId = $seat['type_seat_id'];
                    $seatTypes[$typeId] = ($seatTypes[$typeId] ?? 0) + 1;
                }
            }
        }
        $ticketCount = $ticketCount !== null ? (int) $ticketCount : 0;

        // Tính tỷ lệ phần trăm cho biểu đồ
        $series = [];
        $labels = [];
        if ($ticketCount > 0) {
            $seatTypeNames = [
                1 => 'Ghế Thường',
                2 => 'Ghế Vip',
                3 => 'Ghế Đôi'
            ];
            foreach ($seatTypes as $typeId => $count) {
                $percentage = ($count / $ticketCount) * 100;
                $series[] = round($percentage, 1);
                $labels[] = $seatTypeNames[$typeId] ?? 'Loại ghế ' . $typeId;
            }
        } else {
            $series = [100];
            $labels = ['Không có dữ liệu'];
        }
        $seatSeries = json_encode($series);
        $seatLabels = json_encode($labels);

        // 1. Doanh thu tháng này
        $query = DB::table('tickets')
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$thisMonthStart, $today]);
        if ($branch !== 'all') {
            $query->where('cinema_id', $branch);
        }
        $revenueThisMonth = $query->sum(DB::raw('COALESCE(total_price, 0) - COALESCE(voucher_discount, 0) - COALESCE(point_discount, 0)'));

        // 2. Doanh thu tháng trước
        $revenueLastMonth = DB::table('tickets')
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->when($branch !== 'all', function ($q) use ($branch) {
                $q->where('cinema_id', $branch);
            })
            ->sum(DB::raw('COALESCE(total_price, 0) - COALESCE(voucher_discount, 0) - COALESCE(point_discount, 0)'));

        $revenueChange = $revenueLastMonth > 0
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100)
            : 0;

        //Số vé bán ra tháng này
        $ticketsThisMonth = DB::table('tickets')
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$thisMonthStart, $today])
            ->when($branch !== 'all', function ($q) use ($branch) {
                $q->where('cinema_id', $branch);
            })
            ->count();


        //thống kê biểu đồ

        $years = [2024, 2025];
        $revenueData = [];

        foreach ($years as $selectedYear) {
            $monthlyRevenues = [];
            for ($month = 1; $month <= 12; $month++) {
                $query = DB::table('tickets')
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $month);

                if ($branch !== 'all') {
                    $query->where('cinema_id', $branch);
                }

                $monthlyRevenue = $query->sum(DB::raw('COALESCE(total_price, 0) '));
                $monthlyRevenues[] = round($monthlyRevenue / 1000000, 2);
            }
            $revenueData[$selectedYear] = $monthlyRevenues;
        }

        $revenueDataJson = json_encode($revenueData);
        $selectedYear = $year;


        return view(self::PATH_VIEW . __FUNCTION__, compact(
            'revenueThisMonth',
            'revenueChange',
            'ticketsThisMonth',
            'year',
            'branch',
            'cinemas',
            'totalRevenue',
            'formattedRevenue',
            'monthnow',
            'ticketCount',
            'seatSeries',
            'seatLabels',
            'revenueData',
            'selectedYear',
            'revenueDataJson'
        ));
    }
}
