<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    private const PATH_VIEW = "admin.auth.";

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function showFormLogin()
    {
        return  view(self::PATH_VIEW . ".index");
    }

    public function login(UserRequest $userRequest)
    {
        try {

            $data = $userRequest->validated();

            $user = $this->userService->getUserApi($data['email']);

            // Kiểm tra nếu người dùng không tồn tại
            if (!$user) {
                return back()->with('error', ['email' => 'Email không tồn tại.']);
            }

            if (Hash::check($data['password'], $user->password)) {

                if ($user->type_user === 1 && $user->is_active == true) {
                    Auth::login($user);

                    return redirect()->route('admin.index');
                } else {
                    return back()->with('error', 'Bạn không có quyền đăng nhập vào hệ thống');
                }
            } else {
                return back()->with('error', ['password' => 'Mật khẩu không chính xác.']);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->withErrors(['error' => 'Đã xảy ra lỗi, vui lòng thử lại sau.']);
        }
    }

    // Phương thức logout
    public function logout()
    {
        try {
            Auth::logout();

            session()->flush();

            return redirect()->route('showFormLogin');
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có vấn đề khi đăng xuất
            Log::error('Lỗi', [
                'error_message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'email' => Auth::check() ? Auth::user()->email : 'Unknown user'
            ]);

            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng xuất. Vui lòng thử lại sau.']);
        }
    }
}
