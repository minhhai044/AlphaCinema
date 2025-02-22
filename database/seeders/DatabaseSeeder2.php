<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Cinema;
use App\Models\Branch;
use App\Models\SeatTemplate;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Food;
use App\Models\Rank;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\Combo;
use App\Models\Ticket_combo;
use App\Models\Movie_review;



use Faker\Factory as Faker;


class DatabaseSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // //==========4 bản ghi chi nhánh===============
        // $branches = [
        //     'Hà nội',
        //     'Hồ Chí Minh',
        //     'Đà Nẵng',
        //     'Hải Phòng'
        // ];

        // $branchIds = [];
        // foreach ($branches as $branch) {
        //     $createdBranch = Branch::create([
        //         'name' => $branch,
        //         'slug' => Str::slug($branch),
        //         'surcharge' => rand(10000, 50000),
        //         'is_active' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]);
        //     $branchIds[] = $createdBranch->id;
        // }



        // //==========Đây là rạp==========
        // //8 bản ghi rạp tương ứng với mỗi chi nhánh 2 rạp
        // $cinemas = [
        //     'Hà Đông',
        //     'Mỹ Đình',
        //     'Sài Gòn',
        //     'Gò Vấp',
        //     'Hải Châu',
        //     'Cẩm Lệ',
        //     'Đồ Sơn',
        //     'Lương Khê'
        // ];

        // $counter = 0;
        // foreach ($cinemas as $cinema) {
        //     $branchId = $branchIds[$counter % count($branchIds)];

        //     DB::table('cinemas')->insert([
        //         'branch_id' => $branchId,
        //         'name' => $cinema,
        //         'slug' => Str::slug($cinema),
        //         'address' => $cinema . ', ' . fake()->address(),
        //         'description' => fake()->text(200),
        //         'is_active' => 1, // Để 1 là active
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]);

        //     $counter++;
        // }



        // //===========3 bản ghi loại phòng==============
        // $type_rooms = [
        //     ['name' => '2D', 'surcharge' => 0],
        //     ['name' => '3D', 'surcharge' => 30000],
        //     ['name' => '4D', 'surcharge' => 50000]
        // ];
        // DB::table('type_rooms')->insert($type_rooms);


        // // ===========3 bản ghi loại ghế==============
        // $type_seats = [
        //     ['name' => 'Ghế Thường', 'price' => 50000],
        //     ['name' => 'Ghế Vip', 'price' => 75000],
        //     ['name' => 'Ghế Đôi', 'price' => 120000],
        // ];
        // DB::table('type_seats')->insert($type_seats);



        // //===========movie===============
        // // $faker = Faker::create();

        // // for ($i = 0; $i < 10; $i++) {
        // //     Movie::create([
        // //         'name' => $faker->sentence(3),
        // //         'slug' => $faker->slug,
        // //         'category' => $faker->randomElement(['Hành động', 'Kinh dị', 'Hài hước', 'Tình cảm']),
        // //         'img_thumbnail' => $faker->imageUrl(300, 400, 'movies', true),
        // //         'description' => $faker->paragraph,
        // //         'director' => $faker->name,
        // //         'duration' => rand(90, 180),
        // //         'rating' => $faker->numberBetween(5, 10),
        // //         'release_date' => $faker->date(),
        // //         'end_date' => $faker->date(),
        // //         'trailer_url' => $faker->url,
        // //         'surcharge' => rand(0, 50000),
        // //         'movie_versions' => json_encode(['2D', '3D', '4D']),
        // //         'movie_genres' => json_encode(['Horror', 'Action', 'Comedy']),
        // //         'is_active' => $faker->boolean,
        // //         'is_hot' => $faker->boolean,
        // //         'is_special' => $faker->boolean,
        // //         'is_publish' => $faker->boolean,
        // //     ]);
        // // }

        // $faker = Faker::create();

        // for ($i = 0; $i < 10; $i++) {
        //     Movie::create([
        //         'name' => $faker->sentence(3),
        //         'slug' => $faker->slug,
        //         'category' => $faker->randomElement(['Hành động', 'Kinh dị', 'Hài hước', 'Tình cảm']),
        //         'img_thumbnail' => $faker->imageUrl(300, 400, 'movies', true),
        //         'description' => $faker->paragraph,
        //         'director' => $faker->name,
        //         'duration' => rand(90, 180) ,
        //         'rating' => $faker->numberBetween(5, 10),
        //         'release_date' => $faker->date(),
        //         'end_date' => $faker->date(),
        //         'trailer_url' => $faker->url,
        //         'surcharge' => rand(0, 50000),
        //         'movie_versions' => $faker->randomElements(['2D', '3D', '4D'], rand(1, 3)),
        //         'movie_genres' => $faker->randomElements(['Horror', 'Action', 'Comedy'], rand(1, 3)),
        //         'is_active' => $faker->boolean,
        //         'is_hot' => $faker->boolean,
        //         'is_special' => $faker->boolean,
        //         'is_publish' => $faker->boolean,
        //     ]);
        // }

        


        // //===========7 bản ghi admin=================
        // $users = [
        //     [
        //         'name' => 'System Admin',
        //         'avatar' => '',
        //         'phone' => '0332295555',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'admin@fpt.edu.vn',
        //         'password' => Hash::make('admin@fpt.edu.vn'),
        //         'address' => 'Vân Canh, Nam Từ Liêm, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-02-07',
        //         'type_user' => 1,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Đặng Văn Sơn',
        //         'avatar' => '',
        //         'phone' => '0339530282',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'dangvanson210297@gmail.com',
        //         'password' => Hash::make('dangvanson210297@gmail.com'),
        //         'address' => 'Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-02-07',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Trần Minh Hải',
        //         'avatar' => '',
        //         'phone' => '0338997846',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'tmhai2004@gmail.com',
        //         'password' => Hash::make('tmhai2004@gmail.com'),
        //         'address' => 'Vân Canh, Nam Từ Liêm, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-08-08',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Phùng Quang Huy',
        //         'avatar' => '',
        //         'phone' => '0343989940',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'huyntu24@gmail.com',
        //         'password' => Hash::make('huyntu24@gmail.com'),
        //         'address' => 'Vân Canh, Nam Từ Liêm, Hà Nội.',
        //         'gender' => 0,
        //         'birthday' => '2004-06-06',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Hoa Hương Quỳnh',
        //         'avatar' => '',
        //         'phone' => '0399242313',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'quynhhhph44910@fpt.edu.vn',
        //         'password' => Hash::make('quynhhhph44910@fpt.edu.vn'),
        //         'address' => 'Cổ Nhuế, Bắc Từ Liêm, Hà Nội',
        //         'gender' => 1,
        //         'birthday' => '2004-10-01',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Vũ Nhật Quỳnh',
        //         'avatar' => '',
        //         'phone' => '0359210744',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'vunhatquynh2004@gmail.com',
        //         'password' => Hash::make('vunhatquynh2004@gmail.com'),
        //         'address' => 'Đình Thôn, Nam Từ Liêm, Hà Nội.',
        //         'gender' => 1,
        //         'birthday' => '2004-11-11',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Nguyễn Đức Tùng Lâm',
        //         'avatar' => '',
        //         'phone' => '0878555203',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'nguyenductunglam94@gmail.com',
        //         'password' => Hash::make('nguyenductunglam94@gmail.com'),
        //         'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Nguyễn Hoàng Anh',
        //         'avatar' => '',
        //         'phone' => '0367253666',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'hoang838604@gmail.com',
        //         'password' => Hash::make('hoang838604@gmail.com'),
        //         'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 0,
        //         'cinema_id' => 0,
        //     ],
        //     [
        //         'name' => 'Nhân viên Rạp',
        //         'avatar' => '',
        //         'phone' => '0965266625',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'nhanvienrapHaDong@fpt.edu.vn',
        //         'password' => Hash::make('nhanvienrapHaDong@fpt.edu.vn'),
        //         'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 1,
        //         'cinema_id' => 1,
        //     ],
        //     [
        //         'name' => 'Nhân viên Rạp',
        //         'avatar' => '',
        //         'phone' => '0965265555',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'nhanvienrapMyDinh@fpt.edu.vn',
        //         'password' => Hash::make('nhanvienrapMyDinh@fpt.edu.vn'),
        //         'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 1,
        //         'cinema_id' => 2,
        //     ],
        //     [
        //         'name' => 'Quản lý cơ sở Hà Đông',
        //         'avatar' => '',
        //         'phone' => '0999965555',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'quanlycosoHaDong@fpt.edu.vn',
        //         'password' => Hash::make('quanlycosoHaDong@fpt.edu.vn'),
        //         'address' => 'Bích Hòa, Chương Mỹ, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 1,
        //         'cinema_id' => 1,
        //     ],
        //     [
        //         'name' => 'Quản lý cơ sở Mỹ Đình',
        //         'avatar' => '',
        //         'phone' => '0999999995',
        //         'email_verified_at' => '2025-02-17 19:58:51',
        //         'email' => 'quanlycosoMyDinh@fpt.edu.vn',
        //         'password' => Hash::make('quanlycosoMyDinh@fpt.edu.vn'),
        //         'address' => 'Bích Hòa, Chương Mỹ, Hà Nội',
        //         'gender' => 0,
        //         'birthday' => '2004-10-14',
        //         'type_user' => 1,
        //         'cinema_id' => 2,
        //     ],
        // ];
        // DB::table('users')->insert($users);



        // //===========Voucher============
        // $dataVouchers = [
        //     ['title' => 'Chúc mừng giáng sinh Merry Christmas', 'code' => 'GIANGSINHANLANH', 'description' => 'Nhân dịp giáng sinh AlphaCinema tặng quý khách hàng mã vouchers giảm giá 30.000 VNĐ khi đặt vé tại rạp.', 'discount' => 30000],
        //     ['title' => 'Chúc mừng năm mới 2025', 'code' => 'HPNY2025', 'description' => 'Đầu xuân năm mới AlphaCinema chúc quý khách hàng một năm an khang thịnh vượng !', 'discount' => 10000]
        // ];
        // foreach ($dataVouchers as $vc) {
        //     Voucher::create([
        //         'code' => $vc['code'],
        //         'title' => $vc['title'],
        //         'description' => $vc['description'],
        //         'start_date_time' => Carbon::now()->subDays(rand(0, 30)),
        //         'end_date_time' => Carbon::now()->addDays(rand(30, 60)),
        //         'discount' => $vc['discount'],
        //         'quantity' => fake()->numberBetween(1, 100),
        //         'limit_by_user' => fake()->numberBetween(1, 5),
        //         'is_active' => 1,
        //     ]);
        // }


        // //===========3 bản ghi food============
        // $foods = [
        //     [
        //         'name' => 'Nước có gaz (22oz)',
        //         'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzWcnDbyPmBMtua26Cr1cv-970sm56vJkZUw&s',
        //         'price' => 20000,
        //         'description' => 'Nước uống có ga giúp bạn sảng khoái và tận hưởng bộ phim trọn vẹn hơn.',
        //     ],
        //     [
        //         'name' => 'Bắp (69oz)',
        //         'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXVyPxPb8ZuGNwrTDt6Em_2PlVUl-0ibkgeA&s',
        //         'price' => 30000,
        //         'description' => 'Bỏng ngô giòn rụm, thơm ngon, kết hợp hoàn hảo với nước uống khi xem phim.',
        //     ],
        //     [
        //         'name' => 'Ly Vảy cá kèm nước',
        //         'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxIj_cKCMmduRPAnphuGPCQFiHQIU3IG4kcg&s',
        //         'price' => 40000,
        //         'description' => 'Ly thiết kế vảy cá độc đáo, đi kèm nước uống mát lạnh, thích hợp làm quà tặng.',
        //     ],
        // ];

        // DB::table('food')->insert($foods);


        // // 4 bảng ghi Combos
        // $combos = [
        //     [
        //         'name' => 'Combo Snack',
        //         'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/03/31/combo-online-03-163047-310324-49.png',
        //         'description' => 'Combo gồm nước và bắp',
        //         'price' => 0, // Sẽ cập nhật sau
        //         'price_sale' => 0, // Sẽ cập nhật sau
        //         'is_active' => 1
        //     ],
        //     [
        //         'name' => 'Combo Drink',
        //         'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/06/05/combo-online-26-101802-050624-36.png',
        //         'description' => 'Combo nước uống đặc biệt',
        //         'price' => 0,
        //         'price_sale' => 0,
        //         'is_active' => 1
        //     ],
        //     [
        //         'name' => 'Combo Mixed',
        //         'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/03/31/combo-online-04-163144-310324-32.png',
        //         'description' => 'Combo đồ ăn và nước uống',
        //         'price' => 0,
        //         'price_sale' => 0,
        //         'is_active' => 1
        //     ],
        //     [
        //         'name' => 'Combo Premium',
        //         'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/08/23/combo-see-me-duoi-ca-01-120352-230824-11.png',
        //         'description' => 'Combo cao cấp',
        //         'price' => 0,
        //         'price_sale' => 0,
        //         'is_active' => 1
        //     ],
        // ];

        // DB::table('combos')->insert($combos);

        // // Lấy danh sách combos và foods để ghép món ăn vào combo
        // $combos = DB::table('combos')->get();
        // $foods = DB::table('food')->get();

        // foreach ($combos as $combo) {
        //     $totalPrice = 0;

        //     // Chọn ngẫu nhiên từ 1 đến 3 món ăn cho mỗi combo
        //     foreach ($foods->random(rand(1, 3)) as $food) {
        //         $quantity = rand(1, 4);
        //         $itemPrice = $food->price * $quantity;
        //         $totalPrice += $itemPrice;

        //         // Thêm vào bảng combo_foods
        //         DB::table('combo_food')->insert([
        //             'combo_id' => $combo->id,
        //             'food_id' => $food->id,
        //             'quantity' => $quantity,
        //         ]);
        //     }

        //     // Cập nhật giá combo và giá sale
        //     DB::table('combos')
        //         ->where('id', $combo->id)
        //         ->update([
        //             'price' => $totalPrice,
        //             'price_sale' => max($totalPrice - 20000, 0),
        //         ]);
        // }


        // //===============Rank===========
        // $dataRanks = [
        //     ['name' => 'Member',       'total_spent' => 0,         'ticket_percentage' => 5,     'food_percentage' => 3,  'is_default' => 1],
        //     ['name' => 'Gold',         'total_spent' => 1000000,   'ticket_percentage' => 7,     'food_percentage' => 5,  'is_default' => 0],
        //     ['name' => 'Platinum',     'total_spent' => 3000000,   'ticket_percentage' => 10,    'food_percentage' => 7,  'is_default' => 0],
        //     ['name' => 'Diamond',      'total_spent' => 5000000,   'ticket_percentage' => 15,    'food_percentage' => 10, 'is_default' => 0],
        // ];
        // foreach ($dataRanks as $rank) {
        //     Rank::create($rank);
        // }



        // //===============Days===========
        // $days = [
        //     ['name' => 'Thứ Hai', 'day_surcharge' => 0],
        //     ['name' => 'Thứ Ba', 'day_surcharge' => 0],
        //     ['name' => 'Thứ Tư', 'day_surcharge' => 0],
        //     ['name' => 'Thứ Năm', 'day_surcharge' => 5000],
        //     ['name' => 'Thứ Sáu', 'day_surcharge' => 10000],
        //     ['name' => 'Thứ Bảy', 'day_surcharge' => 15000],
        //     ['name' => 'Chủ Nhật', 'day_surcharge' => 20000],
        // ];

        // DB::table('days')->insert($days);



        // //===============seat_templates===========
        // $seat_templates = [
        //     [
        //         'matrix' => 1,
        //         'name' => 'Template 1',
        //         'seat_structure' => '"[{\"id\":1,\"coordinates_x\":1,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":2,\"coordinates_x\":2,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":3,\"coordinates_x\":3,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":4,\"coordinates_x\":4,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":5,\"coordinates_x\":5,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":6,\"coordinates_x\":6,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":7,\"coordinates_x\":7,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":8,\"coordinates_x\":8,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":9,\"coordinates_x\":9,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":10,\"coordinates_x\":10,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":11,\"coordinates_x\":11,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":12,\"coordinates_x\":12,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":13,\"coordinates_x\":1,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":14,\"coordinates_x\":2,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":15,\"coordinates_x\":3,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":16,\"coordinates_x\":4,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":17,\"coordinates_x\":5,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":18,\"coordinates_x\":6,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":19,\"coordinates_x\":7,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":20,\"coordinates_x\":8,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":21,\"coordinates_x\":9,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":22,\"coordinates_x\":10,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":23,\"coordinates_x\":11,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":24,\"coordinates_x\":12,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":25,\"coordinates_x\":1,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":26,\"coordinates_x\":2,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":27,\"coordinates_x\":3,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":28,\"coordinates_x\":4,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":29,\"coordinates_x\":5,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":30,\"coordinates_x\":6,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":31,\"coordinates_x\":7,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":32,\"coordinates_x\":8,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":33,\"coordinates_x\":9,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":34,\"coordinates_x\":10,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":35,\"coordinates_x\":11,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":36,\"coordinates_x\":12,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":37,\"coordinates_x\":1,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":38,\"coordinates_x\":2,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":39,\"coordinates_x\":3,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":40,\"coordinates_x\":4,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":41,\"coordinates_x\":5,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":42,\"coordinates_x\":6,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":43,\"coordinates_x\":7,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":44,\"coordinates_x\":8,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":45,\"coordinates_x\":9,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":46,\"coordinates_x\":10,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":47,\"coordinates_x\":11,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":48,\"coordinates_x\":12,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":49,\"coordinates_x\":1,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":50,\"coordinates_x\":2,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":51,\"coordinates_x\":3,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":52,\"coordinates_x\":4,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":53,\"coordinates_x\":5,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":54,\"coordinates_x\":6,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":55,\"coordinates_x\":7,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":56,\"coordinates_x\":8,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":57,\"coordinates_x\":9,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":58,\"coordinates_x\":10,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":59,\"coordinates_x\":11,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":60,\"coordinates_x\":12,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":61,\"coordinates_x\":1,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":62,\"coordinates_x\":2,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":63,\"coordinates_x\":3,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":64,\"coordinates_x\":4,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":65,\"coordinates_x\":5,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":66,\"coordinates_x\":6,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":67,\"coordinates_x\":7,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":68,\"coordinates_x\":8,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":69,\"coordinates_x\":9,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":70,\"coordinates_x\":10,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":71,\"coordinates_x\":11,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":72,\"coordinates_x\":12,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":73,\"coordinates_x\":1,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":74,\"coordinates_x\":2,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":75,\"coordinates_x\":3,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":76,\"coordinates_x\":4,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":77,\"coordinates_x\":5,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":78,\"coordinates_x\":6,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":79,\"coordinates_x\":7,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":80,\"coordinates_x\":8,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":81,\"coordinates_x\":9,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":82,\"coordinates_x\":10,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":83,\"coordinates_x\":11,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":84,\"coordinates_x\":12,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":85,\"coordinates_x\":1,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":86,\"coordinates_x\":2,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":87,\"coordinates_x\":3,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":88,\"coordinates_x\":4,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":89,\"coordinates_x\":5,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":90,\"coordinates_x\":6,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":91,\"coordinates_x\":7,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":92,\"coordinates_x\":8,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":93,\"coordinates_x\":9,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":94,\"coordinates_x\":10,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":95,\"coordinates_x\":11,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":96,\"coordinates_x\":12,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":97,\"coordinates_x\":1,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":98,\"coordinates_x\":2,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":99,\"coordinates_x\":3,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":100,\"coordinates_x\":4,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":101,\"coordinates_x\":5,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":102,\"coordinates_x\":6,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":103,\"coordinates_x\":7,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":104,\"coordinates_x\":8,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":105,\"coordinates_x\":9,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":106,\"coordinates_x\":10,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":107,\"coordinates_x\":11,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":108,\"coordinates_x\":12,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":109,\"coordinates_x\":1,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":110,\"coordinates_x\":2,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":111,\"coordinates_x\":3,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":112,\"coordinates_x\":4,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":113,\"coordinates_x\":5,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":114,\"coordinates_x\":6,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":115,\"coordinates_x\":7,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":116,\"coordinates_x\":8,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":117,\"coordinates_x\":9,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":118,\"coordinates_x\":10,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":119,\"coordinates_x\":11,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":120,\"coordinates_x\":12,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":121,\"coordinates_x\":1,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":123,\"coordinates_x\":3,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":125,\"coordinates_x\":5,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":127,\"coordinates_x\":7,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":129,\"coordinates_x\":9,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":131,\"coordinates_x\":11,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":133,\"coordinates_x\":1,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":135,\"coordinates_x\":3,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":137,\"coordinates_x\":5,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":139,\"coordinates_x\":7,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":141,\"coordinates_x\":9,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":143,\"coordinates_x\":11,\"coordinates_y\":\"L\",\"type_seat_id\":3}]"',
        //         'row_regular' => 10,
        //         'row_vip' => 5,
        //         'row_double' => 2,
        //         'description' => 'Template 1 description',
        //         'is_active' => 1,
        //         'is_publish' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ];

        // DB::table('seat_templates')->insert($seat_templates);



        // //===============rooms===============
        // $rooms = [
        //     [
        //         'branch_id' => 1,
        //         'cinema_id' => 1,
        //         'type_room_id' => 1,
        //         'seat_template_id' => 1,
        //         'name' => 'Room 1',
        //         'seat_structures' => "[{\"id\":1,\"coordinates_x\":1,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":2,\"coordinates_x\":2,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":3,\"coordinates_x\":3,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":4,\"coordinates_x\":4,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":5,\"coordinates_x\":5,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":6,\"coordinates_x\":6,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":7,\"coordinates_x\":7,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":8,\"coordinates_x\":8,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":9,\"coordinates_x\":9,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":10,\"coordinates_x\":10,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":11,\"coordinates_x\":11,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":12,\"coordinates_x\":12,\"coordinates_y\":\"A\",\"type_seat_id\":1},{\"id\":13,\"coordinates_x\":1,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":14,\"coordinates_x\":2,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":15,\"coordinates_x\":3,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":16,\"coordinates_x\":4,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":17,\"coordinates_x\":5,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":18,\"coordinates_x\":6,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":19,\"coordinates_x\":7,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":20,\"coordinates_x\":8,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":21,\"coordinates_x\":9,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":22,\"coordinates_x\":10,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":23,\"coordinates_x\":11,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":24,\"coordinates_x\":12,\"coordinates_y\":\"B\",\"type_seat_id\":1},{\"id\":25,\"coordinates_x\":1,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":26,\"coordinates_x\":2,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":27,\"coordinates_x\":3,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":28,\"coordinates_x\":4,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":29,\"coordinates_x\":5,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":30,\"coordinates_x\":6,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":31,\"coordinates_x\":7,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":32,\"coordinates_x\":8,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":33,\"coordinates_x\":9,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":34,\"coordinates_x\":10,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":35,\"coordinates_x\":11,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":36,\"coordinates_x\":12,\"coordinates_y\":\"C\",\"type_seat_id\":1},{\"id\":37,\"coordinates_x\":1,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":38,\"coordinates_x\":2,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":39,\"coordinates_x\":3,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":40,\"coordinates_x\":4,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":41,\"coordinates_x\":5,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":42,\"coordinates_x\":6,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":43,\"coordinates_x\":7,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":44,\"coordinates_x\":8,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":45,\"coordinates_x\":9,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":46,\"coordinates_x\":10,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":47,\"coordinates_x\":11,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":48,\"coordinates_x\":12,\"coordinates_y\":\"D\",\"type_seat_id\":1},{\"id\":49,\"coordinates_x\":1,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":50,\"coordinates_x\":2,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":51,\"coordinates_x\":3,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":52,\"coordinates_x\":4,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":53,\"coordinates_x\":5,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":54,\"coordinates_x\":6,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":55,\"coordinates_x\":7,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":56,\"coordinates_x\":8,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":57,\"coordinates_x\":9,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":58,\"coordinates_x\":10,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":59,\"coordinates_x\":11,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":60,\"coordinates_x\":12,\"coordinates_y\":\"E\",\"type_seat_id\":2},{\"id\":61,\"coordinates_x\":1,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":62,\"coordinates_x\":2,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":63,\"coordinates_x\":3,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":64,\"coordinates_x\":4,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":65,\"coordinates_x\":5,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":66,\"coordinates_x\":6,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":67,\"coordinates_x\":7,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":68,\"coordinates_x\":8,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":69,\"coordinates_x\":9,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":70,\"coordinates_x\":10,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":71,\"coordinates_x\":11,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":72,\"coordinates_x\":12,\"coordinates_y\":\"F\",\"type_seat_id\":2},{\"id\":73,\"coordinates_x\":1,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":74,\"coordinates_x\":2,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":75,\"coordinates_x\":3,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":76,\"coordinates_x\":4,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":77,\"coordinates_x\":5,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":78,\"coordinates_x\":6,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":79,\"coordinates_x\":7,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":80,\"coordinates_x\":8,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":81,\"coordinates_x\":9,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":82,\"coordinates_x\":10,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":83,\"coordinates_x\":11,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":84,\"coordinates_x\":12,\"coordinates_y\":\"G\",\"type_seat_id\":2},{\"id\":85,\"coordinates_x\":1,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":86,\"coordinates_x\":2,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":87,\"coordinates_x\":3,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":88,\"coordinates_x\":4,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":89,\"coordinates_x\":5,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":90,\"coordinates_x\":6,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":91,\"coordinates_x\":7,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":92,\"coordinates_x\":8,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":93,\"coordinates_x\":9,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":94,\"coordinates_x\":10,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":95,\"coordinates_x\":11,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":96,\"coordinates_x\":12,\"coordinates_y\":\"H\",\"type_seat_id\":2},{\"id\":97,\"coordinates_x\":1,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":98,\"coordinates_x\":2,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":99,\"coordinates_x\":3,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":100,\"coordinates_x\":4,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":101,\"coordinates_x\":5,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":102,\"coordinates_x\":6,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":103,\"coordinates_x\":7,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":104,\"coordinates_x\":8,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":105,\"coordinates_x\":9,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":106,\"coordinates_x\":10,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":107,\"coordinates_x\":11,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":108,\"coordinates_x\":12,\"coordinates_y\":\"I\",\"type_seat_id\":2},{\"id\":109,\"coordinates_x\":1,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":110,\"coordinates_x\":2,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":111,\"coordinates_x\":3,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":112,\"coordinates_x\":4,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":113,\"coordinates_x\":5,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":114,\"coordinates_x\":6,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":115,\"coordinates_x\":7,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":116,\"coordinates_x\":8,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":117,\"coordinates_x\":9,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":118,\"coordinates_x\":10,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":119,\"coordinates_x\":11,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":120,\"coordinates_x\":12,\"coordinates_y\":\"J\",\"type_seat_id\":2},{\"id\":121,\"coordinates_x\":1,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":123,\"coordinates_x\":3,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":125,\"coordinates_x\":5,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":127,\"coordinates_x\":7,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":129,\"coordinates_x\":9,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":131,\"coordinates_x\":11,\"coordinates_y\":\"K\",\"type_seat_id\":3},{\"id\":133,\"coordinates_x\":1,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":135,\"coordinates_x\":3,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":137,\"coordinates_x\":5,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":139,\"coordinates_x\":7,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":141,\"coordinates_x\":9,\"coordinates_y\":\"L\",\"type_seat_id\":3},{\"id\":143,\"coordinates_x\":11,\"coordinates_y\":\"L\",\"type_seat_id\":3}]",
        //         'is_active' => 1,
        //         'is_publish' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]

        // ];

        // DB::table('rooms')->insert($rooms);


        // //===========slideshow==============
        // $slideshows = [
        //     [
        //         'img_thumbnail' => 'https://img.freepik.com/free-vector/online-cinema-banner-with-open-clapper-board-film-strip_1419-2242.jpg',
        //         'description' => 'Mô tả cho slideshow 1',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'img_thumbnail' => 'https://static.vecteezy.com/system/resources/previews/021/850/617/non_2x/realistic-cinema-poster-vector.jpg',
        //         'description' => 'Mô tả cho slideshow 2',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'img_thumbnail' => 'https://outreach.com/media/print/product/product_images/BN2308623_l.jpg',
        //         'description' => 'Mô tả cho slideshow 4',
        //         'is_active' => 1,
        //     ],
        // ];

        // DB::table('slideshows')->insert($slideshows);

        

        // //===============showtime===============
        // $faker = Faker::create();

        // // Lấy danh sách các bản ghi hiện có từ các bảng liên quan
        // $movies = Movie::all();
        // $days = DB::table('days')->get();
        // $cinemas = Cinema::all();
        // $rooms = DB::table('rooms')->get();

        // if ($movies->isEmpty() || $days->isEmpty() || $cinemas->isEmpty() || $rooms->isEmpty()) {
        //     $this->command->warn('⚠️ Vui lòng seed dữ liệu cho movies, days, cinemas, rooms trước khi chạy seeder này.');
        //     return;
        // }

        // // Tạo 50 suất chiếu ngẫu nhiên
        // for ($i = 0; $i < 3; $i++) {
        //     $movie = $movies->random();
        //     $day = $days->random();
        //     $cinema = $cinemas->random();
        //     $room = $rooms->random();

        //     $start_time = Carbon::createFromTime(rand(8, 22), rand(0, 59));
        //     $end_time = (clone $start_time)->addMinutes($movie->duration);

        //     Showtime::create([
        //         'movie_id' => $movie->id,
        //         'day_id' => $day->id,
        //         'cinema_id' => $cinema->id,
        //         'room_id' => $room->id,
        //         'seat_structures' => json_encode([]), // Cấu trúc ghế có thể cập nhật sau
        //         'slug' => Str::slug($movie->name . '-' . $start_time->format('H-i')),
        //         'format' => $faker->randomElement(['2D', '3D', '4D']),
        //         'date' => Carbon::now()->addDays(rand(0, 30))->format('Y-m-d'),
        //         'start_time' => $start_time->format('H:i:s'),
        //         'end_time' => $end_time->format('H:i:s'),
        //         'price_special' => rand(100000, 300000),
        //         'description_special' => $faker->boolean ? $faker->sentence : null,
        //         'status_special' => $faker->boolean,
        //         'is_active' => true,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $this->command->info('✅ Đã tạo thành công 3 suất chiếu!');
        


        // //===============Tickets===============
        // $faker = Faker::create();

        // // Lấy dữ liệu từ các bảng liên quan
        // $users = User::all();
        // $cinemas = Cinema::all();
        // $rooms = Room::all();
        // $movies = Movie::all();
        // $showtimes = Showtime::all();
        // $vouchers = Voucher::all();

        // // Kiểm tra nếu thiếu dữ liệu
        // if ($users->isEmpty() || $cinemas->isEmpty() || $rooms->isEmpty() || $movies->isEmpty() || $showtimes->isEmpty()) {
        //     $this->command->warn('⚠️ Vui lòng seed dữ liệu cho users, cinemas, rooms, movies, showtimes trước khi chạy seeder này.');
        //     return;
        // }

        // // Số lượng vé cần tạo
        // $numTickets = 5;

        // for ($i = 0; $i < $numTickets; $i++) {
        //     $user = $users->random();
        //     $cinema = $cinemas->random();
        //     $room = $rooms->random();
        //     $movie = $movies->random();
        //     $showtime = $showtimes->random();
        //     $voucher = $faker->boolean(30) ? $vouchers->random() : null; // 30% cơ hội có voucher

        //     // Chọn ngẫu nhiên số ghế từ 1 đến 4 ghế
        //     $seatCount = rand(1, 4);
        //     $seatNumbers = [];
        //     for ($j = 0; $j < $seatCount; $j++) {
        //         $seatNumbers[] = $faker->randomElement(range(1, 50)) . $faker->randomElement(['A', 'B', 'C', 'D', 'E']);
        //     }

        //     // Tính giá vé
        //     $basePrice = rand(100000, 250000) * $seatCount;
        //     $voucherDiscount = $voucher ? $voucher->discount : 0;
        //     $pointUse = rand(0, 500);
        //     $pointDiscount = round($pointUse * 1000 / 500); // Giả sử 1 điểm tương đương 1000 VND
        //     $totalPrice = max($basePrice - $voucherDiscount - $pointDiscount, 0);

        //     Ticket::create([
        //         'user_id' => $user->id,
        //         'cinema_id' => $cinema->id,
        //         'room_id' => $room->id,
        //         'movie_id' => $movie->id,
        //         'showtime_id' => $showtime->id,
        //         'voucher_code' => $voucher ? $voucher->code : null,
        //         'voucher_discount' => $voucherDiscount,
        //         'point_use' => $pointUse,
        //         'point_discount' => $pointDiscount,
        //         'payment_name' => $faker->randomElement(['Momo', 'VNPay', 'Thẻ tín dụng', 'Tiền mặt']),
        //         'ticket_seats' => json_encode($seatNumbers),
        //         'total_price' => $totalPrice,
        //         'status' => $faker->boolean(80), // 80% là đã suất vé
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }



        // //===========Tiket combo================
        // $faker = Faker::create();

        // // Lấy dữ liệu từ các bảng liên quan
        // $tickets = Ticket::all();
        // $combos = Combo::all();

        // // Kiểm tra nếu thiếu dữ liệu
        // if ($tickets->isEmpty() || $combos->isEmpty()) {
        //     $this->command->warn('⚠️ Vui lòng seed dữ liệu cho tickets, combos trước khi chạy seeder này.');
        //     return;
        // }

        // // Số lượng bản ghi cần tạo
        // $numEntries = 100;

        // for ($i = 0; $i < $numEntries; $i++) {
        //     $ticket = $tickets->random();
        //     $combo = $combos->random();
        //     $quantity = rand(1, 3);
        //     $price = $combo->price * $quantity;
        //     $priceSale = max($combo->price_sale * $quantity, 0);

        //     Ticket_combo::create([
        //         'ticket_id' => $ticket->id,
        //         'combo_id' => $combo->id,
        //         'price' => $price,
        //         'price_sale' => $priceSale,
        //         'quantity' => $quantity,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }



        //=================Movie_review=================
        $users = User::all();
        $movies = Movie::all();
    
        // Tạo 5 đánh giá phim ngẫu nhiên
        foreach (range(1, 5) as $index) {
            Movie_review::create([
                'user_id' => $users->random()->id, 
                'movie_id' => $movies->random()->id, 
                'rating' => rand(1, 10) / 2, // Đánh giá ngẫu nhiên từ 0.5 đến 5.0
                'description' => 'Đánh giá phim ngẫu nhiên ' . $index, 
            ]);
        }

    }
}
