<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Rank;
use App\Models\Room;
use App\Models\RoomChat;
use App\Models\Seat_template;
use App\Models\User;
use App\Models\Vat;
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
        Vat::create([
            'rate' => 8,
            'description' => 'Thuế giá trị gia tăng'
        ]);

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
            'Quỳnh Phụ',
            'Nam Phong',
            'Xuân Trường',
            'Từ Sơn',
            'Thuận Thành',
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
                'name' => 'Quản lý chi nhánh Hà Nội',
                'avatar' => '',
                'phone' => '0987654322',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlcnHN@gmail.com',
                'password' => Hash::make('qlcnHN@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => 1,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý chi nhánh Thanh Hóa',
                'avatar' => '',
                'phone' => '0987654333',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlcnTH@gmail.com',
                'password' => Hash::make('qlcnTH@gmail.com'),
                'address' => 'Thanh Hóa',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => 2,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý chi nhánh Bắc Ninh',
                'avatar' => '',
                'phone' => '0987654323',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlcnBN@gmail.com',
                'password' => Hash::make('qlcnBN@gmail.com'),
                'address' => 'Bắc Ninh',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => null,
                'branch_id' => 5,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý rạp Mỹ Đình',
                'avatar' => '',
                'phone' => '0987654324',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlrMD@gmail.com',
                'password' => Hash::make('qlrMD@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 2,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý rạp Từ Sơn',
                'avatar' => '',
                'phone' => '0987654325',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlrTS@gmail.com',
                'password' => Hash::make('qlrTS@gmail.com'),
                'address' => 'Bắc Ninh',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 9,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Nhân viên rạp Mỹ Đình',
                'avatar' => '',
                'phone' => '0987654326',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'nvMD@gmail.com',
                'password' => Hash::make('nvMD@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 0,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 2,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Nhân viên rạp Từ Sơn',
                'avatar' => '',
                'phone' => '0987654327',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'nvTS@gmail.com',
                'password' => Hash::make('nvTS@gmail.com'),
                'address' => 'Bắc Ninh',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 9,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý rạp Hà Đông',
                'avatar' => '',
                'phone' => '0987654328',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlrHD@gmail.com',
                'password' => Hash::make('qlrHD@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 1,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Quản lý rạp Nga Sơn',
                'avatar' => '',
                'phone' => '0987654343',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'qlrNS@gmail.com',
                'password' => Hash::make('qlrNS@gmail.com'),
                'address' => 'Thanh Hóa',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'cinema_id' => 4,
                'branch_id' => null,
                'point' => 0,
                'google_id' => null,
            ],
            [
                'name' => 'Nhân viên rạp Hà Đông',
                'avatar' => '',
                'phone' => '0987654345',
                'email_verified_at' => '2025-02-22 19:58:51',
                'email' => 'nvHD@gmail.com',
                'password' => Hash::make('nvHD@gmail.com'),
                'address' => 'Hà Nội',
                'gender' => 1,
                'birthday' => '2000-01-01',
                'type_user' => 1,
                'total_amount' => 0,
                'branch_id' => null,
                'cinema_id' => 1,
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
            // 'Thêm liên hệ',
            // 'Xóa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Cấu hình website',
            'Danh sách thống kê',
            'Thống kê đồ ăn',
            'Thống kê vé',
            'Thống kê phim',
            'Thẻ thành viên',
            'Danh sách ngày',
            'Sửa ngày',
            'Danh sách vai trò',
            'Sửa vai trò',
            'Danh sách loại phòng',
            'Sửa loại phòng',
            'Danh sách loại ghế',
            'Sửa loại ghế',
            'Danh sách áp mã giảm giá',
            'Thêm áp mã giảm giá',
            'Sửa áp mã giảm giá',
            'Xóa áp mã giảm giá',
        ];

        // Tạo các quyền từ danh sách
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'System Admin',
            'Quản lý chi nhánh',
            'Quản lý rạp',
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
            'Danh sách rạp',
            'Thêm rạp',
            'Sửa rạp',
            'Xóa rạp',
            'Danh sách mẫu sơ đồ ghế',
            'Thêm mẫu sơ đồ ghế',
            'Sửa mẫu sơ đồ ghế',
            'Xóa mẫu sơ đồ ghế',
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
            'Danh sách đồ ăn',
            'Thêm đồ ăn',
            'Sửa đồ ăn',
            'Xóa đồ ăn',
            'Danh sách combo',
            'Thêm combo',
            'Sửa combo',
            'Xóa combo',
            'Sửa giá',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Danh sách thống kê',
            'Thống kê đồ ăn',
            'Thống kê vé',
            'Thống kê phim',
        ]);
        $managerRole = Role::findByName('Quản lý rạp');
        $managerRole->givePermissionTo([
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',
            'Thêm mẫu sơ đồ ghế',
            'Sửa mẫu sơ đồ ghế',
            'Danh sách phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Xóa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
            'Danh sách đồ ăn',
            'Danh sách combo',
            'Danh sách bài viết',
            'Thêm bài viết',
            'Xem chi tiết bài viết',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Danh sách thống kê',
            'Thống kê đồ ăn',
            'Thống kê vé',
            'Thống kê phim',
        ]);

        $managerRole = Role::findByName('Nhân viên');
        $managerRole->givePermissionTo([
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
        ]);


        $user = User::find(1);
        $user->assignRole('System Admin');

        // Lấy tất cả các người dùng
        $userRole = User::all();

        foreach ($userRole as $user) {
            // Gán vai trò 'Quản lý chi nhánh' cho người dùng có tên chứa 'Quản lý chi nhánh'
            if (strpos($user->name, 'Quản lý chi nhánh') !== false) {
                $user->assignRole('Quản lý chi nhánh');
            }
            // Gán vai trò 'Quản lý rạp' cho người dùng có tên chứa 'Quản lý rạp'
            elseif (strpos($user->name, 'Quản lý rạp') !== false) {
                $user->assignRole('Quản lý rạp');
            }
            // Gán vai trò 'Nhân viên' cho người dùng có tên chứa 'Nhân viên'
            elseif (strpos($user->name, 'Nhân viên') !== false) {
                $user->assignRole('Nhân viên');
            }
        }

        //Seed Movie
        /**
         * Seeder Movies - 10 phim hoạt hình với Comedy, Horror, Action
         */
        $movies = [
            [
                'name' => 'Trừ Tà Ký: Khởi Nguyên Hắc Ám',
                'slug' => Str::slug('Scooby-Doo and the Haunted Carnival'),
                'category' => 'Nam Doh-hyeong, Choi Han, Kim Yeonwoo',
                'img_thumbnail' => 'movie_images/buqwDjp5BahsZDmcWh3GMIzCaosBL0PuxB9awGRl.jpg',
                'description' => 'Cha sứ Park – một linh mục vốn là bác sĩ đã bị khai trừ sau khi thực hiện những nghi lễ trừ tà mà Giáo hội cho là cực đoan, bởi những người trong Giáo hội từ chối tin rằng những thế lực vô hình đang rình rập các linh hồn vô tội. Những tưởng cha Park an phận giải nghệ, một nhà sư đến từ ngôi chùa bí ẩn cất giữ những bí thuật bất ngờ tìm tới ông với một lời khẩn cầu: Bảo vệ một đứa trẻ tên Joon Hoo khỏi vị sư phụ đã hắc hóa của cậu bé. Cha Park buộc phải đối mặt với bóng ma quá khứ để giải cứu đứa trẻ và ngăn chặn bi kịch lặp lại.',
                'director' => 'Kim Dong-Chul',
                'duration' => '80',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/mFGTtYUjQc8',
                'surcharge' => 10000,
                'movie_versions' => json_encode(['2D']),
                'movie_genres' => json_encode(['Comedy', 'Horror']),
                'is_active' => 1,
                'is_hot' => 1,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nàng Bạch Tuyết',
                'slug' => Str::slug('monster-house'),
                'category' => 'Rachel Zegler, Gal Gadot, Andrew Burnap',
                'img_thumbnail' => 'movie_images/qryykDQMapRdPFzUgdrVlyKhg06hCZmgEQvpbd6D.jpg',
                'description' => 'Phiên bản chuyển thể người đóng của bộ phim hoạt hình Bạch Tuyết và bảy chú lùn của Disney năm 1937.',
                'director' => 'Marc Webb',
                'duration' => '108 ',
                'rating' => 4,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/-L2JQolvb9Y',
                'surcharge' => 15000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Comedy', 'Horror']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quỷ Nhập Tràng',
                'slug' => Str::slug('Quỷ Nhập Tràng'),
                'category' => 'Khả Như, Quang Tuấn,…',
                'img_thumbnail' => 'movie_images/j7oArNn32l64je8JUIVlF0sqrzABUcYklhG5DTKp.png',
                'description' => 'Lấy cảm hứng từ truyền thuyết kinh dị nhất về “người chết sống dậy”, Quỷ Nhập Tràng là câu chuyện được lấy bối cảnh tại một ngôi làng chuyên nghề mai táng, gắn liền với những hoạt động đào mộ, tẩm liệm và chôn cất người chết.',
                'director' => 'Pom Nguyễn',
                'duration' => '122',
                'rating' => 3,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/f2SdcotvKKA',
                'surcharge' => 20000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Comedy', 'Horror']),
                'is_active' => 1,
                'is_hot' => 1,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cô Gái Năm Ấy Chúng Ta Cùng Theo Đuổi',
                'slug' => Str::slug('Cô Gái Năm Ấy Chúng Ta Cùng Theo Đuổi'),
                'category' => 'Jung Jinyoung, Kim Da-Hyun...',
                'img_thumbnail' => 'movie_images/NJxe4SIJ98oxGYrxpwtFJK8XTyOD1L1iAB3kSYze.png',
                'description' => 'You Are the Apple of My Eye khắc họa một cách sống động những khoảnh khắc tươi trẻ của tuổi trẻ vào những năm 2000, những cảm xúc tinh tế của mối tình đầu. Jin-woo và những người bạn của anh ấy đều trải qua những thăng trầm của tuổi mới lớn với những sở thích và rắc rối riêng, lại được kết nối bởi một điều chung: Sun-Ah – cô học sinh mẫu mực, duyên dáng và xinh đẹp. Dù là bạn cùng lớp, nhưng trong mắt họ, Sun-Ah vẫn như một giấc mơ, khó có thể với tới. Bất chấp sự khác biệt về tính cách và thành tích học tập, mối liên kết giữa Jin-woo và Sun-Ah ngày càng bền chặt qua những lần cùng nhau khám phá những khía cạnh khác nhau của nhau. Mối quan hệ ấy lướt qua những cung bậc lãng mạn, ngọt ngào xen lẫn chút đắng cay của thời trung học, chạm đến tận sâu trái tim tuổi trẻ đầy cảm xúc.',
                'director' => 'Cho Young-Myoung',
                'duration' => '102',
                'rating' => 7.0,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/7W919zrrWu8',
                'surcharge' => 15000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Horror', 'Action']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 1,
                'is_publish' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phim Điện Ảnh Ninja Rantaro: Giải Cứu Quân Sư',
                'slug' => Str::slug('Phim Điện Ảnh Ninja Rantaro: Giải Cứu Quân Sư'),
                'category' => 'Mayumi Tanaka, Minami Takayama, Teiyû Ichiryûsai',
                'img_thumbnail' => 'movie_images/Z0k73g6NwocZzldxgmprvLF9GXkYfwZch40VvWMG.jpg',
                'description' => 'Gia đình Addams kỳ quái đối mặt với những tình huống hài hước và rùng rợn trong phiên bản hoạt hình.',
                'director' => ' Ninja Rantaro',
                'duration' => '90',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/XgchU0WWuUQ',
                'surcharge' => 12000,
                'movie_versions' => json_encode(['2D']),
                'movie_genres' => json_encode(['Comedy', 'Horror']),
                'is_active' => 1,
                'is_hot' => 1,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lạc Trôi',
                'slug' => Str::slug('Lạc Trôi'),
                'category' => 'Chú Mèo đen, Cá nhỏ',
                'img_thumbnail' => 'movie_images/wM4FqXRDSmlHaAchaD27OPlFGsGVbVAVR2zQXNkF.jpg',
                'description' => 'Flow là hành trình của một chú mèo dũng cảm sau khi ngôi nhà của chú bị tàn phá bởi một trận lũ lớn. Hợp tác với một chú capybara, một chú vượn cáo, một chú chim và một chú chó để điều hướng một chiếc thuyền tìm kiếm vùng đất khô ráo, chúng phải dựa vào sự tin tưởng, lòng dũng cảm và trí thông minh để sống sót trước những nguy hiểm của một hành tinh mới trên mặt nước.',
                'director' => 'Gints Zilbalodis',
                'duration' => '87',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/vob8AGZM4Wc',
                'surcharge' => 18000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Comedy', 'Horror']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 1,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Love Lies: Yêu Vì Tiền - Điên Vì Tình',
                'slug' => Str::slug('Love Lies: Yêu Vì Tiền - Điên Vì Tình'),
                'category' => 'Sandra Kwan Yue Ng, Michael Tin Fu Cheung, Stephy Tang,...',
                'img_thumbnail' => 'movie_images/W6D51pcjaLKzwAR9auuIhyfuUHW5FVtKGaKg9VJ6.png',
                'description' => 'Máy tạo thức ăn từ mây gây ra hỗn loạn hài hước với các pha hành động bất ngờ.',
                'director' => 'Hồ Miêu Kỵ',
                'duration' => '114 ',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/h0Knp6fNkpE',
                'surcharge' => 10000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Comedy', 'Action']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nghề Siêu Khó Nói',
                'slug' => Str::slug('Nghề Siêu Khó Nói'),
                'category' => 'Jong-suk Lee',
                'img_thumbnail' => 'movie_images/hadiY5dNHeLOQKRFnxX8JOAP2uHNtnr6MdGoBJNN.jpg',
                'description' => 'Bộ phim kể về Danbi, một người có ước mơ trở thành nhà văn viết sách thiếu nhi nhưng thực chất lại là lính mới trong đội chống nạn khiêu dâm bất hợp pháp.',
                'director' => 'Sung Dong-il, Park Ji-hyun, Choi Siwon,..',
                'duration' => '102 ',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/8EqTrhd9qC8',
                'surcharge' => 15000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Comedy', 'Action']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Monkey: Tiếng Vọng Kinh Hoàng',
                'slug' => Str::slug('The Monkey: Tiếng Vọng Kinh Hoàng'),
                'category' => 'Theo James, Elijah Wood, Sarah Levy,..',
                'img_thumbnail' => 'movie_images/jEh7kvA6nd4MuTCnMoAq5mAa30ADfCeBNM03dDSy.png',
                'description' => 'Coraline khám phá thế giới bí ẩn đầy kinh dị và hài hước qua cánh cửa nhỏ.',
                'director' => 'Osgood Perkins',
                'duration' => '100',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/8pGDA9To3AA',
                'surcharge' => 20000,
                'movie_versions' => json_encode(['2D', '3D']),
                'movie_genres' => json_encode(['Horror', 'Action']),
                'is_active' => 0,
                'is_hot' => 1,
                'is_special' => 1,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nghi Lễ Trục Quỷ',
                'slug' => Str::slug('Nghi Lễ Trục Quỷ'),
                'category' => 'Jerome Kurnia, Lukman Sardi, Astrid Tiar, ...',
                'img_thumbnail' => 'movie_images/LyfJghF3fgpYG5aNNK2ndwcOmfPSgVPAMKjtPNG2.png',
                'description' => 'Câu chuyện tiết lộ hành trình tâm linh sâu sắc của một linh mục trẻ tên là Thomas, người đã mất niềm tin sau một tai nạn gia đình đau thương. Khi được Rendra, một linh mục trừ tà kỳ cựu, mời trở thành học trò của mình, Thomas miễn cưỡng đồng ý. Tuy nhiên, niềm tin của anh được thắp lại khi họ đối mặt với một vụ ám quỷ liên quan đến một cô gái tên Kayla. Sự suy sụp của Kayla sau khi thực hiện một nghi lễ, cô bị biến đổi đến mức không thể nhận ra, những vết thương tự xuất hiện trên cơ thể cô, hay ánh mắt ma quái đầy đe dọa khi bị thế lực quỷ dữ thao túng. Quyết tâm cứu Kayla bằng mọi giá, Thomas đã phải trải qua một hành trình hết sức khó khăn để chống lại các thế lực đen tối.',
                'director' => 'Bobby Prasetyo',
                'duration' => '84',
                'rating' => 5,
                'release_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'trailer_url' => 'https://youtu.be/a_ayI5h6tJ0',
                'surcharge' => 12000,
                'movie_versions' => json_encode(['2D']),
                'movie_genres' => json_encode(['Comedy', 'Action']),
                'is_active' => 1,
                'is_hot' => 0,
                'is_special' => 0,
                'is_publish' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('movies')->insert($movies);

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
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":13,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":14,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":15,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":16,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":17,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":18,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":19,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":20,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":21,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":22,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":23,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":24,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":25,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":26,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":27,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":28,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":29,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":30,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":31,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":32,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":33,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":34,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":35,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":36,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":37,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":38,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":39,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":40,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":41,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":42,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":43,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":44,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":45,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":46,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":47,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":48,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":49,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":50,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":51,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":52,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":53,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":54,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":55,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":56,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":57,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":58,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":59,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":60,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":73,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":74,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":75,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":76,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":77,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":78,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":79,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":80,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":81,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":82,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":83,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":84,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":97,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":98,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":99,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":100,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":101,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":102,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":103,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":104,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":105,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":106,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":107,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":108,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":109,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":110,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":111,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":112,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":113,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":114,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":115,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":116,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":117,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":118,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":119,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":120,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":121,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":123,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":125,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":127,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":129,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":131,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":133,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":135,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":137,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":139,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":141,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":143,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null}]',
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
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":14,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":15,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":16,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":17,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":18,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":19,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":20,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":21,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":22,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":23,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":24,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":25,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":26,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":27,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":28,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":29,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":30,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":31,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":32,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":33,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":34,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":35,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":36,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":37,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":38,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":39,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":40,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":41,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":42,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":43,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":44,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":45,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":46,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":47,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":48,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":49,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":50,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":51,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":52,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":53,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":54,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":55,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":56,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":57,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":58,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":59,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":60,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":61,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":62,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":63,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":64,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":65,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":66,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":67,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":68,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":69,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":70,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":71,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":72,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":73,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":74,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":75,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":76,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":77,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":78,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":79,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":80,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":81,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":82,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":83,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":84,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":85,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":86,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":87,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":88,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":89,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":90,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":91,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":92,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":93,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":94,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":95,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":96,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":97,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":98,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":99,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":100,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":101,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":102,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":103,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":104,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":105,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":106,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":107,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":108,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":109,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":110,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":111,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":112,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":113,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":114,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":115,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":116,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":117,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":118,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":119,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":120,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":121,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":122,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":123,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":124,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":125,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":126,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":127,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":128,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":129,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":130,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":131,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":132,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":133,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":134,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":135,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":136,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":137,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":138,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":139,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":140,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":141,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":142,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":143,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":144,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":146,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":148,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":150,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":152,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":154,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":157,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":159,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":161,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":163,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":165,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":167,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null}]',
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
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":14,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":15,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":16,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":17,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":18,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":19,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":20,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":21,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":22,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":23,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":24,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":25,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":26,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":27,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":28,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":29,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":30,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":31,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":32,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":33,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":34,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":35,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":36,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":37,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":38,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":39,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":40,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":41,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":42,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":43,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":44,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":45,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":46,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":47,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":48,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":49,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":50,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":51,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":52,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":53,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":54,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":55,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":56,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":57,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":58,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":59,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":60,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":61,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":62,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":63,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":64,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":65,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":66,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":67,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":68,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":69,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":70,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":71,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":72,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":73,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":74,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":75,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":76,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":77,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":78,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":79,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":80,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":81,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":82,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":83,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":84,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":97,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":98,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":99,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":100,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":101,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":102,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":103,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":104,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":105,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":106,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":107,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":108,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":109,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":110,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":111,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":112,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":113,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":114,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":115,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":116,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":117,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":118,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":119,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":120,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":121,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":122,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":123,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":124,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":125,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":126,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":127,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":128,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":129,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":130,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":131,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":132,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":133,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":134,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":135,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":136,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":137,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":138,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":139,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":140,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":141,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":142,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":143,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":144,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":145,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":146,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":147,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":148,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":149,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":150,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":151,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":152,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":153,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":154,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":155,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":156,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":157,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":158,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":159,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":160,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":161,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":162,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":163,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":164,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":165,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":166,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":167,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":168,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":169,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":171,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":173,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":175,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":177,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":179,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":181,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"M","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":183,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":185,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":187,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":189,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":191,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":193,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":195,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null}]',
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
                'seat_structure' => '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":13,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":14,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":15,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":16,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":17,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":18,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":19,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":20,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":21,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":22,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":23,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":24,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":25,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":26,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":27,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":28,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":29,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":30,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":31,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":32,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":33,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":34,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":35,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":36,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":37,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":38,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":39,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":40,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":41,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":42,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":43,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":44,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":45,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":46,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":47,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":48,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":49,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":50,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":51,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":52,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":53,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":54,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":55,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":56,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":57,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":58,"user_id":null,"price":"5000","coordinates_x":13,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":59,"user_id":null,"price":"5000","coordinates_x":14,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":60,"user_id":null,"price":"5000","coordinates_x":15,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":73,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":74,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":75,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":76,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":77,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":78,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":79,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":80,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":81,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":82,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":83,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":84,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":85,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":86,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":87,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":88,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":89,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":90,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":91,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":92,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":93,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":94,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":95,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":96,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":97,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":98,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":99,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":100,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":101,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":102,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":103,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":104,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":105,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":106,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":107,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":108,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":109,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":110,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":111,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":112,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":113,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":114,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":115,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":116,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":117,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":118,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":119,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":120,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":121,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":122,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":123,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":124,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":125,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":126,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":127,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":128,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":129,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":130,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":131,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":132,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":133,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":134,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":135,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":136,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":137,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":138,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":139,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":140,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":141,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":142,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":143,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":144,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":145,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":146,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":147,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":148,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":149,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":150,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":151,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":152,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":153,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":154,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":155,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":156,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":157,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":158,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":159,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":160,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":161,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":162,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":163,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":164,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":165,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"K","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":166,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":167,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":168,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":169,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":170,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":171,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":172,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":173,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":174,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":175,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":176,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":177,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":178,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":179,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":180,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"L","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":181,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":182,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":183,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":184,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":185,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":186,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":187,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":188,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":189,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":190,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":191,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":192,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":193,"user_id":null,"price":"10000","coordinates_x":13,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":194,"user_id":null,"price":"10000","coordinates_x":14,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":195,"user_id":null,"price":"10000","coordinates_x":15,"coordinates_y":"M","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":196,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":198,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":200,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":202,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":204,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":206,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":208,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"N","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":211,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":213,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":215,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":217,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":219,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":221,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":223,"user_id":null,"price":"15000","coordinates_x":13,"coordinates_y":"O","type_seat_id":3,"status":"available","hold_expires_at":null}]',
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

        $dataTemplateRoom = '[{"id":1,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":2,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":3,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":4,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":5,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":6,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":7,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":8,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":9,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":10,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":11,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":12,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"A","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":13,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":14,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":15,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":16,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":17,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":18,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":19,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":20,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":21,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":22,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":23,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":24,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"B","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":25,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":26,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":27,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":28,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":29,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":30,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":31,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":32,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":33,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":34,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":35,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":36,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"C","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":37,"user_id":null,"price":"5000","coordinates_x":1,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":38,"user_id":null,"price":"5000","coordinates_x":2,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":39,"user_id":null,"price":"5000","coordinates_x":3,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":40,"user_id":null,"price":"5000","coordinates_x":4,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":41,"user_id":null,"price":"5000","coordinates_x":5,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":42,"user_id":null,"price":"5000","coordinates_x":6,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":43,"user_id":null,"price":"5000","coordinates_x":7,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":44,"user_id":null,"price":"5000","coordinates_x":8,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":45,"user_id":null,"price":"5000","coordinates_x":9,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":46,"user_id":null,"price":"5000","coordinates_x":10,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":47,"user_id":null,"price":"5000","coordinates_x":11,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":48,"user_id":null,"price":"5000","coordinates_x":12,"coordinates_y":"D","type_seat_id":1,"status":"available","hold_expires_at":null},{"id":49,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":50,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":51,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":52,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":53,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":54,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":55,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":56,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":57,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":58,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":59,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":60,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"E","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":61,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":62,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":63,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":64,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":65,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":66,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":67,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":68,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":69,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":70,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":71,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":72,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"F","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":73,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":74,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":75,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":76,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":77,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":78,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":79,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":80,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":81,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":82,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":83,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":84,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"G","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":85,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":86,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":87,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":88,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":89,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":90,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":91,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":92,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":93,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":94,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":95,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":96,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"H","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":97,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":98,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":99,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":100,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":101,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":102,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":103,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":104,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":105,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":106,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":107,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":108,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"I","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":109,"user_id":null,"price":"10000","coordinates_x":1,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":110,"user_id":null,"price":"10000","coordinates_x":2,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":111,"user_id":null,"price":"10000","coordinates_x":3,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":112,"user_id":null,"price":"10000","coordinates_x":4,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":113,"user_id":null,"price":"10000","coordinates_x":5,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":114,"user_id":null,"price":"10000","coordinates_x":6,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":115,"user_id":null,"price":"10000","coordinates_x":7,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":116,"user_id":null,"price":"10000","coordinates_x":8,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":117,"user_id":null,"price":"10000","coordinates_x":9,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":118,"user_id":null,"price":"10000","coordinates_x":10,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":119,"user_id":null,"price":"10000","coordinates_x":11,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":120,"user_id":null,"price":"10000","coordinates_x":12,"coordinates_y":"J","type_seat_id":2,"status":"available","hold_expires_at":null},{"id":121,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":123,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":125,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":127,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":129,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":131,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"K","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":133,"user_id":null,"price":"15000","coordinates_x":1,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":135,"user_id":null,"price":"15000","coordinates_x":3,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":137,"user_id":null,"price":"15000","coordinates_x":5,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":139,"user_id":null,"price":"15000","coordinates_x":7,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":141,"user_id":null,"price":"15000","coordinates_x":9,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null},{"id":143,"user_id":null,"price":"15000","coordinates_x":11,"coordinates_y":"L","type_seat_id":3,"status":"available","hold_expires_at":null}]';

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
        foreach ($branchs as $branch) {
            foreach ($cinemas as $cinema) {
                if ($cinema->branch_id != $branch->id) {
                    continue;
                }
                Room::create([
                    'branch_id' => $branch->id,
                    'cinema_id' => $cinema->id,
                    'seat_template_id' => 2,
                    'type_room_id' => rand(1, 3),
                    'matrix_colume' => 13,
                    'name' => autoShorten($branch->name) . '_' . getShortName($cinema->name) .  rand(100, 999),
                    'seat_structure' => $seatTemplates[1]['seat_structure'],
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
                    'seat_template_id' => 3,
                    'type_room_id' => rand(1, 3),
                    'matrix_colume' => 14,
                    'name' => autoShorten($branch->name) . '_' . getShortName($cinema->name) .  rand(100, 999),
                    'seat_structure' => $seatTemplates[2]['seat_structure'],
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
        foreach ($branchs as $branch) {
            foreach ($cinemas as $cinema) {
                if ($cinema->branch_id != $branch->id) {
                    continue;
                }
                Room::create([
                    'branch_id' => $branch->id,
                    'cinema_id' => $cinema->id,
                    'seat_template_id' => 4,
                    'type_room_id' => rand(1, 3),
                    'matrix_colume' => 15,
                    'name' => autoShorten($branch->name) . '_' . getShortName($cinema->name) .  rand(100, 999),
                    'seat_structure' => $seatTemplates[3]['seat_structure'],
                    'description' => 'Phòng chiếu',
                    'is_active' => 1,
                    'is_publish' => 1,
                ]);
            }
        }
        RoomChat::create([
            'name' => 'Phòng chat chung'
        ]);
        RoomChat::create([
            'name' => 'Phòng chat VIP'
        ]);


        $vouchers = [
            [
                'code' => 'SALE2025',
                'title' => 'Giảm giá Tết 2025',
                'description' => 'Áp dụng cho tất cả các đơn hàng trong dịp Tết 2025.',
                'start_date_time' => '2025-01-01 00:00:00',
                'end_date_time' => '2025-02-28 23:59:59',
                'discount' => 100000,
                'quantity' => 50,
                'limit_by_user' => 1,
            ],
            [
                'code' => 'SUMMER50',
                'title' => 'Giảm giá hè 50%',
                'description' => 'Ưu đãi giảm giá 50% cho các sản phẩm mùa hè.',
                'start_date_time' => '2025-06-01 00:00:00',
                'end_date_time' => '2025-06-30 23:59:59',
                'discount' => 50000,
                'quantity' => 100,
                'limit_by_user' => 2,
            ],
            [
                'code' => 'FREESHIP',
                'title' => 'Miễn phí vận chuyển',
                'description' => 'Miễn phí vận chuyển cho tất cả đơn hàng.',
                'start_date_time' => '2025-01-01 00:00:00',
                'end_date_time' => '2025-12-31 23:59:59',
                'discount' => 30000,
                'quantity' => 200,
                'limit_by_user' => 1,
            ],
        ];

        // Chèn dữ liệu vào bảng vouchers
        foreach ($vouchers as $voucher) {
            DB::table('vouchers')->insert([
                'code' => $voucher['code'],
                'title' => $voucher['title'],
                'description' => $voucher['description'],
                'start_date_time' => $voucher['start_date_time'],
                'end_date_time' => $voucher['end_date_time'],
                'discount' => $voucher['discount'],
                'quantity' => $voucher['quantity'],
                'limit_by_user' => $voucher['limit_by_user'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $movie_branches = [
            ['branch_id' => 1, 'movie_id' => 1],
            ['branch_id' => 1, 'movie_id' => 2],
            ['branch_id' => 1, 'movie_id' => 3],
            ['branch_id' => 1, 'movie_id' => 4],
            ['branch_id' => 1, 'movie_id' => 5],
            ['branch_id' => 1, 'movie_id' => 6],
            ['branch_id' => 2, 'movie_id' => 3],
            ['branch_id' => 2, 'movie_id' => 4],
            ['branch_id' => 2, 'movie_id' => 5],
            ['branch_id' => 2, 'movie_id' => 7],
            ['branch_id' => 2, 'movie_id' => 8],
            ['branch_id' => 3, 'movie_id' => 6],
            ['branch_id' => 3, 'movie_id' => 7],
            ['branch_id' => 3, 'movie_id' => 8],
            ['branch_id' => 3, 'movie_id' => 9],
            ['branch_id' => 4, 'movie_id' => 1],
            ['branch_id' => 4, 'movie_id' => 2],
            ['branch_id' => 4, 'movie_id' => 5],
            ['branch_id' => 4, 'movie_id' => 9],
            ['branch_id' => 4, 'movie_id' => 10],
            ['branch_id' => 5, 'movie_id' => 1],
            ['branch_id' => 5, 'movie_id' => 3],
            ['branch_id' => 5, 'movie_id' => 6],
            ['branch_id' => 5, 'movie_id' => 7],
            ['branch_id' => 5, 'movie_id' => 8],
            ['branch_id' => 5, 'movie_id' => 10],
        ];

        DB::table('movie_branches')->insert($movie_branches);
    }
}
