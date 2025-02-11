<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Modules\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $users = User::orderByDesc('id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('users'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->validated();

        $this->userService->storeUser($data);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function show($id){
        $user =  $this->userRepository->findByIdUserRepository($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('user'));
    }

    public function edit($id)
    {
        $user =  $this->userRepository->findByIdUserRepository($id);
        // dd($user);
        return view(self::PATH_VIEW . __FUNCTION__, compact('user'));
    }

    public function update(UserRequest $userRequest, $id)
    {

        $data = $userRequest->validated();

        $result = $this->userService->updateUser($id, $data);

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
}
