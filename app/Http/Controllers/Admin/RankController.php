<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Alert;
use App\Helpers\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\RankRequest;
use App\Models\Rank;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankController extends Controller
{
    use ApiResponseTrait;
    private const PATH_VIEW = 'admin.ranks.';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ranks = Rank::query()->orderBy('total_spent', 'asc')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('ranks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RankRequest $request)
    {
        try {
            if (Rank::count() < Rank::MAX_RANK) {
                DB::transaction(function () use ($request) {
                    Rank::query()->create($request->validated());
                });

                Toastr::success('', 'Thêm thành công');
                return redirect()->route('admin.ranks.index');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rank $rank)
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rank $rank)
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RankRequest $request, Rank $rank)
    {
        try {
            DB::transaction(function () use ($request, $rank) {
                $data = $request->validated();

                // Chỉ thêm `total_spent` nếu không phải là rank mặc định
                if (!$rank->is_default) {
                    $data['total_spent'] = $request->total_spent;
                }

                $rank->update($data);
            });

            // Toastr::success('', 'Cập nhật thành công');
            // return redirect()->route('admin.ranks.index');

            return $this->successResponse($request->all(), 'Cập nhật thành công');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rank $rank)
    {
        try {
            DB::transaction(function () use ($rank) {
                if (Rank::count() <= Rank::MIN_RANK) {
                    Alert::error('Số lượng cấp bậc đã đạt đến tối tiểu, không thể xóa', 'Alpha Cinema Thông Báo');
                    return redirect()->back();
                }

                // Kiểm tra nếu $rank có is_default = true
                if ($rank->is_default) {
                    Alert::error('Không thể xóa cấp bậc mặc định', 'Alpha Cinema Thông Báo');
                    return redirect()->back();
                }

                // Thực hiện xóa nếu các điều kiện thỏa mãn
                $rank->delete();
            });

            Alert::success('Xóa thành công', 'Alpha Cinema Thông Báo');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
