<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;

use App\Models\Movie;
use App\Models\Ticket;
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
        $user = Auth::user();

        // Tái sử dụng bộ lọc từ TicketService
        [$tickets, $branches, $branchesRelation, $movies] = $this->ticketService->getService($request);

        // Xác định giá trị lọc dựa trên quyền
        $branchId = $request->input('branch_id', $user->branch_id ?? '');
        $cinemaId = $request->input('cinema_id', $user->cinema_id ?? '');
        $date = $request->input('date');
        $selectedMonth = $request->input('month', Carbon::now()->month) ?: Carbon::now()->month;
        $selectedYear = $request->input('year', Carbon::now()->year) ?: Carbon::now()->year;

        // Validate month/year
        $selectedMonth = $selectedMonth ? max(1, min(12, (int) $selectedMonth)) : null;
        $selectedYear = max(2020, min(Carbon::now()->year, (int) $selectedYear));

        // Date handling
        $today = $date ? Carbon::parse($date) : Carbon::today();

        // Phân quyền mặc định
        if (!$user->hasRole('System Admin')) {
            $branchId = $user->branch_id ?: null;
        }

        // Lấy danh sách chi nhánh và rạp
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

        // Truy vấn doanh thu và số vé theo phim
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

        // Truy vấn số lượng suất chiếu theo phim
        $showtimeQuery = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('COUNT(*) as showtime_count')
            )
            ->groupBy('movies.name');

        // Bộ lọc ngày, tháng, năm
        $filterClosure = function ($q) use ($date, $selectedMonth, $selectedYear, $today) {
            $q->when($date, function ($q) use ($date) {
                $q->whereDate('tickets.created_at', $date);
            }, function ($q) use ($selectedMonth, $selectedYear) {
                $q->when($selectedMonth && $selectedYear, function ($q) use ($selectedMonth, $selectedYear) {
                    $q->whereMonth('tickets.created_at', $selectedMonth)
                        ->whereYear('tickets.created_at', $selectedYear);
                }, fn($q) => $q->whereDate('tickets.created_at', $today));
            });
        };

        $showtimeFilterClosure = function ($q) use ($date, $selectedMonth, $selectedYear, $today) {
            $q->when($date, function ($q) use ($date) {
                $q->where('showtimes.date', $date);
            }, function ($q) use ($selectedMonth, $selectedYear) {
                $q->when($selectedMonth && $selectedYear, function ($q) use ($selectedMonth, $selectedYear) {
                    $q->whereMonth('showtimes.date', $selectedMonth)
                        ->whereYear('showtimes.date', $selectedYear);
                }, fn($q) => $q->where('showtimes.date', $today));
            });
        };

        // Áp dụng phân quyền và bộ lọc
        $revenueQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))->tap($filterClosure);
        $showtimeQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))->tap($showtimeFilterClosure);

        // Lấy dữ liệu
        $revenues = $revenueQuery->orderBy('revenue', 'desc')->get()->toArray();
        $showtimes = $showtimeQuery->orderBy('movie_name')->get()->toArray();

        // Thông báo
        $message = null;
        if (empty($revenues) && empty($showtimes)) {
            if ($user->hasRole('System Admin')) {
                $message = "Không có dữ liệu" .
                    ($date ? " cho ngày $date" :
                        ($selectedMonth && $selectedYear ? " cho tháng $selectedMonth năm $selectedYear" : " hôm nay"));
            } elseif ($user->branch_id) {
                $message = "Không có dữ liệu cho chi nhánh này" .
                    ($date ? " cho ngày $date" :
                        ($selectedMonth && $selectedYear ? " trong tháng $selectedMonth năm $selectedYear" : " hôm nay"));
            } elseif ($user->cinema_id) {
                $message = "Không có dữ liệu cho rạp này" .
                    ($date ? " cho ngày $date" :
                        ($selectedMonth && $selectedYear ? " trong tháng $selectedMonth năm $selectedYear" : " hôm nay"));
            }
        }

        $revenueQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select(
                'movies.name as movie_name',
                'movies.img_thumbnail', // Lấy hình ảnh từ bảng movies
                DB::raw('SUM(tickets.total_price) as revenue'),
                DB::raw('SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) as ticket_count')
            )
            ->groupBy('movies.name', 'movies.img_thumbnail'); // Nhóm theo name và img_thumbnail

        // Áp dụng phân quyền và bộ lọc
        $revenueQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))->tap($filterClosure);

        // Lấy top 6 phim
        $top6Movies = $revenueQuery->orderBy('revenue', 'desc')->limit(6)->get();

        $totalRevenue = array_sum(array_column($revenues, 'revenue'));


        // Truy vấn phim được xem lại nhiều nhất
        $rewatchQuery = Ticket::query()
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('COUNT(DISTINCT tickets.id) as rewatch_count'),
                DB::raw('COUNT(DISTINCT tickets.user_id) as unique_users'),
                DB::raw('SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) as ticket_count')
            )
            ->whereNotNull('tickets.user_id')
            ->groupBy('movies.name', 'movies.id')
            ->havingRaw('COUNT(DISTINCT tickets.user_id) > 0')
            ->orderBy('rewatch_count', 'desc');

        $rewatchQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))
            ->tap($filterClosure);

        $mostRewatchedMovies = $rewatchQuery->get();
        $mostRewatchedMovie = $mostRewatchedMovies->first();


        // Truy vấn tỷ lệ lấp đầy theo phim
        $fillRateQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->select(
                'movies.name as movie_name',
                DB::raw('SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) as seats_sold'),
                DB::raw('SUM(JSON_LENGTH(rooms.seat_structure)) as total_seats'),
                DB::raw('ROUND(
            (SUM(JSON_LENGTH(COALESCE(tickets.ticket_seats, "[]"))) /
            SUM(JSON_LENGTH(rooms.seat_structure))) * 100,
            2
        ) as fill_rate')
            )
            ->where('rooms.is_active', 1)
            ->groupBy('movies.name', 'movies.id')
            ->orderBy('fill_rate', 'desc');

        // Áp dụng phân quyền và bộ lọc thời gian
        $fillRateQuery->tap(fn($q) => $this->applyPermission($q, $user, $branchId, $cinemaId))
            ->tap($filterClosure);

        // Lấy dữ liệu tỷ lệ lấp đầy
        $fillRates = $fillRateQuery->get();

        return view('admin.statistical.cinema_revenue', compact(
            'mostRewatchedMovie',
            'fillRates',
            'mostRewatchedMovies',
            'branches',
            'cinemas',
            'top6Movies',
            'revenues',
            'showtimes',
            'message',
            'branchId',
            'cinemaId',
            'date',
            'selectedMonth',
            'selectedYear',
            'movies',
            'today',
            'branchesRelation',
            'totalRevenue'
        ));
    }









    public function ticketRevenueNew(Request $request)
    {
        $user = Auth::user();

        // Lấy danh sách chi nhánh và rạp
        $branchesQuery = DB::table('branches')->select('id', 'name')->where('is_active', 1);
        $cinemasQuery = DB::table('cinemas')->select('id', 'name', 'branch_id')->where('is_active', 1);

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

        // Lấy giá trị lọc từ request
        $branchId = $request->input('branch_id', $user->branch_id ?? '');
        $cinemaId = $request->input('cinema_id', $user->cinema_id ?? '');
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $selectedMonth = $request->input('month', Carbon::now()->month) ?: Carbon::now()->month;
        $selectedYear = $request->input('year', Carbon::now()->year) ?: Carbon::now()->year;

        // Validate month/year
        $selectedMonth = $selectedMonth ? max(1, min(12, (int) $selectedMonth)) : null;
        $selectedYear = max(2020, min(Carbon::now()->year, (int) $selectedYear));
        $today = $date ? Carbon::parse($date) : Carbon::today();

        // Lấy danh sách phim
        $movies = Movie::all();

        // Xử lý bộ lọc
        $ticketsQuery = Ticket::query()
            ->join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->join('branches', 'cinemas.branch_id', '=', 'branches.id')
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->select(
                'tickets.*',
                'showtimes.start_time',
                'movies.name as movie_name',
                'cinemas.name as cinema_name',
                'branches.name as branch_name',
                'rooms.type_room_id',
                'rooms.seat_structure'
            );

        // Áp dụng phân quyền
        if (!$user->hasRole('System Admin')) {
            if ($user->branch_id) {
                $ticketsQuery->where('branches.id', $user->branch_id);
            } elseif ($user->cinema_id) {
                $ticketsQuery->where('cinemas.id', $user->cinema_id);
            }
        }

        if ($branchId) {
            $ticketsQuery->where('branches.id', $branchId);
        }
        if ($cinemaId) {
            $ticketsQuery->where('cinemas.id', $cinemaId);
        }
        if ($movieId) {
            $ticketsQuery->where('tickets.movie_id', $movieId);
        }
        if ($date) {
            $ticketsQuery->whereDate('tickets.created_at', $date);
        } elseif ($selectedMonth && $selectedYear) {
            $ticketsQuery->whereMonth('tickets.created_at', $selectedMonth)
                ->whereYear('tickets.created_at', $selectedYear);
        } else {
            $ticketsQuery->whereDate('tickets.created_at', $today);
        }

        Log::info('Filter applied:', [
            'branch_id' => $branchId,
            'cinema_id' => $cinemaId,
            'date' => $date,
            'movie_id' => $movieId,
            'month' => $selectedMonth,
            'year' => $selectedYear
        ]);

        try {
            $tickets = $ticketsQuery->get();
            Log::info('Tickets count: ' . $tickets->count());

            $ticketTrendData = $this->getTicketTrendData($tickets, $date, $selectedMonth, $selectedYear);
            $topMoviesData = $this->getTopMoviesData($tickets);
            $ticketTypeData = $this->getTicketTypeData($tickets);
            $peakHoursData = $this->getPeakHoursData($tickets);
            $fillRateData = $this->getFillRateData($tickets);


            $noDataMessage = $tickets->isEmpty() ? 'Không có dữ liệu cho bộ lọc hiện tại.' : null;

            return view('admin.statistical.TicketStatistical', compact(
                'branches',
                'cinemas',
                'branchesRelation',
                'branchId',
                'cinemaId',
                'date',
                'movieId',
                'movies',
                'selectedMonth',
                'selectedYear',
                'tickets',
                'ticketTrendData',
                'topMoviesData',
                'ticketTypeData',
                'peakHoursData',
                'fillRateData',
                'noDataMessage'
            ));
        } catch (\Exception $e) {
            Log::error('Error in ticketRevenueNew: ' . $e->getMessage());
            $tickets = collect([]); // Trả về collection rỗng
            $ticketTrendData = ['categories' => [], 'values' => []];
            $topMoviesData = [];
            $ticketTypeData = [];
            $peakHoursData = ['categories' => [], 'values' => []];
            $fillRateData = ['categories' => [], 'seats_sold' => [], 'seats_empty' => []];
            $noDataMessage = 'Có lỗi xảy ra khi tải dữ liệu thống kê.';

            return view('admin.statistical.TicketStatistical', compact(
                'branches',
                'cinemas',
                'branchesRelation',
                'branchId',
                'cinemaId',
                'date',
                'movieId',
                'movies',
                'selectedMonth',
                'selectedYear',
                'tickets',
                'ticketTrendData',
                'topMoviesData',
                'ticketTypeData',
                'peakHoursData',
                'fillRateData',
                'noDataMessage'
            ));
        }
    }

    private function getTicketTrendData($tickets, $date, $selectedMonth, $selectedYear)
    {
        $data = ['categories' => [], 'values' => []];
        if ($date) {
            $ticketsByHour = $tickets->groupBy(function ($ticket) {
                return Carbon::parse($ticket->created_at)->format('H:00');
            })->map(function ($group) {
                return $group->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0);
            })->toArray();

            for ($i = 0; $i < 24; $i++) {
                $hour = sprintf('%02d:00', $i);
                $data['categories'][] = $hour;
                $data['values'][] = $ticketsByHour[$hour] ?? 0;
            }
        } else {
            $daysInMonth = Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            $ticketsByDay = $tickets->groupBy(function ($ticket) {
                return Carbon::parse($ticket->created_at)->format('d/m');
            })->map(function ($group) {
                return $group->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0);
            })->toArray();

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $day = sprintf('%02d/%02d', $i, $selectedMonth);
                $data['categories'][] = $day;
                $data['values'][] = $ticketsByDay[$day] ?? 0;
            }
        }
        return $data;
    }

    private function getTopMoviesData($tickets)
    {
        $data = $tickets->groupBy('movie_name')->map(function ($group) {
            return [
                'name' => $group->first()->movie_name,
                'y' => $group->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0)
            ];
        })->sortByDesc('y')->take(5)->values()->toArray();

        return $data ?: [];
    }

    private function getTicketTypeData($tickets)
    {
        $roomTypes = DB::table('type_rooms')->pluck('name', 'id')->toArray();

        $rooms = DB::table('rooms')
            ->select('id', 'type_room_id')
            ->whereIn('id', $tickets->pluck('room_id')->unique())
            ->pluck('type_room_id', 'id')
            ->toArray();

        $data = $tickets->groupBy('room_id')->map(function ($group) use ($rooms, $roomTypes) {
            $roomId = $group->first()->room_id;
            $typeRoomId = $rooms[$roomId] ?? 0;
            return [
                'name' => $roomTypes[$typeRoomId] ?? "Phòng không xác định ($typeRoomId)",
                'y' => $group->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0)
            ];
        })->groupBy('name')
            ->map(function ($group) {
                return [
                    'name' => $group->first()['name'],
                    'y' => $group->sum('y')
                ];
            })->values()->toArray();

        return $data ?: [];
    }

    private function getPeakHoursData($tickets)
    {
        $data = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('H:00');
        })->map(function ($group) {
            return $group->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0);
        })->sortByDesc(function ($value) {
            return $value;
        })->take(5)->toArray();

        return [
            'categories' => array_keys($data) ?: [],
            'values' => array_values($data) ?: []
        ];
    }

    private function getFillRateData($tickets)
    {
        $data = ['categories' => [], 'seats_sold' => [], 'seats_empty' => []];
        $ticketsByBranch = $tickets->groupBy('branch_name');

        foreach ($ticketsByBranch as $branchName => $branchTickets) {
            $seatsSold = $branchTickets->sum(fn($ticket) => is_array($ticket->ticket_seats) ? count($ticket->ticket_seats) : 0);
            $totalSeats = $branchTickets->map(fn($ticket) => is_array($ticket->seat_structure) ? count($ticket->seat_structure) : json_decode($ticket->seat_structure ?? '[]', true))
                ->flatten()
                ->count();

            $data['categories'][] = $branchName;
            $data['seats_sold'][] = $seatsSold;
            $data['seats_empty'][] = $totalSeats - $seatsSold;
        }

        return $data;
    }


















    // // Hàm lấy dữ liệu cho biểu đồ Top Phim Bán Chạy
    // private function getTopMoviesData($tickets)
    // {
    //     $movieData = [];
    //     foreach ($tickets as $ticket) {
    //         if (!isset($movieData[$ticket->movie->name])) {
    //             $movieData[$ticket->movie->name] = 0;
    //         }
    //         $movieData[$ticket->movie->name]++;
    //     }
    //     arsort($movieData);
    //     $topMovies = array_slice($movieData, 0, 5, true); // Lấy top 5
    //     $result = [];
    //     foreach ($topMovies as $name => $count) {
    //         $result[] = ['name' => $name, 'y' => $count];
    //     }
    //     return $result;
    // }



    // // Hàm lấy dữ liệu cho biểu đồ Giờ Cao Điểm
    // private function getPeakHoursData($tickets)
    // {
    //     $hourData = [];
    //     foreach ($tickets as $ticket) {
    //         $hour = Carbon::parse($ticket->showtime->time)->format('H:00');
    //         if (!isset($hourData[$hour])) {
    //             $hourData[$hour] = 0;
    //         }
    //         $hourData[$hour]++;
    //     }
    //     arsort($hourData);
    //     $topHours = array_slice($hourData, 0, 5, true); // Lấy top 5
    //     return [
    //         'categories' => array_keys($topHours),
    //         'data' => array_values($topHours),
    //     ];
    // }

    // // Hàm lấy dữ liệu cho biểu đồ Tỷ Lệ Lấp Đầy Rạp
    // private function getFillRateData($tickets)
    // {
    //     // Logic này cần thêm thông tin về tổng số ghế trong rạp từ bảng 'rooms'
    //     // Để đơn giản, tôi sẽ giả định có 100 ghế trong mỗi rạp
    //     $cinemaData = [];
    //     foreach ($tickets as $ticket) {
    //         if (!isset($cinemaData[$ticket->cinema->name])) {
    //             $cinemaData[$ticket->cinema->name] = ['booked' => 0, 'total' => 0];
    //         }

    //         $seats = [];
    //         if(is_string($ticket->ticket_seats)){
    //             $seats = json_decode($ticket->ticket_seats, true) ?? [];
    //         }

    //         $cinemaData[$ticket->cinema->name]['booked'] += count($seats);
    //         $cinemaData[$ticket->cinema->name]['total'] += 100; // Giả định 100 ghế
    //     }
    //     $categories = array_keys($cinemaData);
    //     $booked = array_map(fn($data) => $data['booked'], $cinemaData);
    //     $available = array_map(fn($data) => $data['total'] - $data['booked'], $cinemaData);
    //     return [
    //         'categories' => $categories,
    //         'booked' => $booked,
    //         'available' => $available,
    //     ];
    // }


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

        $branchId = $validated['branch_id'] ?? $user->branch_id ?? null;
        $cinemaId = $validated['cinema_id'] ?? $user->cinema_id ?? null;
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : null;
        $movieId = $validated['movie_id'] ?? null;
        $selectedMonth = $validated['month'] ?? Carbon::now()->month;
        $selectedYear = $validated['year'] ?? Carbon::now()->year;

        $startDate = $date ? $date->startOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $date ? $date->endOfDay() : Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

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
    public function ticketRevenue(Request $request)
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

        // Debug log
        Log::debug("Branch ID: $branchId, Cinema ID: $cinemaId, User Branch: {$user->branch_id}");

        // Trả về view
        return view('admin.statistical.TicketStatistical', compact(
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
            // 'foodRevenue',
            // 'foodNames',
            // 'foodQuantities',
            // 'foodRevenues',
            // 'trendDates',
            // 'trendRevenues',
            // 'foodUsage',
            // 'foodSummaries',
            // 'foodStatistics',
            // 'top6Foods'
        ));
    }
}
