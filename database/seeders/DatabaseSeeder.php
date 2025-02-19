<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cinema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(50)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // for ($i = 0; $i < 20; $i++) {
        //     Cinema::query()->create([
        //         'name' => Str::random(10),
        //         'address' => Str::random(10),
        //         'image' => Str::random(10)
        //     ]);
        // }

        // $permissions = [
        //     'Danh sách chi nhánh',
        //     'Thêm chi nhánh',
        //     'Sửa chi nhánh',
        //     'Xóa chi nhánh',
        //     'Danh sách rạp',
        //     'Thêm rạp',
        //     'Sửa rạp',
        //     'Xóa rạp',
        //     'Danh sách phòng chiếu',
        //     'Thêm phòng chiếu',
        //     'Sửa phòng chiếu',
        //     'Xóa phòng chiếu',
        //     'Xem chi tiết phòng chiếu',
        //     'Danh sách mẫu sơ đồ ghế',
        //     'Thêm mẫu sơ đồ ghế',
        //     'Sửa mẫu sơ đồ ghế',
        //     'Xóa mẫu sơ đồ ghế',
        //     'Danh sách phim',
        //     'Thêm phim',
        //     'Sửa phim',
        //     'Xóa phim',
        //     'Xem chi tiết phim',
        //     'Danh sách suất chiếu',
        //     'Thêm suất chiếu',
        //     'Sửa suất chiếu',
        //     'Xóa suất chiếu',
        //     'Xem chi tiết suất chiếu',
        //     'Danh sách hóa đơn',
        //     'Quét hóa đơn',

        //     'Xem chi tiết hóa đơn',

        //     'Danh sách đồ ăn',
        //     'Thêm đồ ăn',
        //     'Sửa đồ ăn',
        //     'Xóa đồ ăn',
        //     'Danh sách combo',
        //     'Thêm combo',
        //     'Sửa combo',
        //     'Xóa combo',
        //     'Danh sách vouchers',
        //     'Thêm vouchers',
        //     'Sửa vouchers',
        //     'Xóa vouchers',
        //     'Danh sách thanh toán',
        //     'Thêm thanh toán',
        //     'Sửa thanh toán',
        //     'Xóa thanh toán',
        //     'Danh sách giá',
        //     // 'Thêm giá',
        //     'Sửa giá',
        //     // 'Xóa giá',
        //     'Danh sách bài viết',
        //     'Thêm bài viết',
        //     'Sửa bài viết',
        //     'Xóa bài viết',
        //     'Xem chi tiết bài viết',
        //     'Danh sách slideshows',
        //     'Thêm slideshows',
        //     'Sửa slideshows',
        //     'Xóa slideshows',
        //     'Danh sách liên hệ',
        //     // 'Thêm liên hệ',
        //     'Sửa liên hệ',
        //     // 'Xóa liên hệ',
        //     'Danh sách tài khoản',
        //     'Thêm tài khoản',
        //     'Sửa tài khoản',
        //     'Xóa tài khoản',
        //     'Cấu hình website',
        //     'Danh sách thống kê',
        //     'Thẻ thành viên'

        // ];

        // // Tạo các quyền từ danh sách
        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        $adminRole = Role::findByName('System Admin');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findByName('Quản lý cơ sở');
        $managerRole->givePermissionTo([
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xóa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',

            'Danh sách phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Thêm suất chiếu',
            'Sửa suất chiếu',
            'Xóa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
            'Danh sách combo',
            'Danh sách thống kê',
        ]);

        $managerRole = Role::findByName('Nhân viên');
        $managerRole->givePermissionTo([
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
        ]);
    }
}
