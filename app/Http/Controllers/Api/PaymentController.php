<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MonerooHelpers;
use App\Helpers\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Installation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|integer',
                'deviceUuid' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'contact' => 'required|string',
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }


            $payment = new Payment();
            $payment->last_name = $request->input('last_name');
            $payment->first_name = $request->input('first_name');
            $payment->amount = $request->input('amount');
            $payment->deviceUuid = $request->input('deviceUuid');
            $payment->contact = $request->input('contact');
            $payment->saveOrFail();

            //TODO::Replace sandbox key by live key
            $headers = array("Authorization" => "Bearer " . env('MONEROO_SECRET_SANDBOX_KEY'));


            $data = [
                "amount" => $payment->amount,
                "currency" => MonerooHelpers::currency,
                "description" => "Paiement #" . $payment->id,
                "customer" => [
                    "email" => $request->input('email') ?: "john@example.com",
                    "first_name" => $request->input('first_name'),
                    "last_name" => $request->input('last_name'),
                ],
                "return_url" => "https://www.iamyourclounon.bj/",
                "metadata" => [
                    "payment" => $payment->id,
                ],
                //"methods"=> ["mtn_bj", "moov_bj"] # Once again, it is not required
            ];


            $response = Http::withHeaders($headers)->post(env('MONEROO_BASE_URL') . MonerooHelpers::paymentInitUrl, $data);

            if ($response->status() == 201) {
                $jsonData = $response->json();
                $data = $jsonData["data"];
                $url = $data["checkout_url"];
                return response()->json(["paymentUrl" => $url]);
            }

            return response()->json(json_encode($response), $response->status());

        } catch (\Exception $exception) {
            Log::critical('Error when initializing moneroo payment: ' . $exception);
            return response()->json(json_encode($exception), 400);
        }

    }

    public function confirmMoneroo(Request $request)
    {
        $data = $request->all();
        Log::emergency('Payment Confirmation Launched');
        Log::emergency('Payment Confirmation Data ==>' . json_encode($data));
        Log::emergency('Headers ==>' . json_encode($request->headers->all()));

        $hashValue = $request->header(MonerooHelpers::HASH_SECRET_NAME);
        $secret = MonerooHelpers::HASH_SECRET_VALUE;

        $payload = json_encode($data);
        $signature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($signature, $hashValue)) {
            return response()->json([
                'message' => 'INVALID HASH',
                'payload' => $payload,
                'signature' => $signature,
            ], 403);
        }

        $event = $request->input('event');
        $status = $data['data']['status'];
        $isPaymentSuccess = $event == 'payment.success' && $status == "success";


        if (!$isPaymentSuccess) {
            Log::emergency('not a successful payment');

            return response()->json([
                'message' => 'EVENT NOT OK',
            ], 400);
        }

        $stateData = $data['data']['metadata'];

        $isArray = gettype($stateData) == "array";

        $paymentId = ($isArray ? ($stateData['payment'] ?? null) : (json_decode($stateData)->payment) ?? null) ?? null;

        if (!$paymentId) {
            Log::emergency('not a correct payment If');

            return response()->json([
                'message' => 'EVENT NOT OK',
            ], 400);
        }

        Log::alert("PAYMENT ID: " . $paymentId);

        $transactionId = $data['data']["id"];
        $amount = $data['data']["amount"];
        $payment = Payment::findOrFail($paymentId);
        $isPending = $payment->status == PaymentStatusEnum::PENDING;

        if (isset($amount) && $amount == $payment->amount && $isPending) {

            Log::info('Payment found <===> Is Pending');
            $payment->transactionId = $transactionId;
            $payment->status = PaymentStatusEnum::CONFIRMED;
            $payment->save();
            Log::info('Payment confirmed');

            $device = Installation::find($payment->deviceUuid);
            if (!$device->hasSubscribed) {
                $device->hasSubscribed = true;
                $device->saveOrFail();
            }
            return response()->json([
                'message' => 'OK',
            ]);
        }
        Log::info('Payment found <===> probably not Pending');


        return response()->json([
            'message' => 'NOT_OK',
        ], 400);


    }

    public function index()
    {
        $payments = Payment::all();
        return response()->json(PaymentResource::collection($payments));
    }

    public function clear()
    {
        $now = now(config('app.timezone'))->subHour()->format('Y-m-d H:i:s');

        $payments = Payment::where('status', PaymentStatusEnum::PENDING)
            ->where('created_at', '<', $now)->get();

        foreach ($payments as $payment) {
            $payment->deleteOrFail();
        }

        return response()->json([
            'message' => 'OK',
        ]);


    }
}
