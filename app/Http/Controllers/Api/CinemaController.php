<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CinemaRequest;
use App\Models\Cinema;
use App\Services\CinemaService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CinemaController extends Controller
{

    use ApiResponseTrait;
    protected $cinemaService;
    public function __construct(CinemaService $cinemaService)
    {
        $this->cinemaService = $cinemaService;
    }
    public function index()
    {
        try {

            $Cinema = $this->cinemaService->getAllPaginateService();

            return $this->successResponse(
                $Cinema,
                'Thao tác thành công !!!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function getCinemasByBranch(Request $request)
    {
        $branchId = $request->input('branch_id');
        $cinemas = Cinema::where('branch_id', $branchId)->where('is_active', 1)->get(['id', 'name']);
        return response()->json(['cinemas' => $cinemas]);
    }
    public function show(string $id)
    {
        try {
            $Cinema = Cinema::query()->findOrFail($id);

            return $this->successResponse(
                $Cinema,
                'Thao tác thành công !!!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function store(CinemaRequest $request)
    {
        try {
            $data = $this->cinemaService->storeService($request->all());
            return $this->successResponse(
                $data,
                'Thao tác thành công',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function update(CinemaRequest $request, string $id)
    {
        try {
            $data = $this->cinemaService->updateSevice($id, $request->all());
            return $this->successResponse(
                $data,
                'Thao tác thành công',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function delete(string $id)
    {
        try {
            $this->cinemaService->deleteSevice($id);
            $this->successResponse(
                null,
                'Thao tác thành công',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $this->errorResponse($e);
        }
    }
}
