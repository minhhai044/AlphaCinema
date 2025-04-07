<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\User;
use App\Models\Rank;
use App\Repositories\Modules\UserRepository;
use App\Services\UserService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private const PATH_VIEW = 'admin.users.';

    protected $userService;
    protected $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        // $users = User::orderByDesc('id')->get();
        $users = User::where('type_user', 1)->with('cinema.branch')->get();

        $roles = Role::all();


        return view(self::PATH_VIEW . __FUNCTION__, compact('users', 'roles'));
    }

    public function create()
    {
        $typeAdmin = User::TYPE_ADMIN;
        $roles = Role::all();
        $branches = Branch::where('is_active', 1)->get();


        if (auth()->user()->hasRole("Quản lý chi nhánh")) {
            $cinemas = Cinema::where('is_active', '1')
                ->where('branch_id', auth()->user()->branch_id)
                ->get();
        } else {
            $cinemas = Cinema::where('is_active', '1')->get();
        }

        // Trả về view cùng dữ liệu đã lấy
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'roles', 'cinemas', 'branches']));
    }


    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->validated();

        if (!empty($data['cinema_id'])) {
            $data = $userRequest->except('branch_id');
        }

        $data['type_user'] = 1;
        if (auth()->user()->hasRole('Quản lý cơ sở')) {
            $data['cinema_id'] = auth()->user()->cinema_id;
        }

        // dd($data);
        $user =  $this->userService->storeUser($data);

        if ($userRequest->has('role_id')) {
            // dd($userRequest->role_id);
            $user->assignRole($userRequest->role_id);
        }

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function show($id)
    {
        $user = $this->userRepository->findByIdUserRepository($id);

        if ($user->type_user == 0) {
            $userRank = Rank::where('total_spent', '<=', $user->total_amount)
                ->orderByDesc('total_spent')
                ->first();

            $pointHistories = $user->pointHistories()->get();
        } else {
            $userRank = null;
            $pointHistories = [];
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact('user', 'pointHistories', 'userRank'));
    }

    public function edit($id)
    {

        $typeAdmin = User::TYPE_ADMIN;
        $roles = Role::all();

        $branches = Branch::where('is_active', 1)->get();
        $user =  $this->userRepository->findByIdUserRepository($id);

        if (auth()->user()->hasRole("Quản lý chi nhánh")) {
            $cinemas = Cinema::where('is_active', '1')
                ->where('branch_id', '=', auth()->user()->branch_id)
                ->get();
        } else {
            $cinemas = Cinema::where('is_active', '1')
                ->first('branch_id')
                ->get();
        }

        // dd($user);
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'roles', 'cinemas', 'user', 'branches']));
    }

    public function update(UserRequest $userRequest, $id)
    {

        $data = $userRequest->validated();
        // dd($data);


        if (!empty($data['cinema_id'])) {
            $data = $userRequest->except('branch_id');
        }

        $data['type_user'] = 1;

        $result = $this->userService->updateUser($id, $data);

        if ($userRequest->has('role_id')) {
            // dd($userRequest->role_id);
            $result->roles()->sync($userRequest->role_id);
        } else {
            $result->roles()->detach();
        }

        if ($result) {
            return redirect()->route('admin.users.index')->with('success', 'Cập nhật tài khoản thành công.');
        } else {
            return redirect()->back()->with('error', 'Cập nhật tài khoản không thành công.');
        }
    }

    // xóa mềm
    public function softDestroy(string $id)
    {
        try {
            $user =  $this->userRepository->findByIdUserRepository($id);

            if (!empty($user->avatar) && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }

            $user->delete();  // xóa mềm

            return redirect()->route('admin.users.index')->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {

            return back()->with('error', 'Xóa không thành công');
        }
    }

    public function changActiveStatus(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            // return response()->json($request);
            if (!$user) {
                return response()->json(['message' => 'ROLE không tồn tại'], 404);
            }
            $user->is_active = $request->is_active; // Thay đổi giá trị
            $user->save();
            return response()->json([
                'data' => $user,
                'messenge' => "Thao tác thành công !!!"
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messenge' => "Thao tác không thành công !!!"
            ]);
        }
    }
    // Trong controller Laravel
    // Trong controller Laravel
}
