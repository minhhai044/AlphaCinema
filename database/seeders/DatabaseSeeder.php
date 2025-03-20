<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SebastianBergmann\Type\NullType;
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý chi nhánh',
                'avatar' => '',
                'phone' => '0987654345',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlcnHN@gmail.com',
                'password' => Hash::make('qlcnHN@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'branch_id' => 1,
                'cinema_id' => null,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
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
            'Áp dụng vouchers',
            'Sửa áp dụng vouchers',
            'Xóa áp dụng vouchers',
            'Danh sách hạn mức',
            'Sửa hạn mức',

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
            'Thẻ thành viên',
            'Danh sách ngày',
            'Sửa ngày',
        ];

        // Tạo các quyền từ danh sách
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'System Admin',
            'Quản lý chi nhánh',
            'Quản lý cơ sở',
            'Nhân viên'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        $adminRole = Role::findByName('System Admin');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findByName('Quản lý chi nhánh');
        $managerRole->givePermissionTo([
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
            'Sửa giá',
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
            'Sửa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Cấu hình website',
            'Danh sách thống kê',
            'Thẻ thành viên'
        ]);
        $managerRole = Role::findByName('Quản lý cơ sở');
        $managerRole->givePermissionTo([
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',
            'Thêm mẫu sơ đồ ghế',
            'Sửa mẫu sơ đồ ghế',
            'Danh sách phim',
            'Thêm phim',
            'Sửa phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Thêm suất chiếu',
            'Sửa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
            'Danh sách đồ ăn',
            'Thêm đồ ăn',
            'Sửa đồ ăn',
            'Danh sách combo',
            'Thêm combo',
            'Sửa combo',
            'Danh sách vouchers',
            'Thêm vouchers',
            'Sửa vouchers',
            'Danh sách giá',
            'Danh sách bài viết',
            'Thêm bài viết',
            'Xem chi tiết bài viết',
            'Danh sách liên hệ',
            'Sửa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Danh sách thống kê',
        ]);

        $managerRole = Role::findByName('Nhân viên');
        $managerRole->givePermissionTo([
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
        ]);


        $user = User::find(1);
        $user->assignRole('System Admin');

        $user = User::find(7);
        $user->assignRole('Quản lý cơ sở', 'Nhân viên',);
        $user = User::find(8);
        $user->assignRole('Quản lý cơ sở', 'Nhân viên');
        $user = User::find(9);
        $user->assignRole('Quản lý chi nhánh');

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

        DB::table('type_rooms')->insert([
            [
                'name' => 'Phòng 2D',
                'surcharge' => 50000, // Giá mặc định 50k50k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phòng 3D ',
                'surcharge' => 100000, // Giá mặc định 100k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phòng 4D ',
                'surcharge' => 150000, // Giá mặc định 150k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('type_seats')->insert([
            [
                'name' => 'Ghế thường',
                'price' => 5000, // Giá mặc định 50k50k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế VIP',
                'price' => 10000, // Giá mặc định 100k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế đôi',
                'price' => 15000, // Giá mặc định 150k 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $ranks = [
            [
                'name' => 'Member',
                'total_spent' => 0,
                'ticket_percentage' => 0,
                'combo_percentage' => 0,
                'feedback_percentage' => 0,
                'is_default' => 1,
            ],
            [
                'name' => 'Gold',
                'total_spent' => 1000000,
                'ticket_percentage' => 7,
                'combo_percentage' => 5,
                'feedback_percentage' => 3,
                'is_default' => 0,
            ],
            [
                'name' => 'Platinum',
                'total_spent' => 3000000,
                'ticket_percentage' => 10,
                'feedback_percentage' => 5,
                'combo_percentage' => 7,
                'is_default' => 0,
            ],
            [
                'name' => 'Diamond',
                'total_spent' => 5000000,
                'ticket_percentage' => 15,
                'feedback_percentage' => 7,
                'combo_percentage' => 10,
                'is_default' => 0,
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}
