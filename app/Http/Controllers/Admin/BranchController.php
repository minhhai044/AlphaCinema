<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Str;
use App\Http\Requests\BranchRequest;

class BranchController extends Controller
{
    private const PATH_VIEW = 'admin.branches.';
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
            $data['surcharge'] ??= 0;  // Thêm giá trị mặc định cho surcharge

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

        $data = $request->validated(); // Lấy dữ liệu đã validate

        $branch->update([
            'name' => $data['name'],
            
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Cập nhật chi nhánh thành công!');
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
