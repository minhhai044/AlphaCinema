<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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

    public function formSendMail()
    {
        return  view(self::PATH_VIEW . ".showFormMail");
    }

    public function sendMailPassword(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email|exists:users,email', // Kiểm tra email có tồn tại trong bảng users
        ]);

        try {
            // Tìm người dùng
            $user = User::where('email', $request->email)
                ->where('type_user', 1) // Kiểm tra type_user = 1
                ->where('is_active', 1) // Kiểm tra tài khoản là active
                ->first();

            if ($user) {
                // Tạo mật khẩu mới ngẫu nhiên (8 ký tự)
                $newPassword = Str::random(8);

                // Cập nhật mật khẩu mới cho người dùng
                $user->password = Hash::make($newPassword); // Mã hóa mật khẩu mới
                $user->save();

                // Gửi email thông báo cho người dùng
                $this->sendPasswordEmail($user, $newPassword);

                return back()->with('Gửi mail thành công');
            } else {
                // Nếu không tìm thấy tài khoản hoặc tài khoản bị khóa
                return back()->with( 'Tài khoản không tồn tại hoặc đã bị khóa');
            }
        } catch (\Exception $e) {
            // Log lỗi vào file log để phục vụ việc debug
            Log::error('Lỗi khi gửi email mật khẩu mới: ' . $e->getMessage(), [
                'email' => $request->email, // Ghi lại email để debug
                'exception' => $e->getTraceAsString()
            ]);

            // Trả lại thông báo lỗi cho người dùng
            return back()->withErrors(['error' => 'Đã xảy ra lỗi, vui lòng thử lại sau.']);
        }
    }

    // Hàm gửi email thông báo mật khẩu mới
    public function sendPasswordEmail($user, $newPassword)
    {
        $data = [
            'user' => $user,
            'newPassword' => $newPassword,
        ];

        // Gửi email sử dụng view
        Mail::send('mail.sendMailPass', $data, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Mật khẩu mới của bạn');
        });
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

    public function showChangePasswordForm()
    {
        return view('admin.auth.change-password');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('admin.index')->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Throwable $th) {
            Log::error('Đổi mật khẩu thất bại', ['error' => $th->getMessage()]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.']);
        }
    }
}
