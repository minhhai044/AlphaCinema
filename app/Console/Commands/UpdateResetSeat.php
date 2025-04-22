<?php

namespace App\Console\Commands;

use App\Events\RealTimeSeatEvent;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class UpdateResetSeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:Seat';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thời gian hết hạn ghế đặt trong 10 phút';


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $showtimes = Showtime::query()
            ->where('is_active', 1)
            ->where('date', '>=', Carbon::now()->toDateString())
            ->get();

        $nowTime = Carbon::now()->format('H:i');

        foreach ($showtimes as $showtime) {

            $seatStructure = json_decode($showtime->seat_structure, true);

            $isUpdated = false;

            foreach ($seatStructure as &$seat) {
                if (!empty($seat['hold_expires_at']) && Carbon::parse($seat['hold_expires_at'])->format('H:i') === $nowTime) {
                    $seat['hold_expires_at'] = null;
                    $seat['status'] = 'available';
                    $seat['user_id'] = null;
                    $isUpdated = true;
                    broadcast(new RealTimeSeatEvent($seat['id'], $seat['status'], $seat['user_id']))->toOthers();
                }
            }

            if ($isUpdated) {
                $showtime->update([
                    'seat_structure' => $seatStructure
                ]);
            }
        }
    }
}
