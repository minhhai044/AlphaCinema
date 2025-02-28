<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PointHistorySeeder extends Seeder
{
    public function run()
    {
        // Lấy người dùng có type_user = 0 (UserMember)
        $users = User::where('type_user', 0)->get();

        foreach ($users as $user) {
            DB::table('point_histories')->insert([
                [
                    'user_id' => $user->id,
                    'point' => rand(10, 100), 
                    'type' => 'bonus', 
                    'description' => 'Điểm thưởng cho hành động đặc biệt',
                    'expiry_date' => Carbon::now()->addMonths(rand(1, 12)), 
                    'processed' => rand(0, 1), 
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $user->id,
                    'point' => rand(1, 50), 
                    'type' => 'reduction', 
                    'description' => 'Điểm bị giảm do hành động vi phạm',
                    'expiry_date' => Carbon::now()->addMonths(rand(1, 6)),
                    'processed' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
