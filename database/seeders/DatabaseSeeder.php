<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cinema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        \App\Models\User::factory(50)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        for ($i = 0; $i < 20; $i++) {
            Cinema::query()->create([
                'name' => Str::random(10),
                'address' => Str::random(10),
                'image' => Str::random(10)
            ]);
        }
        $roles = [
            'System Admin',
            'Quản lý cơ sở',
            'Nhân viên'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }
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
            'Thêm giá',
            'Sửa giá',
            'Xóa giá',
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
            'Thêm liên hệ',
            'Sửa liên hệ',
            'Xóa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Cấu hình website',
            'Danh sách thống kê',
            'Thẻ thành viên'

        ];


        // Tạo các quyền từ danh sách
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $roles = [
            'System Admin',
            'Quản lý cơ sở',
            'Nhân viên'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

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


         // Seed food table
         $foods = [
            ['name' => 'Burger', 'img_thumbnail' => 'images/burger.jpg', 'type' => 'Đồ ăn', 'price' => 50000, 'description' => 'Burger bò thơm ngon', 'is_active' => 1],
            ['name' => 'Pizza', 'img_thumbnail' => 'images/pizza.jpg', 'type' => 'Đồ ăn', 'price' => 120000, 'description' => 'Pizza hải sản', 'is_active' => 1],
            ['name' => 'Coca Cola', 'img_thumbnail' => 'images/coca.jpg', 'type' => 'Đồ uống', 'price' => 15000, 'description' => 'Nước giải khát có ga', 'is_active' => 1],
            ['name' => 'Pepsi', 'img_thumbnail' => 'images/pepsi.jpg', 'type' => 'Đồ uống', 'price' => 15000, 'description' => 'Nước ngọt Pepsi', 'is_active' => 1],
            ['name' => 'Khoai tây chiên', 'img_thumbnail' => 'images/fries.jpg', 'type' => 'Đồ ăn', 'price' => 30000, 'description' => 'Khoai tây chiên giòn rụm', 'is_active' => 1],
            ['name' => 'Trà sữa', 'img_thumbnail' => 'images/milktea.jpg', 'type' => 'Đồ uống', 'price' => 40000, 'description' => 'Trà sữa trân châu', 'is_active' => 1],
            ['name' => 'Nước lọc', 'img_thumbnail' => 'images/water.jpg', 'type' => 'Đồ uống', 'price' => 10000, 'description' => 'Nước suối tinh khiết', 'is_active' => 1],
            ['name' => 'Xúc xích', 'img_thumbnail' => 'images/sausage.jpg', 'type' => 'Đồ ăn', 'price' => 20000, 'description' => 'Xúc xích chiên giòn', 'is_active' => 1],
            ['name' => 'Bánh ngọt', 'img_thumbnail' => 'images/cake.jpg', 'type' => 'Khác', 'price' => 25000, 'description' => 'Bánh kem ngọt ngào', 'is_active' => 1],
            ['name' => 'Sinh tố bơ', 'img_thumbnail' => 'images/avocado_smoothie.jpg', 'type' => 'Đồ uống', 'price' => 50000, 'description' => 'Sinh tố bơ béo ngậy', 'is_active' => 1],
        ];

        DB::table('food')->insert($foods);

        // Seed combos table
        $combos = [
            ['name' => 'Combo 1', 'img_thumbnail' => 'comboImages/combo1.png', 'price' => 500000, 'price_sale' => 450000, 'description' => 'Combo tiết kiệm 1', 'is_active' => 1, 'combo_food' => [['food_id' => 1, 'quantity' => 2], ['food_id' => 2, 'quantity' => 3]]],
            ['name' => 'Combo 2', 'img_thumbnail' => 'comboImages/combo2.png', 'price' => 600000, 'price_sale' => 550000, 'description' => 'Combo tiết kiệm 2', 'is_active' => 1, 'combo_food' => [['food_id' => 2, 'quantity' => 2], ['food_id' => 3, 'quantity' => 1]]],
            ['name' => 'Combo 3', 'img_thumbnail' => 'comboImages/combo3.png', 'price' => 700000, 'price_sale' => 650000, 'description' => 'Combo tiết kiệm 3', 'is_active' => 1, 'combo_food' => [['food_id' => 3, 'quantity' => 4], ['food_id' => 4, 'quantity' => 2]]],
            ['name' => 'Combo 4', 'img_thumbnail' => 'comboImages/combo4.png', 'price' => 800000, 'price_sale' => 750000, 'description' => 'Combo tiết kiệm 4', 'is_active' => 1, 'combo_food' => [['food_id' => 4, 'quantity' => 3], ['food_id' => 5, 'quantity' => 1]]],
            ['name' => 'Combo 5', 'img_thumbnail' => 'comboImages/combo5.png', 'price' => 900000, 'price_sale' => 850000, 'description' => 'Combo tiết kiệm 5', 'is_active' => 1, 'combo_food' => [['food_id' => 1, 'quantity' => 5], ['food_id' => 5, 'quantity' => 2]]],
        ];

        foreach ($combos as $combo) {
            $comboId = DB::table('combos')->insertGetId([
                'name' => $combo['name'],
                'img_thumbnail' => $combo['img_thumbnail'],
                'price' => $combo['price'],
                'price_sale' => $combo['price_sale'],
                'description' => $combo['description'],
                'is_active' => $combo['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($combo['combo_food'] as $food) {
                DB::table('combo_food')->insert([
                    'combo_id' => $comboId,
                    'food_id' => $food['food_id'],
                    'quantity' => $food['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Seed slideshows table
        $slideshows = [
            ['img_thumbnail' => 'slides/slide1.jpg', 'description' => 'Khuyến mãi lớn tuần này', 'is_active' => 1],
            ['img_thumbnail' => 'slides/slide2.jpg', 'description' => 'Phim mới ra mắt', 'is_active' => 1],
            ['img_thumbnail' => 'slides/slide3.jpg', 'description' => 'Ưu đãi thành viên', 'is_active' => 1],
        ];

        DB::table('slideshows')->insert($slideshows);

    }
}
