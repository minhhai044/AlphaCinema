<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Alert;
use App\Helpers\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\RankRequest;
use App\Models\Rank;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

            dd(1);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            Toastr::success('', 'Thêm không thành công');
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
            Toastr::success('', 'Cập nhật thành công');
            return $this->successResponse([
                'rank' => $rank,
                'res' => $request->all()
            ], 'Cập nhật thành công');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse(
                'Thêm không thành công',
                Response::HTTP_BAD_REQUEST
            );
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
    public function getRankByUser()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
            }

            // Lấy tổng giá trị giao dịch của user
            $totalTransaction = $user->total_amount; // Giả sử có cột này trong bảng users

            // Tìm rank phù hợp dựa trên tổng giao dịch
            $rank = Rank::where('total_spent', '<=', $totalTransaction)
                        ->orderBy('total_spent', 'desc')
                        ->first();

            return response()->json([
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_amount' => $totalTransaction
                ],
                'rank' => $rank
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getRanksJson()
    {
        $ranks = Rank::query()->orderBy('total_spent', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $ranks
        ]);
    }
    

}
