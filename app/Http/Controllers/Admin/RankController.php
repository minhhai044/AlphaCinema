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
    public function __construct( )
    {
        $this->middleware('can:Danh sách hạn mức')->only('index');
    }

    public function index()
    {
        $ranks = Rank::query()->orderBy('total_spent', 'asc')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('ranks'));
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
