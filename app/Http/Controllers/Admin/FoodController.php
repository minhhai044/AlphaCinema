<?php

namespace App\Http\Controllers\Admin;

use App\Models\Food;
use App\Helpers\Alert;
use App\Helpers\Toastr;
use Illuminate\Http\Request;
use App\Services\FoodService;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\FoodRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    private const PATH_VIEW = 'admin.foods.';


    protected $foodService;

    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    // 1. Hiển thị danh sách Food
    public function index()
    {
        $data = $this->foodService->getAllFoodService();
        // dd($data->toArray());

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    // 2. Hiển thị form thêm mới đồ ăn
    public function create()
    {
        $typeFoods = Food::TYPE_FOOD;
        // dd($typeFoods);
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeFoods'));
    }

    // 3. Lưu đồ ăn mới
    public function store(FoodRequest $foodRequest)
    {
        // dd($foodRequest->toArray());
        try {
            $data = $foodRequest->validated();

            $this->foodService->createFoodService($data);

            Toastr::success(null, 'Thêm mới đồ ăn thành công!');

            return $this->successResponse($data, 'Thêm mới đồ ăn thành công!', Response::HTTP_OK);
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
        $typeFoods = Food::TYPE_FOOD;
        $data = $this->foodService->getFoodByIdService($id);
        // dd($data->toArray());
        return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'typeFoods'));
    }

    // 6. Cập nhật đồ ăn
    public function update(FoodRequest $foodRequest, string $id)
    {
        // dd($foodRequest->toArray());
        try {
            $data = $foodRequest->validated();


            $this->foodService->updateFoodService($id, $data);
            Toastr::success(null, 'Cập nhật đồ ăn thành công!');

            return $this->successResponse($data, 'Cập nhật đồ ăn', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return back()->with('Cập nhật không thành công');
        }
    }

    // 7. Xóa đồ ăn
    public function forceDestroy(Food $food)
    {
        try {
            // if ($food->combos()->count() > 0) {
            //     return back()->with('error', 'Không thể xóa đồ ăn vì đã có combo đang sử dụng.');
            // }
            $food->delete();
            if ($food->img_thumbnail && Storage::exists($food->img_thumbnail)) {
                Storage::delete($food->img_thumbnail);
            }

            Alert::success('Xóa thành công', 'AlphaCinema Thông Báo!');
            return redirect()->route('admin.foods.index');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
