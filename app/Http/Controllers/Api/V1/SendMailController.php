<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    // use ApiResponseTrait;
    // public function sendMail(Request $request){
    //     $mail = $request->mail;
    //     Mail::to($mail)->send(new SendMail());
    // }

}
