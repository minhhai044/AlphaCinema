<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use App\Services\ShowtimeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PaymentController extends Controller
{
    use ApiResponseTrait;
    private $showtimeService;
    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public static function generateOrderId()
    {
        $timestamp = substr(time(), -6);
        $randomNumber = mt_rand(100000, 999999);

        return $timestamp . $randomNumber;
    }

    public function payment(Request $request)
    {


        if (!$request->data) {
            return response()->json([
                'messenger' => 'Không có dữ liệu !!!'
            ], Response::HTTP_BAD_REQUEST);
        }
        $data = json_decode($request->data, true);
        $paymentResult = $this->processPayment($data);

        if (!isset($paymentResult['payUrl'])) {
            return response()->json(['message' => 'Lỗi khi tạo đơn hàng MoMo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'url' => $paymentResult['payUrl']
        ]);
    }

    private function processPayment($dataRequest)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = self::generateOrderId();
        $redirectUrl = env('APP_URL') . '/api/v1/checkout';
        $ipnUrl = "https://hehe.test/check-out";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" .  $dataRequest['ticket']['total_price'] . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $dataRequest['ticket']['total_price'],
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));

        $jsonResult = json_decode($result, true);

        if (!isset($jsonResult['payUrl'])) {
            return null;
        }

        Redis::setex("order:$orderId", 900, json_encode([
            'order_id' => $orderId,
            'data' => $dataRequest,
        ]));

        return $jsonResult;
    }

    public function checkout(Request $request)
    {
        $resultCode = $request->query('resultCode');
        $orderId = $request->query('orderId');
        $frontendUrl = env('APP_URL');

        if (!$orderId) {
            return redirect($frontendUrl); //Không tìm thấy orderId
        }
        $orderData = Redis::get("order:$orderId");

        if (!$orderData) {
            return redirect($frontendUrl); //Không tìm thấy orderData
        }

        $orderData = json_decode($orderData, true);

        if ($resultCode == 0) {

            DB::transaction(function () use ($orderData) {
                Ticket::create($orderData['data']['ticket']);

                $dataResetSuccess = [
                    'seat_id' => $orderData['data']['seat_id'],
                    'status' => 'sold',
                    'user_id' => $orderData['data']['ticket']['user_id']
                ];
                $this->showtimeService->resetSuccessService($dataResetSuccess, $orderData['data']['ticket']['showtime_id']);
            
                User::where('id', $orderData['data']['ticket']['user_id'])->increment('total_amount', $orderData['data']['ticket']['total_price']);

            });

            Redis::del("order:$orderId");

            return redirect($frontendUrl)
                ->withCookie(cookie('order_id', $orderId, 10)); // Thành công thì xóa redis
        }
        Redis::del("order:$orderId");
        return redirect($frontendUrl); // Thất bại
    }
}
