<?php

namespace App\Http\Controllers\Site;

use App\Model\Address;
use App\Model\Detail;
use App\Model\Discount;
use App\Model\Order;
use App\Model\ShippingCost;
use App\Model\Systeminfmanage;
use App\Model\Variation;
use App\User;
use App\Utility\DiscountType;
use App\Utility\forgetSession;
use App\Utility\Level;
use App\Utility\Message;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use SEOMeta;
use OpenGraph;
use Twitter;

## or
use SEO;

class CheckoutController extends Controller
{

    protected $user;
    public $showDetails;
    public $sessionAddress;
    public $tax;
    public $userDetails;
    public $basket;
    public $shippingCost;
    public static $freeShippingTwo;
    public static $shippingCostTwo;
    public static $shippingMethods;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->sessionAddress = Session::get('address');
            $this->basket = Session::get('basket');
            /* session */
            $this->shippingCost = self::checkSessionShippingCost();
            return $next($request);
        });
        /* when state in checkout hidden basket details */
        $this->showDetails = "hiddenDetails";
        $this->tax = Systeminfmanage::whereId(52)->where('status', 1)->first();
        self::$freeShippingTwo = Systeminfmanage::where('id', 53)->whereStatus(1)->first();
        self::$shippingCostTwo = Systeminfmanage::where('id', 54)->whereStatus(1)->first();
        self::$shippingMethods = Systeminfmanage::where('systeminf_id', 44)->whereStatus(1)->get();
    }

    public function index()
    {
        /* start check and apply coupon on basket */
        $this->applyCouponOnBasket();
        /* end check and apply coupon on basket */

        $title = "فروشگاه |‌ سبد خرید";
        SEO::setTitle($title);
        /* dont show cart */
        $showDetails = $this->showDetails;
        /* session address */
        $sessionAddress = $this->sessionAddress;
        /* tax */
        $tax = $this->tax;
        /* get user */
        $user = User::with(['address'])->where('id', $this->user->id)->first();

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            alert()->error('متاسفیم!','سبد خرید شما خالی می باشد.')->showConfirmButton('بستن');
            return redirect()->route('site.index');
        }

        if ($basket->totalPrice) {
            $shippingCost = $this->checkSessionShippingCost($basket->totalPrice);
        }
        $freeShippingTwo = self::$freeShippingTwo;
        return view('site.checkout.index', compact('showDetails', 'user', 'sessionAddress', 'tax', 'basket', 'title', 'shippingCost', 'freeShippingTwo'));
    }

    public function address()
    {
        $title = "فروشگاه  |‌ انتخاب آدرس";
        SEO::setTitle($title);
        /* dont show cart */
        $showDetails = $this->showDetails;
        /* tax */
        $tax = $this->tax;

        /* get user */
        $user = User::with(['address'])->where('id', $this->user->id)->first();


        /* set session */
        if (!isset($this->sessionAddress) && empty($this->sessionAddress)) {
            if (isset($user->address[0]) && !empty($user->address[0]) && isset($user->address[0]->fullAddress) && !empty($user->address[0]->fullAddress)) {

                Session::put('address', $user->address[0]);
                $sessionAddress = Session::get('address');
            } else {
                alert()->success('آدرسی یافت نشد , لطفا از قسمت پروفایل آدرس خود را ثبت نمایید.', Lang::get('cms.error'))->showConfirmButton('بستن');
                return redirect()->route('users.panel.address');
            }
        } else {
            $sessionAddress = $this->sessionAddress;
        }

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            alert()->error('متاسفیم!','سبد خرید شما خالی می باشد.')->showConfirmButton('بستن');
            return redirect()->route('site.index');
        }

        if ($basket->totalPrice) {
            $shippingCost = $this->checkSessionShippingCost($basket->totalPrice);
        }
        return view('site.checkout.address', compact('shippingCost','showDetails', 'user', 'sessionAddress', 'tax', 'basket', 'title'));
    }

    public function review()
    {
        /* session */

        $title = "فروشگاه | بازبینی سبد خرید";
        SEO::setTitle($title);
        /* dont show cart */
        $showDetails = $this->showDetails;

        /* tax */
        $tax = $this->tax;

        /* get User */
        $user = User::with(['address'])->where('id', $this->user->id)->first();
        /* set session */
        if (!isset($this->sessionAddress) && empty($this->sessionAddress)) {
            if (isset($user->address[0]) && !empty($user->address[0]) && isset($user->address[0]->fullAddress) && !empty($user->address[0]->fullAddress)) {
                Session::put('address', $user->address[0]);
                $sessionAddress = Session::get('address');
            } else {
                alert()->error('آدرسی یافت نشد , لطفا از قسمت پروفایل آدرس خود را ثبت نمایید.', Lang::get('cms.error'))->showConfirmButton('بستن');
                return redirect()->route('site.index', compact('title'));
            }
        } else {
            $sessionAddress = $this->sessionAddress;
        }

        /* basket */
        $basket = $this->basket;
        $resultBasket = $this->isEmptyBasket($basket);
        if ($resultBasket == false) {
            alert()->error('متاسفیم!','سبد خرید شما خالی می باشد.')->showConfirmButton('بستن');
            return redirect()->route('site.index');
        }
        $shippingMethods = self::$shippingMethods;
        $totalWeight = self::sumWeightBasket($basket);
        $postType = isset($sessionAddress) && $sessionAddress->inTehran($sessionAddress->city_id) ? ShippingCost::TYPES_OF_POST['inTown'] : ShippingCost::TYPES_OF_POST['suburban'];
        $shippingCost = self::calculatorShippingCost(173, $postType, $totalWeight, 0, $basket);
        $freeShippingTwo = self::$freeShippingTwo;
        return view('site.checkout.review', compact('postType', 'totalWeight', 'shippingMethods', 'showDetails', 'user', 'sessionAddress', 'tax', 'basket', 'title', 'shippingCost', 'freeShippingTwo'));
    }

    public function showShippingCost(Request $request)
    {
        $type = $request->input('type');
        $postType = $request->input('postType');
        $totalWeight = $request->input('totalWeight');
        $output = $request->input('output');
        $basket = $this->basket;
        return self::calculatorShippingCost($type, $postType, $totalWeight, $output, $basket);
    }

    /* ======================= extra function  ======================= */

    private function isEmptyBasket($basket)
    {
        if (!isset($basket) || empty($basket)) {
            return false;
        }
        return true;
    }

    /* check and apply coupon on basket */
    private function applyCouponOnBasket()
    {

        $session = Session::get('basket');
        $sessionPercent = Session::get('percent');
        $sessionPrice = Session::get('prices');

        if (isset($session)) {
            if (isset($sessionPercent) && !empty($sessionPercent)) {

                $finishPrice = $session->totalPrice - ((($session->totalPrice * $sessionPercent) / 100));

//                if ($finishPrice > 0) {
                    Session::put('finishPrice', $finishPrice);
//                } else {
                    // forget all session
//                    forgetSession::forgetSession();
//                    return back();
//                }

            } else {
                $finishPrice = $session->totalPrice - $sessionPrice;
//                if ($finishPrice > 0) {
                    Session::put('finishPrice', $finishPrice);
//                } else {
                    // forget all session
//                    forgetSession::forgetSession();
//                    return back();
//                }
            }
        }


    }

    // hazine ersal va hade aghal kharid baraye ersale raygan
    public static function shippingCost($totalPrice)
    {
        $freeShipping = self::$freeShippingTwo;
        $shippingCost = self::$shippingCostTwo;

        if (isset($shippingCost) && $shippingCost->code > 0) {
            if (isset($freeShipping) && $totalPrice > $freeShipping->code) {
                \session()->forget('shippingCost');
                return $totalPrice;
            } elseif (isset($freeShipping) && $totalPrice <= $freeShipping->code) {

                \session()->put('shippingCost', $shippingCost->code);
                return $totalPrice + $shippingCost->code;
            } else {
                \session()->put('shippingCost', $shippingCost->code);
                return $totalPrice + $shippingCost->code;
            }
        }
        return $totalPrice;

    }

    /* private check session shipping cost */
    public static function checkSessionShippingCost($shippingCostSession = false)
    {
        if ($shippingCostSession == false) {
            return $shippingCostSession = Session::get('shippingCost');
        }

        $freeShipping = self::$freeShippingTwo;
        $shippingCost = self::$shippingCostTwo;


        if (isset($shippingCost, $freeShipping) && $shippingCost->code > 0) {
            $shippingCost = $shippingCost->code;
            $freeShipping = $freeShipping->code;
            if (isset($shippingCostSession) && !empty($shippingCostSession)) {
                if (isset($freeShipping) && $shippingCostSession > $freeShipping) {
                    \session()->forget('shippingCost');
                    return $shippingCost = 0;
                } elseif (isset($freeShipping) && $shippingCostSession <= $freeShipping) {
                    \session()->put('shippingCost', $shippingCost);
                    return $shippingCost;
                } else {
                    \session()->put('shippingCost', $shippingCost);
                    return $shippingCost;
                }
            }
        } else {
            $shippingCost = 0;
        }
        return $shippingCost;
    }


    public static function setTypeShippingCost($type): int
    {
        if ($type == 172) {
            return ShippingCost::TYPES['bespoke'];
        } elseif ($type == 173) {
            return ShippingCost::TYPES['vanguard'];
        } else {
            return ShippingCost::TYPES['bikeDelivery'];
        }
    }

    public static function calculatorShippingCost($type, $postType, $weight = 0, $outPutType = 0, $basket = null)
    {
        // $outPutType => 0 Return Value && 1 => Return Json
        $shippingCostPrice = 0;
        $surplus = 0;
        $discount = 0;
        $freeShipping = !is_null(self::$freeShippingTwo) ? self::$freeShippingTwo : Systeminfmanage::where('id', 53)->whereStatus(1)->first();
        $isSetType = self::setTypeShippingCost($type);

        if (isset($freeShipping) && !empty($freeShipping)) {
            $discount = $freeShipping->code;
            if (isset($basket) && !empty($basket) && $basket->totalPrice < $discount) {
                $discount = false;
                if ($weight <= 10000) {
                    $shippingCost = ShippingCost::where([
                        ['type', '=', $isSetType],
                        ['post_type', '=', $postType],
                        ['of_weight', '<', $weight],
                        ['upto_weight', '>=', $weight],
                    ])->first();
                } else {
                    $shippingCost = ShippingCost::where([
                        ['type', '=', $isSetType],
                        ['post_type', '=', $postType],
                        ['upto_weight', '=', 10000],
                    ])->first();
                    $surplus = ((int)ceil(($weight - 10000) / 500)) * 1500;
                }
                if (isset($shippingCost) && !empty($shippingCost)) {
                    $shippingCostPrice = $shippingCost->price + $surplus;
                }
            }
        } else {
            if (isset($basket) && !empty($basket)) {
                if ($weight <= 10000) {
                    $shippingCost = ShippingCost::where([
                        ['type', '=', $isSetType],
                        ['post_type', '=', $postType],
                        ['of_weight', '<', $weight],
                        ['upto_weight', '>=', $weight],
                    ])->first();
                } else {
                    $shippingCost = ShippingCost::where([
                        ['type', '=', $isSetType],
                        ['post_type', '=', $postType],
                        ['upto_weight', '=', 10000],
                    ])->first();
                    $surplus = ((int)ceil(($weight - 10000) / 500)) * 1500;
                }
                if (isset($shippingCost) && !empty($shippingCost)) {
                    $shippingCostPrice = $shippingCost->price + $surplus;
                }
            }
        }


        if ($outPutType == 0) {
            return $shippingCostPrice;
        }

        return [
            'status' => 200,
            'discount' => $discount,
            'type' => $isSetType,
            'shippingCost' => $shippingCostPrice
        ];
    }

    public static function sumWeightBasket($basket): int
    {
        $totalWeight = 0;
        if (isset($basket->items) && !empty($basket->items)) {
            foreach ($basket->items as $items) {
                if (isset($items["item"]->shipping_cost) && $items["item"]->shipping_cost == 1) {
                    $totalWeight += (int)$items["item"]->weight;
                }
            }
        }
        return $totalWeight;
    }

}
