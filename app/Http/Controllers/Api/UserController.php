<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            // Tổng số bản ghi (không lọc)
            $totalRecords = User::count();

            // Tạo query gốc
            $query = User::query();
            $roles = Role::all();

            // Lọc theo giới tính (gender)
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }

            // Lọc theo role (type_user)
            if ($request->filled('type_user')) {
                $query->where('type_user', $request->type_user);
            }

            // Tìm kiếm theo tên hoặc email (DataTables search)
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('email', 'LIKE', "%{$searchValue}%");
                });
            }

            // Số bản ghi sau khi lọc
            $filteredRecords = $query->count();

            // Phân trang
            $length = $request->input('length', 10); // Số bản ghi mỗi trang (mặc định 10)
            $start = $request->input('start', 0);   // Bắt đầu từ bản ghi thứ start
            $users = $query->skip($start)->take($length)->get();

            // Trả về kết quả
            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,     // Tổng số bản ghi trước lọc
                "recordsFiltered" => $filteredRecords, // Số bản ghi sau khi lọc
                "data" =>  $users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'gender' => $user->gender,
                        'roles' => $user->roles->map(function ($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name
                            ];
                        })
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
