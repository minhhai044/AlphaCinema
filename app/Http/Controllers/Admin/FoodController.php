<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Storage;
use App\Models\Food;
use App\Helpers\Alert;
use App\Helpers\Toastr;
use App\Services\FoodService;
use App\Http\Requests\FoodRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const PATH_VIEW = 'admin.foods.';

    protected $foodService;

    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
        $this->middleware('can:Danh sách đồ ăn')->only('index');
        $this->middleware('can:Thêm đồ ăn')->only(['create', 'store']);
        $this->middleware('can:Sửa đồ ăn')->only(['edit', 'update', 'change-active']);
        $this->middleware('can:Xóa đồ ăn')->only('destroy');
    }

    // 1. Hiển thị danh sách Food
    public function index()
    {
        $foods = $this->foodService->getAllFoodService();
        // dd($data->toArray());

        return view(self::PATH_VIEW . __FUNCTION__, compact('foods'));
    }

    // 2. Hiển thị form thêm mới đồ ăn
    public function create()
    {
        $typeFoods = Food::TYPE_FOOD;
        // var_dump($typeFoods);
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeFoods'));
    }

    // 3. Lưu đồ ăn mới
    public function store(FoodRequest $foodRequest)
    {
        // var_dump($foodRequest->all());
        // dd($foodRequest->toArray());
        try {
            // $data = $foodRequest->validated();
            // dd($data);
            $this->foodService->createFoodService($foodRequest->validated());

            Toastr::success(null, 'Thêm mới đồ ăn thành công!');

            return redirect()->route('admin.foods.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Toastr::error(null, 'Thêm mới không thành công!');  // thông báo lỗi
            return back();
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
        $foods = $this->foodService->getFoodByIdService($id);
        // dd($foods->toArray());
        return view(self::PATH_VIEW . __FUNCTION__, compact('foods', 'typeFoods'));
    }

    // 6. Cập nhật đồ ăn
    public function update(FoodRequest $foodRequest, string $id)
    {
        // dd($foodRequest->toArray());
        try {
            // $data = $foodRequest->validated();
            $this->foodService->updateFoodService($id, $foodRequest->validated());
            Toastr::success(null, 'Cập nhật đồ ăn thành công!');

            return redirect()->route('admin.foods.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Toastr::error(null, 'Cập nhật không thành công!');  // thông báo lỗi
            return back();
        }
    }

    // 7. Xóa đồ ăn
    public function destroy(Food $food)
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
