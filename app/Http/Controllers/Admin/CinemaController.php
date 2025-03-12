<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Alert;
use App\Helpers\Toastr;
use App\Models\Cinema;
use App\Http\Controllers\Controller;
use App\Http\Requests\CinemaRequest;
use App\Models\Branch;
use App\Services\CinemaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CinemaController extends Controller
{
    use ApiResponseTrait;
    private const PATH_VIEW = 'admin.cinemas.';
    private CinemaService $cinemaService;
    public function __construct(CinemaService $cinemaService)
    {
        $this->cinemaService = $cinemaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = $this->cinemaService->getAllPaginateService(10);
        $branchs = Branch::query()->orderByDesc('id')->get();

        if (request()->page > $cinemas->lastPage()) {
            return redirect()->route('admin.cinemas.index', ['page' => 1]);
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact('cinemas', 'branchs'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branchs = Branch::query()->orderByDesc('id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('branchs'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CinemaRequest $request)
    {
        try {
            $cinema = $this->cinemaService->storeService($request->validated());

            Toastr::success('', 'Tạo rạp chiếu phim thành công');

            return $this->successResponse(
                $cinema,
                'Thao tác thành công',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            die('Error' . $th->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        $cinema->load('branch');

        return view(self::PATH_VIEW . __FUNCTION__, compact('cinema'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cinema $cinema)
    {
        $branchs = Branch::query()->orderByDesc('id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('cinema', 'branchs'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(CinemaRequest $request, Cinema $cinema)
    {
        try {
            $this->cinemaService->updateSevice($cinema, $request->validated());
            Toastr::success('', 'Sửa rạp chiếu thành công');
            return $this->successResponse([], 'Sửa rạp chiếu thành công');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cinema $cinema)
    {
        try {
            /** 
             * Nếu rạp chiếu đã có phòng không cho xóa
             */
            // $cinema->delete();

            Alert::info('Đang chờ nâng cấp', 'Alpha Cinema Thông Báo');

            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
    public function toggleStatus($id, Request $request)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->is_active = $request->has('is_active');
        $cinema->save();

        return back();
    }
}
