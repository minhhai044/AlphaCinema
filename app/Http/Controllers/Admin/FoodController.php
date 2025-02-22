<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Food;
use App\Services\FoodService;
use App\Http\Requests\FoodRequest;
use App\Http\Controllers\Controller;



class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.foods.';
    protected $foodService;

    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    // 1. Hiển thị danh sách Food
    public function index(Request $request)
    {
        $data = $this->foodService->getAllFoodService();

        //            // filter
//            $selectedNameFood = $request->get('name');
//            $selectedPriceFood = $request->get('price');
//
//            if(!empty($selectedNameFood) && $selectedNameFood !== 'Tất cả'){
//                $data->where('name', $request->get('name'));
//            }
//
//            $namesFood = Food::
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
    public function store(FoodRequest $foodRequest)
    {
        try {
            $data = $foodRequest->validated();
            $this->foodService->createFoodService($data);

            return redirect()->route('admin.foods.index')->with('success', 'Thêm mới đồ ăn thành công!');
        } catch (\Throwable $th) {
            return back()->with('Thêm mới không thành công');
        }
    }

    // 4. Hiển thị chi tiết đồ ăn
    public function show($id)
    {
        $data = $this->foodService->getFoodByIdService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 5. Hiển thị form chỉnh sửa đồ ăn
    public function edit($id)
    {
        $data = $this->foodService->getFoodByIdService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 6. Cập nhật đồ ăn
    public function update(FoodRequest $foodRequest, string $id)
    {
        try {
            $data = $foodRequest->validated();

           // Xử lý loại bỏ dấu phẩy trong trường 'price'
          

            $this->foodService->updateFoodService($id, $data);
            return redirect()->route('admin.foods.index')->with('success', 'Cập nhật đồ ăn thành công!');
        } catch (\Throwable $th) {
            return back()->with('Cập nhật không thành công');
        }
    }

    // 7. Xóa đồ ăn
    public function forceDestroy($id)
    {
        $this->foodService->forceDeleteFoodService($id);
        return redirect()->route('admin.foods.index')->with('success', 'Xóa đồ ăn thành công!');
    }

    // xóa mềm
    public function solfDestroy(Food $food)
    {
        try {
            //            dd($food);
            if (!empty($food->img_thumbnail) && Storage::exists($food->img_thumbnail)) {
                Storage::delete($food->img_thumbnail);
            }

            $food->delete();  // xóa mềm

            return redirect()->route('admin.foods.index')->with('success', 'Xóa thành công');

        } catch (\Throwable $th) {
            return back()->with('error', 'Xóa không thành công');
        }
    }
}
