<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Models\Combo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Slideshow;

class UpdateActiveController extends Controller {

    public function food(Request $request)
    {
        try {
            $food = Food::withCount('combos')->findOrFail($request->id);
    
            // Kiểm tra nếu món ăn đang trong combo và muốn tắt trạng thái
            if ($food->combos_count > 0 && $request->is_active == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tắt món ăn vì đang có trong Combo!',
                ]);
            }
    
            $food->is_active = $request->is_active;
            $food->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công.',
                'data' => $food
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại.',
            ]);
        }
    }

    public function combo(Request $request)
    {
        try {
            $combo = Combo::findOrFail($request->id);

            $combo->is_active = $request->is_active;
            $combo->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $combo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $combo]);
        }
    }
    public function slideshow(Request $request)
    {
        $slideshow = Slideshow::findOrFail($request->id);

        // Không cho phép tắt slideshow hiện tại nếu nó đang được bật
        if ($slideshow->is_active && !$request->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể tắt slideshow đang hoạt động!',
            ], 400);
        }

        if ($request->is_active) {
            // Tắt tất cả các slideshow khác
            Slideshow::where('is_active', 1)->update(['is_active' => 0]);
        }

        // Cập nhật trạng thái của slideshow hiện tại
        $slideshow->is_active = $request->is_active;
        $slideshow->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }
    /**
     * Change IsActive Cinema
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cinema(Request $request)
    {
        try {
            $cinema = Cinema::with('rooms','branch')->find($request->id);
            if (!$cinema) {
                return response()->json(['success' => false, 'message' => 'Rạp không tồn tại !!!']);
            }
            if ($cinema->rooms && $request->is_active == 0) {
                foreach ($cinema->rooms ?? [] as $rooms) {
                    if ($rooms->is_active == 1) {
                        return response()->json(['success' => false, 'message' => 'Không thể tắt hoạt đông rạp chiếu khi phòng chiếu vẫn đang hoạt động !!!']);
                    }
                    
                }
            }
            if ($cinema->branch && $request->is_active == 1) {
                if ($cinema->branch->is_active == 0) {
                    return response()->json(['success' => false, 'message' => 'Không thể bật hoạt đông rạp chiếu khi chi nhánh vẫn đang ngừng hoạt động !!!']);
                }
                
            }
            
            $cinema->is_active = $request->is_active;
            $cinema->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $cinema]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $cinema]);
        }
    }

}
