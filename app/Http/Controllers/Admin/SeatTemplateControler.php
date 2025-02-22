<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Alert;
use App\Helpers\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\SeatTemplateRequest;
use App\Http\Requests\SeatTemplateStructureRequest;
use App\Models\Seat_template;
use App\Services\SeatTemplateService;
use Illuminate\Http\Request;

class SeatTemplateControler extends Controller
{
    private const PATH_VIEW = 'admin.seat_templates.';
    private $seatTemplateService;
    public function __construct(SeatTemplateService $seatTemplateService)
    {
        $this->seatTemplateService = $seatTemplateService;
    }
    public function index(Request $request)
    {
        $dataAll = $this->seatTemplateService->getAll($request);
        $matrixs = Seat_template::MATRIXS;
        return view(self::PATH_VIEW . __FUNCTION__, compact('dataAll', 'matrixs'));
    }
    public function store(SeatTemplateRequest $seatTemplateRequest)
    {
        try {
            $data = $seatTemplateRequest->validated();
            $this->seatTemplateService->storeService($data);
            return back()->with('success', 'Thao tác thành công');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công');
        }
    }
    public function update(SeatTemplateRequest $seatTemplateRequest, string $id)
    {
        try {
            $this->seatTemplateService->updateSevice($id, $seatTemplateRequest->validated());
            return back()->with('success', 'Thao tác thành công');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công');
        }
    }
    public function edit(string $id)
    {

         [$seatTemplate, $matrix, $seatMap,$type_seats] = $this->seatTemplateService->editService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('matrix', 'seatTemplate', 'seatMap','type_seats'));

        $seatTemplate = Seat_template::query()->findOrFail($id);
        // dd($seatTemplate);
        $matrix = Seat_template::getMatrixById($seatTemplate->matrix);
        $seatMap = [];
        if ($seatTemplate->seat_structure) {
            $seats = json_decode($seatTemplate->seat_structure, true);
            // dd(json_decode($seatTemplate->seat_structure));

            // Đếm tổng số ghế
            $totalSeats = 0; // Khởi tạo biến tổng số ghế

            if ($seats) {
                foreach ($seats as $seat) {
                    $coordinates_y = $seat['coordinates_y'];
                    $coordinates_x = $seat['coordinates_x'];

                    if (!isset($seatMap[$coordinates_y])) {
                        $seatMap[$coordinates_y] = [];
                    }

                    $seatMap[$coordinates_y][$coordinates_x] = $seat['type_seat_id'];

                    // Tăng tổng số ghế
                    if ($seat['type_seat_id'] == 3) {
                        // Ghế đôi, cộng thêm 2
                        $totalSeats += 2;
                    } else {
                        // Ghế thường hoặc ghế VIP, cộng thêm 1
                        $totalSeats++;
                    }
                }
            }
        }
        return view(self::PATH_VIEW . __FUNCTION__, compact('matrix', 'seatTemplate', 'seatMap'));

    }
    public function update_seat(SeatTemplateStructureRequest $seatTemplateStructureRequest, string $id)
    {
        try {
            $this->seatTemplateService->updateSevice($id, $seatTemplateStructureRequest->validated());
            return back()->with('success', 'Thao tác thành công');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công');
        }
    }
}
