<?php

namespace App\Http\Controllers\Admin;

use App\Models\Food;
use App\Models\Combo;
use App\Helpers\Alert;
use App\Helpers\Toastr;
use Illuminate\Http\Request;
use App\Services\ComboService;
use App\Http\Requests\ComboRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.combos.';
    protected $comboService;

    public function __construct(ComboService $comboService)
    {
        $this->comboService = $comboService;
        $this->middleware('can:Danh sách combo')->only('index');
        $this->middleware('can:Thêm combo')->only(['create', 'store']);
        $this->middleware('can:Sửa combo')->only(['edit', 'update']);
        $this->middleware('can:Xóa combo')->only('destroy');
    }

    // 1. Hiển thị danh sách Food
    public function index()
    {
        $data = $this->comboService->getAllComboService();
        // $food = Food::query()->select('id', 'name', 'type')->get();

        // dd($data->toArray());


        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 2. Hiển thị form thêm mới đồ ăn
    public function create()
    {
        $food = Food::query()->where('is_active', 1)->pluck('name', 'id');
        $foodPrice = Food::all();

        // dd($food->toArray());
        return view(self::PATH_VIEW . __FUNCTION__, compact('food', 'foodPrice'));
    }

    // 3. Lưu đồ ăn mới
    public function store(ComboRequest $comboRequest)
    {
        try {
            // lấy dữ liệu từ Comboservice để thêm mới
            $this->comboService->createComboService($comboRequest->validated());

            Toastr::success(null, 'Thêm mới đồ ăn thành công!'); // thông báo lỗi

            return redirect()
                ->route('admin.combos.index');
        } catch (\Throwable $th) {
            // return back()->with('error', $th->getMessage());
            Log::error($th->getMessage());
            Toastr::error(null, 'Thêm mới không thành công!');  // thông báo lỗi
            return back();
        }
    }

    // 4. Hiển thị chi tiết đồ ăn
    public function show($id)
    {
        $data = $this->comboService->getComboByIdService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 5. Hiển thị form chỉnh sửa đồ ăn
    public function edit(Combo $combo)
    {
        $combo->load('comboFood');
        $food = Food::query()->where('is_active', '1')->pluck('name', 'id')->all();
        // dd($combo->toArray());
        $foodPrice = Food::all();
        // dd($foodPrice->toArray());
        return view(self::PATH_VIEW . __FUNCTION__, compact('combo', 'food', 'foodPrice'));
    }

    // 6. Cập nhật đồ ăn
    public function update(ComboRequest $comboRequest, string $id)
    {
        try {

            $data = $comboRequest->validated();
            $data['is_active'] = $data['is_active'] ?? 0;
            if (empty($data['price_sale'])) {
                $data['price_sale'] = 0;
            }

            $this->comboService->updateComboService($id, $data);

            Toastr::success(null, 'Cập nhật đồ ăn thành công!'); // thông báo lỗi

            return redirect()->route('admin.combos.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Toastr::error(null, 'Cập nhật không thành công!'); // thông báo lỗi
            return back();
        }
    }

    // // 7. Xóa đồ ăn
    public function destroy(string $id)
    {
        $this->comboService->forceDeleteComboService($id);
        Toastr::success('', 'Xóa thành công');
        return back();
    }
}
