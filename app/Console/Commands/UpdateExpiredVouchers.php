<?php

namespace App\Console\Commands;

use App\Mail\VoucherExpiringSoon;
use App\Models\User;
use App\Models\User_voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UpdateExpiredVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:updateExpired';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật voucher hết hạn sang trạng thái expired';


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $vouchers = User_voucher::with('voucher') // Eager load thông tin voucher
            ->whereHas('voucher', function ($query) {
                $query->where('is_active', 1) // Kiểm tra trạng thái active của voucher
                    ->where('end_date_time', '>=', Carbon::now()) // Voucher chưa hết hạn
                    ->where('end_date_time', '<=', Carbon::now()->addDays(3)); // Voucher sắp hết hạn trong 3 ngày
            })
            ->get();


        foreach ($vouchers as $voucher) {
            $user = User::find($voucher->user_id);

            if ($user) {

                Mail::to($user->email)->send(new VoucherExpiringSoon($user, $voucher->voucher));
                $this->info("Đã gửi email cho: {$user->email}");
            }
        }


        $updated = DB::table('vouchers')
            ->where('is_active', 1)
            ->where('end_date_time', '<', Carbon::now())
            ->update(['is_active' => 0]);

        // Thông báo kết quả cập nhật voucher hết hạn
        if ($updated > 0) {
            $this->info("Đã cập nhật $updated voucher hết hạn.");
        } else {
            $this->info('Không có voucher nào cần cập nhật.');
        }
    }
}
