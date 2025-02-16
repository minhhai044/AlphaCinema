<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Http\Requests\VoucherRequest;
use App\Models\User_voucher;
use App\Models\User;




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
<<<<<<< HEAD

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


=======
{
    try {
        do {
            $code = strtoupper(Str::random(10));
        } while (Voucher::where('code', $code)->exists());

        $data = $request->all();
        $data['code'] = $code;
        $data['is_active'] ??= 1;
        $data['limit_by_user'] = $data['limit_by_user'] ?? 1;
        
        // Loại bỏ dấu phẩy trước khi lưu vào database
        $data['discount'] = (int) str_replace(',', '', $request->input('discount'));

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mới mã giảm giá thành công!');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
    }
}

    
>>>>>>> huypqph45595

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

<<<<<<< HEAD

    public function update(VoucherRequest $request, Voucher $voucher)
    {
        try {
            $data = $request->all();
            $data['discount'] = (int) str_replace(',', '', $request->input('discount'));
    
            $voucher->update($data);
=======
    public function update(VoucherRequest $request, Voucher $voucher)
    {
        try {
            // Lấy dữ liệu từ request
            $data = $request->all();
    
            // Loại bỏ dấu phẩy trước khi lưu vào database
            $data['discount'] = (int) str_replace(',', '', $request->input('discount'));
    
            // Cập nhật bản ghi trong cơ sở dữ liệu
            $voucher->update($data);
    
>>>>>>> huypqph45595
            return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật mã giảm giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }
    


    public function destroy(Voucher $voucher)
    {
        // dd ($typeRoomRequest);
        try {
<<<<<<< HEAD
            $voucher->delete(); 
=======
            $voucher->delete(); // Sử dụng tên biến mới.
>>>>>>> huypqph45595

            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        //
    }
}
