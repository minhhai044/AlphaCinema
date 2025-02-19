<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Models\Combo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateActiveController extends Controller {

    public function food(Request $request)
    {
        try {
            $food = Food::findOrFail($request->id);

            $food->is_active = $request->is_active;
            $food->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $food]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $food]);
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
}
