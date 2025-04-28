<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Str;
use App\Http\Requests\BranchRequest;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    private const PATH_VIEW = 'admin.branches.';

    public function __construct()
    {
        $this->middleware('can:Danh sách chi nhánh')->only('index');
        $this->middleware('can:Thêm chi nhánh')->only(['create', 'store']);
        $this->middleware('can:Sửa chi nhánh')->only(['edit', 'update', 'toggle']);
        $this->middleware('can:Xóa chi nhánh')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Lấy từ khóa tìm kiếm từ request

        // Tìm kiếm theo tên nếu có từ khóa
        $branches = Branch::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest('id')->get();

        // Trả về view kèm dữ liệu
        return view('admin.branches.index', compact('branches', 'search'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(BranchRequest $request)
    {
        $data = $request->validated();

        try {

            if ($data['surcharge']) {
                $data['surcharge'] = str_replace('.', '', $data['surcharge']);
            }

            if ($data['surcharge'] < 1000 || $data['surcharge'] > 100000) {
                return redirect()->back()->with('warning', 'Phụ phí phải lớn hơn hoặc bằng 1000.');
            }

            if (!empty($data['name'])) {
                $data['slug'] = Str::slug($data['name'], '-') . '-' . Str::ulid();
            }

            Branch::create($data);

            return redirect()->route('admin.branches.index')->with('success', 'Thêm chi nhánh thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Lỗi: ' . $th->getMessage());
        }
    }

    public function update(BranchRequest $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $data = $request->validated();

        if ($data['branchSurcharge']) {
            $data['branchSurcharge'] = str_replace('.', '', $data['branchSurcharge']);
        }
        if ($data['branchSurcharge'] < 1000 || $data['branchSurcharge'] > 100000) {
            return redirect()->back()->with('warning', 'Phụ phí phải lớn hơn hoặc bằng 1.000 và nhỏ hơn hoặc bằng 100.000 !!!');
        }

        if ($data['branchName']) {
            $data['slug'] = Str::slug($data['branchName'], '-') . '-' . Str::ulid();
        }

        $branch->update([
            'name' => $data['branchName'],
            'surcharge' => $data['branchSurcharge'],
            'slug' =>  $data['slug']
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Cập nhật chi nhánh thành công!');
    }
    public function changeActive(Request $request)
    {
        $branch = Branch::with('cinemas')->find($request->id);

        if (!$branch) {
            return response()->json(['success' => false, 'message' => 'Chi nhánh không tồn tại.']);
        }
        
        // Khi tắt
        if ($branch->cinemas && $request->is_active == 0) {
            foreach ($branch->cinemas ?? [] as $cinemas) {
                if ($cinemas->is_active == 1) {
                    return response()->json(['success' => false, 'message' => 'Bạn không thể tắt hoạt động chi nhánh khi các rạp vẫn đang hoạt động !!!']);
                }
            }
        }
        $branch->is_active = $request->is_active;
        $branch->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Branch $branch)
    {
        // dd ($typeRoomRequest);
        try {
            $branch->delete(); // Sử dụng tên biến mới.

            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function toggleStatus($id, Request $request)
    {
        $branch = Branch::findOrFail($id);
        $branch->is_active = $request->has('is_active');
        $branch->save();

        return back()->with('success', 'Thao tác thành công !!!');
    }
}
