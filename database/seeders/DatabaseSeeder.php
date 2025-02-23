<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\text;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $branches = [
            'Hà nội',
            'Thanh Hóa',
            'Thái Bình',
            'Nam Định',
            'Bắc Ninh'
        ];
        foreach ($branches as $branch) {
            Branch::create([
                'name' => $branch,
                'slug' => Str::slug($branch),
                'surcharge' => [10000, 20000][array_rand([10000, 20000])],
                'is_active' => 1,
            ]);
        }

        $cinemas = [
            'Hà Đông',
            'Mỹ Đình',
            'Cầu Giấy',
            'Nga Sơn',
            'Kiến Xương',
            'Quất Lâm',
            'Từ Sơn',
            'Quỳnh Phụ',
        ];
        $branchId = 1;
        $counter = 0;
        foreach ($cinemas as $cinema) {
            DB::table('cinemas')->insert([
                'branch_id' => $branchId,
                'name' => $cinema,
                'slug' => Str::slug($cinema),
                'address' => $cinema . ', ' . fake()->address(),
                'description' => $cinema . ', ' . fake()->text(),
                'is_active' => 1,
            ]);
            $counter++;

            if ($counter % 2 == 0) {
                $branchId++;
            }
        }

        $users = [
            [
                'name' => 'System Admin',
                'avatar' => '',
                'phone' => '0987654321',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Đặng Văn Sơn',
                'avatar' => '',
                'phone' => '0987654322',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'dangvanson@gmail.com',
                'password' => Hash::make('dangvanson@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Trần Minh Hải',
                'avatar' => '',
                'phone' => '0987654323',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'tranminhhai@gmail.com',
                'password' => Hash::make('tranminhhai@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Phùng Quang Huy',
                'avatar' => '',
                'phone' => '0987654324',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'phungquanghuy@gmail.com',
                'password' => Hash::make('phungquanghuy@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Nguyễn Đức Tùng Lâm',
                'avatar' => '',
                'phone' => '0987654325',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'nguyenductunglam@gmail.com',
                'password' => Hash::make('nguyenductunglam@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Nguyễn Hoàng Anh',
                'avatar' => '',
                'phone' => '0987654326',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'nguyenhoanganh@gmail.com',
                'password' => Hash::make('nguyenhoanganh@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Vũ Nhật Quỳnh',
                'avatar' => '',
                'phone' => '0987654327',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'vunhatquynh@gmail.com',
                'password' => Hash::make('vunhatquynh@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
            [
                'name' => 'Hoa Hương Quỳnh',
                'avatar' => '',
                'phone' => '0987654328',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'hoahuongquynh@gmail.com',
                'password' => Hash::make('hoahuongquynh@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'cinema_id' => null,
            ],
        ];

        User::insert($users);

        $permissions = [
            'Danh sách chi nhánh',
            'Thêm chi nhánh',
            'Sửa chi nhánh',
            'Xóa chi nhánh',
            'Danh sách rạp',
            'Thêm rạp',
            'Sửa rạp',
            'Xóa rạp',
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xóa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',
            'Thêm mẫu sơ đồ ghế',
            'Sửa mẫu sơ đồ ghế',
            'Xóa mẫu sơ đồ ghế',
            'Danh sách phim',
            'Thêm phim',
            'Sửa phim',
            'Xóa phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Thêm suất chiếu',
            'Sửa suất chiếu',
            'Xóa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',

            'Xem chi tiết hóa đơn',

            'Danh sách đồ ăn',
            'Thêm đồ ăn',
            'Sửa đồ ăn',
            'Xóa đồ ăn',
            'Danh sách combo',
            'Thêm combo',
            'Sửa combo',
            'Xóa combo',
            'Danh sách vouchers',
            'Thêm vouchers',
            'Sửa vouchers',
            'Xóa vouchers',
            'Danh sách thanh toán',
            'Thêm thanh toán',
            'Sửa thanh toán',
            'Xóa thanh toán',
            'Danh sách giá',
            // 'Thêm giá',
            'Sửa giá',
            // 'Xóa giá',
            'Danh sách bài viết',
            'Thêm bài viết',
            'Sửa bài viết',
            'Xóa bài viết',
            'Xem chi tiết bài viết',
            'Danh sách slideshows',
            'Thêm slideshows',
            'Sửa slideshows',
            'Xóa slideshows',
            'Danh sách liên hệ',
            // 'Thêm liên hệ',
            'Sửa liên hệ',
            // 'Xóa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Cấu hình website',
            'Danh sách thống kê',
            'Thẻ thành viên'

        ];

        // for ($i = 0; $i < 20; $i++) {
        //     Cinema::query()->create([
        //         'name' => Str::random(10),
        //         'address' => Str::random(10),
        //         'image' => Str::random(10)
        //     ]);
        // }
        $roles = [
            'System Admin',
            'Quản lý cơ sở',
            'Nhân viên'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        // Tạo các quyền từ danh sách
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        $adminRole = Role::findByName('System Admin');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findByName('Quản lý cơ sở');

        $managerRole = Role::findByName('Nhân viên');
        $managerRole->givePermissionTo([
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
        ]);

        $user = User::find(1);
        $user->assignRole('System Admin');

        $user = User::find(7);
        $user->assignRole('Quản lý cơ sở','Nhân viên', );
        $user = User::find(8);
        $user->assignRole('Quản lý cơ sở','Nhân viên');

        $user = User::find(2);
        $user->assignRole('Quản lý cơ sở');
        $user = User::find(3);
        $user->assignRole('Quản lý cơ sở');
        $user = User::find(4);
        $user->assignRole('Quản lý cơ sở');
        $user = User::find(5);
        $user->assignRole('Quản lý cơ sở');
        $user = User::find(6);
        $user->assignRole('Quản lý cơ sở');

    }
}
