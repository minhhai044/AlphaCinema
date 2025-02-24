<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;

use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
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
                "data" => $users,                    // Danh sách người dùng
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function signUp(UserRequest $UserRequest)
    {
        try {
            $data = $UserRequest->validated();
            $data['type_user'] = 0;

            $user = $this->userService->storeUser($data);

            $token = $user->createToken('UserToken')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'success', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function signIn(UserRequest $userRequest)
    {
        try {
            // Validate dữ liệu đầu vào
            $data = $userRequest->validated();

            // Lấy user theo email
            $user = $this->userService->getUserApi($data['email']);

            // Kiểm tra nếu user không tồn tại
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email không tồn tại'
                ], Response::HTTP_NOT_FOUND);
            }

            // Kiểm tra mật khẩu (So sánh password hash)
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mật khẩu không đúng'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Tạo token xác thực
            $token = $user->createToken('UserToken')->plainTextToken;

            // Tạo cookie chứa token (thời gian sống 24h - 1440 phút)
            $cookie = cookie('user_token', $token, 1440);

            // Trả về phản hồi JSON thành công
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type_user' => $user->type_user
                    ],
                    'token' => $token,
                    'cookie' => $cookie
                ]
            ], Response::HTTP_OK)->withCookie($cookie);
        } catch (\Throwable $th) {
            // Ghi log chi tiết lỗi
            Log::error('Lỗi đăng nhập: ' . $th->getMessage(), [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);

            // Trả về lỗi JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
