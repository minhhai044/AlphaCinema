<?php

namespace App\Http\Controllers\Admin;

use App\Events\RealTimeVouCherEvent;
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

    public function __construct()
    {
        $this->middleware('can:Danh sách vouchers')->only('index');
        $this->middleware('can:Thêm vouchers')->only(['create', 'store']);
        $this->middleware('can:Sửa vouchers')->only(['edit', 'update']);
        $this->middleware('can:Xóa vouchers')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ request

        // Chỉ lọc danh sách voucher theo mã
        $vouchers = Voucher::when($search, function ($query, $search) {
            return $query->where('code', 'like', '%' . $search . '%');
        })->latest('id')->paginate(5);

        return view(self::PATH_VIEW . __FUNCTION__, compact('vouchers', 'search'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(VoucherRequest $request)

    {
        try {
            // Nếu không nhập mã, tạo mã ngẫu nhiên
            $code = $request->code ?? strtoupper(Str::random(10));

            // Kiểm tra trùng lặp
            while (Voucher::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(10));
            }

            $data = $request->all();
            $data['code'] = $code;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['limit_by_user'] = $data['limit_by_user'] ?? 1;
            $data['discount'] = $data['discount'] ?? 0;

            $vouchers = Voucher::create($data);

            session()->forget('voucher_code');

            // broadcast(new RealTimeVouCherEvent($vouchers))->toOthers();

            return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mới mã giảm giá thành công!');
        } catch (\Throwable $th) {
            session()->flash('voucher_code', $request->code);

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

            // Loại bỏ dấu phẩy trước khi lưu vào database
            $data['discount'] = (int) str_replace(',', '', $request->input('discount'));

            // Cập nhật bản ghi trong cơ sở dữ liệu
            $voucher->update($data);

            return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật mã giảm giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }


    public function destroy(Voucher $voucher)
    {
        // dd ($typeRoomRequest);
        try {

            $voucher->delete();


            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        //
    }


    public function toggleStatus($id, Request $request)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->is_active = $request->has('is_active');
        $voucher->save();

        return back();
    }
}
