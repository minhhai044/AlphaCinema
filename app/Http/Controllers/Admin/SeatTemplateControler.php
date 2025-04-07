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

        [$seatTemplate, $matrix, $seatMap, $type_seats] = $this->seatTemplateService->editService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('matrix', 'seatTemplate', 'seatMap', 'type_seats'));
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
