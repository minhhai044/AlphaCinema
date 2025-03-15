<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use App\Services\MailService;
use App\Services\ShowtimeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use PgSql\Lob;

class PaymentController extends Controller
{
    use ApiResponseTrait;
    private $showtimeService;
    private $mailService;
    private const PATH_URL = "https://alphacinema.me";
    public function __construct(ShowtimeService $showtimeService, MailService $mailService)
    {
        $this->showtimeService = $showtimeService;
        $this->mailService = $mailService;
    }

    public static function generateOrderId()
    {
        return substr(time(), -6) . mt_rand(100000, 999999);
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

    public function payment(Request $request, string $payment)
    {


        $data = [
            'ticket' => $request->ticket,
            'seat_id' => $request->seat_id,
        ];
        $payment == 'momo' ? $paymentResult = $this->processPayment($data) : $paymentResult = $this->processVnPayPayment($data);
        if (!isset($paymentResult)) {
            return response()->json(['message' => 'Lỗi khi tạo đơn hàng MoMo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'url' => $paymentResult
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

        return $jsonResult['payUrl'];
    }


    private function processVnPayPayment($dataRequest)
    {

        if (!isset($dataRequest['ticket']['total_price'])) {
            return response()->json(['message' => 'Dữ liệu không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = self::PATH_URL . "/api/v1/checkout";
        $vnp_TmnCode = 'CW3MWMKN';
        $vnp_HashSecret = "2EQ9DCNFBR3H0GRQ4RCVHYTO1VZYXFLZ";
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_TxnRef = self::generateOrderId();
        $vnp_Amount = $dataRequest['ticket']['total_price'] * 100;
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = "Thanh toán Vnpay";
        $vnp_OrderType = "Thanh toán hóa đơn";


        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        Redis::setex("order:$vnp_TxnRef", 900, json_encode([
            'order_id' => $vnp_TxnRef,
            'data' => $dataRequest,
        ]));
        return $vnp_Url;
    }



    public function checkout(Request $request)
    {
        $resultCode = $request->query('resultCode', '');
        $vnp_TransactionStatus = $request->query('vnp_TransactionStatus', '');
        $vnp_TxnRef = $request->query('vnp_TxnRef', '');
        $orderId = $request->query('orderId', '');
        $frontendUrl = "http://localhost:3000";

        $txnRef = $orderId ?: $vnp_TxnRef;
        if (!$txnRef) {
            return redirect($frontendUrl);
        }


        $orderData = Redis::get("order:$txnRef");
        if (!$orderData) {
            return redirect($frontendUrl);
        }

        $orderData = json_decode($orderData, true);

        $isSuccess = ($resultCode === "0" || $vnp_TransactionStatus === "00");

        if ($isSuccess) {
            DB::transaction(function () use ($orderData) {
                $data = $orderData['data']['ticket'];
                $data['code'] = strtoupper(Str::random(8));
                $ticket = Ticket::create($data)->load('user', 'cinema', 'room', 'movie', 'showtime', 'branch');
                Log::info('ticket',[$ticket]);
                $dataResetSuccess = [
                    'seat_id' => $orderData['data']['seat_id'],
                    'status' => 'sold',
                    'user_id' => $orderData['data']['ticket']['user_id']
                ];
                $this->showtimeService->resetSuccessService($dataResetSuccess, $orderData['data']['ticket']['showtime_id']);

                User::where('id', $orderData['data']['ticket']['user_id'])
                    ->increment('total_amount', $orderData['data']['ticket']['total_price']);

                $this->mailService->sendMailService($ticket);
            });

            Redis::del("order:$txnRef");

            // return redirect($frontendUrl)->withCookie(cookie('order_id', $txnRef, 10));
            return redirect($frontendUrl . '/booking-success')->withCookies([
                cookie('order_id', $txnRef, 10),
                cookie('status', true),
                cookie('message', 'Thanh toán thành công'),
            ]);
        }

        $dataResetSuccess = [
            'seat_id' => $orderData['data']['seat_id'],
            'status' => 'available',
            'user_id' => null
        ];

        $this->showtimeService->resetSuccessService($dataResetSuccess, $orderData['data']['ticket']['showtime_id']);

        Redis::del("order:$txnRef");
        return redirect($frontendUrl);
    }
}
