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
public function combAndFoodRevenue(Request $request)
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

    // Truy vấn thống kê Combo
    $comboQuery = "
        SELECT 
            JSON_UNQUOTE(JSON_EXTRACT(tc.combo_item, '$.name')) AS item_name,
            'Combo' AS item_type,
            SUM(JSON_EXTRACT(tc.combo_item, '$.quantity')) AS total_quantity,
            SUM(JSON_EXTRACT(tc.combo_item, '$.price_sale') * JSON_EXTRACT(tc.combo_item, '$.quantity')) AS total_price,
            CONCAT(
                SUM(JSON_EXTRACT(tc.combo_item, '$.quantity')), ' lượt - ',
                FORMAT(SUM(JSON_EXTRACT(tc.combo_item, '$.price_sale') * JSON_EXTRACT(tc.combo_item, '$.quantity')), 0), ' VND'
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
        GROUP BY item_name
    ";

    // Truy vấn thống kê Đồ ăn
    $foodQuery = "
        SELECT 
            JSON_UNQUOTE(JSON_EXTRACT(tf.food_item, '$.name')) AS item_name,
            'Food' AS item_type,
            SUM(JSON_EXTRACT(tf.food_item, '$.quantity')) AS total_quantity,
            SUM(JSON_EXTRACT(tf.food_item, '$.price') * JSON_EXTRACT(tf.food_item, '$.quantity')) AS total_price,
            CONCAT(
                SUM(JSON_EXTRACT(tf.food_item, '$.quantity')), ' lượt - ',
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
        GROUP BY item_name
    ";

    // Kết hợp cả 2 truy vấn
    $fullQuery = "($comboQuery) UNION ALL ($foodQuery) ORDER BY item_type, total_quantity DESC";

    // Thực thi truy vấn
    $statistics = DB::select($fullQuery, [$startDate . ' 00:00:00', $endDate . ' 23:59:59', $startDate . ' 00:00:00', $endDate . ' 23:59:59']);

    // Tách dữ liệu cho Combo và Food
    $comboStatistics = [];
    $foodStatistics = [];
    foreach ($statistics as $stat) {
        if ($stat->item_type === 'Combo') {
            $comboStatistics[] = [
                'name' => $stat->item_name,
                'total_quantity' => $stat->total_quantity,
                'total_price' => $stat->total_price,
                'summary' => $stat->summary,
            ];
        } else {
            $foodStatistics[] = [
                'name' => $stat->item_name,
                'total_quantity' => $stat->total_quantity,
                'total_price' => $stat->total_price,
                'summary' => $stat->summary,
            ];
        }
    }

    $comboQuantities = array_column($comboStatistics, 'total_quantity'); // Dùng số lượng thay vì doanh thu
    $comboNames = array_column($comboStatistics, 'name');
    $comboSummaries = array_column($comboStatistics, 'summary');
    $comboRevenue = array_column($comboStatistics, 'total_price'); // Giữ lại để tính tổng

    $foodQuantities = array_column($foodStatistics, 'total_quantity'); // Dùng số lượng thay vì doanh thu
    $foodNames = array_column($foodStatistics, 'name');
    $foodSummaries = array_column($foodStatistics, 'summary');
    $foodRevenue = array_column($foodStatistics, 'total_price'); // Giữ lại để tính tổng

    // dd($foodSummaries);
    // Trả về view  
    return view('admin.statistical.ComboAndFoodStatistial', compact(
       'branches',
        'branchId',
        'cinemaId',
        'startDate',
        'endDate',
        'comboStatistics',
        'foodStatistics',
        'comboQuantities',
        'comboNames',
        'comboSummaries',
        'comboRevenue',
        'foodQuantities',
        'foodNames',
        'foodSummaries',
        'foodRevenue'
    ));
}
}
