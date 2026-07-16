<?php

namespace App\Http\Controllers\Api\v1\userArea;

use App\Model\Payment;
use App\Model\ProductRequest;
use App\Utility\CustomOrderStatus;
use App\Utility\PaymentStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    public const countOfRender = 7;

    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

    }

    public function index()
    {
        $orders = ProductRequest::owner()->with(['productRequestItem', 'user'])->latest()->get();
        return response([
            'status' => 200,
            'data' => [
                'orders' => $orders,
                ],
            'message' => 'success',
        ]);
    }

    public function PaymentPrepayment($id)
    {
        if (isset($id) && is_numeric($id)) {
            $findProductRequest = ProductRequest::owner()->findOrfail($id);
            $result = $this->Payment($findProductRequest->prepayment, $findProductRequest, PaymentStatus::PREPAYMENT);
            if ($result['status'] == true) {
                return redirect(env('URLPAY') . $result['authority']);
            } else {
                return response([
                    'status' => 102,
                    'message' => 'unSuccess',
                ]);

            }
        } else {
            return response([
                'status' => 102,
                'message' => 'unSuccess',
            ]);
        }
    }

    public function CheckerPayment($id)
    {
        if (isset($id) && is_numeric($id)){
            if ($id == PaymentStatus::PREPAYMENT){
                $result = $this->Checker(PaymentStatus::PREPAYMENT,CustomOrderStatus::PSPREPAYMENT);

            }else{
                $result = $this->Checker(PaymentStatus::SETTLEMENT,CustomOrderStatus::PSCLEARED);

            }
            if ($result['status'] == true){
                $code = $result['code'];
                //event(new eventSendSmsPayment($payment));
                return response([
                    'status' => 200,
                    'message' => "Payment was successful, Tracking Code : $code",
                ]);

            }
        }

    }

    public function PaymentSettlement($id)
    {
        if (isset($id) && is_numeric($id)){
            $findProductRequest = ProductRequest::owner()->findOrfail($id);
            $amount = ($findProductRequest->total_factor_sum - $findProductRequest->prepayment);
            $result = $this->Payment($amount, $findProductRequest, PaymentStatus::SETTLEMENT);
            if ($result['status'] == true) {
                return redirect(env('URLPAY') . $result['authority']);
            } else {
                return response([
                    'status' => 102,
                    'message' => 'Error In Payment',
                ]);
            }
        } else {

            return response([
                'status' => 102,
                'message' => 'unSuccess',
            ]);
        }
    }


    protected function Payment($amount, $find = null, $paymentStatus = PaymentStatus::PRODUCTORDER)
    {
        $Description = env('DESCRIPTION_PAYMENT'); // Required
        $Email = isset($this->user->email) ? $this->user->email : null; // Optional
        $CallbackURL = route('users.panel.checker',$paymentStatus); // Required
        $client = new SoapClient(env('URLCHECK'), ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => env('MERCHANTID'),
                'Amount' => $amount,
                'Description' => $Description,
                'Email' => $Email,
                'CallbackURL' => $CallbackURL,
            ]
        );

        //Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {
            Payment::create([
                'resnumber' => $result->Authority,
                'price' => $amount,
                'tracking_code' => "pay" . time(),
                'details' => serialize($find),
                'user_info' => serialize($this->user),
                'user_id' => $this->user->id,
                'payment_type' => $paymentStatus,
                'paymentable_id' => $find->id,
                'paymentable_type' => get_class($find),
            ]);
            return [
                'status' => true,
                'authority' => $result->Authority
            ];
        } else {
            echo 'ERR: ' . $result->Status;
        }
    }

    protected function Checker($mode = PaymentStatus::PRODUCTORDER, $paymentStatus = CustomOrderStatus::PSPREPAYMENT)
    {
        $Authority = request('Authority');
        $payment = Payment::whereResnumber($Authority)->firstOrFail();

        if ($mode == PaymentStatus::PREPAYMENT || $mode == PaymentStatus::SETTLEMENT) {
            $unsrialize = unserialize($payment->details);
            $findProductRequest = ProductRequest::findOrfail($unsrialize->id);
            $findProductRequest->update([
                'payment_status' => $paymentStatus
            ]);
        }

        if (request('Status') == 'OK') {
            $client = new SoapClient(env('URLCHECK'), ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => env('MerchantID'),
                    'Authority' => $Authority,
                    'Amount' => $payment->price,
                ]
            );

            if ($result->Status == 100) {
                $payment->update([
                    'payment' => PaymentStatus::SUCCESSFUL,
                    'payment_type' => $mode,
                ]);
                $code = $payment->tracking_code;
                return [
                    'status' => true,
                    'code' => $code
                ];
            } else {
                return response([
                    'status' => 102,
                    'message' => 'Payment failed',
                ]);

            }
        } else {
            return response([
                'status' => 102,
                'message' => 'Transaction canceled by user',
            ]);

        }
    }
}
