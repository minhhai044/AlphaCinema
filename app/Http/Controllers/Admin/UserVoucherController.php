<?php

namespace App\Http\Controllers\Admin;

use App\Events\RealTimeVouCherEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserVoucherRequest;
use App\Models\User_voucher;
use App\Models\User;
use App\Models\Voucher;

class UserVoucherController extends Controller
{
    /**
     * Hiển thị danh sách User Voucher.
     */

    public function __construct()
    {
        $this->middleware('can:Danh sách áp mã giảm giá')->only('index');
        $this->middleware('can:Thêm áp mã giảm giá')->only(['create', 'store']);
        $this->middleware('can:Sửa áp mã giảm giá')->only(['edit', 'update']);
        $this->middleware('can:Xóa áp mã giảm giá')->only('destroy');
    }

    public function index()
    {
        $User_vouchers = User_voucher::with(['user', 'voucher'])->latest('id')->get();
        return view('admin.user_vouchers.index', compact('User_vouchers'));
    }

    /**
     * Hiển thị form thêm mới User Voucher.
     */
    public function create()
    {
        $users = User::query()->where('type_user', '0')->get();
        $vouchers = Voucher::where('is_active', 1)->get();

        return view('admin.user_vouchers.create', compact('users', 'vouchers'));
    }

    /**
     * Lưu thông tin User Voucher vào database.
     */


    public function store(UserVoucherRequest $request)
    {
        try {
            $data = $request->validated();

            // Kiểm tra nếu có nhiều người dùng được chọn
            if (!empty($data['user_ids']) && is_array($data['user_ids'])) {
                $insertData = [];
                $userIds = [];
                foreach ($data['user_ids'] as $userId) {
                    if (!User_voucher::where('user_id', $userId)
                        ->where('voucher_id', $data['voucher_id'])
                        ->exists()) {
                        $userIds[] = $userId;
                        $insertData[] = [
                            'user_id' => $userId,
                            'voucher_id' => $data['voucher_id'],
                            'usage_count' => $data['usage_count'] ?? 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // dd($insertData);
                if (!empty($insertData)) {

                    foreach ($insertData as $voucherData) {

                        $userVoucher = User_voucher::create($voucherData);
                        $voucher = $userVoucher->voucher;

                        broadcast(new RealTimeVouCherEvent($voucher, $voucherData['user_id']))->toOthers();
                    }


                    return redirect()->route('admin.user-vouchers.index')->with('success', 'Thêm mới User Voucher thành công!');
                } else {
                    return redirect()->back()->with('error', 'Tất cả người dùng đã nhận voucher này.');
                }
            }

            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một người dùng.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }





    /**
     * Hiển thị form chỉnh sửa User Voucher.
     */
    public function edit(User_voucher $User_voucher)
    {
        $users = User::all();
        $userId = $User_voucher->user_id;

        $usedVoucherIds = User_voucher::where('user_id', $userId)
            ->where('id', '!=', $User_voucher->id)
            ->pluck('voucher_id')
            ->toArray();

        $vouchers = Voucher::whereNotIn('id', $usedVoucherIds)
            ->orWhere('id', $User_voucher->voucher_id)
            ->get();

        return view('admin.user_vouchers.edit', compact('User_voucher', 'users', 'vouchers'));
    }


    /**
     * Cập nhật User Voucher.
     */
    public function update(UserVoucherRequest $request, User_voucher $User_voucher)
    {
        try {
            $data = $request->validated();

            $User_voucher->update($data);

            return redirect()->route('admin.user-vouchers.index')->with('success', 'Cập nhật User Voucher thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    /**
     * Xóa User Voucher.
     */
    public function destroy(User_voucher $User_voucher)
    {
        try {
            $User_voucher->delete();
            return redirect()->route('admin.user-vouchers.index')->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
