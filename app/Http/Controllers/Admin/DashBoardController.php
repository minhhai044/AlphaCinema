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
        $monthnow = Carbon::now()->month; // Tháng hiện tại
        $year = Carbon::now()->year; // Năm hiện tại

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

        // Lấy tất cả bản ghi tickets để tính số ghế và thống kê loại ghế
        $tickets = DB::table('tickets')
            ->whereMonth('created_at', $monthnow)
            ->whereYear('created_at', $year)
            ->select('ticket_seats')
            ->get();

        // Tính tổng số ghế (số vé bán ra) và thống kê loại ghế
        $ticketCount = 0;
        $seatTypes = []; // Mảng đếm số ghế theo type_seat_id
        foreach ($tickets as $ticket) {
            $seats = json_decode($ticket->ticket_seats, true);
            if (is_array($seats)) {
                $ticketCount += count($seats); // Tổng số ghế
                foreach ($seats as $seat) {
                    $typeId = $seat['type_seat_id'];
                    $seatTypes[$typeId] = ($seatTypes[$typeId] ?? 0) + 1; // Đếm theo type_seat_id
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
                $series[] = round($percentage, 1); // Làm tròn 1 chữ số thập phân
                $labels[] = $seatTypeNames[$typeId] ?? 'Loại ghế ' . $typeId;
            }
        } else {
            $series = [100];
            $labels = ['Không có dữ liệu'];
        }
        $seatSeries = json_encode($series);
        $seatLabels = json_encode($labels);

        // 1. Doanh thu tháng này (đã trừ discount)
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

        // 3. Số vé bán ra tháng này (dựa trên số bản ghi tickets, không phải số ghế)
        $ticketsThisMonth = DB::table('tickets')
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$thisMonthStart, $today])
            ->when($branch !== 'all', function ($q) use ($branch) {
                $q->where('cinema_id', $branch);
            })
            ->count();


        $revenueByMonth = [];
        $selectedYear = $request->input('year', Carbon::now()->year);
        $branch = $request->input('branch', 'all');

        for ($month = 1; $month <= 12; $month++) {
            $query = DB::table('tickets')
                ->where('status', 'confirmed')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $month);

            if ($branch !== 'all') {
                $query->where('cinema_id', $branch);
            }

            $monthlyRevenue = $query->sum(DB::raw('COALESCE(total_price, 0) - COALESCE(voucher_discount, 0) - COALESCE(point_discount, 0)'));
            $revenueByMonth[$month] = round($monthlyRevenue / 1000000, 2);
        }

        $revenueData = json_encode($revenueByMonth);

        if ($request->ajax()) {
            return response()->json([
                'revenueData' => json_decode($revenueData, true)
            ]);
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact(
            // ... các biến hiện có
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
        ));
    }
}
