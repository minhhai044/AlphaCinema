<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User_voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        // Lấy người dùng đã đăng nhập
        $user = Auth::user();

        // Truy xuất các bản ghi UserVoucher của người dùng, kèm theo thông tin voucher
        $userVouchers = User_voucher::where('user_id', $user->id)
            ->with('voucher')
            ->get();

        if ($userVouchers->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy voucher nào cho người dùng này'
            ], 404);
        }

        $now = Carbon::now();
        $voucherData = [];

        foreach ($userVouchers as $userVoucher) {
            // Kiểm tra xem có tồn tại thông tin voucher không
            if (!$userVoucher->voucher) {
                continue;
            }

            $voucher = $userVoucher->voucher;
            $startDateTime = Carbon::parse($voucher->start_date_time);
            $endDateTime   = Carbon::parse($voucher->end_date_time);

            // Kiểm tra trạng thái hoạt động của voucher
            if (!$voucher->is_active) {
                $status = 'inactive';
                $message = 'Voucher của bạn đã ngừng hoạt động.';
            } else {
                // So sánh thời gian hiện tại với voucher
                if ($now->lt($startDateTime)) {
                    $status = 'upcoming';
                    $message = 'Voucher của bạn sẽ có hiệu lực từ ' . $startDateTime->toDateTimeString() . '.';
                } elseif ($now->gt($endDateTime)) {
                    // Nếu ngày kết thúc nhỏ hơn thời gian hiện tại thì cập nhật is_active bằng 0
                    if ($voucher->is_active) {
                        $voucher->update(['is_active' => 0]);
                    }
                    $status = 'expired';
                    $message = 'Voucher của bạn đã hết hạn vào ' . $endDateTime->toDateTimeString() . '.';
                } else {
                    $status = 'active';
                    $message = 'Voucher của bạn có hiệu lực đến ' . $endDateTime->toDateTimeString() . '.';
                }
            }

            $voucherData[] = [
                'user_voucher_id' => $userVoucher->id,
                'voucher_id'      => $voucher->id,
                'code'            => $voucher->code,
                'title'           => $voucher->title,
                'description'     => $voucher->description,
                'start_date_time' => $voucher->start_date_time,
                'end_date_time'   => $voucher->end_date_time,
                'status'          => $status,
                'message'         => $message,
                'usage_count'     => $userVoucher->usage_count,
            ];
        }

        return response()->json([
            'vouchers' => $voucherData
        ]);
    }
}
