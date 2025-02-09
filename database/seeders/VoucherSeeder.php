<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách voucher
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
    }
}
