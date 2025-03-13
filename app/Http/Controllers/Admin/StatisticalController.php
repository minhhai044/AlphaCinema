<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{


    public function cinemaRevenue(Request $request)
    {

        $revenues = [
            ['date' => '2025-02-01', 'revenue' => 10000],
            ['date' => '2025-02-02', 'revenue' => 20000],
            ['date' => '2025-02-03', 'revenue' => 80000],
            ['date' => '2025-02-04', 'revenue' => 60000],
            ['date' => '2025-02-05', 'revenue' => 10000]
        ];

        return view('admin.statistical.cinema_revenue', compact('revenues'));
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
