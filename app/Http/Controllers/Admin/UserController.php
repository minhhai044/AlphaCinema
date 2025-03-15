<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Cinema;
use App\Models\User;
use App\Models\Rank;
use App\Repositories\Modules\UserRepository;
use App\Services\UserService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $users = User::where('type_user', 1)->with('cinema')->get();
        $roles = Role::all();

        return view(self::PATH_VIEW . __FUNCTION__, compact('users', 'roles'));
    }

    public function create()
    {
        $typeAdmin = User::TYPE_ADMIN;
        $roles = Role::all();

        $cinemas = Cinema::where('is_active', '1')->first('branch_id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'roles', 'cinemas']));
    }

    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->validated();
        $data['type_user'] = 1;

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

        $cinemas = Cinema::where('is_active', '1')->first('branch_id')->get();

        $user =  $this->userRepository->findByIdUserRepository($id);
        // dd($user);
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'roles', 'cinemas', 'user']));
    }

    public function update(UserRequest $userRequest, $id)
    {

        $data = $userRequest->validated();
        $data['type_user'] = 1;
        // dd($data);
        $result = $this->userService->updateUser($id, $data);

        if ($userRequest->has('role_id')) {
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
    public function solfDestroy(string $id)
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

    // Trong controller Laravel
    // Trong controller Laravel

}
