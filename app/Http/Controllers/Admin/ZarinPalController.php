<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use App\Model\Systeminfmanage;
use App\User;
use App\Utility\Message;
use App\Utility\Status;
use App\Utility\zarinPall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SoapClient;

class ZarinPalController extends Controller
{
    protected $urlBackSeller;
    protected $user;
    protected $priceForProduct;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->priceForProduct = Systeminfmanage::where('id', 34)->where('status', 1)->first();
        $this->urlBackSeller = route('panel.zarin.checkSeller');
    }

    public function seller(Request $request)
    {
        $numberProduct = $request->input('numberOfProduct');
        $priceForProduct = $this->priceForProduct->code;
        if (isset($numberProduct) && !empty($numberProduct) && $numberProduct > 0 && is_numeric($numberProduct)) {

            $total_amount = $priceForProduct * $numberProduct;

            $saveItem = [
                'user_id' => $this->user->id,
                'total_amount' => $total_amount,
                'credit_count' => $numberProduct,
                'tracking_code' => "biiiid" . "-" . time(),
                'status' => Status::UNPAID,
                'user_info' => serialize($this->user)
            ];

            $orderCreate = Order::create($saveItem);
            if ($orderCreate instanceof Order) {
                return zarinPall::zarinPal(env('MERCHANTID'), env('URLPAY'), env('URLCHECK'), $this->user->email
                    , $this->urlBackSeller, $total_amount, $orderCreate->id);
            } else {
                return back()->with(['error' => "خطا در انجام عملیات , لطفا چند لحظه بعد امتحان فرمایید."]);
            }

        } else {
            return back()->with(['error' => Message::illegalError]);
        }
    }

    /* seller check payment */
    public function checkSeller()
    {
        $Authority = request('Authority');
        $findOrder = Order::where('rest_number', $Authority)->firstOrFail();
        if (request('Status') == 'OK') {

            $client = new SoapClient(env('URLCHECK'), ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => env('MERCHANTID'),
                    'Authority' => $Authority,
                    'Amount' => $findOrder->total_amount,
                ]
            );

            if ($result->Status == 100) {
                $updateOrder = $findOrder->update([
                    'status' => Status::PAID
                ]);

                $user = User::is_credit($this->user->id);
                $updateUser = User::where('id', $this->user->id)->update([
                    'credit' => $user->credit + $findOrder->credit_count
                ]);

                if ($updateOrder > 0 && $updateUser > 0) {
                    // todo tracking print for user
                    toast()->success('پرداخت شماباموفقیت انجام شد.', 'موفقیت آمیز!');
                    return redirect()->route('site.request.product.create');
                } else {
                    $tracking_code = $findOrder->tracking_code;
                    return redirect()->route('panel.payment.index')->with(['error' => "{$tracking_code}" . "خطا در انجام افزایش اعتبار , لطفا از طریق پشتیبانی پیگیری فرمایید. شماره پیگیری : "]);
                }

            } else {

                $findOrder->update([
                    'status' => Status::CANCELED
                ]);
                return redirect()->route('panel.payment.index')->with(['error' => 'تراکنش توسط کاربر لغو شده است.']);
            }

        } else {
            $findOrder->update([
                'status' => Status::CANCELED
            ]);
            return redirect()->route('panel.payment.index')->with(['error' => 'تراکنش توسط کاربر لغو شده است.']);
        }

    }

}
