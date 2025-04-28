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
        })->latest('id')->paginate(5);

        // Trả về view kèm dữ liệu
        return view('admin.branches.index', compact('branches', 'search'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(BranchRequest $request)
    {
        $data = $request->validated(); // Lấy dữ liệu đã validate
        try {
            $data['is_active'] = 1;
            $data['surcharge'] ??= 0;

            if ($data['surcharge'] < 1000) {
                return redirect()->back()->with('error', 'Phụ phí phải lớn hơn hoặc bằng 1000.');
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

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surcharge' => 'required|numeric|min:1000',
        ], [
        'name.required' => 'Tên chi nhánh không được để trống.',
        'name.string' => 'Tên chi nhánh phải là một chuỗi.',
        'name.max' => 'Tên chi nhánh không được vượt quá 255 ký tự.',
        'surcharge.required' => 'Phụ thu không được để trống.',
        'surcharge.numeric' => 'Phụ thu phải là số hợp lệ.',
        'surcharge.min' => 'Phụ thu phải từ 1,000 trở lên.',]
    );

        if ($validator->fails()) {
            return redirect()->route('admin.branches.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_modal', $id); 
        }

        $branch->update([
            'name' => $request->name,
            'surcharge' => $request->surcharge,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Cập nhật chi nhánh thành công!');
    }
public function changeActive(Request $request)
{
    $branch = Branch::find($request->id);

    if (!$branch) {
        return response()->json(['success' => false, 'message' => 'Chi nhánh không tồn tại.']);
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

        return back()->with('success','Thao tác thành công !!!');
    }
}
