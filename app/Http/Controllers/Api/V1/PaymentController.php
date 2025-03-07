<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Models\Showtime;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class PaymentController extends Controller
{
    use ApiResponseTrait;

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

    // PaymentRequest
    public function payment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $request->amount; 
        $orderId = Showtime::generateCustomRandomString(); //
        $redirectUrl = env('APP_URL') . '/api/v1/checkout';
        $ipnUrl = "https://hehe.test/check-out";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));

        $jsonResult = json_decode($result, true);  // decode json

        if (!isset($jsonResult['payUrl'])) {
            return response()->json(['message' => 'Lỗi khi tạo đơn hàng MoMo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Redis::setex("order:$orderId", 900, json_encode([
            'order_id' => $orderId,
            'amount' => $amount,
            // Tự điền data t không biết trả về những cái gì
        ]));

        if ($request->amount) {
            return response()->json([
                'url' => $jsonResult['payUrl']
            ]);
        }

        return response()->json([
            'messenger' => 'Vui lòng nhập giá'
        ]);
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

            // Sử lí lưu ticket
            // Chuyển trạng thái ghế
            //
            // XÓA REDIS 
            Redis::del("order:$orderId");

            return redirect($frontendUrl); // Thành công thì xóa redis
        }

        return redirect($frontendUrl); // Thất bại thì không xóa redis
    }
}
