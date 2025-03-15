<?php

namespace App\Services;

use App\Mail\SendMail;
use App\Models\Movie;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
      
        if (!isset($data['user']['email'], $data['user']['name'], $data['total_price'], $data['movie']['name'], $data['showtime']['date'], $data['showtime']['start_time'], $data['cinema']['name'], $data['branch']['name'])) {
            return false;
        }

        $mail           = $data['user']['email'];
        $user_name      = $data['user']['name'];
        $total_price    = $data['total_price'];
        $movie_name     = $data['movie']['name'];
        $date           = $data['showtime']['date'];
        $start_time     = $data['showtime']['start_time'];
        $cinema_name    = $data['cinema']['name'];
        $branch_name    = $data['branch']['name'];

        $seat_name = [];
        if (!empty($data['ticket_seats']) && is_array($data['ticket_seats'])) {
            foreach ($data['ticket_seats'] as $ticket_seats) {
                if (isset($ticket_seats['seat_name'])) {
                    $seat_name[] = $ticket_seats['seat_name'];
                }
            }
        }

        $combo_name = [];
        if (!empty($data['ticket_combos']) && is_array($data['ticket_combos'])) {
            foreach ($data['ticket_combos'] as $ticket_combos) {
                if (isset($ticket_combos['name'], $ticket_combos['quantity'])) {
                    $combo_name[] = [
                        'name'     => $ticket_combos['name'],
                        'quantity' => $ticket_combos['quantity'],
                    ];
                }
            }
        }

        $food_name = [];
        if (!empty($data['ticket_food']) && is_array($data['ticket_food'])) {
            foreach ($data['ticket_food'] as $ticket_food) {
                if (isset($ticket_food['name'], $ticket_food['quantity'])) {
                    $food_name[] = [
                        'name'     => $ticket_food['name'],
                        'quantity' => $ticket_food['quantity'],
                    ];
                }
            }
        }

       
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return false; 
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
    }
}
