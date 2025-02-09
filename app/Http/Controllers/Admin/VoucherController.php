<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Http\Requests\VoucherRequest;




class VoucherController extends Controller
{
    private const PATH_VIEW = 'admin.vouchers.';
    public function index(Request $request)
    {
        $vouchers = Voucher::latest('id')->paginate(5);
        return view(self::PATH_VIEW . __FUNCTION__, compact('vouchers'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(VoucherRequest $request)
    {


        try {
            do {
                $code = strtoupper(Str::random(10));
            } while (Voucher::where('code', $code)->exists());

            $data = $request->all();
            $data['code'] = $code;
            $data['is_active'] ??= 1;
            $data['limit_by_user'] = $data['limit_by_user'] ?? 1;
            $data['discount'] = $data['discount'] ?? 0;

            Voucher::create($data);

            return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mới mã giảm giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    public function show(Voucher $voucher)
    {
        return view(self::PATH_VIEW . __FUNCTION__, compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        //xử lý ngày bắt đầu và kết thúc
        $voucher->start_date_time = $voucher->start_date_time instanceof \Carbon\Carbon
            ? $voucher->start_date_time
            : \Carbon\Carbon::parse($voucher->start_date_time);

        $voucher->end_date_time = $voucher->end_date_time instanceof \Carbon\Carbon
            ? $voucher->end_date_time
            : \Carbon\Carbon::parse($voucher->end_date_time);

        return view('admin.vouchers.edit', compact('voucher'));
    }


    public function update(VoucherRequest $request, Voucher $voucher)
    {
        try {
            // Lấy dữ liệu từ request
            $data = $request->all();
    
            // Cập nhật bản ghi trong cơ sở dữ liệu
            $voucher->update($data);
    
            return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật mã giảm giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    // public function destroy(Voucher $voucher)
    // {
    //     try {
    //         $voucher->delete();

    //         return back()->with('success', true);

    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
    //     }
    // }

    public function destroy(Voucher $voucher)
    {
        // dd ($typeRoomRequest);
        try {
            $voucher->delete(); // Sử dụng tên biến mới.
            
            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        //
    }
    
}
