<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Movie;
use App\Models\Showtime;
use Faker\Factory as Faker;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Lấy danh sách ID từ các bảng liên quan (giả sử đã có dữ liệu trong các bảng này)
        $userIds = User::pluck('id')->toArray();
        $cinemaIds = Cinema::pluck('id')->toArray();
        $roomIds = Room::pluck('id')->toArray();
        $movieIds = Movie::pluck('id')->toArray();
        $showtimeIds = Showtime::pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            $totalPrice = $faker->numberBetween(50000, 200000); // Giá vé ngẫu nhiên
            $voucherDiscount = $faker->boolean(30) ? $faker->numberBetween(10000, 50000) : 0; // 30% cơ hội có giảm giá voucher
            $pointUse = $faker->boolean(20) ? $faker->numberBetween(100, 500) : 0; // 20% cơ hội dùng điểm
            $pointDiscount = $pointUse > 0 ? $pointUse * 100 : 0; // Giả sử 1 điểm = 100 VNĐ

            Ticket::create([
                'user_id' => $faker->randomElement($userIds), // Chọn ngẫu nhiên user
                'cinema_id' => $faker->randomElement($cinemaIds), // Chọn ngẫu nhiên rạp
                'room_id' => $faker->randomElement($roomIds), // Chọn ngẫu nhiên phòng
                'movie_id' => $faker->randomElement($movieIds), // Chọn ngẫu nhiên phim
                'showtime_id' => $faker->randomElement($showtimeIds), // Chọn ngẫu nhiên suất chiếu
                'code' => $faker->unique()->regexify('[A-Z0-9]{8}'), // Mã vé 8 ký tự ngẫu nhiên
                'voucher_code' => $voucherDiscount > 0 ? $faker->regexify('[A-Z0-9]{6}') : null, // Mã voucher nếu có giảm giá
                'voucher_discount' => $voucherDiscount, // Giảm giá từ voucher
                'point_use' => $pointUse, // Số điểm sử dụng
                'point_discount' => $pointDiscount, // Giảm giá từ điểm
                'payment_name' => $faker->randomElement(['Momo', 'ZaloPay', 'Visa', null]), // Phương thức thanh toán
                'ticket_seats' => json_encode($faker->randomElements(['A1', 'A2', 'B1', 'B2', 'C1'], rand(1, 3))), // Ghế ngẫu nhiên
                'ticket_combos' => json_encode($faker->randomElements(['Combo 1 - Bắp + Nước', 'Combo 2 - Bắp lớn'], rand(0, 2))), // Combo ngẫu nhiên
                'total_price' => $totalPrice - $voucherDiscount - $pointDiscount, // Tổng giá sau giảm
                'expiry' => $faker->dateTimeBetween('now', '+1 month'), // Thời hạn vé
                'status' => $faker->randomElement(['pending', 'confirmed']), // Trạng thái
            ]);
        }
    }
}
