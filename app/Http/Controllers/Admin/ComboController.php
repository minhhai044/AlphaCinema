<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Combo;
use Illuminate\Http\Request;
use App\Services\ComboService;
use App\Http\Requests\ComboRequest;
use App\Http\Controllers\Controller;


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
    }

    // 1. Hiển thị danh sách Food
    public function index(Request $request)
    {
        $data = $this->comboService->getAllComboService();

        return view(self::PATH_VIEW . __FUNCTION__, [
            'data' => $data,
        ]);
    }

    // 2. Hiển thị form thêm mới đồ ăn
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    // 3. Lưu đồ ăn mới
    public function store(ComboRequest $comboRequest)
    {
        try {
            $data = $comboRequest->validated();
            $this->comboService->createComboService($data);

            return redirect()->route('admin.combos.index')->with('success', 'Thêm mới đồ ăn thành công!');
        } catch (\Throwable $th) {
            return back()->with('Thêm mới không thành công');
        }
    }

    // 4. Hiển thị chi tiết đồ ăn
    public function show($id)
    {
        $data = $this->comboService->getComboByIdService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 5. Hiển thị form chỉnh sửa đồ ăn
    public function edit($id)
    {
        $data = $this->comboService->getComboByIdService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 6. Cập nhật đồ ăn
    public function update(ComboRequest $comboRequest, string $id)
    {
        try {
            $data = $comboRequest->validated();
            $this->comboService->updateComboService($id, $data);
            return redirect()->route('admin.combos.index')->with('success', 'Cập nhật đồ ăn thành công!');
        } catch (\Throwable $th) {
            return back()->with('Cập nhật không thành công');
        }
    }

    // 7. Xóa đồ ăn
    public function forceDestroy($id)
    {
        $this->comboService->forceDeleteComboService($id);
        return redirect()->route('admin.combos.index')->with('success', 'Xóa đồ ăn thành công!');
    }

    // xóa mềm
    // public function solfDestroy(Combo $combo)
    // {
    //     try {
    //         //            dd($food);
    //         if (!empty($combo->img_thumbnail) && Storage::exists($combo->img_thumbnail)) {
    //             Storage::delete($combo->img_thumbnail);
    //         }

    //         $combo->delete();  // xóa mềm

    //         return redirect()->route('admin.combos.index')->with('success', 'Xóa thành công');

    //     } catch (\Throwable $th) {
    //         return back()->with('error', 'Xóa không thành công');
    //     }
    // }
}
