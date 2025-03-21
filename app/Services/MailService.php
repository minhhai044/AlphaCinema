<?php

namespace App\Services;

use App\Mail\SendMail;
use App\Models\Movie;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    // use ApiResponseTrait;
    // public function sendMail($data)
    // {
    //     $mail           = $data['user']['email'];
    //     $user_name      = $data['user']['name'];
    //     $total_price    = $data['total_price'];
    //     $movie_name     = $data['movie']['name'];
    //     $date           = $data['showtime']['date'];
    //     $start_time     = $data['showtime']['start_time'];
    //     $cinema_name    = $data['cinema']['name'];
    //     $branch_name    = $data['branch']['name'];
    //     $seat_name = [];
    //     foreach ($data['ticket_seats'] as $ticket_seats) {
    //         $seat_name[] = $ticket_seats['seat_name'];
    //     }
    //     $combo_name = [];
    //     foreach ($data['ticket_combos'] as $ticket_combos) {
    //         $combo_name[] = [
    //             'name' => $ticket_combos['name'],
    //             'quantity' => $ticket_combos['quantity'],
    //         ];
    //     }
    //     $food_name = [];
    //     foreach ($data['ticket_food'] as $ticket_food) {
    //         $food_name[] = [
    //             'name' => $ticket_food['name'],
    //             'quantity' => $ticket_food['quantity'],
    //         ];
    //     }
    //     Mail::to($mail)->send(new SendMail($user_name, $total_price, $movie_name, $date, $start_time, $cinema_name, $branch_name, $seat_name, $combo_name, $food_name));
    // }
    public function sendMailService($data)
{
    try {
        $mail = $data['user']['email'] ?? null;
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            Log::error("Email không hợp lệ: " . $mail);
            return false;
        }

        $user_name   = $data['user']['name'] ?? 'Khách hàng';
        $total_price = $data['total_price'] ?? 0;
        $movie_name  = $data['movie']['name'] ?? 'Không xác định';
        $date        = $data['showtime']['date'] ?? 'Chưa có';
        $start_time  = $data['showtime']['start_time'] ?? 'Chưa có';
        $cinema_name = $data['cinema']['name'] ?? 'Không rõ';
        $branch_name = $data['branch']['name'] ?? 'Không rõ';

        $seat_name = [];
        if (!empty($data['ticket_seats']) && is_array($data['ticket_seats'])) {
            foreach ($data['ticket_seats'] as $seat) {
                $seat_name[] = $seat['seat_name'] ?? 'N/A';
            }
        }

        $combo_name = [];
        if (!empty($data['ticket_combos']) && is_array($data['ticket_combos'])) {
            foreach ($data['ticket_combos'] as $combo) {
                $combo_name[] = [
                    'name' => $combo['name'] ?? 'N/A',
                    'quantity' => $combo['quantity'] ?? 0,
                ];
            }
        }

        $food_name = [];
        if (!empty($data['ticket_foods']) && is_array($data['ticket_foods'])) {
            foreach ($data['ticket_foods'] as $food) {
                $food_name[] = [
                    'name' => $food['name'] ?? 'N/A',
                    'quantity' => $food['quantity'] ?? 0,
                ];
            }
        }

        Mail::to($mail)->send(new SendMail(
            $user_name,
            $total_price,
            $movie_name,
            $date,
            $start_time,
            $cinema_name,
            $branch_name,
            $seat_name,
            $combo_name,
            $food_name
        ));

        return true;
    } catch (\Throwable $th) {
        Log::error(__CLASS__ . " - Lỗi gửi email: " . $th->getMessage());
        return false;
    }
}

}
