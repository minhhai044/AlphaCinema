<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowtimeCopyRequest;
use App\Http\Requests\ShowtimeRequest;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\Type_seat;
use App\Services\ShowtimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ShowtimeController extends Controller
{
    private const PATH_VIEW = "admin.showtimes.";

    private $showtimeService;
    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
        $this->middleware('can:Danh sách suất chiếu')->only('index');
        $this->middleware('can:Thêm suất chiếu')->only(['createList', 'create', 'store','storePremium']);
        $this->middleware('can:Sửa suất chiếu')->only(['edit', 'update']);
        $this->middleware('can:Xóa suất chiếu')->only('delete');
        $this->middleware('can:Xem chi tiết suất chiếu')->only('show');
    }
    public function index(Request $request)
    {
        [$branchs, $branchsRelation, $listShowtimes, $movies, $listShowtimesByDates] = $this->showtimeService->getService($request);
        return view(self::PATH_VIEW . __FUNCTION__, compact('branchs', 'branchsRelation', 'listShowtimes', 'movies', 'listShowtimesByDates'));
    }
    public function create(string $id)
    {
        [$branchs, $branchsRelation, $rooms, $movie, $days, $slug, $roomsRelation, $specialshowtimes, $type_seats, $type_rooms] = $this->showtimeService->createService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('type_seats', 'branchs', 'branchsRelation', 'rooms', 'movie', 'days', 'slug', 'roomsRelation', 'specialshowtimes', 'type_rooms'));
    }
    public function store(ShowtimeRequest $showtimeRequest)
    {
        try {
            $this->showtimeService->storeService($showtimeRequest->validated());
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function delete(Request $request)
    {
        try {
            $this->showtimeService->deleteService($request->showtime_id);
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function copys(Request $request)
    {
        $data = $request->all();
        return redirect()->route('admin.showtimes.getCopys')->with([
            'data' => $data
        ]);
    }

    public function getCopys()
    {
        $data = session('data');
        $movie = [];
        $showtime = [];
        $date = [];
        $days = Day::query()->get();
        $specialshowtimes = Showtime::SPECIALSHOWTIMES;
        $type_seats = Type_seat::query()->get();

        if (!empty($data)) {
            $movie = Movie::query()->findOrFail($data['movie_id']);
            $showtimes = json_decode($data['showtime'], true);
            foreach ($showtimes ??= [] as $value) {
                if ($value['room_id'] == $showtimes[0]['room_id']) {
                    array_push($showtime, $value);
                }
            }
            if (empty($data['date'])) {
                return back()->with('warning', 'Bạn quên chọn ngày kìa !!!');
            }
            $date = explode(', ', $data['date']);


            $roomId = $showtime[0]['room']['id'] ?? null;
            if (!$roomId) {
                abort(404, "Không tìm thấy phòng chiếu");
            }

            $existingDates = Showtime::query()
                ->where('room_id', $roomId)
                ->whereIn('date', $date)
                ->pluck('date')
                ->toArray();

            $date = array_filter($date, function ($d) use ($data, $movie, $existingDates) {
                $currentDate = Carbon::now()->format('Y-m-d');
                $createdDate = Carbon::parse($movie['created_at'])->format('Y-m-d');
                $endDate = Carbon::parse($movie['end_date'])->format('Y-m-d');

                return $d > $currentDate
                    && $d != $data['date_showtime']
                    && $d <= $endDate
                    && $d >= $createdDate
                    && !in_array($d, $existingDates);
            });

            $date = array_values($date);
        } else {
            return redirect()->route('admin.showtimes.index')->with('warning', 'Đừng có load lại trang đang lưu session mà ???');
        }


        if (empty($date)) {
            return redirect()->route('admin.showtimes.index')->with('warning', 'Phim không nằm trong thời gian chiếu hoặc đã có suất chiếu tại các ngày đó !!!');
        }

        if (empty($showtime)) {
            return redirect()->route('admin.showtimes.index')->with('error', 'Thao tác không thành công !!!');
        }
        return view(self::PATH_VIEW . __FUNCTION__, compact('movie', 'showtime', 'date', 'days', 'specialshowtimes', 'type_seats'));
    }


    public function storeCopies(ShowtimeCopyRequest $showtimeCopyRequest)
    {
        try {
            $seat_structures = json_decode($showtimeCopyRequest->seat_structure, true);
            $days = json_decode($showtimeCopyRequest->day_id, true);

            foreach ($showtimeCopyRequest->date as $key => $date) {
                foreach ($showtimeCopyRequest->start_time[$key] as $keyStart_time => $start_time) {
                    foreach ($showtimeCopyRequest->end_time[$key] as $keyEnd_time => $end_time) {
                        if ($keyStart_time == $keyEnd_time) {

                            $price_special_value = isset($showtimeCopyRequest->price_special[$key])
                                ? str_replace('.', '', $showtimeCopyRequest->price_special[$key])
                                : 0;


                            Showtime::query()->create([
                                'branch_id' => $showtimeCopyRequest->branch_id,
                                'movie_id' => $showtimeCopyRequest->movie_id,
                                'day_id' => $days[$key]['day_id'] ?? null,
                                'cinema_id' => $showtimeCopyRequest->cinema_id,
                                'room_id' => $showtimeCopyRequest->room_id,
                                'seat_structure' => json_encode($seat_structures[$key]),
                                'slug' => Showtime::generateCustomRandomString(),
                                'date' => $date,
                                'price_special' => $price_special_value,
                                'start_time' => $start_time,
                                'end_time' => $end_time,
                                'is_active' => 0,
                            ]);
                        }
                    }
                }
            }
            return redirect()->route('admin.showtimes.index')->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return redirect()->route('admin.showtimes.index')->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function createList(string $id)
    {
        [$branchs, $branchsRelation, $rooms, $movie, $days, $slug, $roomsRelation, $specialshowtimes, $type_seats, $type_rooms] = $this->showtimeService->createService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('type_seats', 'branchs', 'branchsRelation', 'rooms', 'movie', 'days', 'slug', 'roomsRelation', 'specialshowtimes', 'type_rooms'));
    }
    public function multipleSelect(Request $request, string $id)
    {
        if (empty($request->branches) || empty($request->dates) || empty($request->cinemas) || empty($request->rooms) || empty($request->start_time) || empty($request->end_time)) {
            return back()->with('warning', 'Vui lòng nhập đầy đủ thông tin !!!');
        }

        $movie = Movie::findOrFail($id)->toArray();
        $dataFull = [];
        $dates = explode(',', $request->dates);
        $now = Carbon::now();
        $movieCreatedAt = Carbon::parse($movie['created_at']);
        $movieEndDate = Carbon::parse($movie['end_date']);

        $validDates = array_filter($dates, function ($date) use ($now, $movieCreatedAt, $movieEndDate) {
            $dateObj = Carbon::parse($date);
            return $dateObj->greaterThan($now)
                && $dateObj->greaterThanOrEqualTo($movieCreatedAt)
                && $dateObj->lessThanOrEqualTo($movieEndDate);
        });
        $showtimes = [];
        foreach ($request->start_time ?? [] as $keyTime => $valueTime) {
            $showtimes[] = [
                'start_time' => $valueTime,
                'end_time'   => $request->end_time[$keyTime] ?? null,
            ];
        }
        $CheckDuplicateShowtime = [];
        foreach ($validDates as $date) {
            // Lấy Các showtime để chech trùng
            $CheckDuplicate = Showtime::with('branch', 'cinema', 'room')
                ->where('date', $date)
                ->get()
                ->toArray();

            foreach ($CheckDuplicate as $showtime) {
                $branchName = $showtime['branch']['name'];
                $cinemaName = $showtime['cinema']['name'];
                $roomName = $showtime['room']['name'];
                $CheckDuplicateShowtime[Str::slug($date)][$branchName][$cinemaName][$roomName][] = $showtime;
            }
            // Làm đẹp dữ liệu
            foreach ($request->branches as $branchValue) {
                $branch = Branch::findOrFail($branchValue);
                if (!$branch) continue;

                foreach ($request->cinemas[$branchValue] ?? [] as $cinemaValue) {
                    $cinema = Cinema::findOrFail($cinemaValue);
                    if (!$cinema) continue;

                    foreach ($request->rooms[$cinemaValue] ?? [] as $roomValue) {
                        $room = Room::with('type_room')->findOrFail($roomValue);
                        if (!$room) continue;

                        $dataFull[Str::slug($date)][$branch->name][$cinema->name][] = [
                            'branch' => $branch->toArray(),
                            'cinema' => $cinema->toArray(),
                            'room' => $room->toArray(),
                            'movie' => $movie,
                            'showtimes' => $showtimes
                        ];
                    }
                }
            }
        }

        $dataFulls = [];
        $duplicateData = [];

        foreach ($dataFull as $dateDataFull => $branches) {
            if (isset($CheckDuplicateShowtime[Str::slug($dateDataFull)])) {
                foreach ($branches as $branchName => $cinemas) {
                    foreach ($cinemas as $cinemaName => $rooms) {
                        foreach ($rooms as $key => $roomData) {
                            $roomName = $roomData['room']['name'];
                            if (isset($CheckDuplicateShowtime[Str::slug($dateDataFull)][$branchName][$cinemaName][$roomName])) {
                                unset($duplicateData[$dateDataFull][$branchName][$cinemaName][$key]);
                            } else {
                                $duplicateData[$dateDataFull][$branchName][$cinemaName][] = $roomData;
                            }
                        }
                    }
                }
            } else {
                $dataFulls[$dateDataFull] = $branches;
            }
        }
        $mergedData = array_merge_recursive($dataFulls, $duplicateData);

        uksort($mergedData, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return redirect()->route('admin.showtimes.createList', $id)->with([
            'dataFull' => $mergedData,
            'showtimes' => $showtimes
        ])->with('warning', 'Phòng sẽ bị loại bỏ nếu đã tồn tại suất chiếu trong ngày !!! ');
    }
    public function storePremium(Request $request, string $id)
    {
        // dd($request->all(),$id);
        try {
            if (empty($request['dates']) || empty($request['price_specials']) || empty($request['days']) || empty($request['branches']) || empty($request['cinemas']) || empty($request['rooms']) || empty($request['seat_structure']) || empty($request['start_time']) || empty($request['end_time'])) {
                return back()->with('error', 'Vui lòng nhập đầy đủ thông tin !!!');
            }
            $groupedData = [];

            foreach ($request['dates'] as $keyDate => $date) {

                foreach ($request['branches'][$keyDate] as $branch) {
                    foreach ($request['cinemas'][$keyDate] as $cinema) {
                        foreach ($request['rooms'][$keyDate] as $room) {
                            $groupedData[] = [
                                'date' => $date[0],
                                'branch_id' => $branch,
                                'day_id' => $request['days'][$keyDate][0],
                                'cinema_id' => $cinema,
                                'room_id' => $room,
                                'seat_structure' => $request['seat_structure'][$keyDate][0],
                                'start_times' => $request['start_time'][$keyDate],
                                'end_times' => $request['end_time'][$keyDate],
                                'price_special' => $request['price_specials'][$keyDate][0],
                                'status_special' => $request['status_specials'][$keyDate][0],
                            ];
                        }
                    }
                }
            }
            foreach ($groupedData as $groups) {
                foreach ($groups['start_times'] as $keyStart_times => $start_time) {
                    Showtime::query()->create([
                        'branch_id' => $groups['branch_id'],
                        'movie_id' => $id,
                        'day_id' => $groups['day_id'],
                        'cinema_id' => $groups['cinema_id'],
                        'room_id' => $groups['room_id'],
                        'seat_structure' => $groups['seat_structure'],
                        'slug' => Showtime::generateCustomRandomString(),
                        'date' => $groups['date'],
                        'price_special' => isset($groups['price_special'])
                            ? str_replace('.', '', $groups['price_special'])
                            : 0,
                        'start_time' => $start_time,
                        'end_time' => $groups['end_times'][$keyStart_times],
                        'is_active' => 1,
                        'status_special' => $groups['status_special'],
                    ]);
                }
            }
            return redirect()->route('admin.showtimes.index')->with('success','Thao tác thành công !!!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);

            return back()->with('error','Thao tác không thành công !!!');
        }
    }
    public function show(string $id)
    {

        [
            $showtime,
            $seatMap,
            $matrix_colume,
            $totalSeats,
            $soldSeats

        ] = $this->showtimeService->showServive($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('showtime', 'matrix_colume', 'seatMap','totalSeats','soldSeats'));
    }
}
