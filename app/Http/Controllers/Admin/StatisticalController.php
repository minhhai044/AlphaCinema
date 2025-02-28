<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
}
