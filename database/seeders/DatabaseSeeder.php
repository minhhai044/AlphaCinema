<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Rank;
use App\Models\Room;
use App\Models\Seat_template;
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
        /**
         * Seeder Branch
         */
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
        /**
         * Seeder Cinema
         */
        $cinemas = [
            'Hà Đông',
            'Mỹ Đình',
            'Sầm sơn',
            'Nga Sơn',
            'Kiến Xương',
            'Quất Lâm',
            'Từ Sơn',
            'Quỳnh Phụ',
            'Đài bắc',
            'Trung hoa',
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
        /**
         * Seeder Users
         */
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
            ['name' => 'Bỏng ngô thường', 'img_thumbnail' => 'foodImages/ExZZzbTWCffHEbC9hreNjbxgMJlc0WS1zPZXkDsK.png', 'type' => 'Đồ ăn', 'price' => 80000, 'description' => 'Bắp rang bơ thường', 'is_active' => 1],
            ['name' => 'Nước Cocacola', 'img_thumbnail' => 'foodImages/0DDHnitdJcLWaqn30DggqUYQkZZtrjGoWmnFioP7.png', 'type' => 'Đồ uống', 'price' => 30000, 'description' => 'Nước giải khát có ga', 'is_active' => 1],
            ['name' => 'Snack vị bí đỏ ', 'img_thumbnail' => 'foodImages/eBbuAnLlHAeqrlORz6nWUe1eNAplcaVMjDXvliFo.png', 'type' => 'Đồ ăn', 'price' => 15000, 'description' => 'Đồ ăn vặt', 'is_active' => 1],
            ['name' => 'Cốc uống nước', 'img_thumbnail' => 'foodImages/XeLClFZXZzzxHVel8OphU2wI8uXeKyI4XSXkN8Ng.png', 'type' => 'Khác', 'price' => 90000, 'description' => 'Ly Cầu vồng', 'is_active' => 1],
            ['name' => 'Khoai tây chiên', 'img_thumbnail' => 'foodImages/4cc6B1xM2cR0cPcdRZPbNODeHpENe1zJka26ODbU.png', 'type' => 'Đồ ăn', 'price' => 30000, 'description' => 'Khoai tây chiên giòn rụm', 'is_active' => 1],
            ['name' => 'Trà sữa', 'img_thumbnail' => 'foodImages/w3J9NRrcc0HSGrEwXigQ0vyK3LzmdBpF9YO6Q3Em.png', 'type' => 'Đồ uống', 'price' => 40000, 'description' => 'Trà sữa trân châu', 'is_active' => 1],
            ['name' => 'Nước lọc', 'img_thumbnail' => 'foodImages/3Tyn0ao42FNo4op6VyyDWaZzyI8JwizwiG1kKlAc.png', 'type' => 'Đồ uống', 'price' => 10000, 'description' => 'Nước suối tinh khiết', 'is_active' => 1],
            ['name' => 'Xúc xích', 'img_thumbnail' => 'foodImages/9VSxWJuIkw0YGNqdY8qpdUaGs4PAHs5AlaOGadpG.png', 'type' => 'Đồ ăn', 'price' => 20000, 'description' => 'Xúc xích chiên giòn', 'is_active' => 1],
            ['name' => 'Bánh ngọt', 'img_thumbnail' => 'foodImages/Hpy6jIoYMX6pFVL41N5L5e20y081dmx1fQyZJhuY.png', 'type' => 'Khác', 'price' => 25000, 'description' => 'Bánh kem ngọt ngào', 'is_active' => 1],
            ['name' => 'Sinh tố bơ', 'img_thumbnail' => 'foodImages/9rx7OY31Z4rXHkeBRxnvDEaoco9sGZtOLtG45a6L.png', 'type' => 'Đồ uống', 'price' => 50000, 'description' => 'Sinh tố bơ béo ngậy', 'is_active' => 1],
        ];

        DB::table('food')->insert($foods);

        // Seed combos table
        $combos = [
            [
                'name' => 'Combo ngọt ngào',
                'img_thumbnail' => 'comboImages/QpaY6X34CWEZuhn1danLRsxUe0wgIDHExU2OHvqb.png',
                'price' => 140000,
                'price_sale' => 90000,
                'description' => 'TIẾT KIỆM 50K!!! Gồm: 1 Bắp + 2 Nước có gaz',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 1],
                    ['food_id' => 2, 'quantity' => 2],
                ],
            ],
            [
                'name' => 'Combo Gia đình',
                'img_thumbnail' => 'comboImages/RQ8UPqOcPraPy4F4fSG3T4kD6GmXSan2cR6V9Ety.png',
                'price' => 310000,
                'price_sale' => 210000,
                'description' => 'TIẾT KIỆM 100K!!! Gồm: 2 Bắp  + 4 Nước có gaz  + 1 khoai tây chiên',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 2],
                    ['food_id' => 2, 'quantity' => 4],
                    ['food_id' => 5, 'quantity' => 1],
                ],
            ],
            [
                'name' => 'Combo See Mê - Cầu Vồng',
                'img_thumbnail' => 'comboImages/27VjNN6H1jLK5xmU9Km7fl7IUj0xLI579M8Q8YH1.png',
                'price' => 170000,
                'price_sale' => 114000,
                'description' => 'TIẾT KIỆM 56K!!! Sỡ hữu ngay: 1 Ly Cầu Vồng kèm nước + 1 Bắp (69oz)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'combo_food' => [
                    ['food_id' => 1, 'quantity' => 1],
                    ['food_id' => 4, 'quantity' => 1],
                ],
            ],
            
        ];

        foreach ($combos as $combo) {
            $comboId = DB::table('combos')->insertGetId([
                'name' => $combo['name'],
                'img_thumbnail' => $combo['img_thumbnail'],
                'price' => $combo['price'],
                'price_sale' => $combo['price_sale'],
                'description' => $combo['description'],
                'is_active' => $combo['is_active'],
                'created_at' => $combo['created_at'],
                'updated_at' => $combo['updated_at'],
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
                'name' => '2D',
                'surcharge' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '3D ',
                'surcharge' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '4D ',
                'surcharge' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('type_seats')->insert([
            [
                'name' => 'Ghế thường',
                'price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế VIP',
                'price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghế đôi',
                'price' => 15000,
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
                'total_spent' => 3000000,
                'ticket_percentage' => 7,
                'combo_percentage' => 5,
                'feedback_percentage' => 3,
                'is_default' => 0,
            ],
            [
                'name' => 'Platinum',
                'total_spent' => 7000000,
                'ticket_percentage' => 10,
                'feedback_percentage' => 5,
                'combo_percentage' => 7,
                'is_default' => 0,
            ],
            [
                'name' => 'Diamond',
                'total_spent' => 10000000,
                'ticket_percentage' => 15,
                'feedback_percentage' => 7,
                'combo_percentage' => 10,
                'is_default' => 0,
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
        /**
         * Day
         */
        $days = [
            ['Ngày thường', 0],
            ['Ngày cuối tuần', 3000],
            ['Ngày đặc biệt', 5000]
        ];
        foreach ($days as $day) {
            Day::create([
                'name' => $day[0],
                'day_surcharge' => $day[1],
            ]);
        }
        /**
         * Seat Template
         */
        $seatTemplates = [
            [
                'name' => 'Mẫu Full 12x12',
                'matrix' => 1,
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":13,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":14,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":15,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":16,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":17,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":18,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":19,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":20,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":21,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":22,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":23,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":24,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":25,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":26,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":27,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":28,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":29,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":30,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":31,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":32,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":33,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":34,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":35,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":36,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":37,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":38,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":39,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":40,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":41,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":42,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":43,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":44,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":45,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":46,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":47,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":48,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":49,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":50,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":51,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":52,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":53,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":54,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":55,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":56,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":57,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":58,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":59,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":60,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":73,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":74,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":75,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":76,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":77,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":78,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":79,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":80,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":81,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":82,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":83,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":84,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":97,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":98,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":99,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":100,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":101,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":102,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":103,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":104,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":105,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":106,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":107,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":108,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":109,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":110,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":111,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":112,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":113,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":114,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":115,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":116,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":117,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":118,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":119,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":120,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":121,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":123,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":125,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":127,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":129,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":131,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":133,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":135,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":137,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":139,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":141,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":143,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10}]',
                'row_regular' => 4,
                'row_vip' => 6,
                'row_double' => 2,
                'description' => 'Mẫu sơ đồ ghế 1',
                'is_active' => 1,
                'is_publish' => 1,
            ],
            [
                'name' => 'Mẫu Full 13x13',
                'matrix' => 2,
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":14,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":15,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":16,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":17,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":18,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":19,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":20,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":21,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":22,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":23,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":24,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":25,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":26,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":27,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":28,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":29,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":30,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":31,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":32,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":33,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":34,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":35,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":36,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":37,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":38,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":39,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":40,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":41,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":42,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":43,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":44,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":45,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":46,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":47,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":48,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":49,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":50,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":51,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":52,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":53,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":54,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":55,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":56,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":57,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":58,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":59,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":60,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":61,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":62,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":63,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":64,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":65,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":66,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":67,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":68,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":69,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":70,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":71,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":72,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":73,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":74,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":75,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":76,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":77,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":78,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":79,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":80,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":81,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":82,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":83,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":84,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":85,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":86,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":87,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":88,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":89,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":90,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":91,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":92,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":93,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":94,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":95,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":96,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":97,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":98,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":99,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":100,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":101,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":102,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":103,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":104,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":105,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":106,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":107,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":108,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":109,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":110,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":111,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":112,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":113,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":114,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":115,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":116,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":117,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":118,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":119,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":120,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":121,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":122,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":123,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":124,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":125,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":126,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":127,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":128,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":129,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":130,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":131,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":132,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":133,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":134,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":135,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":136,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":137,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":138,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":139,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":140,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":141,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":142,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":143,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":144,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":146,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":148,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":150,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":152,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":154,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":157,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":159,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":161,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":163,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":165,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":167,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10}]',
                'row_regular' => 4,
                'row_vip' => 7,
                'row_double' => 2,
                'description' => 'Mẫu sơ đồ ghế 2',
                'is_active' => 1,
                'is_publish' => 1,
            ],
            [
                'name' => 'Mẫu Full 14x14',
                'matrix' => 3,
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":14,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":15,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":16,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":17,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":18,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":19,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":20,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":21,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":22,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":23,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":24,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":25,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":26,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":27,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":28,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":29,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":30,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":31,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":32,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":33,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":34,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":35,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":36,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":37,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":38,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":39,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":40,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":41,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":42,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":43,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":44,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":45,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":46,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":47,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":48,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":49,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":50,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":51,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":52,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":53,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":54,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":55,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":56,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":57,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":58,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":59,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":60,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":61,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":62,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":63,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":64,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":65,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":66,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":67,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":68,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":69,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":70,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":71,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":72,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":73,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":74,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":75,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":76,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":77,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":78,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":79,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":80,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":81,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":82,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":83,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":84,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":97,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":98,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":99,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":100,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":101,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":102,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":103,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":104,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":105,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":106,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":107,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":108,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":109,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":110,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":111,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":112,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":113,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":114,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":115,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":116,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":117,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":118,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":119,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":120,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":121,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":122,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":123,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":124,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":125,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":126,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":127,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":128,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":129,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":130,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":131,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":132,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":133,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":134,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":135,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":136,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":137,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":138,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":139,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":140,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":141,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":142,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":143,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":144,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":145,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":146,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":147,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":148,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":149,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":150,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":151,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":152,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":153,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":154,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":155,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":156,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":157,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":158,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":159,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":160,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":161,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":162,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":163,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":164,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":165,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":166,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":167,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":168,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":169,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":171,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":173,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":175,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":177,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":179,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":181,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":183,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":185,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":187,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":189,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":191,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":193,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":195,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10}]',
                'row_regular' => 4,
                'row_vip' => 8,
                'row_double' => 2,
                'description' => 'Mẫu sơ đồ ghế 3',
                'is_active' => 1,
                'is_publish' => 1,
            ],
            [
                'name' => 'Mẫu Full 15x15',
                'matrix' => 4,
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":14,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":15,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":16,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":17,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":18,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":19,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":20,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":21,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":22,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":23,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":24,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":25,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":26,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":27,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":28,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":29,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":30,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":31,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":32,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":33,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":34,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":35,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":36,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":37,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":38,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":39,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":40,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":41,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":42,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":43,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":44,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":45,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":46,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":47,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":48,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":49,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":50,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":51,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":52,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":53,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":54,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":55,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":56,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":57,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":58,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":59,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":60,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":73,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":74,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":75,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":76,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":77,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":78,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":79,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":80,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":81,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":82,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":83,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":84,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":85,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":86,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":87,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":88,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":89,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":90,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":91,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":92,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":93,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":94,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":95,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":96,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":97,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":98,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":99,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":100,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":101,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":102,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":103,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":104,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":105,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":106,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":107,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":108,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":109,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":110,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":111,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":112,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":113,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":114,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":115,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":116,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":117,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":118,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":119,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":120,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":121,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":122,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":123,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":124,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":125,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":126,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":127,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":128,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":129,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":130,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":131,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":132,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":133,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":134,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":135,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":136,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":137,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":138,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":139,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":140,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":141,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":142,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":143,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":144,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":145,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":146,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":147,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":148,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":149,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":150,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":151,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":152,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":153,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":154,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":155,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":156,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":157,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":158,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":159,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":160,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":161,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":162,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":163,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":164,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":165,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":166,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":167,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":168,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":169,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":170,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":171,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":172,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":173,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":174,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":175,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":176,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":177,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":178,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":179,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":180,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":181,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":182,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":183,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":184,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":185,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":186,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":187,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":188,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":189,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":190,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":191,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":192,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":193,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":194,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":195,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":196,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":198,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":200,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":202,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":204,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":206,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":208,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":211,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":213,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":215,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":217,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":219,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":221,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":223,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":10}]',
                'row_regular' => 4,
                'row_vip' => 9,
                'row_double' => 2,
                'description' => 'Mẫu sơ đồ ghế 4',
                'is_active' => 1,
                'is_publish' => 1,
            ]
        ];
        foreach ($seatTemplates as $value) {
            Seat_template::create($value);
        }

        $dataTemplateRoom = '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":13,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":14,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":15,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":16,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":17,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":18,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":19,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":20,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":21,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":22,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":23,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":24,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":25,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":26,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":27,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":28,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":29,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":30,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":31,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":32,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":33,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":34,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":35,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":36,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":37,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":38,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":39,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":40,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":41,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":42,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":43,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":44,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":45,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":46,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":47,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":48,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":10},{"id":49,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":50,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":51,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":52,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":53,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":54,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":55,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":56,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":57,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":58,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":59,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":60,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":73,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":74,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":75,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":76,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":77,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":78,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":79,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":80,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":81,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":82,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":83,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":84,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":97,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":98,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":99,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":100,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":101,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":102,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":103,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":104,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":105,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":106,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":107,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":108,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":109,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":110,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":111,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":112,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":113,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":114,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":115,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":116,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":117,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":118,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":119,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":120,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":10},{"id":121,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":123,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":125,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":127,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":129,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":131,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":133,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":135,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":137,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":139,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":141,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10},{"id":143,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":10}]';

        function autoShorten($location)
        {
            $words = explode(' ', $location);
            $initials = '';

            foreach ($words as $word) {
                $initials .= mb_substr($word, 0, 1);
            }

            return strtoupper($initials);
        }
        function getShortName($location)
        {
            $location = trim($location);
            return strtoupper(mb_substr($location, 0, 1));
        }
        $branchs = Branch::query()->get();
        $cinemas = Cinema::query()->get();
        foreach ($branchs as $branch) {
            foreach ($cinemas as $cinema) {
                if ($cinema->branch_id != $branch->id) {
                    continue;
                }
                Room::create([
                    'branch_id' => $branch->id,
                    'cinema_id' => $cinema->id,
                    'seat_template_id' => 1,
                    'type_room_id' => rand(1, 3),
                    'matrix_colume' => 12,
                    'name' => autoShorten($branch->name) . '_' . getShortName($cinema->name) .  rand(100, 999),
                    'seat_structure' => $dataTemplateRoom,
                    'description' => 'Phòng chiếu',
                    'is_active' => 1,
                    'is_publish' => 1,
                ]);
            }
        }
        foreach ($branchs as $branch) {
            foreach ($cinemas as $cinema) {
                if ($cinema->branch_id != $branch->id) {
                    continue;
                }
                Room::create([
                    'branch_id' => $branch->id,
                    'cinema_id' => $cinema->id,
                    'seat_template_id' => 1,
                    'type_room_id' => rand(1, 3),
                    'matrix_colume' => 12,
                    'name' => autoShorten($branch->name) . '_' . getShortName($cinema->name) .  rand(100, 999),
                    'seat_structure' => $dataTemplateRoom,
                    'description' => 'Phòng chiếu',
                    'is_active' => 1,
                    'is_publish' => 1,
                ]);
            }
        }
    }
}
